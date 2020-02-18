<?php

use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Products\Brand::create([
            'name' => 'Apple'
        ]);

        \App\Models\Products\Brand::create([
            'name' => 'Samsung'
        ]);

        \App\Models\Products\Brand::create([
            'name' => 'Xiaomi'
        ]);

        \App\Models\Products\Brand::create([
            'name' => 'Huawei'
        ]);

        \App\Models\Products\Brand::create([
            'name' => 'Sony'
        ]);
    }
}
