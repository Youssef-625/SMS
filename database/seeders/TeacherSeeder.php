<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teachers = [
            [
                'email' => 'ahmed.hassan@sms.com',
                'teacher_id' => 'TCH001',
                'date_of_birth' => '1985-03-10',
                'gender' => 'male',
                'address' => '789 Teacher St, Cairo',
                'phone' => '+201234567895',
                'hire_date' => '2020-08-15',
                'qualification' => 'Master\'s in Mathematics',
                'subject_specialization' => 'Mathematics',
            ],
            [
                'email' => 'sarah.johnson@sms.com',
                'teacher_id' => 'TCH002',
                'date_of_birth' => '1988-07-22',
                'gender' => 'female',
                'address' => '321 Educator Ave, Alexandria',
                'phone' => '+201234567896',
                'hire_date' => '2021-09-01',
                'qualification' => 'Bachelor\'s in English',
                'subject_specialization' => 'English Literature',
            ],
            [
                'email' => 'mohamed.ibrahim@sms.com',
                'teacher_id' => 'TCH003',
                'date_of_birth' => '1982-11-05',
                'gender' => 'male',
                'address' => '456 Academy Rd, Giza',
                'phone' => '+201234567897',
                'hire_date' => '2019-02-20',
                'qualification' => 'PhD in Physics',
                'subject_specialization' => 'Physics',
            ],
        ];

        foreach ($teachers as $teacherData) {
            $user = User::where('email', $teacherData['email'])->first();
            
            if ($user) {
                Teacher::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'teacher_id' => $teacherData['teacher_id'],
                        'date_of_birth' => $teacherData['date_of_birth'],
                        'gender' => $teacherData['gender'],
                        'address' => $teacherData['address'],
                        'phone' => $teacherData['phone'],
                        'hire_date' => $teacherData['hire_date'],
                        'qualification' => $teacherData['qualification'],
                        'subject_specialization' => $teacherData['subject_specialization'],
                    ]
                );
            }
        }

        $this->command->info('✓ Teachers seeded successfully');
    }
}
