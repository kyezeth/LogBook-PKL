<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class StudentController extends Controller
{
    /**
     * Display a listing of students
     */
    public function index(Request $request): View
    {
        $query = User::members();

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Filter by institution
        if ($request->filled('institution')) {
            $query->where('institution', $request->input('institution'));
        }

        $students = $query->orderBy('name')->paginate(20);

        // Get unique institutions for filter
        $institutions = User::members()
            ->whereNotNull('institution')
            ->distinct()
            ->pluck('institution');

        return view('admin.students.index', compact('students', 'institutions'));
    }

    /**
     * Show the form for creating a new student
     */
    public function create(): View
    {
        return view('admin.students.create');
    }

    /**
     * Store a newly created student
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nis' => ['required', 'string', 'max:20', 'unique:users,nis'],
            'nisn' => ['required', 'string', 'size:10', 'unique:users,nisn', 'regex:/^[0-9]+$/'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'birth_date' => ['nullable', 'date'],
            'gender' => ['nullable', 'in:male,female'],
            'institution' => ['nullable', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'pkl_start_date' => ['nullable', 'date'],
            'pkl_end_date' => ['nullable', 'date', 'after_or_equal:pkl_start_date'],
            'password' => ['required', 'confirmed', Password::defaults()->min(8)],
        ], [
            'nis.unique' => 'NIS sudah terdaftar.',
            'nisn.unique' => 'NISN sudah terdaftar.',
            'nisn.size' => 'NISN harus 10 digit.',
            'email.unique' => 'Email sudah terdaftar.',
            'pkl_end_date.after_or_equal' => 'Tanggal selesai PKL harus setelah tanggal mulai.',
        ]);

        $student = User::create([
            'name' => $validated['name'],
            'nis' => $validated['nis'],
            'nisn' => $validated['nisn'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'birth_date' => $validated['birth_date'],
            'gender' => $validated['gender'],
            'institution' => $validated['institution'],
            'department' => $validated['department'],
            'pkl_start_date' => $validated['pkl_start_date'],
            'pkl_end_date' => $validated['pkl_end_date'],
            'password' => Hash::make($validated['password']),
            'role' => 'member',
            'is_active' => true,
        ]);

        AuditLog::log('created', $student, null, [
            'name' => $student->name,
            'nis' => $student->nis,
            'nisn' => $student->nisn,
        ]);

        return redirect()->route('admin.students.index')
            ->with('success', 'Siswa berhasil ditambahkan.');
    }

    /**
     * Display the specified student
     */
    public function show(User $student): View
    {
        // Load relationships
        $student->load(['attendances' => function ($q) {
            $q->orderBy('date', 'desc')->limit(30);
        }, 'activityLogs' => function ($q) {
            $q->orderBy('date', 'desc')->limit(10);
        }, 'performanceAssessments' => function ($q) {
            $q->orderBy('period_end', 'desc');
        }]);

        // Calculate attendance stats
        $attendanceStats = [
            'total' => $student->attendances()->count(),
            'present' => $student->attendances()->where('status', 'present')->count(),
            'late' => $student->attendances()->where('status', 'late')->count(),
        ];

        return view('admin.students.show', compact('student', 'attendanceStats'));
    }

    /**
     * Show the form for editing the specified student
     */
    public function edit(User $student): View
    {
        return view('admin.students.edit', compact('student'));
    }

    /**
     * Update the specified student
     */
    public function update(Request $request, User $student): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nis' => ['required', 'string', 'max:20', 'unique:users,nis,' . $student->id],
            'nisn' => ['required', 'string', 'size:10', 'unique:users,nisn,' . $student->id, 'regex:/^[0-9]+$/'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $student->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'birth_date' => ['nullable', 'date'],
            'gender' => ['nullable', 'in:male,female'],
            'institution' => ['nullable', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'pkl_start_date' => ['nullable', 'date'],
            'pkl_end_date' => ['nullable', 'date', 'after_or_equal:pkl_start_date'],
            'is_active' => ['boolean'],
            'password' => ['nullable', 'confirmed', Password::defaults()->min(8)],
        ]);

        $oldValues = $student->only(['name', 'nis', 'nisn', 'email', 'is_active']);

        $student->fill([
            'name' => $validated['name'],
            'nis' => $validated['nis'],
            'nisn' => $validated['nisn'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'birth_date' => $validated['birth_date'],
            'gender' => $validated['gender'],
            'institution' => $validated['institution'],
            'department' => $validated['department'],
            'pkl_start_date' => $validated['pkl_start_date'],
            'pkl_end_date' => $validated['pkl_end_date'],
            'is_active' => $request->boolean('is_active'),
        ]);

        if ($request->filled('password')) {
            $student->password = Hash::make($validated['password']);
        }

        $student->save();

        AuditLog::log('updated', $student, $oldValues, $student->only(['name', 'nis', 'nisn', 'email', 'is_active']));

        return redirect()->route('admin.students.show', $student)
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    /**
     * Remove the specified student
     */
    public function destroy(User $student): RedirectResponse
    {
        // Soft delete - just deactivate
        $student->update(['is_active' => false]);

        AuditLog::log('deleted', $student);

        return redirect()->route('admin.students.index')
            ->with('success', 'Siswa berhasil dinonaktifkan.');
    }
}
