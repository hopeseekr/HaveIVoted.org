<?php declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateVotesByCountyView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement(self::votesByCountySQL());
    }

    public static function votesByCountySQL(): string
    {
        return <<<SQL
            CREATE VIEW votes_by_county AS
            SELECT c.name county, COUNT(*) votes 
            FROM voter_rolls vr 
            JOIN counties c 
                ON c.id=vr.county_id 
            GROUP BY c.name
            ORDER BY COUNT(*) DESC;
        SQL;
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS votes_by_county');
    }
}
