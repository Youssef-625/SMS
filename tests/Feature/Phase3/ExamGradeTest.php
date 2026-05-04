<?php

namespace Tests\Feature\Phase3;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Exam;
use App\Models\Grade;

class ExamGradeTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_can_create_exam_and_grade_student()
    {
        $this->artisan('db:seed');

        $teacherUser = User::where('role','teacher')->first();
        $studentUser = User::where('role','student')->first();

        $examPayload = [
            'subject_id' => 1,
            'classroom_id' => 1,
            'name' => 'Test Exam',
            'type' => 'quiz',
            'max_score' => 50,
        ];

        $this->actingAs($teacherUser, 'sanctum')
             ->postJson('/api/exams', $examPayload)
             ->assertStatus(201)
             ->assertJson(['success' => true]);

        $exam = Exam::where('name','Test Exam')->first();

        $this->actingAs($teacherUser, 'sanctum')
             ->postJson('/api/grades', [
                 'exam_id' => $exam->id,
                 'student_id' => $studentUser->student->id,
                 'score' => 45,
                 'grade' => 'A',
             ])->assertStatus(201)
               ->assertJson(['success' => true]);
    }
}

