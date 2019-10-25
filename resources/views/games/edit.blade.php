@extends('layouts.app')
@section('content')

    @if ($errors->any())

        <div class="alert alert-danger" role="alert">
            <p class="mb-0">{{ __('Errors') }}</p>
            <ul class="mb-0">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>
        </div>

    @endif

    {!! Form::open(['route' => ['games.update', $game], 'method' => 'put']) !!}

        <div class="form-group">

            {{ Form::label('second_player_name', __('Second player')) }}

            {{ Form::text('second_player_name', old('second_player_name'), ['class' => 'form-control'
                . ($errors->has('second_player_name') ? ' is-invalid' : ''), 'placeholder' => __('Name')]) }}

            @if ($errors->has('second_player_name'))
                <div class="invalid-feedback">

                    {{ $errors->first('second_player_name') }}

                </div>
            @endif

        </div>

        {!! Form::submit(__('Start'), ['class' => 'btn btn-primary']) !!}

    {!! Form::close() !!}

@endsection
