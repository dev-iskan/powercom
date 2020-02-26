<?php

use Illuminate\Database\Seeder;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Orders\OrderStatus::create([
            'name' => 'новый',
            'color' => '#00000',
            'class' => 'info'
        ]);

        \App\Models\Orders\OrderStatus::create([
            'name' => 'в процессе',
            'color' => '#00000',
            'class' => 'warning'
        ]);

        \App\Models\Orders\OrderStatus::create([
            'name' => 'завершен',
            'color' => '#00000',
            'class' => 'success'
        ]);


        \App\Models\Orders\OrderStatus::create([
            'name' => 'отменен',
            'color' => '#00000',
            'class' => 'danger'
        ]);


        \App\Models\Orders\OrderSetting::create([
            'status_created' => 1,
            'status_in_progress' => 2,
            'status_completed' => 3,
            'status_cancelled' => 4
        ]);
    }
}
