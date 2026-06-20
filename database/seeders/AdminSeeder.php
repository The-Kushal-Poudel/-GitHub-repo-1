<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use RuntimeException;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $credentials = [
            'email' => config('admin.email'),
            'password' => config('admin.password'),
        ];

        if (! $credentials['email'] || ! $credentials['password']) {
            $this->command?->warn('Skipping AdminSeeder because ADMIN_EMAIL or ADMIN_PASSWORD is missing.');

            return;
        }

        $validator = Validator::make($credentials, [
            'email' => ['required', 'email', 'max:255'],
            'password' => [
                'required',
                'string',
                Password::min(12)->letters()->mixedCase()->numbers()->symbols(),
            ],
        ]);

        if ($validator->fails()) {
            throw new RuntimeException('ADMIN_EMAIL or ADMIN_PASSWORD does not meet the required security rules.');
        }

        Admin::updateOrCreate(
            ['email' => $credentials['email']],
            ['password' => $credentials['password']]
        );
    }
}
