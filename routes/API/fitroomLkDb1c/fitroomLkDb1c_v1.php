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
Route::get('trainers', [LK1cController::class, 'trainers']);
Route::get('shop/products', [LK1cController::class, 'products']);
Route::post('login', [LK1cController::class, 'login']);
Route::post('reg', [LK1cController::class, 'register']);
Route::get('client', [LK1cController::class, 'getClient']);
Route::put('client', [LK1cController::class, 'updateClient']);
Route::post('confirm_phone', [LK1cController::class, 'confirmPhone']);
Route::post('reset_password', [LK1cController::class, 'resetPassword']);
