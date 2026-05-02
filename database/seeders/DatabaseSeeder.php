<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed users first (required for all other seeders)
        $this->call([
            UserSeeder::class,
            StudentSeeder::class,
            TeacherSeeder::class,
            ParentSeeder::class,
            NotificationSeeder::class,
        ]);

        // Seed Phase 2 academic structure
        $this->call([
            ClassroomSeeder::class,
            SubjectSeeder::class,
            ScheduleSeeder::class,
            ClassroomRelationshipSeeder::class,
        ]);

        $this->command->info('✅ Phase 1 database seeding completed successfully!');
        $this->command->info('✅ Phase 2 academic structure seeding completed successfully!');
        $this->command->info('');
        $this->command->info('Test Credentials:');
        $this->command->info('Super Admin: superadmin@sms.com / password');
        $this->command->info('Admin: admin@sms.com / password');
        $this->command->info('Teacher: ahmed.hassan@sms.com / password');
        $this->command->info('Student: john.doe@sms.com / password');
        $this->command->info('Parent: parent.one@sms.com / password');
    }
}
