<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Classroom;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeacherRelationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_fetch_teacher_classrooms_and_students()
    {
        // create admin
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'super_admin',
        ]);

        $teacherUser = User::create([
            'name' => 'Teacher One',
            'email' => 'teacher1@example.com',
            'password' => bcrypt('password'),
            'role' => 'teacher',
        ]);

        $teacher = Teacher::create([
            'user_id' => $teacherUser->id,
            'teacher_id' => 'T100',
        ]);

        $class1 = Classroom::create(['name' => 'A', 'grade_level' => '1', 'academic_year' => '2026']);
        $class2 = Classroom::create(['name' => 'B', 'grade_level' => '1', 'academic_year' => '2026']);

        $teacher->classrooms()->attach($class1->id, ['role' => 'subject_teacher', 'assigned_at' => now()->toDateString()]);
        $teacher->classrooms()->attach($class2->id, ['role' => 'subject_teacher', 'assigned_at' => now()->toDateString()]);

        $student1User = User::create(['name' => 'S1', 'email' => 's1@example.com', 'password' => bcrypt('password'), 'role' => 'student']);
        $student2User = User::create(['name' => 'S2', 'email' => 's2@example.com', 'password' => bcrypt('password'), 'role' => 'student']);

        $student1 = Student::create(['user_id' => $student1User->id, 'student_id' => 'S100']);
        $student2 = Student::create(['user_id' => $student2User->id, 'student_id' => 'S101']);

        $class1->students()->attach($student1->id, ['enrolled_at' => now()->toDateString()]);
        $class2->students()->attach($student2->id, ['enrolled_at' => now()->toDateString()]);

        $this->actingAs($admin, 'sanctum')
             ->getJson("/api/teachers/{$teacher->id}/classrooms")
             ->assertStatus(200)
             ->assertJsonStructure(['success', 'message', 'data']);

        $this->actingAs($admin, 'sanctum')
             ->getJson("/api/teachers/{$teacher->id}/students")
             ->assertStatus(200)
             ->assertJsonStructure(['success', 'message', 'data']);
    }

    public function test_teacher_can_fetch_their_own_classrooms_and_students()
    {
        $teacherUser = User::create([
            'name' => 'Teacher Me',
            'email' => 'teachme@example.com',
            'password' => bcrypt('password'),
            'role' => 'teacher',
        ]);

        $teacher = Teacher::create(['user_id' => $teacherUser->id, 'teacher_id' => 'T200']);

        $class = Classroom::create(['name' => 'C', 'grade_level' => '2', 'academic_year' => '2026']);
        $teacher->classrooms()->attach($class->id, ['role' => 'homeroom', 'assigned_at' => now()->toDateString()]);

        $studentUser = User::create(['name' => 'Stu', 'email' => 'stu@example.com', 'password' => bcrypt('password'), 'role' => 'student']);
        $student = Student::create(['user_id' => $studentUser->id, 'student_id' => 'S200']);
        $class->students()->attach($student->id, ['enrolled_at' => now()->toDateString()]);

        $this->actingAs($teacherUser, 'sanctum')
             ->getJson('/api/teachers/me/classrooms')
             ->assertStatus(200)
             ->assertJsonStructure(['success', 'message', 'data']);

        $this->actingAs($teacherUser, 'sanctum')
             ->getJson('/api/teachers/me/students')
             ->assertStatus(200)
             ->assertJsonStructure(['success', 'message', 'data']);
    }
}

