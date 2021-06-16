<?php

use App\Http\Controllers\API\ClubController;
use App\Http\Controllers\API\lists\FilialController;
use App\Http\Controllers\API\lists\GKController;
use App\Http\Controllers\API\lists\MetroController;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DataController;
use App\Http\Controllers\API\lists\CityController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Роуты для админки
|
*/
Route::resource('/cities', CityController::class);
Route::resource('/metro', MetroController::class);
Route::resource('/gk', GKController::class);
Route::resource('/filials', FilialController::class);
Route::apiResource('/clubs', ClubController::class);
Route::get('/load-data', [DataController::class, 'loadData']);
