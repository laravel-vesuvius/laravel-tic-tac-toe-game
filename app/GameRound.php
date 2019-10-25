<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\GameRound
 *
 * @property int $id
 * @property int $game_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Game $game
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\GameHistory[] $gameHistories
 * @property-read int|null $game_histories_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GameRound newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GameRound newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GameRound query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GameRound whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GameRound whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GameRound whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GameRound whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class GameRound extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'game_id',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function gameHistories()
    {
        return $this->hasMany(GameHistory::class, 'game_round_id', 'id');
    }
}
