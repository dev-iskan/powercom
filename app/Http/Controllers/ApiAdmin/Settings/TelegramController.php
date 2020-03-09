<?php

namespace App\Http\Controllers\ApiAdmin\Settings;

use App\Http\Controllers\Controller;
use App\Services\TelegramService;
use Illuminate\Http\Request;

class TelegramController extends Controller
{
    public function getUpdates()
    {
        return TelegramService::getUpdates();
    }

    public function sendMessage(Request $request)
    {
        $text = $request->text;

        $options = [
            'text' => $text,
            'chat_id' => config('local.telegram.chat_id')
        ];

        return TelegramService::sendMessage($options);
    }
}
