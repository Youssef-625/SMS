<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            [
                'name' => 'Mathematics',
                'code' => 'MATH',
                'description' => 'Mathematics fundamentals and problem solving',
                'credits' => 5,
                'type' => 'core',
                'is_active' => true,
            ],
            [
                'name' => 'English Language',
                'code' => 'ENG',
                'description' => 'English reading, writing, and communication',
                'credits' => 4,
                'type' => 'core',
                'is_active' => true,
            ],
            [
                'name' => 'Science',
                'code' => 'SCI',
                'description' => 'General science including biology, chemistry, physics',
                'credits' => 4,
                'type' => 'core',
                'is_active' => true,
            ],
            [
                'name' => 'Social Studies',
                'code' => 'SOC',
                'description' => 'History, geography, and civics',
                'credits' => 3,
                'type' => 'core',
                'is_active' => true,
            ],
            [
                'name' => 'Physical Education',
                'code' => 'PE',
                'description' => 'Physical fitness and sports activities',
                'credits' => 2,
                'type' => 'core',
                'is_active' => true,
            ],
            [
                'name' => 'Art',
                'code' => 'ART',
                'description' => 'Visual arts and creative expression',
                'credits' => 2,
                'type' => 'elective',
                'is_active' => true,
            ],
            [
                'name' => 'Music',
                'code' => 'MUS',
                'description' => 'Music theory and practice',
                'credits' => 2,
                'type' => 'elective',
                'is_active' => true,
            ],
            [
                'name' => 'Computer Science',
                'code' => 'CS',
                'description' => 'Basic computer skills and programming',
                'credits' => 3,
                'type' => 'elective',
                'is_active' => true,
            ],
        ];

        foreach ($subjects as $subject) {
            Subject::create($subject);
        }
    }
}
