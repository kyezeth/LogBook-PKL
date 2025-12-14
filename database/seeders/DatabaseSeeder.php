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
        // Create admin user (only if doesn't exist)
        User::firstOrCreate(
            ['email' => 'admin@injourney.id'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('elbandjaya3'),
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Seeder completed!');
        $this->command->info('Admin: admin@injourney.id / elbandjaya3');
    }
}
