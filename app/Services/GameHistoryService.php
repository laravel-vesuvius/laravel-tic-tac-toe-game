<?php

namespace App\Services;

use App\Game;
use App\GameHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class GameHistoryService
{
    public function create(array $data): GameHistory
    {
        $gameHistory = GameHistory::make(Arr::only($data, [
            'game_id',
            'game_round_id',
            'game_row',
            'game_column',
            'player_type',
        ]));

        return DB::transaction(function () use ($gameHistory) {
            $gameHistory->saveOrFail();

            return $gameHistory;
        });
    }

    public function getAllByGame(Game $game, int $roundId): Collection
    {
        $gameHistories = GameHistory::where([
            'game_id' => $game->id,
            'game_round_id' => $roundId,
        ])->orderBy('id', 'asc');

        return $gameHistories->get();
    }

    public function countByGame(Game $game, int $roundId): int
    {
        $gameHistories = GameHistory::where([
            'game_id' => $game->id,
            'game_round_id' => $roundId,
        ]);

        return $gameHistories->count();
    }

    public function isFullGameField(int $countHistories, int $gameSize): bool
    {
        return $countHistories === ($gameSize * $gameSize);
    }

    public function getPreparedData(Game $game, int $roundId, int $gameSize): array
    {
        $gameHistories = [];
        $playerHistories = [];

        $playerType = null;

        $histories = $this->getAllByGame($game, $roundId);

        foreach ($histories as $history) {
            $playerType = $history->player_type;

            $gameHistories[$history->game_row][$history->game_column] = $playerType;
            $playerHistories[$playerType][$history->game_row][$history->game_column] = true;
        }

        $horizontalSuccess = $this->getHorizontalSuccess($gameHistories, $playerHistories, $gameSize);
        $verticalSuccess = $this->getVerticalSuccess($gameHistories, $playerHistories, $gameSize);

        $diagonalRightSuccess = $this->getDiagonalRightSuccess($gameHistories, $playerHistories, $gameSize);
        $diagonalLeftSuccess = $this->getDiagonalLeftSuccess($gameHistories, $playerHistories, $gameSize);

        $gameOver = $this->isGameOver($gameSize, $horizontalSuccess, $verticalSuccess, $diagonalRightSuccess, $diagonalLeftSuccess);

        $playerWinner = $this->getPlayerWinner($gameSize, $gameHistories, $horizontalSuccess, $verticalSuccess, $diagonalRightSuccess, $diagonalLeftSuccess);

        return compact(
            'playerType',
            'playerWinner',
            'gameHistories',
            'horizontalSuccess',
            'verticalSuccess',
            'diagonalRightSuccess',
            'diagonalLeftSuccess',
            'gameOver'
        );
    }

    private function getPlayerWinner(
        int $gameSize,
        array $gameHistories,
        array $horizontalSuccess,
        array $verticalSuccess,
        array $diagonalRightSuccess,
        array $diagonalLeftSuccess
    ): ?int {
        for ($row = 1; $row <= $gameSize; $row++) {
            for ($col = 1; $col <= $gameSize; $col++) {
                if ($horizontalSuccess[$row] ?? null) {
                    return $gameHistories[$row][$col];

                } elseif ($verticalSuccess[$col] ?? null) {
                    return $gameHistories[$row][$col];

                } elseif ($diagonalRightSuccess[$row][$col] ?? null) {
                    return $gameHistories[$row][$col];

                } elseif ($diagonalLeftSuccess[$row][$col] ?? null) {
                    return $gameHistories[$row][$col];
                }
            }
        }

        return null;
    }

    private function isGameOver(
        int $gameSize,
        array $horizontalSuccess,
        array $verticalSuccess,
        array $diagonalRightSuccess,
        array $diagonalLeftSuccess
    ): bool {
        $gameOver = false;

        for ($row = 1; $row <= $gameSize; $row++) {
            for ($col = 1; $col <= $gameSize; $col++) {
                if ($horizontalSuccess[$row] ?? null) {
                    $gameOver = true;

                } elseif ($verticalSuccess[$col] ?? null) {
                    $gameOver = true;

                } elseif ($diagonalRightSuccess[$row][$col] ?? null) {
                    $gameOver = true;

                } elseif ($diagonalLeftSuccess[$row][$col] ?? null) {
                    $gameOver = true;
                }
            }
        }

        return $gameOver;
    }

    private function getHorizontalSuccess(array $gameHistories, array $playerHistories, int $gameSize): array
    {
        $horizontalSuccess = [];

        for ($row = 1; $row <= $gameSize; $row++) {
            $horizontalSuccess[$row] = true;
            $firstCell = null;

            for ($col = 1; $col <= $gameSize; $col++) {
                if ($firstCell === null) {
                    $firstCell = $gameHistories[$row][$col] ?? false;
                }

                $cell = $playerHistories[$firstCell][$row][$col] ?? false;

                $horizontalSuccess[$row] = $horizontalSuccess[$row] && $cell;
            }
        }

        return $horizontalSuccess;
    }

    private function getVerticalSuccess(array $gameHistories, array $playerHistories, int $gameSize): array
    {
        $verticalSuccess = [];

        for ($col = 1; $col <= $gameSize; $col++) {
            $verticalSuccess[$col] = true;
            $firstCell = null;

            for ($row = 1; $row <= $gameSize; $row++) {
                if ($firstCell === null) {
                    $firstCell = $gameHistories[$row][$col] ?? false;
                }

                $cell = $playerHistories[$firstCell][$row][$col] ?? false;

                $verticalSuccess[$col] = $verticalSuccess[$col] && $cell;
            }
        }

        return $verticalSuccess;
    }

    private function getDiagonalRightSuccess(array $gameHistories, array $playerHistories, int $gameSize): array
    {
        $diagonalRightSuccess = [];
        $diagonalRight = 0;

        $firstCell = null;

        for ($row = 1; $row <= $gameSize; $row++) {
            for ($col = 1; $col <= $gameSize; $col++) {
                if ($row === $col) {
                    if ($firstCell === null) {
                        $firstCell = $gameHistories[$row][$col] ?? false;
                    }

                    if (! isset($diagonalRightSuccess[$row][$col])) {
                        $diagonalRightSuccess[$row][$col] = true;
                    }

                    $cell = $playerHistories[$firstCell][$row][$col] ?? false;

                    if ($cell) {
                        $diagonalRight++;
                    }

                    $diagonalRightSuccess[$row][$col] = $diagonalRightSuccess[$row][$col] && $cell;
                }
            }
        }

        if ($diagonalRight < $gameSize) {
            $diagonalRightSuccess = [];
        }

        return $diagonalRightSuccess;
    }

    private function getDiagonalLeftSuccess(array $gameHistories, array $playerHistories, int $gameSize): array
    {
        $diagonalLeftSuccess = [];
        $diagonalLeft = 0;

        $lastCell = null;

        for ($row = 1; $row <= $gameSize; $row++) {
            for ($col = 1; $col <= $gameSize; $col++) {
                if (($col === $gameSize && $row === 1) || ($col === ($gameSize - $row + 1))) {
                    if ($lastCell === null) {
                        $lastCell = $gameHistories[$row][$col] ?? false;
                    }

                    if (! isset($diagonalLeftSuccess[$row][$col])) {
                        $diagonalLeftSuccess[$row][$col] = true;
                    }

                    $cell = $playerHistories[$lastCell][$row][$col] ?? false;

                    if ($cell) {
                        $diagonalLeft++;
                    }

                    $diagonalLeftSuccess[$row][$col] = $diagonalLeftSuccess[$row][$col] && $cell;
                }
            }
        }

        if ($diagonalLeft < $gameSize) {
            $diagonalLeftSuccess = [];
        }

        return $diagonalLeftSuccess;
    }
}
