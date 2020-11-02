<?php declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\VoterMetadata;
use App\Models\VoterRoll;
use App\Models\VotesByCounty;
use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PHPExperts\ConciseUuid\ConciseUuid;
use PHPExperts\CSVSpeaker\CSVReader;

class RecordVoterRollsWA extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'voters:record-wa';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Records voters for The State of Washington.';

    protected $countyCounts = [];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $recordVoters = function (array $voters, $county, $votingDate) {
            if (!$this->countyCounts) {
                $this->countyCounts = VotesByCounty::query()
                    ->where(['state' => 'WA'])
                    ->pluck('votes', 'county')->toArray();
            }

            $voterCount = count($voters['voters']);
            $this->countyCounts[$county] = ($this->countyCounts[$county] ?? 0) + $voterCount;
            dump(sprintf("Recording %5s voters [%7s] on $votingDate in $county County, WA...", $voterCount, $this->countyCounts[$county]));

            DB::transaction(function () use ($voters) {
                VoterRoll::query()->insert($voters['voters']);
                VoterMetadata::query()->insert($voters['meta']);
            });
        };

        $state = 'WA';

        // 1. Get a list of the available CSV files.
        $csvFiles = glob("data/{$state}/*.csv");

        $recordedVotes = 0;
        $totalVotes = 0;

        date_default_timezone_set('PST8PDT');

        // 2. Parse the CSV data.
        foreach ($csvFiles as $csvFile) {
            $csv = CSVReader::fromFile($csvFile);
            $votersData = $csv->toArray();
            unset($csv);

            // 3. Truncate the voting rolls for the CSV's counties. We're going to rebuild them all.
            // Figure out what counties are in the CSV so we can truncate them.
            $counties = array_unique(array_column($votersData, 'County'));
            array_walk($counties, function (&$value) {
                $value = strtoupper($value);
            });

//            // @see https://stackoverflow.com/a/45224067/430062
//            $builder = DB::table((new VoterRoll())->getTable());
//            $sql = $builder->getGrammar()->compileDelete($builder
//                ->where(['state' => $state])
//                ->whereIn('county', $counties)
//            );
//            dd($sql);

            // Prune the voter metadata.
            VoterMetadata::query()
                ->join('voter_rolls AS v', 'v.id', '=', 'voter_metadata.voter_roll_id')
                ->whereIn('v.county', $counties)
                ->delete();

            // Prune the voter rolls.
            VoterRoll::query()
                ->where(['state' => $state])
                ->whereIn('county', $counties)
                ->delete();

            $voters = [
                'voters' => [],
                'meta'   => [],
            ];
            $votersInBatch = 0;
            $currentCounty = null;
            foreach ($votersData as $data) {
                $votingDate = $data['Received Date'];
                $votingDate = $votingDate !== '' ? $votingDate : $data['Sent Date'];
                $votingDate = Carbon::parseFromLocale($votingDate, 'us')->format('Y-m-d');

                $county = strtoupper($data['County']);

                // Periodically write to the database, once per county.
                if ($county !== $currentCounty) {
                    if ($currentCounty !== null) {
                        $recordVoters($voters, $currentCounty, $votingDate);
                        $recordedVotes += count($voters['voters']);

                        unset($voters);
                        $voters = [];
                        $votersInBatch = 0;
                    }

                    $currentCounty = $county;
                }

                // Periodically write to the database, every 10,000 votes.
                if ($votersInBatch === 5000) {
                    $recordVoters($voters, $county, $votingDate);
                    $recordedVotes += count($voters['voters']);

                    unset($voters);
                    $voters = [
                        'voters' => [],
                        'meta'   => [],
                    ];
                    $votersInBatch = 0;
                }

                $now = Carbon::now()->format('c');

                $voterRollId = ConciseUuid::generateNewId();
                $voters['voters'][] = [
                    'id'            => $voterRollId,
                    'county'        => $county,
                    'state'         => $state,
                    'last_name'     => trim($data['Last Name']),
                    'given_names'   => trim($data['First Name']),
                    'voter_id'      => $data['Voter ID'],
                    'voting_method' => $data['Return Method'],
                    'precinct'      => $data['Precinct'],
                    'recorded_on'   => $votingDate,
                    'created_at'    => $now,
                    'updated_at'    => $now,
                ];
                // voter_roll_id | state | zipcode | ballot_id | ballot_status | challenge_reason | sent_on | received_on | created_at | updated_at
                $voters['meta'][] = [
                    'voter_roll_id'    => $voterRollId,
                    'state'            => $state,
                    'zipcode'          => $data['Zip'],
                    'ballot_id'        => $data['Ballot ID'],
                    'ballot_status'    => $data['Ballot Status'] !== '' ? $data['Ballot Status'] : null,
                    'challenge_reason' => $data['Challenge Reason'] !== '' ? $data['Challenge Reason'] : null,
//                    'sent_at'          => $data['Sent Date'] !== '' ? Carbon::parse($data['Sent Date'])->setTimezone(new DateTimeZone('PST8PDT'))->format('Y-m-d H:i:sO') : null,
//                    'received_at'      => $data['Received Date'] !== '' ? Carbon::parse($data['Received Date'])->setTimezone(new DateTimeZone('PST8PDT'))->format('Y-m-d H:i:sO') : null,
                    'sent_at'          => $data['Sent Date'] !== '' ? date('Y-m-d H:i:sO', strtotime($data['Sent Date'])) : null,
                    'received_at'      => $data['Received Date'] !== '' ? date('Y-m-d H:i:sO', strtotime($data['Received Date'])) : null,
                    'created_at'       => $now,
                    'updated_at'       => $now,
                ];

                ++$votersInBatch;
            }

            unset($data);

            // Record the last batch of voters.
            $recordVoters($voters, $county, $votingDate);
            $recordedVotes += count($voters);

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

        foreach ($this->countyCounts as $count) {
            $totalVotes += $count;
        }

        dump('Recorded votes: ' . $recordedVotes);
        dump('Total Texan votes: ' . $totalVotes);

        return 0;
    }
}
