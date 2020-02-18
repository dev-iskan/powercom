<?php

use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product1 = \App\Models\Products\Product::create([
            'name' => 'iphone xs 12',
            'short_description' => 'super iphone',
            'description' => 'every girl wants it',
            'quantity'=> 10,
            'price' =>  100000000000,
            'active' => true,
            'order' => 1
        ]);

        $product1->brand()->associate(1)->save();
        $product1->categories()->sync([1, 2]);
    }
}
