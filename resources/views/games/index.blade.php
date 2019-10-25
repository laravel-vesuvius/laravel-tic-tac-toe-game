@extends('layouts.app')
@section('content')

    <div class="table-responsive">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">{{ __('The players') }}</th>
                <th scope="col">{{ __('Number of games') }}</th>
            </tr>
            </thead>
            <tbody>

            @foreach ($games as $game)
            <tr>
                <th scope="row">{{ $game->id }}</th>
                <td>{{ $game->first_player_name }} | {{ $game->second_player_name }}</td>
                <td>{{ $game->countGameRounds() }}</td>
            </tr>
            @endforeach

            </tbody>
        </table>
    </div>

    <hr>

    <div class="row">
        <div class="col text-center">

            {{ link_to_route('games.create', __('New game'), [], ['class' => 'btn btn-success btn-block mb-3']) }}

        </div>
    </div>

@endsection
