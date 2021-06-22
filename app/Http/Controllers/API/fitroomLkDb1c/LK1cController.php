<?php

namespace App\Http\Controllers\API\fitroomLkDb1c;

use App\Http\Controllers\API\ClubController;
use App\Http\Controllers\Controller;
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

    public function trainers(Request $request)
    {
        $clubId = $request->header("club_id");
        $utoken = $request->cookie("utoken");

        $trainers = Trainers::getTrainers($clubId, $utoken);
        return response($trainers);
    }

    public function products(Request $request)
    {
        $clubId = $request->header('club_id');
        $utoken = $request->cookie('usertoken');

        $products = Shop::getShopProducts($clubId, $utoken);
        return response($products);
    }

}
