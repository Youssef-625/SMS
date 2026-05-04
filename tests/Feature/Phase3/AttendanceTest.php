<?php

namespace Tests\Feature\Phase3;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Student;

class AttendanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_can_record_attendance()
    {
        $this->artisan('db:seed');

        $teacherUser = User::where('role','teacher')->first();
        $student = Student::first();
        $classroom_id = $student->classrooms()->first()->id ?? 1;

        $this->actingAs($teacherUser, 'sanctum')
             ->postJson('/api/attendances', [
                 'student_id' => $student->id,
                 'classroom_id' => $classroom_id,
                 'date' => now()->toDateString(),
                 'status' => 'present',
             ])->assertStatus(201)
               ->assertJson(['success' => true]);
    }
}

