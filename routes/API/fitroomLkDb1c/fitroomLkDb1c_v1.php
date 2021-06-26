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
// CLUBS
Route::get('clubs', [LK1cController::class, 'clubs']);
Route::get('club/{id}', [LK1cController::class, 'club']);

// IMAGES
Route::get('images/upload', [LK1cController::class, 'uploadImage']);

// TRAINERS
Route::get('trainers', [LK1cController::class, 'trainers']);
Route::get('trainers/detail', [LK1cController::class, 'trainersDetail']);
Route::get('training/cancel', [LK1cController::class, 'trainingCancel']);

// CLIENTS
Route::post('auth/login', [LK1cController::class, 'login']);
Route::post('reg', [LK1cController::class, 'register']);
Route::get('client', [LK1cController::class, 'getClient']);
Route::put('client', [LK1cController::class, 'updateClient']);
Route::post('auth/confirm-phone', [LK1cController::class, 'confirmPhone']);
Route::post('auth/reset', [LK1cController::class, 'resetPassword']);
Route::post('verified/send', [LK1cController::class, 'verified']);

//SHOP
Route::get('shop/products', [LK1cController::class, 'products']);
Route::post('subscription/write', [LK1cController::class, 'subWrite']);
Route::post('subscription/product/reserved', [LK1cController::class, 'buyReservedTrain']);
Route::post('subscription/write/once', [LK1cController::class, 'reserveTrainBeforePay']);
Route::post('subscription/product/pay', [LK1cController::class, 'buySub']);
Route::post('order/check', [LK1cController::class, 'checkOrder']);

// SBER
Route::get('payment/webhook_notify', [LK1cController::class, 'sberCallback']);

// TEST
Route::get('test', function () {
    $result = \App\Http\Controllers\OrderController::findById('3121');
    return response($result);
});

