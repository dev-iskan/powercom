<?php

namespace App\Services;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\Facades\DB;

class SmsService
{
    public static function sendSms($phone, $message)
    {
        $data = [
            'mobile_phone' => $phone,
            'message' => $message
        ];

        $client = self::createRequest();

        $response = $client->request('POST', 'api/message/sms/send', [
            'form_params' => $data,
            'headers' => [
                'Authorization' => 'Bearer ' . DB::table('base_settings')->value('eskiz_token')
            ]
        ]);
        return self::parseResponse($response);

    }

    public static function getToken()
    {
        $data = [
            'email' => config('services.eskiz.email'),
            'password' => config('services.eskiz.password')
        ];

        $client = self::createRequest();

        $response = $client->request('POST', 'api/auth/login', [
            'form_params' => $data
        ]);

        return self::parseResponse($response);
    }

    protected static function createRequest()
    {
        return new HttpClient([
            'cookies' => true,
            'verify' => false,
            'base_uri' => config('services.eskiz.domain'),
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
        ]);
    }

    protected static function parseResponse($response)
    {
        return json_decode($response->getBody()->getContents(), true);
    }
}
