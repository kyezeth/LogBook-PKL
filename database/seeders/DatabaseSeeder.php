<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create or update admin user
        User::updateOrCreate(
            ['email' => 'admin@injourney.id'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('elbandjaya3'),
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
                'failed_login_attempts' => 0,
                'locked_until' => null,
            ]
        );

        $this->command->info('Seeder completed!');
        $this->command->info('Admin: admin@injourney.id / elbandjaya3');
    }
}
