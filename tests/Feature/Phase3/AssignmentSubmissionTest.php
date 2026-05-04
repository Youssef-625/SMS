<?php

namespace Tests\Feature\Phase3;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Assignment;
use App\Models\Submission;

class AssignmentSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_submit_assignment_and_teacher_can_grade()
    {
        // seed minimal data
        $this->artisan('db:seed');

        $studentUser = User::where('role','student')->first();
        $teacherUser = User::where('role','teacher')->first();

        $assignment = Assignment::first();

        $this->actingAs($studentUser, 'sanctum')
             ->postJson("/api/assignments/{$assignment->id}/submit", [
                 'assignment_id' => $assignment->id,
                 'student_id' => $studentUser->student->id,
                 'content' => 'Test answers',
             ])->assertStatus(201)
               ->assertJson(['success' => true]);

        $submissionId = Submission::where('assignment_id',$assignment->id)->where('student_id',$studentUser->student->id)->first()->id;

        $this->actingAs($teacherUser, 'sanctum')
             ->postJson("/api/submissions/{$submissionId}/grade", [
                 'score' => 90,
                 'feedback' => 'Nice work',
             ])->assertStatus(200)
               ->assertJson(['success' => true]);
    }
}

