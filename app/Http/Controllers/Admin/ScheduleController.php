<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShiftSchedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    /**
     * Display schedule list.
     */
    public function index(Request $request): View
    {
        $schedules = ShiftSchedule::with('user')
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'asc')
            ->paginate(20);

        return view('admin.schedules.index', compact('schedules'));
    }

    /**
     * Show create form.
     */
    public function create(): View
    {
        $students = User::members()->active()->orderBy('name')->get();
        return view('admin.schedules.create', compact('students'));
    }

    /**
     * Store a new schedule.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'shift_name' => 'required|string|max:100',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'notes' => 'nullable|string|max:500',
        ], [
            'user_id.required' => 'Siswa wajib dipilih.',
            'date.required' => 'Tanggal wajib diisi.',
            'shift_name.required' => 'Nama shift wajib diisi.',
            'start_time.required' => 'Waktu mulai wajib diisi.',
            'end_time.required' => 'Waktu selesai wajib diisi.',
            'end_time.after' => 'Waktu selesai harus setelah waktu mulai.',
        ]);

        $validated['created_by'] = auth()->id();

        ShiftSchedule::create($validated);

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal berhasil ditambahkan.');
    }

    /**
     * Show edit form.
     */
    public function edit(ShiftSchedule $schedule): View
    {
        $students = User::members()->active()->orderBy('name')->get();
        return view('admin.schedules.edit', compact('schedule', 'students'));
    }

    /**
     * Update a schedule.
     */
    public function update(Request $request, ShiftSchedule $schedule): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'shift_name' => 'required|string|max:100',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'notes' => 'nullable|string|max:500',
        ]);

        $validated['updated_by'] = auth()->id();

        $schedule->update($validated);

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal berhasil diperbarui.');
    }

    /**
     * Delete a schedule.
     */
    public function destroy(ShiftSchedule $schedule): RedirectResponse
    {
        $schedule->delete();

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal berhasil dihapus.');
    }

    /**
     * Bulk create schedules for all students.
     */
    public function bulkCreate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'shift_name' => 'required|string|max:100',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'notes' => 'nullable|string|max:500',
        ]);

        $students = User::members()->active()->get();

        foreach ($students as $student) {
            ShiftSchedule::create([
                'user_id' => $student->id,
                'date' => $validated['date'],
                'shift_name' => $validated['shift_name'],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'notes' => $validated['notes'],
                'created_by' => auth()->id(),
            ]);
        }

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal untuk semua siswa berhasil ditambahkan.');
    }
}
