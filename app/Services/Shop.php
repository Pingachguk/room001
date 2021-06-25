<?php


namespace App\Services;


use App\Http\Controllers\OrderController;
use App\Services\fitroomLkDb1c\RequestDB;

class Shop
{
    public static function getCategoryTitle($title)
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

    public static function getCategory($item)
    {
        $title = trim($item['title']);
        $category = $item['category']['title'];
        $categorySlug = Null;
        $categoryPackage = Null;

        if ($category == "Аренда") $categorySlug = "office";
        if ($category == "Пакеты тренировок") $categorySlug = "trainer";

        if (str_contains($title, 'Пакет')) $categoryPackage = true;

        $categories = [
            'Пробная тренировка с тренером' => ['first' => 'trainer'],
            'Разовая тренировка с тренером' => ['once' => 'trainer'],
            'Разовая аренда студии' => ['once' => 'office'],
            'Аренда зала Москва ул. Новотушинская д. 2' => ['once' => 'office'],
            'аренда зала автозаводская д. 23б' => ['once' => 'office']
        ];

        if ($categoryPackage && $categorySlug) return ['package' => $categorySlug];

        foreach (array_keys($categories) as $item) {
            if ($item == $title) return $categories[$item];
        }
    }

    public static function promocodeCheck($clubId, $utoken, $productId, $promocode)
    {
        $cartObj = [
            "cart_array" => [
                [
                    "purchase_id" => $productId,
                    "count" => 1
                ]
            ]
        ];
        $queryParam = json_encode($cartObj);

        $result = RequestDB::getPromocodeCheck($clubId, $utoken, $queryParam, $promocode);
        return $result;
    }

