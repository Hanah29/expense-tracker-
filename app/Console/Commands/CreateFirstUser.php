<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateFirstUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:creater';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create first user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
             User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        
        $this->info('User created successfully!');
    }
}
