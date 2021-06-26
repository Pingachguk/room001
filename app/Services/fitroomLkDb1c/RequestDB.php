<?php

namespace App\Services\fitroomLkDb1c;

use App\Services\Clubs;

//use http\Env\Request;
use Illuminate\Support\Facades\Http;

class RequestDB
{
    public static function postAuth($phone, $password)
    {
        $apikey = env('TECH_APIKEY');
        $client_auth = Http::withBasicAuth(env('APP_BASIC_LOGIN'), env('APP_BASIC_PASSWORD'))
            ->withHeaders([
                'apikey' => $apikey,
            ])
            ->post(env('API_ADDR') . '/auth_client/',
                [
                    'phone' => $phone,
                    'password' => $password,
                ]);
        return $client_auth->json();
    }

    public static function postRegister($clubId, $data)
    {
        $apikey = Clubs::getClubKeyById($clubId);

        $response = Http::withBasicAuth(env('APP_BASIC_LOGIN'), env('APP_BASIC_PASSWORD'))
            ->withHeaders([
                'apikey' => $apikey
            ])
            ->post(env('API_ADDR') . '/reg_and_auth_client/', $data);

        return $response->json();
    }

    public static function getClient($clubId, $utoken)
    {
        $apikey = env('TECH_APIKEY');
        $client = Http::withBasicAuth(env('APP_BASIC_LOGIN'), env('APP_BASIC_PASSWORD'))
            ->withHeaders([
                'apikey' => $apikey,
                'usertoken' => $utoken,
            ])
            ->get(env('API_ADDR') . '/client/');

        return $client->json();
    }

    public static function getTickets($clubId, $utoken)
    {
        $apikey = env('TECH_APIKEY');

        $ticket = Http::withBasicAuth(env('APP_BASIC_LOGIN'), env('APP_BASIC_PASSWORD'))
            ->withHeaders([
                'apikey' => $apikey,
                'usertoken' => $utoken,
            ])
            ->get(env('API_ADDR') . '/tickets/');

        return $ticket->json();
    }

    public static function getAppointments($clubId, $utoken)
    {
        $apikey = env('TECH_APIKEY');

        $appointments = Http::withBasicAuth(env('APP_BASIC_LOGIN'), env('APP_BASIC_PASSWORD'))
            ->withHeaders([
                'apikey' => $apikey,
                'usertoken' => $utoken,
            ])
            ->get(env('API_ADDR') . '/appointments/');

        return $appointments->json();
    }

    public static function getAppointmentsClub($clubId, $utoken, $appointmentId)
    {
        $apikey = Clubs::getClubKeyById($clubId);
        $clubId = Clubs::getClubIdById($clubId);

        $appointments = Http::withBasicAuth(env('APP_BASIC_LOGIN'), env('APP_BASIC_PASSWORD'))
            ->withHeaders([
                'apikey' => $apikey,
                'usertoken' => $utoken,
            ])
            ->get(env('API_ADDR') . '/appointments/', [
                'appointment_id' => $appointmentId,
                'club_id' => $clubId
            ]);

        return $appointments->json();
    }

    public static function getAppoint($clubId, $utoken, $appointmentId)
    {
        $apikey = CLubs::getClubKeyById($clubId);
        $clubId = Clubs::getClubIdById($clubId);

        $appoint = Http::withBasicAuth(env('APP_BASIC_LOGIN'), env('APP_BASIC_PASSWORD'))
            ->withHeaders([
                'apikey' => $apikey,
                'usertoken' => $utoken,
            ])
            ->get(env('API_ADDR') . '/appoint/', [
                'club_id' => $clubId,
                'appointment_id' => $appointmentId
            ]);

        return $appoint->json();
    }

    public static function postAppointment($clubId, $utoken, $data)
    {
        $apikey = CLubs::getClubKeyById($clubId);

        $appoint = Http::withBasicAuth(env('APP_BASIC_LOGIN'), env('APP_BASIC_PASSWORD'))
            ->withHeaders([
                'apikey' => $apikey,
                'usertoken' => $utoken,
            ])
            ->get(env('API_ADDR') . '/appoint/', $data);

        return $appoint->json();

    }

