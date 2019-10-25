<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use App\Game;

class GameService
{
    public function create(array $data): Game
    {
        $game = Game::make(Arr::only($data, [
            'first_player_name'
        ]));

        $game->setToken();

        return DB::transaction(function () use ($game) {
            $game->saveOrFail();

            $game->gameRounds()->create();

            return $game;
        });
    }

    public function update(Game $game, array $data): Game
    {
        $game->update(Arr::only($data, [
            'second_player_name',
        ]));

        return $game;
    }

    public function getFirstByToken(string $token): Game
    {
        $game = Game::where([
            'token' => $token
        ]);

        return $game->firstOrFail();
    }
}
