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
            'name' => 'Store Admin',
            'email' => 'admin@bookstore.com',
            'password' => Hash::make('123456'),
        ]);

        Book::create(['title' => 'Modern PHP Programming', 'author' => 'Josh Lockhart', 'category' => 'Programming', 'price' => 48.00, 'quantity' => 14]);
        Book::create(['title' => 'SQL & Relational Databases', 'author' => 'Chris Date', 'category' => 'Database', 'price' => 62.00, 'quantity' => 9]);
        Book::create(['title' => 'Web Application Architecture', 'author' => 'Leon Shklar', 'category' => 'Software Engineering', 'price' => 54.00, 'quantity' => 11]);
        Book::create(['title' => 'Network Security Essentials', 'author' => 'William Stallings', 'category' => 'Networks', 'price' => 58.00, 'quantity' => 7]);
    }
}
