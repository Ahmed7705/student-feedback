@extends('layouts.main')

@section('title', 'Home')

@section('content')
<section class="hero">
    <div>
        <h1>Academic Advising</h1>
        <p>Schedule and manage one-on-one advising sessions online.</p>
    </div>
    <a href="{{ route('appointments.create') }}" class="btn btn-primary">Book Appointment</a>
</section>

<div class="section-header">
    <h2>Available Dates &amp; Times</h2>
    <div class="legend">
        <span class="badge badge-available">Available</span>
        <span class="badge badge-booked">Booked</span>
    </div>
</div>

<div class="days-grid">
    @foreach ($days as $day)
        <div class="day-card">
            <div class="day-title">{{ \Carbon\Carbon::parse($day)->format('D, M j, Y') }}</div>
            <ul class="slot-list">
                @foreach ($slots as $dbTime => $label)
                    @php $isBooked = isset($bookedMap[$day][$dbTime]); @endphp
                    <li class="slot-item {{ $isBooked ? 'booked' : 'available' }}">
                        <span>{{ $label }}</span>
                        @if ($isBooked)
                            <span class="slot-tag">Booked</span>
                        @else
                            <a class="slot-link" href="{{ route('appointments.create', ['date' => $day, 'time' => substr($dbTime, 0, 5)]) }}">Book</a>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    @endforeach
</div>
@endsection
