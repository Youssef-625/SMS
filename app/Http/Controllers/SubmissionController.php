<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\Assignment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Submission::with(['student.user','assignment']);

        if ($request->has('assignment_id')) {
            $query->where('assignment_id', $request->assignment_id);
        }

        if ($request->has('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        $subs = $query->orderBy('submitted_at','desc')->paginate(20);

        return $this->success($subs);
    }

    public function store(Request $request): JsonResponse
    {
        // students submit; teachers/admins can also create on behalf
        $validated = $request->validate([
            'assignment_id' => 'required|exists:assignments,id',
            'student_id' => 'required|exists:students,id',
            'content' => 'nullable|string',
            'file_path' => 'nullable|string',
            'submitted_at' => 'nullable|date',
        ]);

        $assignment = Assignment::findOrFail($validated['assignment_id']);

        $submission = Submission::updateOrCreate(
            ['assignment_id' => $validated['assignment_id'], 'student_id' => $validated['student_id']],
            ['content' => $validated['content'] ?? null, 'file_path' => $validated['file_path'] ?? null, 'submitted_at' => $validated['submitted_at'] ?? now()]
        );

        return $this->success($submission, 'Submission recorded', 201);
    }

    public function show(Submission $submission): JsonResponse
    {
        return $this->success($submission->load(['student.user','assignment','grader']));
    }

    public function grade(Request $request, Submission $submission): JsonResponse
    {
        $user = auth()->user();
        if (!$user || !in_array($user->role, ['admin','super_admin','teacher'])) {
            abort(403, 'Forbidden');
        }

        $validated = $request->validate([
            'score' => 'nullable|numeric|min:0',
            'feedback' => 'nullable|string',
        ]);

        $submission->update([
            'score' => $validated['score'] ?? $submission->score,
            'feedback' => $validated['feedback'] ?? $submission->feedback,
            'graded_by' => $user->id,
            'graded_at' => now(),
        ]);

        return $this->success($submission->fresh());
    }

    public function destroy(Submission $submission): JsonResponse
    {
        $user = auth()->user();
        if (!$user || !in_array($user->role, ['admin','super_admin','teacher'])) {
            abort(403, 'Forbidden');
        }

        $submission->delete();
        return $this->success(null, 'Submission deleted');
    }
}

