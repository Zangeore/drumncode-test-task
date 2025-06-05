<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

class MakeUserCommand extends Command
{
    protected $signature = 'make:user';

    protected $description = 'Command description';

    public function handle(): void
    {
        $user = User::create([
            'name' => text(
                label: 'Enter your name: ',
                placeholder: 'John Doe',
                required: true,
            ),
            'email' => text(
                label: 'Enter your email: ',
                placeholder: 'john.doe@example.com',
                required: true,
                validate: [
                    'email' => 'email|unique:users,email',
                ]
            ),
            'password' => password(
                label: 'Enter your password: ',
                placeholder: '********',
                required: true,
            )
        ]);
        $this->info(sprintf('User created successfully: %s (%s) with ID %s', $user->name, $user->email, $user->id));
    }
}
