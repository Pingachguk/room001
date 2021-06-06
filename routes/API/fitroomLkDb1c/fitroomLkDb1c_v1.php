<?php

//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\fitroomLkDb1c\LK1cController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Роуты для личного кабинета fitroom c привязкой к удаленной базе 1С
|
*/
Route::get('clubs', [LK1cController::class, 'clubs']);
Route::get('club/{id}', [LK1cController::class, 'club']);
Route::post('login', [LK1cController::class, 'login']);
Route::post('reg', [LK1cController::class, 'register']);
Route::post('reset', [LK1cController::class, 'resetPassword']);
Route::get('client', [LK1cController::class, 'getClient']);
Route::put('client', [LK1cController::class, 'updateClient']);