    public static function getShopProducts($clubId, $utoken)
    {
        $authTech = RequestDB::postAuth(env('TECH_PHONE'), env('TECH_PASSWORD'));
        $techUtoken = Null;
        if ($authTech['result']) {
            $techUtoken = $authTech['data']['user_token'];
        }

        $isVerified = false;
        $subscriptions = [
            'first' => [
                'trainer' => []
            ],
            'once' => [
                'office' => [],
                'trainer' => []
            ],
            'package' => [
                'office' => [],
                'trainer' => []
            ]
        ];

        # ЗАПРОС С ТЕХНИЧЕСКОГО АККАУНТА
        $productTech = RequestDB::getProductsShop($clubId, $techUtoken);

        # ЗАПРОС С КЛИЕНТСКОГО
        $productClient = RequestDB::getProductsShop($clubId, $utoken);

        if ($productTech['result']) {
            $data = $productTech['data'];
            foreach ($data as $item) {
                $category = Shop::getCategory($item);
                if ($category) {
                    $typeCategory = array_keys($category)[0];

                    if ($category[$typeCategory] == 'office') {
                        $isVerified = true;
                    }

                    if ($typeCategory != 'first') {
                        $subscriptions[$typeCategory][$category[$typeCategory]] = $item;
                    }
                }
            }
        }

        if ($productClient['result']) {
            $data = $productClient['data'];
            foreach ($data as $item) {
                $category = Shop::getCategory($item);
                if ($category) {
                    $typeCategory = array_keys($category)[0];

                    if ($category[$typeCategory] == 'office') {
                        $isVerified = true;
                    }

                    if ($typeCategory != 'first') {
                        $subscriptions[$typeCategory][$category[$typeCategory]] = $item;
                    }
                }
            }
        }

        $subscriptions['is_verified'] = $isVerified;
        return [
            'result' => true,
            'data' => $subscriptions
        ];
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

    public static function reserveBeforePay($clubId, $utoken, $category, $type, $employeeId, $date, $time, $promocode)
    {
        $serviceId = Null;
        $products = Null;
        $client = RequestDB::getClient($clubId, $utoken);
        $clientPhone = $client['phone'];
        $writingResponse = Null;

        $description = [
            'trainer:first' => 'Оплата пробной тренировки с тренером',
            'trainer:once' => 'Оплата разовой тренировки с тренером',
            'trainer:package' => 'Оплата пакета тренировок с тренером',
            'office:once' => 'Оплата разовой аренды студии',
            'office:package' => 'Оплата пакета аренды студии'
        ];

        $categoryType = $category;
        $itemDescription = '';
        if ($description[$category.':'.$type]) {
            $itemDescription = $description[$category.':'.$type];
            $itemCategory = $category.':'.$type;

            if ($itemCategory == 'trainer:first') {
                $categoryType = 'first';
            }
        } else {
            $itemDescription = 'Оплата услуги';
        }

//        product_price = product_list['data'][item.type][item.category][0]['price']
        $productPrice = $products['data'][$type][$category][0]['price'];
        if ($promocode) {
            $promocodeResponse = self::promocodeCheck($clubId, $utoken,$products['data'][$type][$category][0]['id'], $promocode);

            if ($promocodeResponse['result']) {
                $productPrice = $promocodeResponse['data']['total_amount'];
            }
        }

        $paymentItem = [
            'phone' => $clientPhone,
            'description' => 'Оплата тренировки',
            'category_type' => $categoryType,
            'amount' => strval($productPrice), #Берем первый абоменемет в разовых
            'orderNumber' => '#FR' . random_int(111111, 999999)
        ];
        $servicesTrainer = RequestDB::getServicesTrainer($clubId, $utoken, $employeeId);

        if ($servicesTrainer['result']) {
            if ($servicesTrainer['data']) {
                $serviceId = $servicesTrainer['data'][0]['id'];

                $writeObject = [
                    'club_id' => $clubId,
                    'employee_id' => $employeeId,
                    'service_id' => $serviceId,
                    'date_time' => $date.' '.$time
                ];

                $writingResponse = RequestDB::postWriting($clubId, $utoken, $writeObject);
                if ($writingResponse['result']) {
                    $paymentData = Sber::sberRegisterDo($paymentItem);

                    if ($paymentData) {
                        $db_data = [
                            'order_id'=> $paymentData['orderId'],
                            'action'=> 'timetable',
                            'utoken'=> $utoken,
                            'phone'=> $clientPhone,
                            'club_id'=> $clubId,
                            'type'=> $categoryType,
                            'appointment_id'=> $writingResponse['data']['appointment_id'],
                            'promocode'=> $promocode
                        ];

                        $order = OrderController::create($db_data);
                    }
                    $writingResponse['data']['payment'] = $paymentData;
                }
            }
        }
        return $writingResponse;
    }

    public static function payReservedTrain($clubId, $utoken, $appointmentId)
    {
        $client = RequestDB::getClient($clubId, $utoken);

        $clientPhone = $client['data']['phone'];
        $appointments = RequestDB::getAppointmentsClub($clubId, $utoken, $appointmentId);

        if ($appointments['result'] && $appointments['data']) {
            foreach ($appointments['data'] as $itemApp) {
                if ($itemApp['appointment_id'] == $appointmentId) {
                    if ($itemApp['status'] != 'canceled') {
                        if (!$itemApp['payment']['ticket_id']) {
                            $trainingCategory = [
                                'АРЕНДА СТУДИИ ДЛЯ ТРЕНЕРА' => 'office',
                                'Персональная тренировка' => 'trainer'
                            ];
                            $serviceType = $trainingCategory[$itemApp['service']['title']];

                            $tickets = RequestDB::getTickets($clubId, $utoken);

                            foreach ($tickets as $ticket) {
                                $ticketCategory = self::getCategoryTitle(trim($ticket['title']));

                                if ($ticket['count'] > 0) {
                                    if ($ticketCategory == $serviceType) {
                                        $appointSendData = [
                                            'club_id' => $clubId,
                                            'appointment_id' => $appointmentId
                                        ];
//                                        Списываем абонемент
                                        $postAppoint = RequestDB::postAppointment($clubId, $utoken, $appointSendData);

                                        if ($postAppoint['result']) {
                                            return [
                                                'result' => true,
                                                'data' => []
                                            ];
                                        }
                                    }
                                }
                            }

                            # Товары магазина
                            $products = self::getShopProducts($clubId, $utoken);
                            $productAmount = $products['data']['once']['service_type'][0]['price'];

                            $paymentItem = [
                                'phone' => $clientPhone,
                                'description' => 'Оплата тренировки',
                                'category_type' => $serviceType,
                                'amount' => $productAmount, #Берем первый абоменемет в разовых
                                'orderNumber' => '#FR' . random_int(111111, 999999)
                            ];

//                          Регистрируем заказ и получаем ссылку на оплату
                            $paymentData = Sber::sberRegisterDo($paymentItem);

                            if ($paymentData) {
                                $db_data = [
                                    'order_id' => $paymentData['orderId'],
                                    'action' => 'reserved',
                                    'utoken' => $utoken,
                                    'phone' => $clientPhone,
                                    'club_id' => $clubId,
                                    'type' => $serviceType,
                                    'appointment_id' => $appointmentId,
                                ];
//                                Добавление в БД заказ
                                $order = OrderController::create($paymentData);
                            }

                            return [
                                'result' => true,
                                'data' => $paymentData
                            ];
                        }
                    }
                }
            }
        }
        return [
            'result' => false,
            'error' => 'Ошибка при оплате забронированной тренировки'
        ];
    }

    public static function pay($clubId, $utoken, $productId, $promocode)
    {
        $client = RequestDB::getClient($clubId, $utoken);
        $clientPhone = $client['data']['phone'];

        $products = RequestDB::getProductsShop($clubId, $utoken);

        foreach ($products as $product) {
            if ($product['id'] == $productId) {
                $productPrice = $product['price'];

                if ($promocode) {
                    $promocodeResponse = self::promocodeCheck($clubId, $utoken, $productId, $promocode);

                    if ($promocodeResponse['result']) {
                        $productPrice = $promocodeResponse['data']['total_amount'];
                    }
                }
                $paymentItem = [
                    'phone' => $clientPhone,
                    'description' => $product['title'],
                    'category_type' => $product['type'],
                    'amount' => strval($productPrice),
                    'orderNumber' => '#FR' . random_int(111111, 999999)
                ];

                $paymentData = Sber::sberRegisterDo($paymentItem);

                if ($paymentData) {
                    $db_data = [
                        'order_id' => $paymentData['orderId'],
                        'action' => 'subscription',
                        'utoken' => $utoken,
                        'phone' => $clientPhone,
                        'club_id' => $clubId,
                        'type' => $product['type'],
                        'ticket_id' => $productId,
                        'promocode' => $promocode
                    ];

                    $orderCreate = OrderController::create($db_data);
                }

                return [
                    'result' => true,
                    'data' => $paymentData
                ];
            }
        }
    }
}
