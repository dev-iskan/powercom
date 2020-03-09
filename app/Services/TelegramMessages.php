<?php

namespace App\Services;

class TelegramMessages
{
    public static function notifyNewOrder()
    {
        $text = "🔥 Новый заказ 🔥\n "
            . $client->full_name . " \n "
            . $client->phone . " \n "
            . "https://crm.alifshop.uz/#/applications/" . $application->id;
        return $text;
    }
}
