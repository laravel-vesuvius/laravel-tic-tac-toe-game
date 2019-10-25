<?php

namespace App\Http\Controllers\Api;

use App\Game;
use App\Http\Controllers\Controller;
use App\Services\GameHistoryService;
use Illuminate\Http\Request;
use App\Http\Resources\GameHistoryCollection;
use App\Http\Resources\GameHistory as GameHistoryResource;
use App\Http\Requests\Api\StoreGameHistory;

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

        return new GameHistoryResource($gameHistory);
    }
}
