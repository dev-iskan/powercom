<?php

namespace App\Jobs;

use App\Services\TelegramService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendTelegramNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $text;
    private $options;

    public function __construct($text, $options)
    {
        $this->text = $text;
        $this->options = $options;
    }

    public function handle()
    {
        TelegramService::sendNotification($this->text, $this->options);
    }
}
