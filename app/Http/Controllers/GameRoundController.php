<?php

namespace App\Http\Controllers;

use App\GameRound;
use Illuminate\Http\Request;
use App\Http\Requests\StoreGameRound;
use App\Services\GameRoundService;

class GameRoundController extends Controller
{
    protected $gameRoundService;

    public function __construct(GameRoundService $gameRoundService)
    {
        $this->gameRoundService = $gameRoundService;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGameRound $request)
    {
        $gameRound = $this->gameRoundService->create($request->validated());

        return redirect()->route('games.show', [
            'game' => $gameRound->game,
            'token' => $gameRound->game->token,
            'round' => $gameRound->id,
        ]);
    }
}
