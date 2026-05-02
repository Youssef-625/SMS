<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Subject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassroomRelationshipController extends Controller
{
    // Student Enrollment Management
    public function enrollStudent(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'student_id' => 'required|exists:students,id',
            'enrolled_at' => 'required|date',
            'status' => 'in:active,transferred,graduated,suspended',
        ]);

        // Check if student is already enrolled
        $exists = DB::table('classroom_student')
            ->where('classroom_id', $validated['classroom_id'])
            ->where('student_id', $validated['student_id'])
            ->exists();

        if ($exists) {
            return $this->error('Student is already enrolled in this classroom', 422);
        }

        DB::table('classroom_student')->insert($validated);

        return $this->success($validated, 201);
    }

    public function removeStudent(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'student_id' => 'required|exists:students,id',
        ]);

        $deleted = DB::table('classroom_student')
            ->where('classroom_id', $validated['classroom_id'])
            ->where('student_id', $validated['student_id'])
            ->delete();

        if (!$deleted) {
            return $this->error('Student enrollment not found', 404);
        }

        return $this->success(null, 204);
    }

    public function updateStudentStatus(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'student_id' => 'required|exists:students,id',
            'status' => 'required|in:active,transferred,graduated,suspended',
        ]);

        $updated = DB::table('classroom_student')
            ->where('classroom_id', $validated['classroom_id'])
            ->where('student_id', $validated['student_id'])
            ->update(['status' => $validated['status']]);

        if (!$updated) {
            return $this->error('Student enrollment not found', 404);
        }

        return $this->success($validated);
    }

    // Teacher Assignment Management
    public function assignTeacher(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'teacher_id' => 'required|exists:teachers,id',
            'role' => 'required|in:homeroom,subject_teacher,assistant',
            'assigned_at' => 'required|date',
        ]);

        // Check if teacher is already assigned
        $exists = DB::table('classroom_teacher')
            ->where('classroom_id', $validated['classroom_id'])
            ->where('teacher_id', $validated['teacher_id'])
            ->exists();

        if ($exists) {
            return $this->error('Teacher is already assigned to this classroom', 422);
        }

        DB::table('classroom_teacher')->insert($validated);

        return $this->success($validated, 201);
    }

    public function removeTeacher(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'teacher_id' => 'required|exists:teachers,id',
        ]);

        $deleted = DB::table('classroom_teacher')
            ->where('classroom_id', $validated['classroom_id'])
            ->where('teacher_id', $validated['teacher_id'])
            ->delete();

        if (!$deleted) {
            return $this->error('Teacher assignment not found', 404);
        }

        return $this->success(null, 204);
    }

    public function updateTeacherRole(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'teacher_id' => 'required|exists:teachers,id',
            'role' => 'required|in:homeroom,subject_teacher,assistant',
        ]);

        $updated = DB::table('classroom_teacher')
            ->where('classroom_id', $validated['classroom_id'])
            ->where('teacher_id', $validated['teacher_id'])
            ->update(['role' => $validated['role']]);

        if (!$updated) {
            return $this->error('Teacher assignment not found', 404);
        }

        return $this->success($validated);
    }

    // Subject Assignment Management (with teacher changes)
    public function assignSubject(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'weekly_hours' => 'required|integer|min:1|max:10',
            'semester' => 'required|in:first,second',
            'academic_year' => 'required|string|max:20',
        ]);

        // Check if subject is already assigned
        $exists = DB::table('classroom_subject')
            ->where('classroom_id', $validated['classroom_id'])
            ->where('subject_id', $validated['subject_id'])
            ->exists();

        if ($exists) {
            return $this->error('Subject is already assigned to this classroom', 422);
        }

        DB::table('classroom_subject')->insert($validated);

        return $this->success($validated, 201);
    }

    public function removeSubject(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id' => 'required|exists:subjects,id',
        ]);

        $deleted = DB::table('classroom_subject')
            ->where('classroom_id', $validated['classroom_id'])
            ->where('subject_id', $validated['subject_id'])
            ->delete();

        if (!$deleted) {
            return $this->error('Subject assignment not found', 404);
        }

        return $this->success(null, 204);
    }

    public function changeSubjectTeacher(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'new_teacher_id' => 'required|exists:teachers,id|different:old_teacher_id',
        ]);

        $updated = DB::table('classroom_subject')
            ->where('classroom_id', $validated['classroom_id'])
            ->where('subject_id', $validated['subject_id'])
            ->update(['teacher_id' => $validated['new_teacher_id']]);

        if (!$updated) {
            return $this->error('Subject assignment not found', 404);
        }

        return $this->success([
            'classroom_id' => $validated['classroom_id'],
            'subject_id' => $validated['subject_id'],
            'teacher_id' => $validated['new_teacher_id']
        ]);
    }

    public function updateSubjectHours(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'weekly_hours' => 'required|integer|min:1|max:10',
        ]);

        $updated = DB::table('classroom_subject')
            ->where('classroom_id', $validated['classroom_id'])
            ->where('subject_id', $validated['subject_id'])
            ->update(['weekly_hours' => $validated['weekly_hours']]);

        if (!$updated) {
            return $this->error('Subject assignment not found', 404);
        }

        return $this->success($validated);
    }

    // Get all relationships for a classroom
    public function getClassroomRelationships(Classroom $classroom): JsonResponse
    {
        $relationships = [
            'classroom' => $classroom,
            'students' => $classroom->students()->with('user')->get(),
            'teachers' => $classroom->teachers()->with('user')->get(),
            'subjects' => $classroom->subjects()->with('teachers')->get(),
        ];

        return $this->success($relationships);
    }
}
