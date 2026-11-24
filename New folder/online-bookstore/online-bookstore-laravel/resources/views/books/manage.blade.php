@extends('layouts.app')

@section('title', 'Manage Books')

@section('content')
<div class="panel-box p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0" style="color: var(--theme-teal);">Book Inventory</h5>
        <a href="{{ route('books.create') }}" class="btn btn-store btn-sm px-3">+ New Book</a>
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
                @forelse($list as $item)
                    <tr>
                        <td class="fw-semibold">{{ $item->title }}</td>
                        <td class="text-secondary small">{{ $item->author }}</td>
                        <td class="text-secondary small">{{ $item->category }}</td>
                        <td class="cost-label">{{ number_format((float)$item->price, 2) }} SAR</td>
                        <td class="small">{{ $item->quantity }}</td>
                        <td class="text-end">
                            @auth
                                <a href="{{ route('books.edit', $item) }}" class="btn btn-sm btn-outline-secondary py-0 px-2">Edit</a>
                                <form action="{{ route('books.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove this book?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2">Delete</button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="small text-decoration-none" style="color: var(--theme-teal);">Sign in to edit</a>
                            @endauth
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No books in inventory.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
