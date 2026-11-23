<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedbacks';

    protected $fillable = [
        'course_id',
        'student_name',
        'student_id',
        'rating',
        'category',
        'title',
        'comments',
        'image_path',
        'is_anonymous',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function getImageUrlAttribute()
    {
        if (!empty($this->image_path)) {
            return asset($this->image_path);
        }
        if (str_contains(strtolower($this->category), 'content')) {
            return asset('images/course-web-stacks.jpg');
        }
        if ($this->course && $this->course->course_code === 'COMP-241') {
            return asset('images/course-database.jpg');
        }
        if ($this->course && $this->course->course_code === 'COMP-212') {
            return asset('images/course-oop.jpg');
        }
        if ($this->course && $this->course->course_code === 'COMP-331') {
            return asset('images/course-networks.jpg');
        }
        return asset('images/course-web-dev.jpg');
    }
}
