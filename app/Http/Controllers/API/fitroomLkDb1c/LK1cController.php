<?php

namespace App\Http\Controllers\API\fitroomLkDb1c;

use App\Http\Controllers\Controller;
use App\Services\Clubs;
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

    public function clubs(Request $request)
    {
        return response(Clubs::getClubs(), 200);
    }
}
