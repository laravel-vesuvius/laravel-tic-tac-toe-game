<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGameHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_histories', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('game_id');
            $table->unsignedBigInteger('game_round_id');

            $table->foreign('game_id')->references('id')->on('games')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('game_round_id')->references('id')->on('game_rounds')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->unsignedTinyInteger('player_type');

            $table->unsignedSmallInteger('game_row');

            $table->unsignedSmallInteger('game_column');

            $table->unique([
                'game_round_id',
                'player_type',
                'game_row',
                'game_column',
            ], 'game_histories_unique');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_histories');
    }
}
