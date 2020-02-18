<?php

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Products\Category::create([
            'name' => 'Телефоны',
            'short_description' => 'телефоны по выгодным ценам'
        ]);

        \App\Models\Products\Category::create([
            'name' => 'Компьютеры'
        ]);

        \App\Models\Products\Category::create([
            'name' => 'Ноутбуки',
            'short_description' => 'ноутбуки по выгодным ценам'
        ])->parent()->associate(2)->save();
    }
}
