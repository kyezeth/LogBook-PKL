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
        // Create admin user
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@logbook.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create sample student users
        $students = [
            [
                'name' => 'Ahmad Rizki Pratama',
                'nis' => '2024001',
                'nisn' => '0012345678',
                'email' => 'ahmad@student.test',
                'phone' => '081234567890',
                'institution' => 'SMK Negeri 1 Jakarta',
                'department' => 'XII RPL 1',
                'gender' => 'male',
            ],
            [
                'name' => 'Siti Nurhaliza',
                'nis' => '2024002',
                'nisn' => '0012345679',
                'email' => 'siti@student.test',
                'phone' => '081234567891',
                'institution' => 'SMK Negeri 1 Jakarta',
                'department' => 'XII RPL 1',
                'gender' => 'female',
            ],
            [
                'name' => 'Budi Santoso',
                'nis' => '2024003',
                'nisn' => '0012345680',
                'email' => 'budi@student.test',
                'phone' => '081234567892',
                'institution' => 'SMK Negeri 2 Bandung',
                'department' => 'XII TKJ 2',
                'gender' => 'male',
            ],
        ];

        foreach ($students as $student) {
            User::create([
                'name' => $student['name'],
                'nis' => $student['nis'],
                'nisn' => $student['nisn'],
                'email' => $student['email'],
                'phone' => $student['phone'],
                'institution' => $student['institution'],
                'department' => $student['department'],
                'gender' => $student['gender'],
                'password' => Hash::make('password'),
                'role' => 'member',
                'is_active' => true,
                'email_verified_at' => now(),
                'pkl_start_date' => now()->startOfMonth(),
                'pkl_end_date' => now()->addMonths(3)->endOfMonth(),
            ]);
        }

        $this->command->info('Seeder completed!');
        $this->command->info('Admin: admin@logbook.test / password');
        $this->command->info('Student: 0012345678 / password (NISN login)');
    }
}
