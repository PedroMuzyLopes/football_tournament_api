<?php

use App\Http\Controllers\TeamController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\UserController;

use Illuminate\Support\Facades\Route;

/* 
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::post('/user', [UserController::class, 'create']);

Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    /* 
|--------------------------------------------------------------------------
| Team
|--------------------------------------------------------------------------
*/

    // Create new team
    Route::post('/teams', [TeamController::class, 'create']);

    // List all teams
    Route::get('/teams', [TeamController::class, 'listAll']);

    // List specific team, searching by ID
    Route::get('/teams/{id}', [TeamController::class, 'listById']);

    // // Create new team
    // Route::post('/teams', [TeamController::class, 'create']);

    // Update existing team
    Route::match(['put', 'patch'], '/teams/{id}', [TeamController::class, 'update']);

    // Delete team
    Route::delete('/teams/{id}', [TeamController::class, 'delete']);

    // Add player to the team
    Route::post('/teams/{id}/players', [TeamController::class, 'addPlayer']);

    // List all players in specifc team
    Route::get('/teams/{id}/players', [TeamController::class, 'getPlayers']);

    // Remove player from specifc team
    Route::delete('/teams/{id}/players', [TeamController::class, 'removePlayer']);

    /* 
|--------------------------------------------------------------------------
| Player
|--------------------------------------------------------------------------
*/

    // List all players
    Route::get('/players', [PlayerController::class, 'listAll']);

    // List specific player, searching by ID
    Route::get('/players/{id}', [PlayerController::class, 'listById']);

    // Create new Player
    Route::post('/players', [PlayerController::class, 'create']);

    // Update existing player
    Route::match(['put', 'patch'], '/players/{id}', [PlayerController::class, 'update']);

    // Delete player
    Route::delete('/players/{id}', [PlayerController::class, 'delete']);


    /* 
|--------------------------------------------------------------------------
| Match
|--------------------------------------------------------------------------
*/

    // List all teams
    Route::get('/matches', [GameController::class, 'listAll']);

    // List specific team, searching by ID
    Route::get('/matches/{id}', [GameController::class, 'listById']);

    // Create new team
    Route::post('/matches', [GameController::class, 'create']);

    // Update existing team
    Route::match(['put', 'patch'], '/matches/{id}', [GameController::class, 'update']);

    // Delete team
    Route::delete('/matches/{id}', [GameController::class, 'delete']);


    /* 
|--------------------------------------------------------------------------
| Ranking / Classifications
|--------------------------------------------------------------------------
*/

    // List all
    Route::get('/ranking', [RankingController::class, 'listAll']);
});
