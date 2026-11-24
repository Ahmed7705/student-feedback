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

    public function getImageUrlAttribute()
    {
        if (!empty($this->image_path)) {
            return asset($this->image_path);
        }
        $cat = strtolower($this->category ?? '');
        if (str_contains($cat, 'database')) {
            return asset('images/book-db.jpg');
        }
        if (str_contains($cat, 'software')) {
            return asset('images/book-oop.jpg');
        }
        if (str_contains($cat, 'network')) {
            return asset('images/book-net.jpg');
        }
        return asset('images/book-web.jpg');
    }
}
