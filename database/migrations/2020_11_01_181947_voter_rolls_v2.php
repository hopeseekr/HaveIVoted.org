<?php declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class VoterRollsV2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function () {
            DB::statement('DROP VIEW votes_by_county');

            Schema::create('voter_rolls_v2', function (Blueprint $table) {
                $table->char('id', 22);
                $table->string('county');
                $table->char('state', 2);
                $table->string('last_name');
                $table->string('given_names');
                $table->bigInteger('voter_id');
                $table->string('voting_method');
                $table->string('precinct');
                $table->date('recorded_on');

                $table->timestamps();

                $table->index('county');
                $table->index('last_name');
                $table->index('given_names');
                $table->index(['last_name', 'given_names']);
                $table->index('voting_method');
                $table->index('recorded_on');
            });

            $sql = <<<'SQL'
                INSERT INTO voter_rolls_v2
                    SELECT 
                        v.id, c.name county, 'TX' state, last_name, given_names, voter_id, 
                        voting_method, precinct, recorded_on, created_at, updated_at 
                    FROM voter_rolls v 
                    JOIN counties c ON c.id=v.county_id
                    ORDER BY county, state, recorded_on
            SQL;
            DB::statement($sql);

            Schema::dropIfExists('voter_rolls');
            DB::statement('ALTER TABLE "voter_rolls_v2" RENAME TO "voter_rolls"');

            DB::statement(self::votesByCountySQL());

            DB::statement('ALTER INDEX "voter_rolls_v2_county_index" RENAME TO "voter_rolls_county_index";');
            DB::statement('ALTER INDEX "voter_rolls_v2_last_name_index" RENAME TO "voter_rolls_last_name_index";');
            DB::statement('ALTER INDEX "voter_rolls_v2_given_names_index" RENAME TO "voter_rolls_given_names_index";');
            DB::statement('ALTER INDEX "voter_rolls_v2_last_name_given_names_index" RENAME TO "voter_rolls_last_name_given_names_index";');
            DB::statement('ALTER INDEX "voter_rolls_v2_voting_method_index" RENAME TO "voter_rolls_voting_method_index";');
            DB::statement('ALTER INDEX "voter_rolls_v2_recorded_on_index" RENAME TO "voter_rolls_recorded_on_index";');
        });
    }

    public static function votesByCountySQL(): string
    {
        return <<<SQL
            CREATE VIEW votes_by_county AS
            SELECT county, state, COUNT(*) votes 
            FROM voter_rolls vr 
            GROUP BY county, state
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
        throw new LogicException('This is a one-way migration. Plan carefully!');
    }
}

