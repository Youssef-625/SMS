<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\Classroom;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $students = Student::limit(10)->get();
        $classrooms = Classroom::limit(3)->get();

        foreach ($classrooms as $classroom) {
            foreach ($students as $student) {
                Attendance::create([
                    'student_id' => $student->id,
                    'classroom_id' => $classroom->id,
                    'date' => now()->subDays(rand(0,14))->toDateString(),
                    'status' => ['present','absent','late','excused'][array_rand(['present','absent','late','excused'])],
                    'notes' => null,
                ]);
            }
        }
    }
}

