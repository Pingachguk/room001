<?php

namespace App\Http\Controllers\API\fitroomLkDb1c;

use App\Http\Controllers\API\ClubController;
use App\Http\Controllers\Controller;
use App\Services\Client;
use App\Services\fitroomLkDb1c\RequestDB;
use App\Services\fitroomLkDb1c\Trainers;
use App\Services\Sber;
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

    public function uploadImage(Request $request)
    {
        $image = $request->input('image');

        $contentType = [
            'image/png' => 'png',
            'image/jpg' => 'jpg',
            'image/jpeg' => 'jpeg'
        ];

//        if (array_key_exists(filetype())){
//
//        }

        if (getimagesize($image) < 30000000) {
            $newFilename = 'IMAGE'.random_int(11111111, 999999999);
            $photoWrite = fopen('images/uploads' . $newFilename, 'w');
            fwrite($photoWrite, $image);
            return [
                'result' => true,
                'filename' => $newFilename
            ];
        } else {
            return [
                'result' => false,
                'message' => 'Загрузите изображение меньшего размера'
            ];
        }
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

    public function verified(Request $request)
    {
        $name = $request->input('name');
        $lastName = $request->input('last_name');
        $phone = $request->input('phone');
        $passportNumber = $request->input('passport_number');
        $passportDate = $request->input('passport_date');
        $passportPlace = $request->input('passport_place');
        $images = $request->input('images');

        $result = Client::sendPassport($name, $lastName, $phone, $passportNumber, $passportDate, $passportPlace, $images);

        return response($result);
    }

    public function getClient(Request $request)
    {
        $clubId = $request->header('club_id');
        $utoken = $request->header('utoken');

        $client = Client::getClient($clubId, $utoken);
        return response($client);
    }

    public function updateClient(Request $request)
    {
        $utoken = $request->header('utoken');
        $response = RequestDB::updateClient($utoken, $request->input());
        return response($response);
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
        info($products);
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

    public function buyReservedTrain(Request $request)
    {
        $clubId = $request->input("club_id");
        $appointmentId = $request->input("appointment_id");
        $utoken = $request->header("usertoken");

        $payment = Shop::payReservedTrain($clubId, $utoken, $appointmentId);
        return response($payment);
    }

    public function reserveTrainBeforePay(Request $request)
    {
        $clubId = $request->input('club_id');
        $utoken = $request->header('usertoken');
        $category = $request->input('category');
        $type = $request->input('type');
        $employeeId = $request->input('employee_id');
        $date = $request->input('date');
        $time = $request->input('time');
        $promocode = $request->input('promocode');

        $result = Shop::reserveBeforePay($clubId, $utoken, $category, $type, $employeeId, $date, $time, $promocode);

        return response($result);
    }

    public function checkOrder(Request $request)
    {
        $orderId = $request->input('order_id');
        $result = Sber::getCallback($orderId, Null, Null, Null);
        return response($result);
    }

    public function sberCallback(Request $request)
    {
        $mdOrder = $request->input('mdOrder');
        $orderNumber = $request->input('orderNumber');
        $operation = $request->input('operation');
        $status = $request->input('status');

        $callback = Sber::getCallback($mdOrder, $orderNumber, $operation, $status);

        return response($callback);
    }
}
