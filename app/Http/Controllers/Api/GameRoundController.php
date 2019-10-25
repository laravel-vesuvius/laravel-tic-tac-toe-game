<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GameRoundService;
use Illuminate\Http\Request;
use App\Http\Resources\GameRoundCollection;
use App\Http\Resources\GameRound as GameRoundResource;
use App\Http\Requests\Api\StoreGameRound;

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

        return new GameRoundResource($gameRound);
    }
}
