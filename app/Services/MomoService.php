<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class MomoService
{
    public function createPayment($orderId, $amount, $orderInfo): array
    {
        $endpoint    = config('services.momo.endpoint');
        $partnerCode = config('services.momo.partner_code');
        $accessKey   = config('services.momo.access_key');
        $secretKey   = config('services.momo.secret_key');
        $returnUrl   = config('services.momo.return_url');
        $notifyUrl   = config('services.momo.notify_url');

        $requestId   = (string) time();
        $requestType = 'payWithMethod';
        $extraData   = '';

        $rawHash = "accessKey=$accessKey&amount=$amount&extraData=$extraData&ipnUrl=$notifyUrl&orderId=$orderId&orderInfo=$orderInfo&partnerCode=$partnerCode&redirectUrl=$returnUrl&requestId=$requestId&requestType=$requestType";
        $signature = hash_hmac('sha256', $rawHash, $secretKey);

        $payload = [
            'partnerCode' => $partnerCode,
            'partnerName' => 'MyShop',
            'storeId'     => 'MyShopStore',
            'requestId'   => $requestId,
            'amount'      => $amount,
            'orderId'     => (string) $orderId,
            'orderInfo'   => $orderInfo,
            'redirectUrl' => $returnUrl,
            'ipnUrl'      => $notifyUrl,
            'lang'        => 'vi',
            'extraData'   => $extraData,
            'requestType' => $requestType,
            'signature'   => $signature,
        ];

        $res = Http::post($endpoint, $payload);
        return $res->json();
    }
}
