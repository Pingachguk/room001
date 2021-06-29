<?php

namespace App\Services\fitroomLkDb1c;

use Illuminate\Support\Facades\Http;

class Trainers
{
    public static function checkPhoto($data)
    {
        $photoName = basename(parse_url($data["photo"])['path']);
        $checkPhoto = file_exists('images/' . $photoName);

        if ($checkPhoto) {
            $data['photo'] = env('SERVER_IMAGES_DEBUG') . $photoName;
        } else {
            $photoRead = Http::withBasicAuth(env('APP_BASIC_LOGIN'), env('APP_BASIC_PASSWORD'))
                ->get($data['photo']);
            $content = $photoRead->body();
            $photoWrite = fopen('images/' . $photoName, 'w');
            fwrite($photoWrite, $content);
            $data['photo'] = env('SERVER_IMAGES_DEBUG') . $photoName;
        }

        return $data;
    }

    public static function getDetail($clubId, $utoken, $employeeId)
    {
        $trainer = RequestDB::getTrainer($clubId, $utoken, $employeeId);
        $detail = [];

        if ($trainer['result']) {
            $detail = $trainer["data"];

            if ($detail["photo"]) {
                $detail = self::checkPhoto($detail);
                return [
                    'result' => true,
                    'data' => $detail
                ];
            }
        }
        return $detail;
    }

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

    public static function getServices($clubId, $utoken)
    {
        $response = RequestDB::getServices($clubId, $utoken);
        $services = array();

        if ($response['result']) {
            foreach ($response['data'] as $item) {
                if ($item['title'] == "Персональная тренировка") {
                    $services['trainer'] = $item["id"];
                } elseif ($item["title"] == "АРЕНДА СТУДИИ ДЛЯ ТРЕНЕРА") {
                    $services['office'] = $item["id"];
                }
            }
        }

        return $services;
    }

    public static function getTrainers($clubId, $utoken)
    {
        $trainersJson = RequestDB::getTrainers($clubId, $utoken);
        $services = self::getServices($clubId, $utoken);

        $trainers = [
            "result" => True,
            "data" => [
                "club_id" => $clubId,
                "office_id" => Null,
                "trainers" => []
            ]];


        if ($trainersJson["result"]) {
            foreach ($trainersJson["data"] as $item) {
//                Получили тренеров и заменили фотки на локальный сервер (1С не даёт смотреть фото без авторизации)
                if ($item["photo"]) {
                    $item = self::checkPhoto($item);
                }

                if (!$item["position"]["id"]) {
                    $trainers["data"]["office_id"] = $item["id"];
                    $nameSplit = explode(" ", $item["name"]);
                    $item["name"] = $nameSplit[0] . ' ' . $nameSplit[1];
                }

//                ПОЛУЧАЕМ ВРЕМЯ ТРЕНЕРА С ПАРАМЕТРОМ УСЛУГИ
                $serviceId = Null;
                if (key_exists("office_id", $trainers['data'])) {
                    if ($item['id'] == $trainers["data"]["office_id"]) {
                        $serviceId = $services['office'];
                    } else {
                        $serviceId = $services['trainer'];
                    }
                } else {
                    $serviceId = $services['trainer'];
                }

//                $timesJson = RequestDB::getTimesOfService($clubId, $utoken, $item["id"], $serviceId)["data"];
                $timesJson = RequestDB::getTimesOfService($clubId, $utoken, $item["id"], $serviceId)["data"];

                if (count($timesJson)) {
                    $times = [];

                    foreach ($timesJson as $itemTime) {
                        $date = explode(' ', $itemTime['date_time']);
                        $itemDateString = $date[0];
                        $itemHour = explode(':', $date[1])[0];
                        $itemSeason = '';

                        if ($itemHour >= intval('06') && $itemHour <= intval('11')) $itemSeason = 'morning';
                        if ($itemHour >= intval('12') && $itemHour <= intval('17')) $itemSeason = 'day';
                        if ($itemHour >= intval('18') && $itemHour <= intval('23')) $itemSeason = 'evening';
                        if ($itemHour >= intval('00') && $itemHour <= intval('05')) $itemSeason = 'night';

                        if (!in_array($itemDateString, array_keys($times))) {
                            $times[$itemDateString] = [
                                "morning" => [
                                    "season" => 'morning',
                                    "season_name" => 'Утро',
                                    "time_list" => []
                                ],
                                "day" => [
                                    "season" => 'day',
                                    "season_name" => 'День',
                                    "time_list" => []
                                ],
                                "evening" => [
                                    "season" => 'evening',
                                    "season_name" => 'Вечер',
                                    "time_list" => []
                                ],
                                "night" => [
                                    "season" => 'night',
                                    "season_name" => 'Ночь',
                                    "time_list" => []
                                ]
                            ];
                        }

                        $valuePush = [
                            "time" => $itemTime["time"],
                            "status" => "free"
                        ];
                        array_push($times[$itemDateString][$itemSeason]['time_list'], $valuePush);
                    }

                    $calendar = [];
                    $dateTimes = [];

                    foreach ($timesJson as $itemTime) {
                        $date = explode(' ', $itemTime['date_time']);
                        $itemDateString = $date[0];
                        $calendar[$itemDateString] = self::getCalendarDay($itemTime['date_time']);

                        if (!in_array($itemDateString, array_keys($dateTimes))) {
                            $dateTimes[$itemDateString] = [];
                        }

                        array_push($dateTimes[$itemDateString], $itemTime['time']);
                    }

                    $item['calendar'] = $calendar;
                    $item['times'] = $times;
                    $item['date_times'] = $dateTimes;
                    array_push($trainers['data']['trainers'], $item);
                }
            }
        }

        return $trainers;
    }



//    public function club(Request $request)
//    {
//        $response = Http::withBasicAuth(env('APP_BASIC_LOGIN'), env('APP_BASIC_PASSWORD'))
//            ->withHeaders(['apikey' => '1a5a6f3b-4504-40b7-b286-14941fd2f635'])
//            ->get(env('API_ADDR').'/clubs');
//        return response($response->json()['data'], 200);
//    }
}
