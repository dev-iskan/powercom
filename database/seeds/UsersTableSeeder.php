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
        ])->admin()->create();

        \App\Models\Users\User::create([
            'name' => 'Акбар',
            'surname' => 'Аминов',
            'phone' => '998933938274',
            'email' => 'detskiy98@gmail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('secret')
        ])->operator()->create();
    }
}
