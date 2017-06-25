<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCyclesUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cycle_user', function (Blueprint $table) {
            // cycle
            $table->integer('cycle_id')->unsigned()->index();
            $table->foreign('cycle_id')->references('id')
                ->on('cycles')->onDelete('cascade');

            // user
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('cascade');

            // composite key
            $table->primary(['cycle_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cycle_user');
    }
}
