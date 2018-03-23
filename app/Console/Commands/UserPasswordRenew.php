<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

class UserPasswordRenew extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:passwordrenew {email : The email for the account}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Renew a password for account';

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
        $email = $this->argument('email');
        $password = Str::random(15);
        try
        {
            $user = User::where('email','=',$email)->firstOrFail();
            $user->update(['password' => bcrypt($password)]);
            $this->info('New password : '.$password);
        }
        catch (ModelNotFoundException $e)
        {
            $this->info('User '. $email .' Not Found.');
        }
    }
}
