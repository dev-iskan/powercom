<?php

namespace App\Services;
use GuzzleHttp\Client as HttpClient;

class TelegramService
{
    public static function sendNotification($text, $options = [])
    {
        $options = array_merge([
            'text' => $text,
            'chat_id' => config('local.telegram.chat_id')
        ], $options);

        return self::sendMessage($options);
    }

    public static function sendMessage(array $options)
    {
        $request = self::createRequest();
        $response = $request->request('GET', 'sendMessage', [
            'query' => $options
        ]);
        return self::parseResponse($response);
    }

    public static function getUpdates(array $options = [])
    {
        $request = self::createRequest();
        $response = $request->request('GET', 'getUpdates', [
            'query' => $options
        ]);
        return self::parseResponse($response);
    }

    private static function createRequest()
    {
        $url = config('local.telegram.url') . 'bot' . config('local.telegram.bot_token') . '/';
        return new HttpClient([
            'base_uri' => $url,
        ]);
    }

    protected static function parseResponse($response)
    {
        return json_decode($response->getBody()->getContents(), true);
    }
}
