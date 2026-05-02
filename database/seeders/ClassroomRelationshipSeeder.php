<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassroomRelationshipSeeder extends Seeder
{
    public function run(): void
    {
        // Get existing data
        $classrooms = Classroom::all();
        $students = Student::all();
        $teachers = Teacher::all();
        $subjects = Subject::all();

        if ($students->isEmpty() || $teachers->isEmpty()) {
            $this->command->info('No students or teachers found. Please run other seeders first.');
            return;
        }

        // Classroom-Student relationships (enroll students in classrooms)
        $classroomStudentData = [
            // Grade 1A students
            ['classroom_id' => 1, 'student_id' => 1, 'enrolled_at' => '2024-09-01', 'status' => 'active'],
            ['classroom_id' => 1, 'student_id' => 2, 'enrolled_at' => '2024-09-01', 'status' => 'active'],
            ['classroom_id' => 1, 'student_id' => 3, 'enrolled_at' => '2024-09-01', 'status' => 'active'],
            
            // Grade 1B students
            ['classroom_id' => 2, 'student_id' => 4, 'enrolled_at' => '2024-09-01', 'status' => 'active'],
            ['classroom_id' => 2, 'student_id' => 5, 'enrolled_at' => '2024-09-01', 'status' => 'active'],
            
            // Grade 2A students
            ['classroom_id' => 3, 'student_id' => 4, 'enrolled_at' => '2024-09-01', 'status' => 'active'],
            ['classroom_id' => 3, 'student_id' => 5, 'enrolled_at' => '2024-09-01', 'status' => 'active'],
        ];

        // Classroom-Teacher relationships (assign teachers to classrooms)
        $classroomTeacherData = [
            // Grade 1A - Homeroom teacher
            ['classroom_id' => 1, 'teacher_id' => 1, 'role' => 'homeroom', 'assigned_at' => '2024-09-01'],
            
            // Grade 1B - Homeroom teacher
            ['classroom_id' => 2, 'teacher_id' => 2, 'role' => 'homeroom', 'assigned_at' => '2024-09-01'],
            
            // Grade 2A - Homeroom teacher
            ['classroom_id' => 3, 'teacher_id' => 3, 'role' => 'homeroom', 'assigned_at' => '2024-09-01'],
            
            // Additional subject teachers
            ['classroom_id' => 1, 'teacher_id' => 2, 'role' => 'subject_teacher', 'assigned_at' => '2024-09-01'],
            ['classroom_id' => 3, 'teacher_id' => 1, 'role' => 'subject_teacher', 'assigned_at' => '2024-09-01'],
        ];

        // Classroom-Subject relationships (assign subjects to classrooms with teachers)
        $classroomSubjectData = [
            // Grade 1A subjects
            ['classroom_id' => 1, 'subject_id' => 1, 'teacher_id' => 1, 'weekly_hours' => 5, 'semester' => 'first', 'academic_year' => '2024-2025'], // Math
            ['classroom_id' => 1, 'subject_id' => 2, 'teacher_id' => 2, 'weekly_hours' => 4, 'semester' => 'first', 'academic_year' => '2024-2025'], // English
            ['classroom_id' => 1, 'subject_id' => 3, 'teacher_id' => 3, 'weekly_hours' => 3, 'semester' => 'first', 'academic_year' => '2024-2025'], // Science
            ['classroom_id' => 1, 'subject_id' => 5, 'teacher_id' => 3, 'weekly_hours' => 2, 'semester' => 'first', 'academic_year' => '2024-2025'], // PE
            
            // Grade 1B subjects
            ['classroom_id' => 2, 'subject_id' => 1, 'teacher_id' => 1, 'weekly_hours' => 5, 'semester' => 'first', 'academic_year' => '2024-2025'], // Math
            ['classroom_id' => 2, 'subject_id' => 2, 'teacher_id' => 2, 'weekly_hours' => 4, 'semester' => 'first', 'academic_year' => '2024-2025'], // English
            ['classroom_id' => 2, 'subject_id' => 3, 'teacher_id' => 3, 'weekly_hours' => 3, 'semester' => 'first', 'academic_year' => '2024-2025'], // Science
            
            // Grade 2A subjects
            ['classroom_id' => 3, 'subject_id' => 1, 'teacher_id' => 1, 'weekly_hours' => 6, 'semester' => 'first', 'academic_year' => '2024-2025'], // Math
            ['classroom_id' => 3, 'subject_id' => 2, 'teacher_id' => 2, 'weekly_hours' => 4, 'semester' => 'first', 'academic_year' => '2024-2025'], // English
            ['classroom_id' => 3, 'subject_id' => 8, 'teacher_id' => 3, 'weekly_hours' => 2, 'semester' => 'first', 'academic_year' => '2024-2025'], // Computer Science
        ];

        // Insert relationships
        DB::table('classroom_student')->insert($classroomStudentData);
        DB::table('classroom_teacher')->insert($classroomTeacherData);
        DB::table('classroom_subject')->insert($classroomSubjectData);

        $this->command->info('✅ Classroom relationships seeded successfully!');
        $this->command->info('   - ' . count($classroomStudentData) . ' student enrollments');
        $this->command->info('   - ' . count($classroomTeacherData) . ' teacher assignments');
        $this->command->info('   - ' . count($classroomSubjectData) . ' subject assignments');
    }
}
