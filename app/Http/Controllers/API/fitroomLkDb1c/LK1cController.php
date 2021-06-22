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

    public function login(Request $request)
    {
        $phone = $request->input('phone');
        $password = $request->input('password');

        $products = RequestDB::postAuth($phone, $password);
        return response($products);
    }

    public function register(Request $request)
    {
        $clubId = $request->header('club_id');

        $response = RequestDB::postRegister($clubId, $request->input());
        if ($response->json()['reuslt']) {
            $utoken = $response->json()['data']['user_token'];
            return response('200', 200)->cookie('utoken', $utoken, 60 * 24);
        } else {
            return response($response->json());
        }
    }

    public function getClient(Request $request)
    {
        $clubId = $request->header('club-id');
        $utoken = $request->header('utoken');

        $client = Client::getClient($clubId, $utoken);
        return response($client);
    }

    public function updateClient(Request $request)
    {
        $utoken = $request->cookie('utoken');
        $response = RequestDB::updateClient($utoken, $request->input());
        return response($response->json());
    }

    public function confirmPhone(Request $request)
    {
        $data = $request->input();
        $clubId = $request->header('club_id');

        if (key_exists('confirmation_code', $data)) {
            $response = RequestDB::postConfirmationCode($clubId, $data);
            return response($response);
        } else {
            $code = RequestDB::getCodeOnPhone($clubId, $data);
            return response($code);
        }
    }

    public function resetPassword(Request $request)
    {
        $data = $request->input();
        $clubId = $request->header('club_id');

        $response = RequestDB::postNewPassword($clubId, $data);
        return response($response);
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

        $subscriptions = Shop::subWrite($clubId, $utoken, $employeeId, $date, $time);
        return response($subscriptions);
    }

    public function subInvoice(Request $request)
    {
        $clubId = $request->input("club_id");
        $appointmentId = $request->input("appointment_id");
        $utoken = $request->cookie("usertoken");

        $payment = Shop::subPay($clubId, $utoken, $appointmentId);
        return response($payment);
    }
}
