<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCountiesByPolParty extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // ,votes_dem,votes_gop,total_votes,per_dem,per_gop,diff,per_point_diff,state_abbr,county_name,combined_fips
        Schema::create('counties_by_pol_party_2016', function (Blueprint $table) {
            $table->char('county_id', 22)->primary();
            $table->integer('total_votes');
            $table->float('republican');
            $table->float('democrat');

            $table->index('county_id');

            $table->foreign('county_id')
                ->references('id')
                ->on('counties');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('counties_by_pol_party_2016');
    }
}
