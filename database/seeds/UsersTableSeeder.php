<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Users\User::create([
            'name' => 'root',
            'phone' => '998909889322',
            'email' => 'root@root.root',
            'password' => \Illuminate\Support\Facades\Hash::make('secret')
        ]);

        \App\Models\Users\Admin::create(['user_id' => 1]);
    }
}
