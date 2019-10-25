<?php

namespace App\Services;

use App\GameRound;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class GameRoundService
{
    public function create(array $data): GameRound
    {
        $gameRound = GameRound::make(Arr::only($data, [
            'game_id',
        ]));

        return DB::transaction(function () use ($gameRound) {
            $gameRound->saveOrFail();

            return $gameRound;
        });
    }
}
