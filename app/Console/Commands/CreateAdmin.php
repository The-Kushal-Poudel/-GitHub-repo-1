<?php

namespace App\Console\Commands;

use App\Models\Admin;
use Illuminate\Console\Command;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Validator;

class CreateAdmin extends Command
{
    protected $signature = 'admin:create {--email=} {--password=}';

    protected $description = 'Create or update the portfolio administrator account';

    public function handle(): int
    {
        $email = $this->option('email') ?: config('admin.email');
        $password = $this->option('password') ?: config('admin.password');

        if (! $email && $this->input->isInteractive()) {
            $email = $this->ask('Admin email');
        }

        if (! $password && $this->input->isInteractive()) {
            $password = $this->secret('Admin password (minimum 12 characters, mixed case, number and symbol)');
        }

        $validator = Validator::make(
            ['email' => $email, 'password' => $password],
            [
                'email' => ['required', 'email', 'max:255'],
                'password' => [
                    'required',
                    'string',
                    Password::min(12)->letters()->mixedCase()->numbers()->symbols(),
                ],
            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        Admin::updateOrCreate(
            ['email' => $email],
            ['password' => $password]
        );

        $this->info('Administrator account is ready.');

        return self::SUCCESS;
    }
}
