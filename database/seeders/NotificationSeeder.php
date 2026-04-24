<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users to create notifications for
        $users = User::all();

        foreach ($users as $user) {
            // Create welcome notification
            Notification::create([
                'user_id' => $user->id,
                'type' => 'info',
                'title' => 'Welcome to SMS',
                'message' => "Welcome {$user->name} to the School Management System!",
                'data' => null,
                'link' => '/dashboard',
                'is_read' => false,
                'read_at' => null,
            ]);

            // Create role-specific notifications
            if ($user->role === 'student') {
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'info',
                    'title' => 'Academic Year Started',
                    'message' => 'The new academic year has begun. Check your schedule.',
                    'data' => ['year' => '2024-2025'],
                    'link' => '/schedule',
                    'is_read' => false,
                    'read_at' => null,
                ]);

                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'alert',
                    'title' => 'Assignment Due',
                    'message' => 'You have an assignment due tomorrow.',
                    'data' => ['assignment_id' => 1, 'subject' => 'Mathematics'],
                    'link' => '/assignments/1',
                    'is_read' => true,
                    'read_at' => now()->subHours(2),
                ]);
            } elseif ($user->role === 'teacher') {
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'info',
                    'title' => 'Class Schedule Updated',
                    'message' => 'Your class schedule has been updated for next week.',
                    'data' => ['week' => '2024-W17'],
                    'link' => '/teacher/schedule',
                    'is_read' => false,
                    'read_at' => null,
                ]);

                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'alert',
                    'title' => 'New Student Enrolled',
                    'message' => 'A new student has been enrolled in your class.',
                    'data' => ['student_count' => 25],
                    'link' => '/teacher/students',
                    'is_read' => true,
                    'read_at' => now()->subDay(),
                ]);
            } elseif ($user->role === 'parent') {
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'info',
                    'title' => 'Parent-Teacher Meeting',
                    'message' => 'Reminder: Parent-teacher meeting scheduled for next week.',
                    'data' => ['date' => '2024-04-25'],
                    'link' => '/parent/meetings',
                    'is_read' => false,
                    'read_at' => null,
                ]);

                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'alert',
                    'title' => 'Fee Payment Reminder',
                    'message' => 'School fee payment is due by the end of this month.',
                    'data' => ['amount' => 5000, 'currency' => 'EGP'],
                    'link' => '/parent/fees',
                    'is_read' => true,
                    'read_at' => now()->subHours(6),
                ]);
            } elseif ($user->role === 'admin' || $user->role === 'super_admin') {
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'info',
                    'title' => 'System Update',
                    'message' => 'System has been updated to Phase 1.0.',
                    'data' => ['version' => '1.0.0'],
                    'link' => '/admin/settings',
                    'is_read' => false,
                    'read_at' => null,
                ]);

                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'alert',
                    'title' => 'New User Registration',
                    'message' => '5 new users have registered today.',
                    'data' => ['count' => 5],
                    'link' => '/admin/users',
                    'is_read' => true,
                    'read_at' => now()->subHours(3),
                ]);
            }
        }

        $this->command->info('✓ Notifications seeded successfully');
    }
}
