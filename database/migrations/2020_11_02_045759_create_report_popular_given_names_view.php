<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateReportPopularGivenNamesView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement(self::reportPopularGivenNamesSQL());
    }

    public static function reportPopularGivenNamesSQL(): string
    {
        $sql = <<<'SQL'
        CREATE VIEW report_popular_given_names AS
        SELECT
            row_number() OVER (ORDER BY (count(*)) DESC) AS row_number,
            voter_rolls.given_names,
            count(*) AS count
        FROM voter_rolls
        WHERE voter_rolls.given_names::text ~~ '% %'::text
        GROUP BY voter_rolls.given_names
        ORDER BY (count(*)) DESC;
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
        DB::statement('DROP VIEW IF EXISTS report_popular_given_names');
    }
}
