<?php

use Illuminate\Support\Facades\Route;
use App\Mail\VerificationMail;
use Illuminate\Support\Facades\Mail;

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

Route::get('/', function () {
    return view('welcome');
});

// Emails
Route::get('secretemail/', function () {
    $data["email"] = "andreiyakimov4@yandex.ru";
    $data["title"] = "websolutionstuff.com";
    $data["body"] = "This is test mail with attachment";

//    Mail::to($data['email'])->send('');

    echo "Mail send successfully !!";
});
