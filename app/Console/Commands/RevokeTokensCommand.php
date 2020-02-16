<?php

namespace App\Console\Commands;

use App\Models\Users\User;
use Illuminate\Console\Command;

class RevokeTokensCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:tokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all users airlock tokens';

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
        User::chunk(10, function ($users) {
            foreach ($users as $user) {
                $user->tokens()->delete();
            }
        });
    }
}
