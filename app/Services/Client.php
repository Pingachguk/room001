<?php

namespace App\Services;

use App\Services\Clubs;
use App\Services\fitroomLkDb1c\RequestDB;

class Client
{
    public static function getCategory($title)
    {
        $categories = [
            'Пробная тренировка с тренером' => 'trainer',
            'Разовая тренировка с тренером' => 'trainer',
            'Пакет из 4 тренировок с тренером' => 'trainer',
            'Пакет из 8 тренировок с тренером' => 'trainer',
            'Пакет из 12 тренировок с тренером' => 'trainer',

            'Пакет из 5 тренировок с тренером' => 'trainer',
            'Пакет из 5 тренировок с тренером (Пироженко Руслан)' => 'trainer',
            'Пакет из 10 тренировок с тренером' => 'trainer',
            'Пакет из 10 тренировок с тренером (Пироженко Руслан)' => 'trainer',

            'Разовая аренда студии' => 'office',
            'Пакет на 5 посещений' => 'office',
            'Пакет на 10 посещений' => 'office',
            'Пакет на 20 посещений' => 'office',
            'Пакет Аренда студии на 5 посещений' => 'office',
            'Пакет Аренда студии на 10 посещений' => 'office',
            'Пакет Аренда студии на 20 посещений' => 'office',
            'Пакет на 100 посещений' => 'office',

            'Аренда зала Москва ул. Новотушинская д. 2' => 'office',
            'аренда зала автозаводская д. 23б' => 'office'
        ];

        foreach ($categories as $item) {
            if ($item == $title) {
                return $categories[$item];
            }
        }
    }

    public static function getFlags($title)
    {
        $categories = [
            'Пробная тренировка с тренером' => 'trainer:once',
            'Разовая тренировка с тренером' => 'trainer:once',
            'Пакет из 4 тренировок с тренером' => 'trainer:package',
            'Пакет из 8 тренировок с тренером' => 'trainer:package',
            'Пакет из 12 тренировок с тренером' => 'trainer:package',

            'Пакет из 5 тренировок с тренером' => 'trainer:package',
            'Пакет из 5 тренировок с тренером (Пироженко Руслан)' => 'trainer:package',
            'Пакет из 10 тренировок с тренером' => 'trainer:package',
            'Пакет из 10 тренировок с тренером (Пироженко Руслан)' => 'trainer:package',

            'Разовая аренда студии' => 'office:once',
            'Пакет на 5 посещений' => 'office:package',
            'Пакет на 10 посещений' => 'office:package',
            'Пакет на 20 посещений' => 'office:package',
            'Пакет Аренда студии на 5 посещений' => 'office:package',
            'Пакет Аренда студии на 10 посещений' => 'office:package',
            'Пакет Аренда студии на 20 посещений' => 'office:package',
            'Пакет на 100 посещений' => 'office:package',

            'Аренда зала Москва ул. Новотушинская д. 2' => 'office:once',
            'аренда зала автозаводская д. 23б' => 'office:once'
        ];

        foreach ($categories as $item) {
            if ($item == $title) {
                return $categories[$title];
            }
        }
    }

    public static function setSubscriptions(array $clientJson, array $ticketsJson)
    {
        $clientJson['subscription_flags'] = [
            'trainer' => [],
            'office' => []
        ];
        foreach ($ticketsJson['data'] as $itemTicket) {
            if ($itemTicket['count'] != 0) {
                $itemTicket['category_type'] = Client::getCategory($itemTicket['title']);
                $itemTicket['category_subscription'] = explode(':', Client::getFlags($itemTicket['title']))[1];
                $getterFlags = explode(':', Client::getFlags($itemTicket['title']));

                if ($getterFlags) {
                    array_push($clientJson['subscription_flags'][$getterFlags[0]], $getterFlags[1]);
                    array_push($clientJson['subscriptions'], $itemTicket);
                }
            }
        }
        return $clientJson;
    }

    public static function setAppointments(array $clientJson, array $appointmentsJson, string $utoken)
    {
        foreach ($appointmentsJson as $itemApp) {
            $appClubId = $itemApp['club_id'];
            $appAppointmentId = $itemApp['appointment_id'];
            $appKey = Clubs::getClubKeyById($appClubId);

            $statusName = [
                'canceled' => 'Отменено',
                'ended' => 'Завершено',
                'passes' => 'В процессе',
                'planned' => 'Ожидается'
            ];
            $itemApp['status_name'] = $statusName[$itemApp['status']];
            $clientJson['workouts_history'] = $itemApp;

            if ($itemApp['status'] != 'canceled') {
                $clientJson['metrics']['training'][$itemApp['status']] += 1;

                $appoint = RequestDB::getAppoint(
                    Clubs::getClubKeyById($appClubId), $utoken, $appClubId, $appAppointmentId
                );
                $appointJson = $appoint->json();

                if ($appointJson['result']) {
                    if (!($appointJson['data']['canceled'])) {
                    /*
                     * Парсинг фото с сервера 1С на свой
                     */

                    }
                }
            }
        }
        return $clientJson;
    }

    public static function subWrite($clubId, $utoken, $employeeId, $date, $time)
    {
        $services = RequestDB::getServicesTrainer($clubId, $utoken, $employeeId);

        if ($services['result']) {
            if ($services['data']) {
                $serviceId = $services['data'][0]['id'];
                $writeObject = [
                    'club_id' => $clubId,
                    'employee_id' => $employeeId,
                    'service_id' => $serviceId,
                    'date_time' => $date . ' ' . $time
                ];

                $responseWrite = RequestDB::postWriting($clubId, $utoken, $writeObject);
                return $responseWrite;
            }
        } else {
            return $services['result'];
        }
    }
}
