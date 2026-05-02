<?php

namespace Database\Seeders;

use App\Models\Schedule;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $classrooms = Classroom::all();
        $subjects = Subject::all();
        $teachers = Teacher::all();

        if ($teachers->isEmpty()) {
            $this->command->info('No teachers found. Please run TeacherSeeder first.');
            return;
        }

        $schedules = [
            // Grade 1A Schedule
            [
                'classroom_id' => 1,
                'subject_id' => 1, // Mathematics
                'teacher_id' => 1,
                'day_of_week' => 'monday',
                'start_time' => '08:00',
                'end_time' => '09:00',
                'room_number' => '101',
                'semester' => 'first',
                'academic_year' => '2024-2025',
                'is_active' => true,
            ],
            [
                'classroom_id' => 1,
                'subject_id' => 2, // English
                'teacher_id' => 2,
                'day_of_week' => 'monday',
                'start_time' => '09:00',
                'end_time' => '10:00',
                'room_number' => '101',
                'semester' => 'first',
                'academic_year' => '2024-2025',
                'is_active' => true,
            ],
            [
                'classroom_id' => 1,
                'subject_id' => 3, // Science
                'teacher_id' => 3,
                'day_of_week' => 'tuesday',
                'start_time' => '08:00',
                'end_time' => '09:00',
                'room_number' => '102',
                'semester' => 'first',
                'academic_year' => '2024-2025',
                'is_active' => true,
            ],
            [
                'classroom_id' => 1,
                'subject_id' => 5, // Physical Education
                'teacher_id' => 3,
                'day_of_week' => 'wednesday',
                'start_time' => '10:00',
                'end_time' => '11:00',
                'room_number' => 'GYM',
                'semester' => 'first',
                'academic_year' => '2024-2025',
                'is_active' => true,
            ],
            // Grade 2A Schedule
            [
                'classroom_id' => 3,
                'subject_id' => 1, // Mathematics
                'teacher_id' => 1,
                'day_of_week' => 'monday',
                'start_time' => '08:00',
                'end_time' => '09:30',
                'room_number' => '201',
                'semester' => 'first',
                'academic_year' => '2024-2025',
                'is_active' => true,
            ],
            [
                'classroom_id' => 3,
                'subject_id' => 2, // English
                'teacher_id' => 2,
                'day_of_week' => 'tuesday',
                'start_time' => '08:00',
                'end_time' => '09:30',
                'room_number' => '201',
                'semester' => 'first',
                'academic_year' => '2024-2025',
                'is_active' => true,
            ],
            [
                'classroom_id' => 3,
                'subject_id' => 8, // Computer Science
                'teacher_id' => 3,
                'day_of_week' => 'thursday',
                'start_time' => '11:00',
                'end_time' => '12:00',
                'room_number' => 'LAB',
                'semester' => 'first',
                'academic_year' => '2024-2025',
                'is_active' => true,
            ],
        ];

        foreach ($schedules as $schedule) {
            Schedule::create($schedule);
        }
    }
}
