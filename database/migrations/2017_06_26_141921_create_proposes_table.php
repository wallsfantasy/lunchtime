<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProposesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proposes', function (Blueprint $table) {
            $table->increments('id');
            $table->date('for_date');
            $table->text('note')->nullable();
            $table->dateTime('proposed_at');
            $table->timestamps();

            // user
            $table->integer('user_id')->unsigned()->index();

            // restaurant
            $table->integer('restaurant_id')->unsigned()->index();

            // index
            $table->index(['user_id', 'for_date']);
            $table->index(['user_id', 'for_date', 'proposed_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proposes');
    }
}
