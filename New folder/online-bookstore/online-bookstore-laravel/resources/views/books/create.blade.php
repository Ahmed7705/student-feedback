@extends('layouts.app')

@section('title', 'Add Book')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="panel-box p-4">
            <h5 class="fw-bold mb-3" style="color: var(--theme-teal);">Add Book to Catalog</h5>

            @if ($errors->any())
                <div class="alert alert-danger py-2 small">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $msg)
                            <li>{{ $msg }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label small fw-bold">Book Title *</label>
                    <input type="text" name="title" class="form-control form-control-sm" value="{{ old('title') }}" placeholder="e.g. Modern PHP Programming" required>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Author *</label>
                        <input type="text" name="author" class="form-control form-control-sm" value="{{ old('author') }}" placeholder="e.g. Josh Lockhart" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Category *</label>
                        <select name="category" class="form-select form-select-sm" required>
                            @foreach($cat_list as $c)
                                <option value="{{ $c }}" {{ old('category') === $c ? 'selected' : '' }}>{{ $c }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Price (SAR) *</label>
                        <input type="number" step="0.01" min="0.01" name="price" class="form-control form-control-sm" value="{{ old('price') }}" placeholder="e.g. 48.00" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Quantity *</label>
                        <input type="number" min="0" name="quantity" class="form-control form-control-sm" value="{{ old('quantity') }}" placeholder="e.g. 10" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold">Cover Image (Optional)</label>
                    <input type="file" name="image" class="form-control form-control-sm" accept="image/*">
                </div>

                <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                    <button type="reset" class="btn btn-outline-secondary btn-sm">Reset</button>
                    <button type="submit" class="btn btn-store btn-sm px-4">Add Book</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
