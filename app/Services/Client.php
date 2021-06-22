<?php

namespace App\Services;

use App\Services\Clubs;
use App\Services\fitroomLkDb1c\RequestDB;
use Illuminate\Support\Facades\Http;

class Client
{
    public static function getCalendarDay($data)
    {
        $weekName = ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'];
        $monthName = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'];
        $monthShortName = ['Янв', 'Фев', 'Мар', 'Апр', 'Мая', 'Июн', 'Июл', 'Авг', 'Сен', 'Окт', 'Нояб', 'Дек'];

        $today = false;

        if (strftime('%Y-%m-%d', strtotime($data)) == strftime('%Y-%m-%d', strtotime(date('Y-m-d')))) {
            $today = true;
        } else {
            $today = false;
        }

        return [
            'id'=> strftime('%Y-%m-%d', strtotime($data)),
            'day' => strftime('%d', strtotime($data)),
            'day_name' => $weekName[date('w', strtotime($data))],
            'month_number'=> strftime('%m', strtotime($data)),
            'month_name' => $monthName[date('m', strtotime($data)) - 1],
            'month_short_number'=> strftime('%d', strtotime($data)).' '.$monthShortName[date('m', strtotime($data)) - 1],
            'date_iso'=> strftime('%Y-%m-%d', strtotime($data)),
            'year'=> strftime('%Y', strtotime($data)),
            'today'=> $today,
            'time'=> strftime('%H:%M', strtotime($data))
        ];
    }

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

        foreach (array_keys($categories) as $item) {
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

        foreach (array_keys($categories) as $item) {
            if ($item == $title) {
                return $categories[$title];
            }
        }
    }

    public static function setSubscriptions($clientObject, $client, $tickets)
    {
        $clientObject['subscription_flags'] = ['trainer' => [], 'office' => []];
        foreach ($tickets['data'] as $itemTicket) {
            if ($itemTicket['count'] != 0) {
                $title = trim($itemTicket['title']);
                $itemTicket['category_type'] = self::getCategory($title);
                $itemTicket['category_subscription'] = explode(':', self::getFlags($title))[1];
                $getterFlags = explode(':', self::getFlags($title));

                if ($getterFlags) {
                    array_push($clientObject['subscription_flags'][$getterFlags[0]], $getterFlags[1]);
                    array_push($clientObject['subscriptions'], $itemTicket);
                }
            }
        }
        return $clientObject;
    }

    public static function setAppointments($clientObject, $client, $appointments, $utoken)
    {
        if ($appointments['result'] && sizeof($appointments['data']) != 0) {
            foreach ($appointments as $itemApp) {
                $clubId = $itemApp['club_id'];
                $appointmentId = $itemApp['appointment_id'];

                $statusName = [
                    'canceled' => 'Отменено',
                    'ended' => 'Завершено',
                    'passes' => 'В процессе',
                    'planned' => 'Ожидается'
                ];
                $itemApp['status_name'] = $statusName[$itemApp['status']];
                array_push($clientObject['workouts_history'], $itemApp);

                if ($itemApp['status'] != 'canceled') {
                    $clientObject['metrics']['training'][$itemApp['status']] += 1;

                    $appoint = RequestDB::getAppoint($clubId, $utoken, $appointmentId);

                    if ($appoint['result']) {
                        if (!($appoint['data']['canceled'])) {
                            if ($appoint['data']['employee']['photo']) {
                                $photoName = basename(parse_url($appoint['data']['employee']['photo'])['path']);
                                $checkPhoto = file_exists('images/' . $photoName);

                                if ($checkPhoto) {
                                    $appoint['data']['employee']['photo'] = env('SERVER_IMAGES_DEBUG') . $photoName;
                                } else {
                                    $photoRead = Http::withBasicAuth(env('APP_BASIC_LOGIN'), env('APP_BASIC_PASSWORD'))
                                        ->get($appoint['data']['employee']['photo']);
                                    $content = $photoRead->body();
                                    $photoWrite = fopen('images/' . $photoName, 'w');
                                    fwrite($photoWrite, $content);
                                    $appoint['data']['employee']['photo'] = env('SERVER_IMAGES_DEBUG') . $photoName;
                                }
                            }

                            # Устанавливаем категории для записи
                            if (trim($appoint['data']['employee']['name']) == 'Аренда зала' || trim($appoint['data']['employee']['name']) == 'Аренда студии') {
                                $appoint['data']['category_type'] = 'office';

                                if ($itemApp['status'] == 'ended') {
                                    $clientObject['data']['category_type'] = 'trainer';
                                }
                            } else {
                                $appoint['data']['category_type'] = 'trainer';

                                if ($itemApp['status'] == 'ended') {
                                    $clientObject['metrics']['training']['trainer'] += 1;
                                }
                            }

                            if ($appoint['data']['status'] == 'reserved' ||
                                $appoint['data']['status'] == 'temporarily_reserved_need_payment') {
                                $appoint['data']['status_type'] = 'reserved';
                            } else {
                                $appoint['data']['status_type'] = 'active';
                            }

                            $employeeName = explode(' ', $appoint['data']['employee']['name']);
                            if ($employeeName[0]) {
                                $appoint['data']['exmployee']['surname'] = $employeeName[0];
                            }
                            if ($employeeName[1]) {
                                $appoint['data']['employee']['firstname'] = $employeeName[1];
                            }

                            $appointJson['data']['date_object'] = self::getCalendarDay($appoint['data']['start_date']);
                            $statusType = $appoint['data']['status_type'];
                            $clientObject['workouts']['status_type'] = $appoint['data'];
                            $clientObject['workouts']['count'] += 1;
                        }
                    }
                } else {
                    $clientObject['metrics']['training']['canceled'] += 1;
                }
            }
        }

        array_push($clientObject['workouts_history'], array_reverse($clientObject['workouts_history']));
        array_push($clientObject['office_id'], $client['data']['club']['id']);
        array_push($clientObject['info'], $client['data']);
        return $clientObject;
    }

    public static function getClient($clubId, $utoken)
    {
        $client = RequestDB::getClient($clubId, $utoken);

        $clientObject = [
        'office_id'=> [],
        'is_verified'=> false,
        'info'=> [],
        'subscriptions'=> [],
        'workouts'=> [
            'reserved'=> [],
            'active'=> [],
            'count'=> 0
        ],
        'workouts_history'=> [],
        'cabinet'=> [
            'cover'=> 'https://i.pinimg.com/originals/2c/84/0e/2c840e86d494c5e809f850b00a69ad29.jpg',
            'is_editable'=> true
        ],
        'metrics'=> [
            'training'=> [
                'trainer'=> 0,
                'office'=> 0,

                'canceled'=> 0,
                'visited'=> 0,
                'planned'=> 0,
                'ended'=> 0,
                'passes'=> 0
            ]
        ]
    ];

        if (!$client['result']) {
            return $client;
        }
        $tickets = RequestDB::getTickets(13, $utoken);
        $appointments = RequestDB::getAppointments(13, $utoken);

        if ($tickets['result']) {
            $clientObject = self::setSubscriptions($clientObject, $client, $tickets);
        }

        $clientObject = self::setAppointments($clientObject, $client, $appointments, $utoken);

        return $clientObject;
    }
}
