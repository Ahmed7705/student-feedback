@extends('layouts.app')

@section('title', 'Submit Feedback')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card p-4 bg-white">
            <h5 class="fw-bold mb-3" style="color: var(--app-blue);">Submit Course Evaluation</h5>

            @if ($errors->any())
                <div class="alert alert-danger py-2 small">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('feedbacks.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label small fw-bold">Select Course *</label>
                    <select name="course_id" class="form-select form-select-sm" required>
                        <option value="">-- Choose Course --</option>
                        @foreach($courses as $c)
                            <option value="{{ $c->id }}" {{ old('course_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->course_code }} - {{ $c->course_name }} ({{ $c->instructor_name }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Student Name *</label>
                        <input type="text" name="student_name" class="form-control form-control-sm" value="{{ old('student_name', Auth::check() ? Auth::user()->name : '') }}" placeholder="e.g. Maryam Jafry" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Student ID *</label>
                        <input type="text" name="student_id" class="form-control form-control-sm" value="{{ old('student_id') }}" placeholder="e.g. 202500647" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Category *</label>
                        <select name="category" class="form-select form-select-sm" required>
                            <option value="Teaching Quality">Teaching Quality</option>
                            <option value="Course Content">Course Content</option>
                            <option value="Lab & Practical">Lab & Practical</option>
                            <option value="Assessment & Grading">Assessment & Grading</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Rating (1 to 5) *</label>
                        <select name="rating" class="form-select form-select-sm" required>
                            <option value="5">5 - Excellent (5/5)</option>
                            <option value="4">4 - Very Good (4/5)</option>
                            <option value="3">3 - Good (3/5)</option>
                            <option value="2">2 - Fair (2/5)</option>
                            <option value="1">1 - Poor (1/5)</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Feedback Title *</label>
                    <input type="text" name="title" class="form-control form-control-sm" value="{{ old('title') }}" placeholder="Short summary" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Comments *</label>
                    <textarea name="comments" rows="3" class="form-control form-control-sm" placeholder="Detailed feedback..." required>{{ old('comments') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Attachment / Image (Optional)</label>
                    <input type="file" name="image" class="form-control form-control-sm" accept="image/*">
                </div>

                <div class="form-check mb-4">
                    <input type="checkbox" name="is_anonymous" class="form-check-input" id="anon" value="1">
                    <label class="form-check-label small" for="anon">Submit Anonymously (Hide student name)</label>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">Cancel</a>
                    <button type="submit" class="btn btn-blue btn-sm px-4">Submit Feedback</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection