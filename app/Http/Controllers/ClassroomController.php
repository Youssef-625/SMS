<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClassroomController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Classroom::with(['students', 'teachers', 'subjects']);
        
        if ($request->has('grade_level')) {
            $query->where('grade_level', $request->grade_level);
        }
        
        if ($request->has('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }
        
        $classrooms = $query->paginate(10);
        
        return $this->success($classrooms);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'grade_level' => 'required|string|max:50',
            'capacity' => 'integer|min:1|max:100',
            'academic_year' => 'required|string|max:20',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $classroom = Classroom::create($validated);
        
        return $this->success($classroom->load(['students', 'teachers', 'subjects']), 201);
    }

    public function show(Classroom $classroom): JsonResponse
    {
        return $this->success($classroom->load(['students', 'teachers', 'subjects']));
    }

    public function update(Request $request, Classroom $classroom): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255', Rule::unique('classrooms')->ignore($classroom->id)],
            'grade_level' => 'sometimes|string|max:50',
            'capacity' => 'sometimes|integer|min:1|max:100',
            'academic_year' => 'sometimes|string|max:20',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $classroom->update($validated);
        
        return $this->success($classroom->load(['students', 'teachers', 'subjects']));
    }

    public function destroy(Classroom $classroom): JsonResponse
    {
        $classroom->delete();
        
        return $this->success(null, 'Classroom deleted successfully');
    }
}
