<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@bookstore.com',
            'password' => Hash::make('123456'),
        ]);

        Book::create(['title' => 'PHP & MySQL Web Development', 'author' => 'Luke Welling', 'category' => 'Programming', 'price' => 45.00, 'quantity' => 12]);
        Book::create(['title' => 'Database System Concepts', 'author' => 'Abraham Silberschatz', 'category' => 'Database', 'price' => 60.00, 'quantity' => 8]);
        Book::create(['title' => 'Clean Code Handbook', 'author' => 'Robert Martin', 'category' => 'Software Engineering', 'price' => 50.00, 'quantity' => 15]);
        Book::create(['title' => 'Computer Networking', 'author' => 'James Kurose', 'category' => 'Networks', 'price' => 55.00, 'quantity' => 10]);
    }
}
