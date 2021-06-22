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
Route::get('trainers/detail', [LK1cController::class, 'trainersDetail']);
Route::get('training/cancel', [LK1cController::class, 'trainingCancel']);
Route::get('shop/products', [LK1cController::class, 'products']);
Route::post('auth/login', [LK1cController::class, 'login']);
Route::post('reg', [LK1cController::class, 'register']);
Route::get('client', [LK1cController::class, 'getClient']);
Route::put('client', [LK1cController::class, 'updateClient']);
Route::post('confirm_phone', [LK1cController::class, 'confirmPhone']);
Route::post('reset_password', [LK1cController::class, 'resetPassword']);
Route::post('subscription/write', [LK1cController::class, 'subWrite']);
Route::post('subscription/product/reserved', [LK1cController::class, 'subInvoice']);

