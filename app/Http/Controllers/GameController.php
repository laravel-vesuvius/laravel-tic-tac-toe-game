<?php

namespace App\Http\Controllers;

use App\Game;
use App\GameRound;
use App\Services\GameService;
use App\Services\GameHistoryService;
use Illuminate\Http\Request;
use App\Http\Requests\StoreGame;
use App\Http\Requests\UpdateGame;

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
        $games = Game::all();

        return view('games.index', compact('games'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('games.create');
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

        return redirect()->route('games.edit', [$game]);
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
        abort_unless($token && $round, 404);

        $gameByToken = $this->gameService->getFirstByToken($token);
        abort_unless($game->id === $gameByToken->id, 404);

        $gameRound = GameRound::find($round);
        abort_unless($gameRound && $gameRound->game_id === $game->id, 404);

        $gameSize = Game::DEFAULT_SIZE;
        $firstPlayerType = Game::FIRST_PLAYER_TYPE;
        $secondPlayerType = Game::SECOND_PLAYER_TYPE;

        $prepareData = $this->gameHistoryService->getPreparedData($game, $round, $gameSize);
        $gameCountHistories = $this->gameHistoryService->countByGame($game, $round);

        $isFullGameField = $this->gameHistoryService->isFullGameField($gameCountHistories, $gameSize);

        return view('games.show', compact(
            'game',
            'gameSize',
            'gameCountHistories',
            'prepareData',
            'round',
            'isFullGameField',
            'firstPlayerType',
            'secondPlayerType'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function edit(Game $game)
    {
        return view('games.edit', compact('game'));
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

        return redirect()->route('games.show', [
            'game' => $game,
            'token' => $game->token,
            'round' => $game->gameRoundLatest->id ?? null,
        ]);
    }
}
