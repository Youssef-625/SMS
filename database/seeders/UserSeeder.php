<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin
        User::updateOrCreate(
            ['email' => 'superadmin@sms.com'],
            [
                'name' => 'Super Admin',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'super_admin',
            ]
        );

        // Create Admin
        User::updateOrCreate(
            ['email' => 'admin@sms.com'],
            [
                'name' => 'Admin User',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Create Teachers
        $teachers = [
            ['name' => 'Dr. Ahmed Hassan', 'email' => 'ahmed.hassan@sms.com'],
            ['name' => 'Sarah Johnson', 'email' => 'sarah.johnson@sms.com'],
            ['name' => 'Mohamed Ibrahim', 'email' => 'mohamed.ibrahim@sms.com'],
        ];

        foreach ($teachers as $teacher) {
            User::updateOrCreate(
                ['email' => $teacher['email']],
                [
                    'name' => $teacher['name'],
                    'email_verified_at' => now(),
                    'password' => Hash::make('password'),
                    'role' => 'teacher',
                ]
            );
        }

        // Create Students
        $students = [
            ['name' => 'John Doe', 'email' => 'john.doe@sms.com'],
            ['name' => 'Jane Smith', 'email' => 'jane.smith@sms.com'],
            ['name' => 'Ali Ahmed', 'email' => 'ali.ahmed@sms.com'],
            ['name' => 'Fatima Mohamed', 'email' => 'fatima.mohamed@sms.com'],
            ['name' => 'Omar Khalil', 'email' => 'omar.khalil@sms.com'],
        ];

        foreach ($students as $student) {
            User::updateOrCreate(
                ['email' => $student['email']],
                [
                    'name' => $student['name'],
                    'email_verified_at' => now(),
                    'password' => Hash::make('password'),
                    'role' => 'student',
                ]
            );
        }

        // Create Parents
        $parents = [
            ['name' => 'Parent One', 'email' => 'parent.one@sms.com'],
            ['name' => 'Parent Two', 'email' => 'parent.two@sms.com'],
            ['name' => 'Parent Three', 'email' => 'parent.three@sms.com'],
        ];

        foreach ($parents as $parent) {
            User::updateOrCreate(
                ['email' => $parent['email']],
                [
                    'name' => $parent['name'],
                    'email_verified_at' => now(),
                    'password' => Hash::make('password'),
                    'role' => 'parent',
                ]
            );
        }

        $this->command->info('✓ Users seeded successfully');
    }
}
