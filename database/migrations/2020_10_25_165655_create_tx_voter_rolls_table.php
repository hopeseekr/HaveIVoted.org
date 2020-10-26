<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTXVoterRollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // "COUNTY","VOTER_NAME","ID_VOTER","VOTING_METHOD","PRECINCT"
        Schema::create('voter_rolls', function (Blueprint $table) {
            $table->char('id', 22);
            $table->char('county_id', 22);
            $table->string('last_name');
            $table->string('given_names');
            $table->bigInteger('voter_id');
            $table->string('voting_method');
            $table->string('precinct');
            $table->date('recorded_on');

            $table->timestamps();

            $table->index('county_id');
            $table->index('last_name');
            $table->index('given_names');
            $table->index(['last_name', 'given_names']);
            $table->index('voting_method');
            $table->index('recorded_on');

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
        Schema::dropIfExists('voter_rolls');
    }
}
