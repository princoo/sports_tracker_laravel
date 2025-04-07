<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'USER',
            'ADMIN',
            'CEO',
            'HSO',
            'FOOTBALL_DIRECTOR',
            'TECHNITIAN',
            'COACH',
        ];

        foreach ($roles as $roleName) {
            // Use updateOrCreate which is similar to upsert in Prisma
            Role::updateOrCreate(
                ['role_name' => $roleName],
                [] // No additional fields to update
            );
        }

        $this->command->info('Roles seeded successfully.');
    }
}
