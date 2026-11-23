@extends('layouts.main')

@section('title', 'Edit Appointment')

@section('content')
<h1 class="page-title">Edit Appointment #{{ $appointment->id }}</h1>
<p class="page-subtitle">Update details or change appointment status.</p>

<div class="card form-card">
    @if ($errors->any())
        <div class="alert alert-error" role="alert">
            <ul class="error-list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('appointments.update', $appointment) }}" novalidate>
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="customer_name">Name</label>
            <input type="text" id="customer_name" name="customer_name" value="{{ old('customer_name', $appointment->customer_name) }}" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email', $appointment->email) }}" required>
        </div>

        <div class="form-group">
            <label for="appointment_date">Appointment Date</label>
            <input type="date" id="appointment_date" name="appointment_date" value="{{ old('appointment_date', $appointment->appointment_date) }}" required>
        </div>

        <div class="form-group">
            <label for="appointment_time">Time</label>
            <input type="time" id="appointment_time" name="appointment_time" value="{{ old('appointment_time', substr($appointment->appointment_time, 0, 5)) }}" list="standard-slots" required>
            <datalist id="standard-slots">
                @foreach ($slots as $dbTime => $label)
                    <option value="{{ substr($dbTime, 0, 5) }}">{{ $label }}</option>
                @endforeach
            </datalist>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status" required>
                @foreach (['Booked', 'Completed', 'Cancelled'] as $st)
                    <option value="{{ $st }}" {{ old('status', $appointment->status) === $st ? 'selected' : '' }}>{{ $st }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="{{ route('appointments.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </form>
</div>
@endsection
