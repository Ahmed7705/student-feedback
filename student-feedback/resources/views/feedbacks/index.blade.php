@extends('layouts.app')

@section('title', 'Course Reviews')

@section('content')
<div class="card p-3 mb-4">
    <form method="GET" action="{{ route('home') }}" class="row g-2 align-items-center">
        <div class="col-md-5">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search feedback..." value="{{ request('search') }}">
        </div>
        <div class="col-md-5">
            <select name="course_id" class="form-select form-select-sm">
                <option value="">All Courses</option>
                @foreach($courses as $c)
                    <option value="{{ $c->id }}" {{ request('course_id') == $c->id ? 'selected' : '' }}>
                        {{ $c->course_code }} - {{ $c->course_name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 d-flex gap-1">
            <button type="submit" class="btn btn-blue btn-sm flex-grow-1">Filter</button>
            <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
        </div>
    </form>
</div>

<div class="row g-3">
    @forelse($feedbacks as $fb)
        <div class="col-md-6">
            <div class="card h-100 d-flex flex-column overflow-hidden">
                <img src="{{ $fb->image_url }}" alt="{{ $fb->course->course_name }}" class="card-img-top border-bottom" style="height: 150px; object-fit: cover;">

                <div class="p-3 d-flex flex-column flex-grow-1">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold" style="color: var(--app-blue); font-size: 0.9rem;">{{ $fb->course->course_code }}</span>
                        <span class="text-secondary small fw-semibold">Rating: {{ $fb->rating }}/5</span>
                    </div>

                    <h6 class="fw-bold mb-1">{{ $fb->title }}</h6>
                    <div class="text-muted small mb-2">
                        Instructor: <strong>{{ $fb->course->instructor_name }}</strong> &middot; {{ $fb->category }}
                    </div>
                    <p class="text-secondary small mb-3 flex-grow-1">{{ $fb->comments }}</p>

                    <div class="d-flex justify-content-between align-items-center pt-2 border-top small text-muted">
                        <div>
                            Student: <strong>{{ $fb->is_anonymous ? 'Anonymous' : $fb->student_name }}</strong>
                        </div>
                        @auth
                            <div class="d-flex gap-1">
                                <a href="{{ route('feedbacks.edit', $fb) }}" class="btn btn-sm btn-outline-secondary py-0 px-2">Edit</a>
                                <form action="{{ route('feedbacks.destroy', $fb) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this feedback?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2">Delete</button>
                                </form>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center text-muted py-5 bg-white rounded border">
            No course reviews found.
        </div>
    @endforelse
</div>
@endsection
