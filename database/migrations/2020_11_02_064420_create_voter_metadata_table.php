<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVoterMetadataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voter_metadata', function (Blueprint $table) {
            $table->char('voter_roll_id', 22)->primary();
            $table->char('state', 2);
            $table->string('zipcode', 10)->nullable();
            $table->bigInteger('ballot_id')->nullable();
            $table->text('ballot_status')->nullable();
            $table->text('challenge_reason')->nullable();
            $table->datetimeTz('sent_at')->nullable();
            $table->datetimeTz('received_at')->nullable();

            $table->timestamps();

            $table->index(['voter_roll_id', 'state']);

            $table->foreign('state')
                ->references('code')
                ->on('states');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('voter_metadata');
    }
}
