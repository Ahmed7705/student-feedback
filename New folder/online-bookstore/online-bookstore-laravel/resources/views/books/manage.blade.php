@extends('layouts.app')

@section('title', 'Manage Books')

@section('content')
<div class="card p-4 bg-white">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0" style="color: var(--app-blue);">Manage Books</h5>
        <a href="{{ route('books.create') }}" class="btn btn-blue btn-sm px-3">+ Add Book</a>
    </div>

    <div class="table-responsive">
        <table class="table table-sm align-middle mb-0">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($books as $b)
                    <tr>
                        <td class="fw-semibold">{{ $b->title }}</td>
                        <td class="text-secondary small">{{ $b->author }}</td>
                        <td class="text-secondary small">{{ $b->category }}</td>
                        <td class="price-text">{{ number_format((float)$b->price, 2) }} SAR</td>
                        <td class="small">{{ $b->quantity }}</td>
                        <td class="text-end">
                            @auth
                                <a href="{{ route('books.edit', $b) }}" class="btn btn-sm btn-outline-secondary py-0 px-2">Edit</a>
                                <form action="{{ route('books.destroy', $b) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this book?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2">Delete</button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="small text-decoration-none" style="color: var(--app-blue);">Login to edit</a>
                            @endauth
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No books found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
