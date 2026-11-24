<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    private array $categories = ['Programming', 'Database', 'Software Engineering', 'Networks'];

    public function index(Request $request)
    {
        $query = Book::query();

        // Filter books
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Search title or author
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%$s%")
                  ->orWhere('author', 'like', "%$s%");
            });
        }

        $books = $query->orderBy('id', 'desc')->get();
        $categories = $this->categories;
        return view('books.index', compact('books', 'categories'));
    }

    public function manage()
    {
        $books = Book::orderBy('id', 'desc')->get();
        return view('books.manage', compact('books'));
    }

    public function create()
    {
        // Check login
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors(['email' => 'Please login first.']);
        }
        $categories = $this->categories;
        return view('books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Validate input
        $data = $request->validate([
            'title'    => 'required|min:2|max:150',
            'author'   => 'required|min:2|max:100',
            'category' => 'required',
            'price'    => 'required|numeric|gt:0',
            'quantity' => 'required|integer|min:0',
            'image'    => 'nullable|image|max:2048',
        ], [
            'title.required'    => 'Book title is required.',
            'title.min'         => 'Book title is required.',
            'author.required'   => 'Author is required.',
            'author.min'        => 'Author is required.',
            'category.required' => 'Category is required.',
            'price.required'    => 'Price must be a valid positive number.',
            'price.numeric'     => 'Price must be a valid positive number.',
            'price.gt'          => 'Price must be a valid positive number.',
            'quantity.required' => 'Quantity must be 0 or more.',
            'quantity.integer'  => 'Quantity must be 0 or more.',
            'quantity.min'      => 'Quantity must be 0 or more.',
            'image.image'       => 'Invalid image file.',
        ]);

        // Upload image
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $name);
            $data['image_path'] = 'uploads/' . $name;
        }

        Book::create($data);
        return redirect()->route('books.manage')->with('success', 'Book added.');
    }

    public function edit(Book $book)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors(['email' => 'Please login first.']);
        }
        $categories = $this->categories;
        return view('books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $data = $request->validate([
            'title'    => 'required|min:2|max:150',
            'author'   => 'required|min:2|max:100',
            'category' => 'required',
            'price'    => 'required|numeric|gt:0',
            'quantity' => 'required|integer|min:0',
            'image'    => 'nullable|image|max:2048',
        ], [
            'title.required'    => 'Book title is required.',
            'title.min'         => 'Book title is required.',
            'author.required'   => 'Author is required.',
            'author.min'        => 'Author is required.',
            'category.required' => 'Category is required.',
            'price.required'    => 'Price must be a valid positive number.',
            'price.numeric'     => 'Price must be a valid positive number.',
            'price.gt'          => 'Price must be a valid positive number.',
            'quantity.required' => 'Quantity must be 0 or more.',
            'quantity.integer'  => 'Quantity must be 0 or more.',
            'quantity.min'      => 'Quantity must be 0 or more.',
            'image.image'       => 'Invalid image file.',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $name);
            $data['image_path'] = 'uploads/' . $name;
        }

        $book->update($data);
        return redirect()->route('books.manage')->with('success', 'Book updated.');
    }

    public function destroy(Book $book)
    {
        // Delete record
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $book->delete();
        return redirect()->route('books.manage')->with('success', 'Book deleted.');
    }
}
