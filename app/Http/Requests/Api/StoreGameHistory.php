<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Game;

class StoreGameHistory extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'game_id' => 'required|exists:games,id',
            'game_round_id' => 'required|exists:game_rounds,id',
            'game_row' => 'required|integer',
            'game_column' => 'required|integer',
            'player_type' => [
                'required',
                'integer',
                Rule::in(array_keys(Game::getPlayerTypes())),
            ],
        ];
    }
}
