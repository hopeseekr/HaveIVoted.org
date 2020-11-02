<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateReportVoteFraudDiffPeopleView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement(self::reportVoteFraudDiffPeopleSQL());
    }

    public static function reportVoteFraudDiffPeopleSQL(): string
    {
        $sql = <<<'SQL'
        CREATE VIEW report_vote_fraud_diff_people AS
        SELECT voter_rolls.voter_id,
            voter_rolls.last_name,
            voter_rolls.given_names
           FROM voter_rolls
          WHERE (voter_rolls.voter_id IN ( SELECT voter_rolls_1.voter_id
                   FROM voter_rolls voter_rolls_1
                  WHERE voter_rolls_1.voter_id <> 0
                  GROUP BY voter_rolls_1.voter_id
                 HAVING count(*) > 1))
          GROUP BY voter_rolls.voter_id, voter_rolls.last_name, voter_rolls.given_names
         HAVING count(*) = 1
          ORDER BY (count(*)) DESC, voter_rolls.last_name, voter_rolls.given_names;
        SQL;

        return $sql;
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS report_vote_fraud_diff_people');
    }
}
