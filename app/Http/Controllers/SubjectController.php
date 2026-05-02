<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubjectController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Subject::with(['classrooms', 'teachers']);
        
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }
        
        $subjects = $query->paginate(10);
        
        return $this->success($subjects);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:subjects',
            'description' => 'nullable|string',
            'credits' => 'integer|min:1|max:10',
            'type' => 'in:core,elective,extracurricular',
            'is_active' => 'boolean',
        ]);

        $subject = Subject::create($validated);
        
        return $this->success($subject->load(['classrooms', 'teachers']), 201);
    }

    public function show(Subject $subject): JsonResponse
    {
        return $this->success($subject->load(['classrooms', 'teachers']));
    }

    public function update(Request $request, Subject $subject): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'code' => ['sometimes', 'string', 'max:20', Rule::unique('subjects')->ignore($subject->id)],
            'description' => 'nullable|string',
            'credits' => 'sometimes|integer|min:1|max:10',
            'type' => 'sometimes|in:core,elective,extracurricular',
            'is_active' => 'boolean',
        ]);

        $subject->update($validated);
        
        return $this->success($subject->load(['classrooms', 'teachers']));
    }

    public function destroy(Subject $subject): JsonResponse
    {
        $subject->delete();
        
        return $this->success(null, 'Subject deleted successfully');
    }
}
