<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Exam;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Grade::with(['student.user','exam']);

        if ($request->has('exam_id')) {
            $query->where('exam_id', $request->exam_id);
        }

        if ($request->has('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        $grades = $query->paginate(20);

        return $this->success($grades);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorizeRequest();

        $validated = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'student_id' => 'required|exists:students,id',
            'score' => 'nullable|numeric|min:0',
            'grade' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        $exam = Exam::findOrFail($validated['exam_id']);

        $grade = Grade::updateOrCreate(
            ['exam_id' => $validated['exam_id'], 'student_id' => $validated['student_id']],
            [
                'score' => $validated['score'] ?? null,
                'grade' => $validated['grade'] ?? null,
                'remarks' => $validated['remarks'] ?? null,
                'graded_by' => auth()->id(),
            ]
        );

        return $this->success($grade, 'Grade recorded', 201);
    }

    public function show(Grade $grade): JsonResponse
    {
        return $this->success($grade->load(['student.user','exam','grader']));
    }

    public function destroy(Grade $grade): JsonResponse
    {
        $this->authorizeRequest();
        $grade->delete();
        return $this->success(null, 'Grade deleted');
    }

    protected function authorizeRequest()
    {
        $user = auth()->user();
        if (!$user || !in_array($user->role, ['admin', 'super_admin', 'teacher'])) {
            abort(403, 'Forbidden');
        }
    }
}

