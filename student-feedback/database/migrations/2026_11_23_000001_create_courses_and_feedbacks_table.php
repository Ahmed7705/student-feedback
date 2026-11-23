<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Courses table
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('course_code')->unique();
            $table->string('course_name');
            $table->string('instructor_name');
            $table->string('department')->default('Computer Science');
            $table->timestamps();
        });

        // Feedbacks table
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->string('student_name');
            $table->string('student_id');
            $table->integer('rating');
            $table->string('category');
            $table->string('title');
            $table->text('comments');
            $table->string('image_path')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
        Schema::dropIfExists('courses');
    }
};
