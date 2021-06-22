<?php

namespace App\Http\Controllers\API\fitroomLkDb1c;

use App\Http\Controllers\API\ClubController;
use App\Http\Controllers\Controller;
use App\Services\Client;
use App\Services\fitroomLkDb1c\RequestDB;
use App\Services\fitroomLkDb1c\Trainers;
use App\Services\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LK1cController extends Controller
{
    public function club(Request $request)
    {
        $response = Http::withBasicAuth(env('APP_BASIC_LOGIN'), env('APP_BASIC_PASSWORD'))
            ->withHeaders(['apikey' => '1a5a6f3b-4504-40b7-b286-14941fd2f635'])
            ->get(env('API_ADDR').'/clubs');
        return response($response->json()['data'], 200);
    }

    public function clubs()
    {
        $clubs = ClubController::index();
        return response($clubs);
    }

    public function login()
    {

    }

    public function register()
    {

    }

    public function getClient()
    {

    }

    public function updateClient()
    {

    }

    public function confirmPhone()
    {

    }

    public function resetPassword()
    {

    }

    public function trainers(Request $request)
    {
        $clubId = $request->header("club_id");
        $utoken = $request->cookie("utoken");

        $trainers = Trainers::getTrainers($clubId, $utoken);
        return response($trainers);
    }

    public function trainersDetail(Request $request)
    {
        $utoken = $request->cookie("utoken");
        $clubId = $request->input("club_id");
        $employeeId = $request->input("employee_id");

        $trainer = Trainers::getDetail($clubId, $utoken, $employeeId);
        return $trainer;
    }

    public function trainingCancel(Request $request)
    {
        $club_id = $request->header("club_id");
        $utoken = $request->cookie("utoken");

        $response = RequestDB::deleteTraining($club_id, $utoken, $request->input('appointment_id'));
        return $response;
    }

    public function products(Request $request)
    {
        $clubId = $request->header('club_id');
        $utoken = $request->cookie('usertoken');

        $products = Shop::getShopProducts($clubId, $utoken);
        return response($products);
    }

    public function subWrite(Request $request)
    {
        $clubId = $request->header('club_id');
        $utoken = $request->cookie('usertoken');
        $employeeId = $request->input('employee_id');
        $date = $request->input('date');
        $time = $request->input('time');

        $subscriptions = Client::subWrite($clubId, $utoken, $employeeId, $date, $time);
        return $subscriptions;
    }

}
