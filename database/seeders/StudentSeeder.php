<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $courses = ['Computer Science', 'Information Technology', 'Business Administration', 'Engineering', 'Psychology'];
        $years = ['1st Year', '2nd Year', '3rd Year', '4th Year'];

        for ($i = 1; $i <= 50; $i++) {
            $user = User::create([
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'password' => Hash::make('password'),
                'role' => 'student'
            ]);

            Student::create([
                'user_id' => $user->id,
                'student_id' => '22011' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'phone' => fake()->numerify('09#########'),
                'course' => fake()->randomElement($courses),
                'year' => fake()->randomElement($years),
            ]);
        }
    }
}