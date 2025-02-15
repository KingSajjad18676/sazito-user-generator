<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'api']);

        if (!User::whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->exists()) {
            $admin = User::updateOrCreate(
                ['email' => 'admin@example.com'],
                [
                    'name' => 'Main Admin',
                    'password' => Hash::make('password123'),
                ]
            );

            $admin->assignRole($adminRole);

            $this->command->info('Admin user created successfully!');
        } else {
            $this->command->info('Admin user already exists. No changes made.');
        }
    }
}
