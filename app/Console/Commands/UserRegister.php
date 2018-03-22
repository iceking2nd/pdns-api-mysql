<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use App\User;
use Illuminate\Database\QueryException;

class UserRegister extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:register {name : User\'s name} {email : User\'s Email as login name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register a user for this system';

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
        $name = $this->argument('name');
        $email = $this->argument('email');
        $password = Str::random(15);
        $user = [
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password)
        ];
        if(User::where('email','like',$email)->count() == 0)
        {
            User::create($user);
            $this->comment('User '.$name. ' has been created succeeded.');
            $this->comment('The password is '.$password);
        }
        else
        {
            $this->comment('User '.$name. ' has been exists.');
        }
    }
}
