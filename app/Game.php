<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * App\Game
 *
 * @property int $id
 * @property string $first_player_name
 * @property string|null $second_player_name
 * @property string $token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\GameRound $gameRoundLatest
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\GameRound[] $gameRounds
 * @property-read int|null $game_rounds_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Game newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Game newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Game query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Game whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Game whereFirstPlayerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Game whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Game whereSecondPlayerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Game whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Game whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Game extends Model
{
    public const DEFAULT_SIZE = 3;
    public const FIRST_PLAYER_TYPE = 1;
    public const SECOND_PLAYER_TYPE = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_player_name',
        'second_player_name',
        'token',
    ];

    public static function generateToken(): string
    {
        return (string) Str::uuid();
    }

    public static function getPlayerTypes(?int $type = null)
    {
        $types = [
            self::FIRST_PLAYER_TYPE => 'x',
            self::SECOND_PLAYER_TYPE => 'o',
        ];

        if ($type !== null) {
            return $types[$type] ?? null;
        }

        return $types;
    }

    public function setToken(): void
    {
        $this->token = self::generateToken();
    }

    public function gameRounds()
    {
        return $this->hasMany(GameRound::class, 'game_id', 'id');
    }

    public function countGameRounds()
    {
        return $this->hasMany(GameRound::class, 'game_id', 'id')->count();
    }

    public function gameRoundLatest()
    {
        return $this->hasOne(GameRound::class, 'game_id', 'id')->latest();
    }
}
