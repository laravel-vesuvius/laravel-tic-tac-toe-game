<?php

namespace App\Http\Controllers\Api;

use App\Game;
use App\GameRound;
use App\Http\Controllers\Controller;
use App\Services\GameHistoryService;
use App\Services\GameService;
use Illuminate\Http\Request;
use App\Http\Requests\Api\StoreGame;
use App\Http\Requests\Api\UpdateGame;
use App\Http\Resources\GameCollection;
use App\Http\Resources\Game as GameResource;

class GameController extends Controller
{
    protected $gameService;

    protected $gameHistoryService;

    public function __construct(GameService $gameService, GameHistoryService $gameHistoryService)
    {
        $this->gameService = $gameService;
        $this->gameHistoryService = $gameHistoryService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new GameCollection(Game::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGame $request)
    {
        $game = $this->gameService->create($request->validated());

        return new GameResource($game);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Game $game)
    {
        $token = $request->query('token');
        $round = $request->query('round');

        if (! ($token && $round)) {
            return response()->json([], 404);
        }

        $gameByToken = $this->gameService->getFirstByToken($token);

        if (! ($gameByToken && $gameByToken->id === $game->id)) {
            return response()->json([], 404);
        }

        $gameRound = GameRound::find($round);

        if (! ($gameRound && $gameRound->game_id === $game->id)) {
            return response()->json([], 404);
        }

        $gameSize = Game::DEFAULT_SIZE;
        $firstPlayerType = Game::FIRST_PLAYER_TYPE;
        $secondPlayerType = Game::SECOND_PLAYER_TYPE;

        $prepareData = $this->gameHistoryService->getPreparedData($game, $round, $gameSize);
        $gameCountHistories = $this->gameHistoryService->countByGame($game, $round);

        $isFullGameField = $this->gameHistoryService->isFullGameField($gameCountHistories, $gameSize);

        return response()->json([
            'data' => compact(
                'game',
                'gameSize',
                'gameCountHistories',
                'prepareData',
                'round',
                'isFullGameField',
                'firstPlayerType',
                'secondPlayerType'
            ),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGame $request, Game $game)
    {
        $game = $this->gameService->update($game, $request->validated());

        return new GameResource($game);
    }
}
