<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DropCountiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE "counties_by_pol_party_2016" RENAME TO "counties_by_pol_party_2016_orig"');

        Schema::create('votes_by_county_2016', function (Blueprint $table) {
            $table->string('county');
            $table->char('state', 2);
            $table->integer('votes');
            $table->float('republican');
            $table->float('democrat');

            $table->primary(['county', 'state']);
            $table->index(['county', 'state']);
        });

        $sql = <<<'SQL'
INSERT INTO "votes_by_county_2016"
SELECT c.name, 'TX', total_votes, republican, democrat
FROM "counties_by_pol_party_2016_orig" c2016
JOIN "counties" c ON c.id=c2016.county_id
ORDER BY c.name;
SQL;

        DB::statement($sql);

        Schema::dropIfExists('counties_by_pol_party_2016_orig');
        Schema::dropIfExists('counties');
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
