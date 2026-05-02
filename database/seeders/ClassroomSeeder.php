<?php

namespace Database\Seeders;

use App\Models\Classroom;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassroomSeeder extends Seeder
{
    public function run(): void
    {
        $classrooms = [
            [
                'name' => 'Grade 1A',
                'grade_level' => 'Grade 1',
                'capacity' => 25,
                'academic_year' => '2024-2025',
                'description' => 'Primary Grade 1 Class A',
                'is_active' => true,
            ],
            [
                'name' => 'Grade 1B',
                'grade_level' => 'Grade 1',
                'capacity' => 25,
                'academic_year' => '2024-2025',
                'description' => 'Primary Grade 1 Class B',
                'is_active' => true,
            ],
            [
                'name' => 'Grade 2A',
                'grade_level' => 'Grade 2',
                'capacity' => 30,
                'academic_year' => '2024-2025',
                'description' => 'Primary Grade 2 Class A',
                'is_active' => true,
            ],
            [
                'name' => 'Grade 3A',
                'grade_level' => 'Grade 3',
                'capacity' => 28,
                'academic_year' => '2024-2025',
                'description' => 'Primary Grade 3 Class A',
                'is_active' => true,
            ],
            [
                'name' => 'Grade 4A',
                'grade_level' => 'Grade 4',
                'capacity' => 32,
                'academic_year' => '2024-2025',
                'description' => 'Primary Grade 4 Class A',
                'is_active' => true,
            ],
            [
                'name' => 'Grade 5A',
                'grade_level' => 'Grade 5',
                'capacity' => 30,
                'academic_year' => '2024-2025',
                'description' => 'Primary Grade 5 Class A',
                'is_active' => true,
            ],
        ];

        foreach ($classrooms as $classroom) {
            Classroom::create($classroom);
        }
    }
}
