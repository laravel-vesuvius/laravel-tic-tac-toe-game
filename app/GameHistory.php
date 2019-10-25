<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\GameHistory
 *
 * @property int $id
 * @property int $game_id
 * @property int $game_round_id
 * @property int $player_type
 * @property int $game_row
 * @property int $game_column
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Game $game
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GameHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GameHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GameHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GameHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GameHistory whereGameColumn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GameHistory whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GameHistory whereGameRoundId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GameHistory whereGameRow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GameHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GameHistory wherePlayerType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GameHistory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class GameHistory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'game_id',
        'game_round_id',
        'player_type',
        'game_row',
        'game_column',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
