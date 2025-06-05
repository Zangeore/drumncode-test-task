<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakeUserTokenCommand extends Command
{
    protected $signature = 'make:user-token {userId}';

    protected $description = 'Command description';

    public function handle(): void
    {
        $user = User::findOrFail($this->argument('userId'));
        $token = $user->createToken('User Token')->plainTextToken;
        $this->info('User token created successfully: ' . $token);
    }
}
