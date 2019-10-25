<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Game extends JsonResource
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
            'first_player_name' => $this->first_player_name,
            'second_player_name' => $this->second_player_name,
            'countGameRounds' => $this->countGameRounds(),
        ];
    }
}
