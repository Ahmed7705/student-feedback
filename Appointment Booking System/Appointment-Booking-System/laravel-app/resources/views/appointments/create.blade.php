@extends('layouts.main')

@section('title', 'Book Appointment')

@section('content')
<h1 class="page-title">Book Appointment</h1>
<p class="page-subtitle">Select a date and time to reserve your slot.</p>

<div class="card form-card">
    @if ($errors->any())
        <div class="alert alert-error" role="alert">
            <strong>Please fix the following:</strong>
            <ul class="error-list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('appointments.store') }}" enctype="multipart/form-data" novalidate>
        @csrf

        <div class="form-group">
            <label for="customer_name">Name</label>
            <input type="text" id="customer_name" name="customer_name" value="{{ old('customer_name', auth()->user()->name ?? '') }}" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" required>
        </div>

        <div class="form-group">
            <label for="appointment_date">Appointment Date</label>
            <input type="date" id="appointment_date" name="appointment_date" value="{{ old('appointment_date', $selectedDate) }}" min="{{ now()->format('Y-m-d') }}" required>
        </div>

        <div class="form-group">
            <label for="appointment_time">Time</label>
            <input type="time" id="appointment_time" name="appointment_time" value="{{ old('appointment_time', substr($selectedTime, 0, 5)) }}" list="standard-slots" required>
            <datalist id="standard-slots">
                @foreach ($slots as $dbTime => $label)
                    <option value="{{ substr($dbTime, 0, 5) }}">{{ $label }}</option>
                @endforeach
            </datalist>
            <span class="form-hint">Standard slots: 09:00, 10:00, 11:00, 13:00, 14:00, 15:00.</span>
        </div>

        <div class="form-group">
            <label for="attachment">Attachment (Optional)</label>
            <input type="file" id="attachment" name="attachment" accept=".pdf,.png,.jpg,.jpeg,.txt">
            <span class="form-hint">PDF, PNG, JPG, or TXT (max 2 MB).</span>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Submit</button>
            <button type="reset" class="btn btn-secondary">Reset</button>
        </div>
    </form>
</div>
@endsection
