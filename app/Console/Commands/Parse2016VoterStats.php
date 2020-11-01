<?php

namespace App\Console\Commands;

use App\Models\County;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PHPExperts\CSVSpeaker\CSVReader;

class Parse2016VoterStats extends Command
{
    /** @var string */
    protected $signature = 'voters:parse-2016';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parses the Texas 2016 county stats.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // ,votes_dem,votes_gop,total_votes,per_dem,per_gop,diff,per_point_diff,state_abbr,county_name,combined_fips
        $csv = CSVReader::fromFile('data/2016-results.tx.csv');
        $votingStats = $csv->toArray();

        $countyIds = County::query()->pluck('id', 'name');

        $notFound = [];
        $toInsert = [];
        foreach ($votingStats as $row) {
            $countyName = strtoupper(substr($row['county_name'], 0, strpos($row['county_name'], ' County')));
            // Handle data exceptions:
            $countyName = $countyName !== 'LA SALLE' ? $countyName : "LASALLE";

            if (isset($countyIds[$countyName])) {
                dump([$countyName, $countyIds[$countyName]]);
            } else {
                $notFound[] = $countyName;
            }

            $toInsert[] = [
                'county_id'   => $countyIds[$countyName],
                'total_votes' => (int) $row['total_votes'],
                'republican'  => $row['votes_gop'] / $row['total_votes'],
                'democrat'    => $row['votes_dem'] / $row['total_votes'],
            ];
        }

        if (!empty($notFound)) {
            dump('Did not find the following counties:');
            dd($notFound);
        }

        DB::table('counties_by_pol_party_2016')->insert($toInsert);

        return 0;
    }
}
