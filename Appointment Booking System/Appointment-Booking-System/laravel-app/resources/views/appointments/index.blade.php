@extends('layouts.main')

@section('title', 'Manage Appointments')

@section('content')
<div class="section-header">
    <div>
        <h1 class="page-title">{{ $isAdmin ? 'Manage Appointments' : 'My Appointments' }}</h1>
        <p class="page-subtitle">{{ $isAdmin ? 'Filter, edit, cancel, or remove bookings.' : 'View or cancel your bookings.' }}</p>
    </div>
    <div>
        @if ($isAdmin)
            <a href="{{ route('appointments.export') }}" class="btn btn-secondary btn-sm">Export CSV</a>
        @endif
        <a href="{{ route('appointments.create') }}" class="btn btn-primary btn-sm">New Booking</a>
    </div>
</div>

<form method="GET" action="{{ route('appointments.index') }}" class="card filter-bar">
    <div class="form-group">
        <label for="filter_date">Date</label>
        <input type="date" id="filter_date" name="date" value="{{ $filterDate }}">
    </div>

    <div class="form-group">
        <label for="filter_status">Status</label>
        <select id="filter_status" name="status">
            <option value="">All Statuses</option>
            @foreach (['Booked', 'Completed', 'Cancelled'] as $st)
                <option value="{{ $st }}" {{ $filterStatus === $st ? 'selected' : '' }}>{{ $st }}</option>
            @endforeach
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Filter</button>
    <a href="{{ route('appointments.index') }}" class="btn btn-secondary">Clear</a>
</form>

<div class="table-wrap">
    @if ($appointments->isEmpty())
        <div class="empty-state">No appointments found.</div>
    @else
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>File</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($appointments as $item)
                    <tr>
                        <td>#{{ $item->id }}</td>
                        <td>{{ $item->customer_name }}</td>
                        <td>{{ $item->email }}</td>
                        <td>{{ $item->appointment_date }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->appointment_time)->format('h:i A') }}</td>
                        <td>
                            <span class="badge badge-{{ strtolower($item->status) }}">{{ $item->status }}</span>
                        </td>
                        <td>
                            @if ($item->attachment)
                                <a href="{{ route('appointments.download', $item) }}" target="_blank" rel="noopener">View</a>
                            @else
                                &mdash;
                            @endif
                        </td>
                        <td>
                            <div class="actions-cell">
                                @if ($isAdmin)
                                    <a href="{{ route('appointments.edit', $item) }}" class="btn btn-secondary btn-sm">Edit</a>
                                @endif

                                @if ($item->status !== 'Cancelled')
                                    <form method="POST" action="{{ route('appointments.cancel', $item) }}" class="inline-form js-confirm-cancel">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-danger btn-sm">Cancel</button>
                                    </form>
                                @endif

                                @if ($isAdmin)
                                    <form method="POST" action="{{ route('appointments.destroy', $item) }}" class="inline-form js-confirm-delete">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-secondary btn-sm">Delete</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
