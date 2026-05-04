<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Assignment;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Teacher;

class AssignmentSeeder extends Seeder
{
    public function run(): void
    {
        $classrooms = Classroom::all();
        $subjects = Subject::all();
        $teachers = Teacher::all();

        foreach ($classrooms->take(5) as $classroom) {
            $subject = $subjects->random();
            $teacher = $teachers->random();

            Assignment::create([
                'classroom_id' => $classroom->id,
                'subject_id' => $subject->id,
                'teacher_id' => $teacher->id,
                'title' => 'Homework for ' . $subject->name,
                'description' => 'Complete exercises 1-5',
                'assigned_at' => now()->subDays(2),
                'due_at' => now()->addDays(5),
                'points' => 100,
                'is_active' => true,
            ]);
        }
    }
}

