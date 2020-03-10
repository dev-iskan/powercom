<?php

namespace App\Services;

class TelegramMessages
{
    public static function notifyNewOrder($full_name, $phone, $address, $url)
    {
        $text = "🔥 Новый заказ 🔥\n "
            . $full_name . " \n "
            . '+' . $phone . " \n "
            . $address . " \n "
            . $url;
        return $text;
    }
}
