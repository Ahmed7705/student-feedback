<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    private array $cat_list = ['Programming', 'Database', 'Software Engineering', 'Networks'];

    public function index(Request $request)
    {
        $q = Book::query();

        if ($request->filled('category')) {
            $q->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $txt = $request->search;
            $q->where(function ($sub) use ($txt) {
                $sub->where('title', 'like', "%$txt%")
                    ->orWhere('author', 'like', "%$txt%");
            });
        }

        $list = $q->orderBy('id', 'desc')->get();
        $cat_list = $this->cat_list;
        return view('books.index', compact('list', 'cat_list'));
    }

    public function manage()
    {
        $list = Book::orderBy('id', 'desc')->get();
        return view('books.manage', compact('list'));
    }

    public function create()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors(['email' => 'Please sign in first.']);
        }
        $cat_list = $this->cat_list;
        return view('books.create', compact('cat_list'));
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $payload = $request->validate([
            'title'    => 'required|min:2|max:150',
            'author'   => 'required|min:2|max:100',
            'category' => 'required',
            'price'    => 'required|numeric|gt:0',
            'quantity' => 'required|integer|min:0',
            'image'    => 'nullable|image|max:2048',
        ], [
            'title.required'    => 'Enter book title.',
            'title.min'         => 'Enter book title.',
            'author.required'   => 'Enter author name.',
            'author.min'        => 'Enter author name.',
            'category.required' => 'Choose a category.',
            'price.required'    => 'Enter a valid price greater than zero.',
            'price.numeric'     => 'Enter a valid price greater than zero.',
            'price.gt'          => 'Enter a valid price greater than zero.',
            'quantity.required' => 'Enter a valid quantity.',
            'quantity.integer'  => 'Enter a valid quantity.',
            'quantity.min'      => 'Enter a valid quantity.',
            'image.image'       => 'Unsupported image format.',
        ]);

        if ($request->hasFile('image')) {
            $f = $request->file('image');
            $saved_name = time() . '_' . $f->getClientOriginalName();
            $f->move(public_path('uploads'), $saved_name);
            $payload['image_path'] = 'uploads/' . $saved_name;
        }

        Book::create($payload);
        return redirect()->route('books.manage')->with('success', 'Book saved to catalog.');
    }

    public function edit(Book $book)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors(['email' => 'Please sign in first.']);
        }
        $record = $book;
        $cat_list = $this->cat_list;
        return view('books.edit', compact('record', 'cat_list'));
    }

    public function update(Request $request, Book $book)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $payload = $request->validate([
            'title'    => 'required|min:2|max:150',
            'author'   => 'required|min:2|max:100',
            'category' => 'required',
            'price'    => 'required|numeric|gt:0',
            'quantity' => 'required|integer|min:0',
            'image'    => 'nullable|image|max:2048',
        ], [
            'title.required'    => 'Enter book title.',
            'title.min'         => 'Enter book title.',
            'author.required'   => 'Enter author name.',
            'author.min'        => 'Enter author name.',
            'category.required' => 'Choose a category.',
            'price.required'    => 'Enter a valid price greater than zero.',
            'price.numeric'     => 'Enter a valid price greater than zero.',
            'price.gt'          => 'Enter a valid price greater than zero.',
            'quantity.required' => 'Enter a valid quantity.',
            'quantity.integer'  => 'Enter a valid quantity.',
            'quantity.min'      => 'Enter a valid quantity.',
            'image.image'       => 'Unsupported image format.',
        ]);

        if ($request->hasFile('image')) {
            $f = $request->file('image');
            $saved_name = time() . '_' . $f->getClientOriginalName();
            $f->move(public_path('uploads'), $saved_name);
            $payload['image_path'] = 'uploads/' . $saved_name;
        }

        $book->update($payload);
        return redirect()->route('books.manage')->with('success', 'Book details updated.');
    }

    public function destroy(Book $book)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $book->delete();
        return redirect()->route('books.manage')->with('success', 'Book removed.');
    }
}
