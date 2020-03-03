<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call(UsersTableSeeder::class);
         $this->call(CategorySeeder::class);
         $this->call(BrandSeeder::class);
         $this->call(ProductSeeder::class);
         $this->call(OrdersTableSeeder::class);

        DB::table('base_settings')->insert(['eskiz_token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9ub3RpZnkuZXNraXoudXpcL2FwaVwvYXV0aFwvbG9naW4iLCJpYXQiOjE1ODMyNTUxMzgsImV4cCI6MTU4NTg0NzEzOCwibmJmIjoxNTgzMjU1MTM4LCJqdGkiOiI3a21YdzlrWDJOYnBkSzVsIiwic3ViIjoxMTEsInBydiI6Ijg3ZTBhZjFlZjlmZDE1ODEyZmRlYzk3MTUzYTE0ZTBiMDQ3NTQ2YWEifQ.sluv7tlMG9xGvgk39oP7YukWzETDuhGGSJ_ZoEq-iYU']);
    }
}
