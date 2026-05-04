<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exam;
use App\Models\Subject;
use App\Models\Classroom;

class ExamSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = Subject::all();
        $classrooms = Classroom::all();

        foreach ($classrooms->take(5) as $classroom) {
            foreach ($subjects->take(3) as $subject) {
                Exam::create([
                    'subject_id' => $subject->id,
                    'classroom_id' => $classroom->id,
                    'name' => $subject->name . ' - Quiz 1',
                    'type' => 'quiz',
                    'max_score' => 100,
                    'scheduled_at' => now()->addDays(rand(1,30)),
                    'duration_minutes' => 30,
                    'is_online' => (bool) rand(0,1),
                    'instructions' => 'Answer all questions',
                ]);
            }
        }
    }
}

