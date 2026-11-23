<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AppointmentController extends Controller
{
    private const SLOTS = [
        '09:00:00' => '09:00 AM',
        '10:00:00' => '10:00 AM',
        '11:00:00' => '11:00 AM',
        '13:00:00' => '01:00 PM',
        '14:00:00' => '02:00 PM',
        '15:00:00' => '03:00 PM',
    ];

    public function home()
    {
        $days = [];
        for ($i = 0; $i < 3; $i++) {
            $days[] = now()->addDays($i)->format('Y-m-d');
        }

        $booked = Appointment::whereBetween('appointment_date', [$days[0], end($days)])
            ->where('status', 'Booked')
            ->get();

        $bookedMap = [];
        foreach ($booked as $item) {
            $bookedMap[$item->appointment_date][$item->appointment_time] = true;
        }

        return view('home', [
            'days' => $days,
            'slots' => self::SLOTS,
            'bookedMap' => $bookedMap,
        ]);
    }

    public function create(Request $request)
    {
        return view('appointments.create', [
            'slots' => self::SLOTS,
            'selectedDate' => $request->query('date', now()->format('Y-m-d')),
            'selectedTime' => $request->query('time', '09:00'),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:120'],
            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
            'appointment_time' => ['required', 'regex:/^\d{2}:\d{2}(:\d{2})?$/'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,png,jpg,jpeg,txt', 'max:2048'],
        ]);

        $normalizedTime = strlen($validated['appointment_time']) === 5
            ? $validated['appointment_time'] . ':00'
            : $validated['appointment_time'];

        // Prevent double booking
        $conflict = Appointment::where('appointment_date', $validated['appointment_date'])
            ->where('appointment_time', $normalizedTime)
            ->where('status', 'Booked')
            ->exists();

        if ($conflict) {
            return back()
                ->withInput()
                ->withErrors(['appointment_time' => 'Selected date and time is already booked.']);
        }

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('attachments', 'local');
        }

        Appointment::create([
            'customer_name' => $validated['customer_name'],
            'email' => $validated['email'],
            'appointment_date' => $validated['appointment_date'],
            'appointment_time' => $normalizedTime,
            'status' => 'Booked',
            'attachment' => $attachmentPath,
        ]);

        return redirect()->route('home')->with('success', 'Appointment booked.');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Appointment::query();

        // Regular users only see their own bookings
        if (!$user->isAdmin()) {
            $query->whereRaw('LOWER(email) = LOWER(?)', [$user->email]);
        }

        if ($request->filled('date')) {
            $query->where('appointment_date', $request->input('date'));
        }

        if ($request->filled('status') && in_array($request->input('status'), ['Booked', 'Completed', 'Cancelled'], true)) {
            $query->where('status', $request->input('status'));
        }

        $appointments = $query
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc')
            ->get();

        return view('appointments.index', [
            'appointments' => $appointments,
            'filterDate' => $request->input('date', ''),
            'filterStatus' => $request->input('status', ''),
            'isAdmin' => $user->isAdmin(),
        ]);
    }

    public function edit(Appointment $appointment)
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        return view('appointments.edit', [
            'appointment' => $appointment,
            'slots' => self::SLOTS,
        ]);
    }

    public function update(Request $request, Appointment $appointment)
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:120'],
            'appointment_date' => ['required', 'date'],
            'appointment_time' => ['required', 'regex:/^\d{2}:\d{2}(:\d{2})?$/'],
            'status' => ['required', 'in:Booked,Completed,Cancelled'],
        ]);

        $normalizedTime = strlen($validated['appointment_time']) === 5
            ? $validated['appointment_time'] . ':00'
            : $validated['appointment_time'];

        if ($validated['status'] === 'Booked') {
            // Prevent double booking
        $conflict = Appointment::where('appointment_date', $validated['appointment_date'])
                ->where('appointment_time', $normalizedTime)
                ->where('status', 'Booked')
                ->where('id', '!=', $appointment->id)
                ->exists();

            if ($conflict) {
                return back()
                    ->withInput()
                    ->withErrors(['appointment_time' => 'Selected slot is already booked.']);
            }
        }

        $appointment->update([
            'customer_name' => $validated['customer_name'],
            'email' => $validated['email'],
            'appointment_date' => $validated['appointment_date'],
            'appointment_time' => $normalizedTime,
            'status' => $validated['status'],
        ]);

        return redirect()->route('appointments.index')->with('success', 'Appointment updated.');
    }

    public function cancel(Appointment $appointment)
    {
        // Only admin or booking owner can cancel
        $user = Auth::user();
        if (!$user->isAdmin() && strcasecmp($appointment->email, $user->email) !== 0) {
            abort(403);
        }

        $appointment->update(['status' => 'Cancelled']);

        return redirect()->route('appointments.index')->with('success', 'Appointment cancelled.');
    }

    public function destroy(Appointment $appointment)
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $appointment->delete();

        return redirect()->route('appointments.index')->with('success', 'Appointment deleted.');
    }

    public function download(Appointment $appointment)
    {
        $user = Auth::user();
        if (!$user->isAdmin() && strcasecmp($appointment->email, $user->email) !== 0) {
            abort(403);
        }

        if (!$appointment->attachment || !Storage::disk('local')->exists($appointment->attachment)) {
            abort(404);
        }

        return Storage::disk('local')->response($appointment->attachment);
    }

    public function export()
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $appointments = Appointment::orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc')
            ->get();

        $filename = 'appointments_' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($appointments) {
            $output = fopen('php://output', 'w');
            fputcsv($output, ['ID', 'Name', 'Email', 'Date', 'Time', 'Status']);

            foreach ($appointments as $item) {
                fputcsv($output, [
                    $item->id,
                    $item->customer_name,
                    $item->email,
                    $item->appointment_date,
                    $item->appointment_time,
                    $item->status,
                ]);
            }

            fclose($output);
        }, $filename, ['Content-Type' => 'text/csv; charset=utf-8']);
    }
}
