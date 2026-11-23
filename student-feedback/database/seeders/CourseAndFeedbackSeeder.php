<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Feedback;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CourseAndFeedbackSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Maryam Jafry',
            'email' => 'maryam@jazanu.edu.sa',
            'password' => Hash::make('123456'),
        ]);

        $c1 = Course::create(['course_code' => 'COMP-315', 'course_name' => 'Web Programming', 'instructor_name' => 'Dr. Khalid Al-Zahrani', 'department' => 'Computer Science']);
        $c2 = Course::create(['course_code' => 'COMP-212', 'course_name' => 'Object-Oriented Programming', 'instructor_name' => 'Dr. Sara Al-Qahtani', 'department' => 'Computer Science']);
        $c3 = Course::create(['course_code' => 'COMP-241', 'course_name' => 'Database Systems', 'instructor_name' => 'Dr. Noura Al-Shehri', 'department' => 'Computer Science']);
        $c4 = Course::create(['course_code' => 'COMP-331', 'course_name' => 'Computer Networks', 'instructor_name' => 'Dr. Omar Al-Harbi', 'department' => 'Information Technology']);

        Feedback::create([
            'course_id' => $c1->id,
            'student_name' => 'Maryam Jafry',
            'student_id' => '202500647',
            'rating' => 5,
            'category' => 'Teaching Quality',
            'title' => 'Good web lectures',
            'comments' => 'Clear explanation of HTML, PHP, and database labs.',
            'is_anonymous' => false,
        ]);

        Feedback::create([
            'course_id' => $c1->id,
            'student_name' => 'Khadija Hussein',
            'student_id' => '202514175',
            'rating' => 5,
            'category' => 'Course Content',
            'title' => 'Useful course content',
            'comments' => 'Bootstrap and PHP tasks were helpful and easy to follow.',
            'is_anonymous' => false,
        ]);

        Feedback::create([
            'course_id' => $c3->id,
            'student_name' => 'Mayaz Ahmed',
            'student_id' => '202502521',
            'rating' => 4,
            'category' => 'Lab & Practical',
            'title' => 'Helpful SQL labs',
            'comments' => 'Good practice on MySQL tables and queries in the lab.',
            'is_anonymous' => false,
        ]);
    }
}
