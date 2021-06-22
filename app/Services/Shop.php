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
}
