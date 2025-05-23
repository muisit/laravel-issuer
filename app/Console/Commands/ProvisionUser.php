<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProvisionUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:add {name : Name of the user} {--p|password= : Password for the user} {--e|email= : E-mail address of the user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add or update a user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::where('name', $this->argument('name'))->first();
        if (empty($user)) {
            $user = new User();
            $user->name = $this->argument('name');
        }
        $options = $this->options();
        if (isset($options['password'])) {
            $pw = $options['password'];
            if (strlen($pw) < 8) {
                $this->error('Password is too short');
                return;
            }
            $user->password = Hash::make($pw);
        }

        if (isset($options['email'])) {
            $user->email = $options['email'];
        }
        else if(empty($user->email)) {
            $user->email = '';
        }
        $user->save();
        $this->info('User was saved');
    }
}
