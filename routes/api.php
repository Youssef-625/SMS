<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ClassroomRelationshipController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\SubmissionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return response()->json([
        'message' => 'Hello World'
    ]);
});

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/files/upload', [FileUploadController::class, 'upload']);
    Route::post('/files/upload-multiple', [FileUploadController::class, 'uploadMultiple']);
    Route::delete('/files/delete', [FileUploadController::class, 'delete']);

    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead']);
    Route::get('/notifications/{notification}', [NotificationController::class, 'show']);
    Route::post('/notifications/{notification}/mark-read', [NotificationController::class, 'markAsRead']);
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy']);
    // Authenticated teacher 'me' endpoints (for logged-in teachers)
    Route::get('/teachers/me/classrooms', [TeacherController::class, 'myClassrooms']);
    Route::get('/teachers/me/students', [TeacherController::class, 'myStudents']);
});

Route::group(['middleware' => ['auth:sanctum','role:admin|super_admin']], function () {
    Route::apiResource('users', UserController::class);
    Route::apiResource('students', StudentController::class);
    Route::apiResource('teachers', TeacherController::class);
    Route::apiResource('parents', ParentController::class);
    Route::apiResource('classrooms', ClassroomController::class);
    Route::apiResource('subjects', SubjectController::class);
    Route::apiResource('schedules', ScheduleController::class);
    Route::apiResource('attendances', AttendanceController::class)->only(['index','store','show','update','destroy']);
    Route::apiResource('exams', ExamController::class);
    Route::apiResource('grades', GradeController::class)->only(['index','store','show','destroy']);
    Route::apiResource('assignments', AssignmentController::class);
    // Submissions: admins can list/delete; students submit via auth routes below
    Route::apiResource('submissions', SubmissionController::class)->only(['index','show','destroy']);
    // Admin endpoints to fetch teacher relationships
    Route::get('teachers/{teacher}/classrooms', [TeacherController::class, 'classrooms']);
    Route::get('teachers/{teacher}/students', [TeacherController::class, 'students']);

    // Classroom Relationship Management
    Route::prefix('classroom-relationships')->group(function () {
        // Student Enrollment
        Route::post('/enroll-student', [ClassroomRelationshipController::class, 'enrollStudent']);
        Route::delete('/remove-student', [ClassroomRelationshipController::class, 'removeStudent']);
        Route::put('/update-student-status', [ClassroomRelationshipController::class, 'updateStudentStatus']);
        
        // Teacher Assignment
        Route::post('/assign-teacher', [ClassroomRelationshipController::class, 'assignTeacher']);
        Route::delete('/remove-teacher', [ClassroomRelationshipController::class, 'removeTeacher']);
        Route::put('/update-teacher-role', [ClassroomRelationshipController::class, 'updateTeacherRole']);
        
        // Subject Assignment
        Route::post('/assign-subject', [ClassroomRelationshipController::class, 'assignSubject']);
        Route::delete('/remove-subject', [ClassroomRelationshipController::class, 'removeSubject']);
        Route::put('/change-subject-teacher', [ClassroomRelationshipController::class, 'changeSubjectTeacher']);
        Route::put('/update-subject-hours', [ClassroomRelationshipController::class, 'updateSubjectHours']);
        
        // Get all relationships
        Route::get('/classroom/{classroom}', [ClassroomRelationshipController::class, 'getClassroomRelationships']);
    });
});

// Authenticated routes for students/teachers (submissions, attendances marking by teacher/student)
Route::middleware('auth:sanctum')->group(function () {
    // Students submit assignments
    Route::post('/assignments/{assignment}/submit', [SubmissionController::class, 'store']);
    // Teachers grade submission
    Route::post('/submissions/{submission}/grade', [SubmissionController::class, 'grade']);
    // Teachers or admin can record attendance via attendances resource (store)
});
