<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@jazanu.edu.sa'],
            [
                'name' => 'Academic Advisor',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('appointments')->insert([
            [
                'customer_name' => 'Ahmed Al-Qahtani',
                'email' => 'ahmed@student.jazanu.edu.sa',
                'appointment_date' => now()->addDay()->format('Y-m-d'),
                'appointment_time' => '09:00:00',
                'status' => 'Booked',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'customer_name' => 'Sara Al-Harbi',
                'email' => 'sara@student.jazanu.edu.sa',
                'appointment_date' => now()->addDay()->format('Y-m-d'),
                'appointment_time' => '10:00:00',
                'status' => 'Booked',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'customer_name' => 'Khalid Al-Zahrani',
                'email' => 'khalid@student.jazanu.edu.sa',
                'appointment_date' => now()->addDays(2)->format('Y-m-d'),
                'appointment_time' => '11:00:00',
                'status' => 'Booked',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'customer_name' => 'Noura Al-Ghamdi',
                'email' => 'noura@student.jazanu.edu.sa',
                'appointment_date' => now()->format('Y-m-d'),
                'appointment_time' => '09:00:00',
                'status' => 'Completed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'customer_name' => 'Faisal Al-Otaibi',
                'email' => 'faisal@student.jazanu.edu.sa',
                'appointment_date' => now()->addDays(2)->format('Y-m-d'),
                'appointment_time' => '13:00:00',
                'status' => 'Cancelled',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
