@extends('layouts.app')

@section('title', 'Team')

@section('content')
<div class="row justify-content-center mt-3">
    <div class="col-lg-8">
        <div class="text-center mb-4">
            <h4 class="fw-bold mb-1" style="color: var(--app-blue);">Project Team</h4>
            <p class="text-muted small mb-0">COMP-315 Web Programming</p>
        </div>

        <div class="row g-3">
            <div class="col-md-4">
                <div class="card team-card p-4 text-center h-100">
                    <h6 class="fw-bold mb-2" style="color: #0f172a;">Maryam Jafry</h6>
                    <div class="student-id">202500647</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card team-card p-4 text-center h-100">
                    <h6 class="fw-bold mb-2" style="color: #0f172a;">Khadija Hussein</h6>
                    <div class="student-id">202514175</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card team-card p-4 text-center h-100">
                    <h6 class="fw-bold mb-2" style="color: #0f172a;">Mayaz Ahmed</h6>
                    <div class="student-id">202502521</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
