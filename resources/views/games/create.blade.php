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

    {!! Form::open(['route' => 'games.store', 'method' => 'post']) !!}

        <div class="form-group">

            {{ Form::label('first_player_name', __('First player')) }}

            {{ Form::text('first_player_name', old('first_player_name'), ['class' => 'form-control'
                . ($errors->has('first_player_name') ? ' is-invalid' : ''), 'placeholder' => __('Name')]) }}

            @if ($errors->has('first_player_name'))
                <div class="invalid-feedback">

                    {{ $errors->first('first_player_name') }}

                </div>
            @endif

        </div>

        {!! Form::submit(__('Start'), ['class' => 'btn btn-primary']) !!}

    {!! Form::close() !!}

@endsection
