<?php


namespace App\Services;


use App\Http\Controllers\OrderController;
use App\Services\fitroomLkDb1c\RequestDB;
use Illuminate\Support\Facades\Http;

class Sber
{
    public static function actionSubscription($orderQuery, $amount)
    {
        $data = [
            "transaction_id" => $orderQuery['order_id'],
            "club_id" => $orderQuery['club_id'],
            "cart" => [
                [
                    "purchase_id" => $orderQuery['ticket_id'],
                    "count" => 1
                ]
            ],
            "payment_list" => [
                [
                    "type" => "card",
                    "amount" => $amount
                ]
            ],
            "promocode" => $orderQuery['promocode']
        ];

        return RequestDB::postPayment($orderQuery['club_id'], $orderQuery['utoken'], $data);
    }

    public static function actionReserved($orderQuery, $amount)
    {
        $products = Shop::getShopProducts($orderQuery['club_id'], $orderQuery['utoken']);

        if ($orderQuery['type'] != 'first') {
            $productId = $products['data']['once'][$orderQuery['type']][0]['id'];
        } else {
            $productId = $products['data']['first']['trainer'][0]['id'];
        }

        $data = [
            "transaction_id" => $orderQuery['order_id'],
            "club_id" => $orderQuery['club_id'],
            "cart" => [
                [
                    "purchase_id" => $productId,
                    "count" => 1
                ]
            ],
            "payment_list" => [
                [
                    "type" => "card",
                    "amount" => $amount
                ]
            ],
            "promocode" => $orderQuery['promocode']
        ];

        $responsePayment = RequestDB::postPayment($orderQuery['club_id'], $orderQuery['uroken'], $data);

        if ($responsePayment['result']) {
            $appointData = [
                'club_id' => $orderQuery['club_id'],
                'appointment_id' => $orderQuery['appointment_id']
            ];

            $responseAppoint = RequestDB::postAppoint($orderQuery['club_id'], $orderQuery['utoken'], $appointData);
            if ($responseAppoint['result']) {
                return $responseAppoint;
            }
        } else {
            return $responsePayment;
        }
    }

    public static function getCallback($mdOrder, $orderNumber, $operationItem, $status)
    {
        $ACTION_SUBSCRIPTION = 'subscription';
        $ACTION_RESERVED = 'reserved';
        $ACTION_TIMETABLE = 'timetable';

        $orderId = $mdOrder;
//        Достаем из БД заказ
        $orderQuery = OrderController::findById($orderId)->toArray();

        if ($orderQuery) {
            if (!$orderQuery['confirm']) {
                $orderCheck = self::sberCheckDo($orderId);
                if ($orderCheck['errorCode'] != 0) {
                    if ($orderCheck['actionCode'] == 0) {
                        $client = RequestDB::getClient($orderQuery['club_id'], $orderQuery['utoken']);
                        $clientPhone = $client['clientPhone'];

                        $resultAction = false;
//                        Покупаем абонемент
                        if ($orderQuery['action'] == $ACTION_SUBSCRIPTION) {
                            $returnAction = self::actionSubscription($orderQuery, $orderCheck['amount']);
                            $resultAction = $returnAction['result'];
                        }

                        if ($orderQuery['action'] == $ACTION_RESERVED) {
                            $returnAction = self::actionReserved($orderQuery, $orderCheck['amount']);
                            $resultAction = $returnAction['result'];
                        }

                        if ($orderQuery['action'] == $ACTION_TIMETABLE) {
                            $returnAction = self::actionReserved($orderQuery, $orderCheck['amount']);
                            $resultAction = $returnAction['result'];
                        }

                        if ($resultAction) {
//                            $db_data = [
//                                'order_id' => $orderId,
//                                'confirm' => false
//                            ];
//                            $db_object = database.schemas.OrderConfirm(db_data);
//                            $order_confirm = database.order_app.confirm_order(db_object);
//
//                            $operation = false;
//
//                            if ($operationItem) {
//                                $operation = $operationItem;
//                            }

                            $order = OrderController::confirm($orderId);
                            $price = strval(intval($orderCheck['amount'])/100);
                            $tgMessage = "Клиент: {$clientPhone}\r\nПродукт: {$orderCheck['orderDescription']}\r\nСтоимость: {$price}";
                            $tgMessageFitroom = "Продукт: {$orderCheck['orderDescription']}\r\nСтоимость: {$price}";

                            Http::get('https://api.telegram.org/bot'.env('BOT_TOKEN').'/sendMessage?chat_id=-1001302056869&text='.$tgMessage);
                            Http::get('https://api.telegram.org/bot'.env('BOT_TOKEN').'/sendMessage?chat_id=-1001438006769&text='.$tgMessageFitroom);

                            return [
                                'result' => true,
                                'confirm' => true
                            ];
                        }
                    } else {
                        return [
                            'result' => false,
                            'status' => 'error',
                            'code' => $orderCheck['actionCode']
                        ];
                    }
                }
                return $orderCheck;
            } else {
                return [
                    'result' => true,
                    'confirm' => true,
                    'message' => 'Заказ уже подтвержден'
                ];
            }
        } else {
            return [
                'result' => false,
                'status' => 'error',
                'message' => 'Заказ с таким номером не найден'
            ];
        }
    }

    public static function sberRegisterDo($item)
    {
        $itemType = $item['category_type'];

        if ($itemType == 'trainer' || $itemType == 'office') $itemType = 'once';

        $params = [
            'userName' => 'fitroom-api',
            'password' => 'Mobifitness*1',
            # ВКЛЮЧЕН БОЕВОЙ ТОКЕН!!!!!!!!
            #'token' => env('SBER_TOKEN'),
            'amount' => $item['amount'] . '00',
            'returnUrl' => 'https://app.fitroom.ru/success?type=' . $itemType . '&subtype=' . $item['category_type'],
            'orderNumber' => $item['orderNumber'],
            'description' => $item['description'],
            'phone' => $item['phone']
        ];

        $response = Http::get('https://securepayments.sberbank.ru/payment/rest/register.do', $params);
        return $response->json();
    }

    public static function sberCheckDo($orderId)
    {
        $params = [
            'userName' => 'fitroom-api',
            'password' => 'Mobifitness*1',
            # ВКЛЮЧЕН БОЕВОЙ ТОКЕН!!!!!!!!
            #'token' => env('SBER_TOKEN'),
            'orderId' => $orderId
        ];

        $response = Http::get('https://securepayments.sberbank.ru/payment/rest/getOrderStatusExtended.do', $params);
        return $response->json();
    }
}
