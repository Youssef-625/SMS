<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Submission;
use App\Models\Assignment;
use App\Models\Student;

class SubmissionSeeder extends Seeder
{
    public function run(): void
    {
        $assignments = Assignment::all();
        $students = Student::all();

        foreach ($assignments as $assignment) {
            foreach ($students->take(5) as $student) {
                Submission::create([
                    'assignment_id' => $assignment->id,
                    'student_id' => $student->id,
                    'submitted_at' => now()->subDays(rand(0,3)),
                    'content' => 'Sample submission content',
                    'file_path' => null,
                ]);
            }
        }
    }
}

