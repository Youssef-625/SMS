<?php

namespace Database\Seeders;

use App\Models\ParentModel;
use App\Models\User;
use Illuminate\Database\Seeder;

class ParentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parents = [
            [
                'email' => 'parent.one@sms.com',
                'parent_id' => 'PAR001',
                'phone' => '+201234567898',
                'address' => '555 Parent Lane, Cairo',
                'occupation' => 'Engineer',
            ],
            [
                'email' => 'parent.two@sms.com',
                'parent_id' => 'PAR002',
                'phone' => '+201234567899',
                'address' => '666 Family St, Alexandria',
                'occupation' => 'Doctor',
            ],
            [
                'email' => 'parent.three@sms.com',
                'parent_id' => 'PAR003',
                'phone' => '+201234567900',
                'address' => '777 Home Ave, Giza',
                'occupation' => 'Business Owner',
            ],
        ];

        foreach ($parents as $parentData) {
            $user = User::where('email', $parentData['email'])->first();
            
            if ($user) {
                ParentModel::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'parent_id' => $parentData['parent_id'],
                        'phone' => $parentData['phone'],
                        'address' => $parentData['address'],
                        'occupation' => $parentData['occupation'],
                    ]
                );
            }
        }

        $this->command->info('✓ Parents seeded successfully');
    }
}
