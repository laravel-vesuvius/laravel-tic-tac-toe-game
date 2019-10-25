<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'WelcomeController@index')
    ->name('welcome');

Route::resource('games', 'GameController')->except(['destroy']);

Route::post('game-histories', 'GameHistoryController@store')
    ->name('gameHistories.store');

Route::post('game-rounds', 'GameRoundController@store')
    ->name('gameRounds.store');
