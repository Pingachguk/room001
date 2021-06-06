<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RequestDB
{
    public static function getClient(string $apiKey, string $utoken)
    {
        $client = Http::withBasicAuth(env('APP_BASIC_LOGIN'), env('APP_BASIC_PASSWORD'))
            ->withHeaders([
                'apikey' => $apiKey,
                'usertoken' => $utoken,
            ])
            ->get(env('API_ADDR') . '/client/');

        return $client;
    }

    public static function getTickets(string $apiKey, string $utoken)
    {
        $ticket = Http::withBasicAuth(env('APP_BASIC_LOGIN'), env('APP_BASIC_PASSWORD'))
            ->withHeaders([
                'apikey' => $apiKey,
                'usertoken' => $utoken,
            ])
            ->get(env('API_ADDR') . '/tickets/');
        return $ticket;
    }

    public static function getAppointments(string $apiKey, string $utoken)
    {
        $appointments = Http::withBasicAuth(env('APP_BASIC_LOGIN'), env('APP_BASIC_PASSWORD'))
            ->withHeaders([
                'apikey' => $apiKey,
                'usertoken' => $utoken,
            ])
            ->get(env('API_ADDR') . '/appointments/');
        return $appointments;
    }

    public static function getAppoint(string $apiKey, string $utoken, $clubId, $appointmentId)
    {
        $appoint = Http::withBasicAuth(env('APP_BASIC_LOGIN'), env('APP_BASIC_PASSWORD'))
            ->withHeaders([
                'apikey' => $apiKey,
                'usertoken' => $utoken,
            ])
            ->get(env('API_ADDR') . '/appoint/', [
                'club_id' => $clubId,
                'appointment_id' => $appointmentId
            ]);

        return $appoint;
    }

    public static function updateClient($utoken, $data)
    {
        $response = Http::withBasicAuth(env('APP_BASIC_LOGIN'), env('APP_BASIC_PASSWORD'))
            ->withHeaders([
                'usertoken' => $utoken,
            ])
            ->put(env('API_ADDR') . '/client/', $data);

        return $response;
    }
}
