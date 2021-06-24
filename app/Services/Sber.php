<?php


namespace App\Services;


use Illuminate\Support\Facades\Http;

class Sber
{
    public static function sberRegisterDo($item)
    {
        $itemType = $item['category_type'];

        if ($itemType == 'trainer' || $itemType == 'office') $itemType = 'once';

        $params = [
            'userName' => 'fitroom-api',
            'password' => 'Mobifitness*1',
            # ВКЛЮЧЕН БОЕВОЙ ТОКЕН!!!!!!!!
            #'token': 'env('SBER_TOKEN')',
            'amount' => $item['amount'].'00',
            'returnUrl' => 'https://app.fitroom.ru/success?type='.$itemType.'&subtype='.$item['category_type'],
            'orderNumber' => $item['orderNumber'],
            'description' => $item['description'],
            'phone' => $item['phone']
        ];

        $response = Http::get('https://securepayments.sberbank.ru/payment/rest/register.do', $params);
        return $response->json();
    }
}