    public static function postAppoint($clubId, $utoken, $data)
    {
        $apikey = Clubs::getClubKeyById($clubId);
        $response = Http::withBasicAuth(env('APP_BASIC_LOGIN'), env('APP_BASIC_PASSWORD'))
            ->withHeaders([
                'apikey' => $apikey,
                'utoken' => $utoken
            ])
            ->post(env('API_ADDR') . '/appointment/', $data);

        return $response->json();
    }

    public static function updateClient($utoken, $data)
    {
        $apikey = env('TECH_APIKEY');

        $response = Http::withBasicAuth(env('APP_BASIC_LOGIN'), env('APP_BASIC_PASSWORD'))
            ->withHeaders([
                'apikey' => $apikey,
                'usertoken' => $utoken,
            ])
            ->put(env('API_ADDR') . '/client/', $data);

        return $response->json();
    }

    public static function getCodeOnPhone($clubId, $data)
    {
        $apikey = Clubs::getClubKeyById($clubId);
        $response = Http::withBasicAuth(env('APP_BASIC_LOGIN'), env('APP_BASIC_PASSWORD'))
            ->withHeaders([
                'apikey' => $apikey,
            ])
            ->post(env('API_ADDR') . '/confirm_phone/', $data);

        return $response->json();
    }

    public static function postConfirmationCode($clubId, $data)
    {
        $apikey = Clubs::getClubKeyById($clubId);
        $response = Http::withBasicAuth(env('APP_BASIC_LOGIN'), env('APP_BASIC_PASSWORD'))
            ->withHeaders([
                'apikey' => $apikey,
            ])
            ->post(env('API_ADDR') . '/confirm_phone/', $data);

        return $response->json();
    }

    public static function postNewPassword($clubId, $data)
    {
        $apikey = Clubs::getClubKeyById($clubId);
        $response = Http::withBasicAuth(env('APP_BASIC_LOGIN'), env('APP_BASIC_PASSWORD'))
            ->withHeaders([
                'apikey' => $apikey,
            ])
            ->post(env('API_ADDR') . '/password/', $data);

        return $response->json();
    }

    public static function deleteTraining($clubId, $utoken, $appointmentId)
    {
        $apikey = Clubs::getClubKeyById($clubId);
        $clubId = Clubs::getClubIdById($clubId);

        $response = Http::withBasicAuth(env('APP_BASIC_LOGIN'), env('APP_BASIC_PASSWORD'))
            ->withHeaders([
                'apikey' => $apikey,
                'usertoken' => $utoken
            ])
            ->delete(env('API_ADDR') . '/appointment/', [
                'club_id' => $clubId,
                'appointment_id' => $appointmentId
            ]);

        return $response->json();
    }

    public static function getTrainers($clubId, $utoken)
    {
        $apikey = Clubs::getClubKeyById($clubId);
        $club_id = Clubs::getClubIdById($clubId);
        $response = Http::withBasicAuth(env('APP_BASIC_LOGIN'), env('APP_BASIC_PASSWORD'))
            ->withHeaders([
                'apikey' => $apikey,
                'usertoken' => $utoken
            ])
            ->get(env('API_ADDR') . '/appointment_trainers/', [
                "club_id" => $club_id
            ]);

        return $response->json();
    }

    public static function getTrainer($clubId, $utoken, $employeeId)
    {
        $apikey = Clubs::getClubKeyById($clubId);
        $clubId = Clubs::getClubIdById($clubId);
        $response = Http::withBasicAuth(env('APP_BASIC_LOGIN'), env('APP_BASIC_PASSWORD'))
            ->withHeaders([
                'apikey' => $apikey,
                'usertoken' => $utoken
            ])
            ->get(env('API_ADDR') . '/employee/', [
                "club_id" => $clubId,
                "employee_id" => $employeeId
            ]);

        return $response->json();
    }

