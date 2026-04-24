<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = [
            [
                'email' => 'john.doe@sms.com',
                'student_id' => 'STU001',
                'date_of_birth' => '2010-05-15',
                'gender' => 'male',
                'address' => '123 Main St, Cairo',
                'phone' => '+201234567890',
                'enrollment_date' => '2024-09-01',
            ],
            [
                'email' => 'jane.smith@sms.com',
                'student_id' => 'STU002',
                'date_of_birth' => '2010-08-20',
                'gender' => 'female',
                'address' => '456 Oak Ave, Alexandria',
                'phone' => '+201234567891',
                'enrollment_date' => '2024-09-01',
            ],
            [
                'email' => 'ali.ahmed@sms.com',
                'student_id' => 'STU003',
                'date_of_birth' => '2009-12-10',
                'gender' => 'male',
                'address' => '789 Palm St, Giza',
                'phone' => '+201234567892',
                'enrollment_date' => '2024-09-01',
            ],
            [
                'email' => 'fatima.mohamed@sms.com',
                'student_id' => 'STU004',
                'date_of_birth' => '2011-03-25',
                'gender' => 'female',
                'address' => '321 Cedar Rd, Cairo',
                'phone' => '+201234567893',
                'enrollment_date' => '2024-09-01',
            ],
            [
                'email' => 'omar.khalil@sms.com',
                'student_id' => 'STU005',
                'date_of_birth' => '2010-07-08',
                'gender' => 'male',
                'address' => '654 Oak Ln, Alexandria',
                'phone' => '+201234567894',
                'enrollment_date' => '2024-09-01',
            ],
        ];

        foreach ($students as $studentData) {
            $user = User::where('email', $studentData['email'])->first();
            
            if ($user) {
                Student::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'student_id' => $studentData['student_id'],
                        'date_of_birth' => $studentData['date_of_birth'],
                        'gender' => $studentData['gender'],
                        'address' => $studentData['address'],
                        'phone' => $studentData['phone'],
                        'enrollment_date' => $studentData['enrollment_date'],
                    ]
                );
            }
        }

        $this->command->info('✓ Students seeded successfully');
    }
}
