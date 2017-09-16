<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCyclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // cycles
        Schema::create('cycles', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->timestamps();
            $table->string('name');
            $table->time('propose_until');
            $table->time('lunchtime');
            $table->integer('creator_user_id')->unsigned()->nullable();
        });

        // cycle_members
        Schema::create('cycle_members', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            // cycle
            $table->uuid('cycle_id')->index();
            $table->foreign('cycle_id')->references('id')
                ->on('cycles')->onDelete('cascade');

            // user
            $table->integer('user_id')->unsigned()->index();

            // composite index
            $table->unique(['cycle_id', 'user_id']);
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cycle_members');
        Schema::dropIfExists('cycles');
    }
}