    public static function getServices($clubId, $utoken)
    {
        $apikey = Clubs::getClubKeyById($clubId);
        $club_id = Clubs::getClubIdById($clubId);

        $response = Http::withBasicAuth(env('APP_BASIC_LOGIN'), env('APP_BASIC_PASSWORD'))
            ->withHeaders([
                'apikey' => $apikey,
                'usertoken' => $utoken
            ])
            ->get(env('API_ADDR') . '/appointment_services/', [
                "club_id" => $club_id
            ]);

        return $response->json();
    }

    public static function getTimesOfService($clubId, $utoken, $employeeId, $serviceId)
    {
        $apikey = Clubs::getClubKeyById($clubId);
        $clubId = Clubs::getClubIdById($clubId);

        $response = Http::withBasicAuth(env('APP_BASIC_LOGIN'), env('APP_BASIC_PASSWORD'))
            ->withHeaders([
                'apikey' => $apikey,
                'usertoken' => $utoken
            ])
            ->get(env('API_ADDR') . '/appointment_times/', [
                "club_id" => $clubId,
                "employee_id" => $employeeId,
                "service_id" => $serviceId
            ]);

        return $response->json();
    }

    public static function getPromocodeCheck($clubId, $utoken, $queryParam, $promocode)
    {
        $apikey = Clubs::getClubKeyById($clubId);
        $clubId = Clubs::getClubIdById($clubId);

        $response = Http::withBasicAuth(env('APP_BASIC_LOGIN'), env('APP_BASIC_PASSWORD'))
            ->withHeaders([
                'apikey' => $apikey,
                'usertoken' => $utoken
            ])
            ->get(env('API_ADDR') . '/cart_cost/?cart=' . $queryParam, [
                "club_id" => $clubId,
                "promocode" => $promocode
            ]);

        return $response->json();
    }

    public static function getProductsShop($clubId, $utoken)
    {
        $apikey = Clubs::getClubKeyById($clubId);
        $clubId = Clubs::getClubIdById($clubId);

        $response = Http::withBasicAuth(env('APP_BASIC_LOGIN'), env('APP_BASIC_PASSWORD'))
            ->withHeaders([
                'apikey' => $apikey,
                'usertoken' => $utoken
            ])
            ->get(env('API_ADDR') . '/price_list/', [
                "club_id" => $clubId
            ]);

        return $response->json();
    }

    public static function getServicesTrainer($clubId, $utoken, $employeeId)
    {
        $apikey = Clubs::getClubKeyById($clubId);
        $clubId = Clubs::getClubIdById($clubId);

        $response = Http::withBasicAuth(env('APP_BASIC_LOGIN'), env('APP_BASIC_PASSWORD'))
            ->withHeaders([
                'apikey' => $apikey,
                'usertoken' => $utoken
            ])
            ->get(env('API_ADDR') . '/appointment_services/', [
                "club_id" => $clubId,
                "employee_id" => $employeeId
            ]);

        return $response->json();
    }

    public static function postWriting($clubId, $utoken, $data)
    {
        $apikey = Clubs::getClubKeyById($clubId);
        $clubId = Clubs::getClubIdById($clubId);

        $response = Http::withBasicAuth(env('APP_BASIC_LOGIN'), env('APP_BASIC_PASSWORD'))
            ->withHeaders([
                'apikey' => $apikey,
                'usertoken' => $utoken
            ])
            ->post(env('API_ADDR') . '/appointment/', $data);

        return $response->json();
    }

    public static function postPayment($clubId, $utoken, $data)
    {
        $apikey = Clubs::getClubKeyById($clubId);
        $clubId = Clubs::getClubIdById($clubId);

        $response = Http::withBasicAuth(env('APP_BASIC_LOGIN'), env('APP_BASIC_PASSWORD'))
            ->withHeaders([
                'apikey' => $apikey,
                'usertoken' => $utoken
            ])
            ->post(env('API_ADDR') . '/payment/', $data);

        return $response->json();
    }
}
