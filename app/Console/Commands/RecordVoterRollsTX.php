<?php

namespace App\Console\Commands;

use App\Models\County;
use App\Models\VoterRoll;
use App\Models\VotesByCounty;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PHPExperts\ConciseUuid\ConciseUuid;
use PHPExperts\CSVSpeaker\CSVReader;

class RecordVoterRollsTX extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'voters:record-tx';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Records voters for the State of Texas.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $recordVoters = function (array $voters, $county, $votingDate) {
            static $counts;

            if (!$counts) {
                $counts = VotesByCounty::all()->pluck('votes', 'county')->toArray();
            }

            $voterCount = count($voters);
            $counts[$county] = ($counts[$county] ?? 0) + $voterCount;
            dump(sprintf("Recording %5s voters [{$counts[$county]}] on $votingDate in $county County, TX, to the database...", $voterCount));
            VoterRoll::query()->insert($voters);
        };

        $state = 'TX';

        // 1. Get a list of the available CSV files.
        $csvFiles = glob('data/TX/*.csv');

        // 2. Parse the CSV data.
        foreach ($csvFiles as $csvFile) {
            $csv = CSVReader::fromFile($csvFile);
            $votersData = $csv->toArray();

            preg_match('/[0-9]{4}-[0-9]{2}-[0-9]{2}/', $csvFile, $matches);
            $votingDate = $matches[0];

            // 3. Truncate the voting rolls for the current date. We're going to rebuild them all.
            VoterRoll::query()->where(['recorded_on' => $votingDate])->delete();

            /*
 * @property string $id
 * @property string $county_id
 * @property string $last_name
 * @property string $given_names
 * @property int    $voter_id
 * @property string $voting_method
 * @property int    $precinct
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property County $county
             */
            $voters = [];
            $countyIds = [];
            $votersInBatch = 0;
            $currentCounty = null;
            foreach ($votersData as $data) {
                $county = $data['COUNTY'];
                if (!isset($countyIds[$county])) {
                    $countyId = County::query()->where(['name' => $county])
                        ->value('id');
                    if (!$countyId) {
                        $county = ucwords($county);
                        dump("$county County is not in the database!!");
                        $countyId = County::query()->create([
                            'name'  => $county,
                            'state' => $state,
                        ])->id;
                    }

                    //dump("$county County's ID is $countyId");
                    $countyIds[$county] = $countyId;

                    if (!$currentCounty) {
                        $currentCounty = $county;
                    }
                }

                // Periodically write to the database, once per county.
                if ($county !== $currentCounty) {
                    $recordVoters($voters, $currentCounty, $votingDate);

                    unset($voters);
                    $voters = [];
                    $votersInBatch = 0;
                    $currentCounty = $county;
                }

                // Periodically write to the database, every 10,000 votes.
                if ($votersInBatch === 5000) {
                    $recordVoters($voters, $county, $votingDate);

                    unset($voters);
                    $voters = [];
                    $votersInBatch = 0;
                }

                $voters[] = [
                    'id'            => ConciseUuid::generateNewId(),
                    'county_id'     => $countyId,
                    'last_name'     => trim(substr($data['VOTER_NAME'], 0, strpos($data['VOTER_NAME'], ', '))),
                    'given_names'   => trim(substr($data['VOTER_NAME'], strpos($data['VOTER_NAME'], ', ') + 2)),
                    'voter_id'      => $data['ID_VOTER'],
                    'voting_method' => $data['VOTING_METHOD'],
                    'precinct'      => $data['PRECINCT'],
                    'recorded_on'   => $votingDate,
                    'created_at'    => Carbon::now()->format('c'),
                    'updated_at'    => Carbon::now()->format('c'),
                ];

                ++$votersInBatch;
            }

            // Record the last batch of voters.
            $recordVoters($voters, $county, $votingDate);

            /**
            array:5 [
                "COUNTY" => "ANDERSON"
                "VOTER_NAME" => "SUBER, WILFORD LEANDREE"
                "ID_VOTER" => "1040877229"
                "VOTING_METHOD" => "MAIL-IN"
                "PRECINCT" => "16"
            ]
             */

        }

        return 0;
    }
}
