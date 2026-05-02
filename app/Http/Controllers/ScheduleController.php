<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ScheduleController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Schedule::with(['classroom', 'subject', 'teacher']);
        
        if ($request->has('classroom_id')) {
            $query->where('classroom_id', $request->classroom_id);
        }
        
        if ($request->has('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }
        
        if ($request->has('day_of_week')) {
            $query->where('day_of_week', $request->day_of_week);
        }
        
        if ($request->has('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }
        
        $schedules = $query->orderBy('day_of_week')->orderBy('start_time')->paginate(20);
        
        return $this->success($schedules);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room_number' => 'nullable|string|max:50',
            'semester' => 'in:first,second',
            'academic_year' => 'required|string|max:20',
            'is_active' => 'boolean',
        ]);

        $schedule = Schedule::create($validated);
        
        return $this->success($schedule->load(['classroom', 'subject', 'teacher']), 201);
    }

    public function show(Schedule $schedule): JsonResponse
    {
        return $this->success($schedule->load(['classroom', 'subject', 'teacher']));
    }

    public function update(Request $request, Schedule $schedule): JsonResponse
    {
        $validated = $request->validate([
            'classroom_id' => 'sometimes|exists:classrooms,id',
            'subject_id' => 'sometimes|exists:subjects,id',
            'teacher_id' => 'sometimes|exists:teachers,id',
            'day_of_week' => 'sometimes|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'sometimes|date_format:H:i',
            'end_time' => 'sometimes|date_format:H:i|after:start_time',
            'room_number' => 'nullable|string|max:50',
            'semester' => 'sometimes|in:first,second',
            'academic_year' => 'sometimes|string|max:20',
            'is_active' => 'boolean',
        ]);

        $schedule->update($validated);
        
        return $this->success($schedule->load(['classroom', 'subject', 'teacher']));
    }

    public function destroy(Schedule $schedule): JsonResponse
    {
        $schedule->delete();
        
        return $this->success(null, 'Schedule deleted successfully');
    }
}
