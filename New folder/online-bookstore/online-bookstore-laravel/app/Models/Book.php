<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $table = 'books';

    protected $fillable = [
        'title',
        'author',
        'category',
        'price',
        'quantity',
        'image_path',
    ];

    public function getPhotoUrlAttribute()
    {
        if (!empty($this->image_path)) {
            return asset($this->image_path);
        }
        $c = strtolower($this->category ?? '');
        if (str_contains($c, 'database')) {
            return asset('images/cover-db.jpg');
        }
        if (str_contains($c, 'software')) {
            return asset('images/cover-se.jpg');
        }
        if (str_contains($c, 'network')) {
            return asset('images/cover-net.jpg');
        }
        return asset('images/cover-prog.jpg');
    }
}
