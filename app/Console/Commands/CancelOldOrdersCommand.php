<?php

namespace App\Console\Commands;

use App\Models\Orders\Order;
use Illuminate\Console\Command;

class CancelOldOrdersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'credits:cancel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Order::inProgress()->whereDate('created_at', '<', today()->subDays(1))
            ->with('order_delivery', 'items.product')
            ->chunk(10, function ($orders) {
                /** @var Order $order */
                foreach ($orders as $order) {
                    $order->cancel();
                }
            });
    }
}
