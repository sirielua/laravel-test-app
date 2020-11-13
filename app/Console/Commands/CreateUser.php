<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Services\UserService;
use Illuminate\Validation\ValidationException;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-user {name} {email} {password?} {password_confirmation?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates user. Useful to create the very first administrator';

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
    public function handle(UserService $users)
    {
        $input = array_merge($this->arguments(), [
            'password' => $this->argument('password') ?? $this->secret('Input password'),
            'password_confirmation' => $this->argument('password_confirmation') ?? $this->secret('Confirm password'),
        ]);

        try {
            $user = $users->create($input);
            $this->info('User with id'.$user->id.' created!');
        } catch (ValidationException $ex) {
            $this->error('Validataion error!');
            print_r($ex->errors());
        }
    }
}
