<?php


namespace App\Services;


use App\Services\fitroomLkDb1c\RequestDB;

class Shop
{
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

    public static function getShopProducts($clubId, $utoken)
    {
        $products = RequestDB::getProductsShop($clubId, $utoken);
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

        if ($products['result']) {
            $data = $products['data'];
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
        return $subscriptions;
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

    public static function subPay($clubId, $utoken, $appointmentId)
    {
        $client = Client::getClient();
        return;
    }
}
