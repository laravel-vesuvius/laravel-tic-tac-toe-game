<?php

namespace App\Http\Controllers;

use App\GameHistory;
use App\Services\GameHistoryService;
use Illuminate\Http\Request;
use App\Http\Requests\StoreGameHistory;

class GameHistoryController extends Controller
{
    protected $gameHistoryService;

    public function __construct(GameHistoryService $gameHistoryService)
    {
        $this->gameHistoryService = $gameHistoryService;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGameHistory $request)
    {
        $gameHistory = $this->gameHistoryService->create($request->validated());

        return redirect()->route('games.show', [
            'game' => $gameHistory->game,
            'token' => $gameHistory->game->token,
            'round' => $gameHistory->game_round_id,
        ]);
    }
}
