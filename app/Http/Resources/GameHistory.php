<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GameHistory extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'game_id' => $this->game_id,
            'game_round_id' => $this->game_round_id,
            'player_type' => $this->player_type,
            'game_row' => $this->game_row,
            'game_column' => $this->game_column,
        ];
    }
}
