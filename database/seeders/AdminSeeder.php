<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin'),
            'role' => 'admin'
        ]);

        Admin::create([
            'user_id' => $user->id,
            'employee_id' => 'ADMIN001',
            'department' => 'Information Technology',
            'position' => 'System Administrator'
        ]);
    }
}