<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nis' => ['required', 'string', 'max:20', 'unique:users,nis'],
            'nisn' => ['required', 'string', 'size:10', 'unique:users,nisn', 'regex:/^[0-9]+$/'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
            'institution' => ['nullable', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'pkl_start_date' => ['nullable', 'date'],
            'pkl_end_date' => ['nullable', 'date', 'after_or_equal:pkl_start_date'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()->min(8)],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'nis.required' => 'NIS wajib diisi.',
            'nis.unique' => 'NIS sudah terdaftar.',
            'nisn.required' => 'NISN wajib diisi.',
            'nisn.size' => 'NISN harus 10 digit.',
            'nisn.unique' => 'NISN sudah terdaftar.',
            'nisn.regex' => 'NISN hanya boleh berisi angka.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'phone.regex' => 'Format nomor telepon tidak valid.',
            'pkl_end_date.after_or_equal' => 'Tanggal selesai harus setelah tanggal mulai.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'nis' => $request->nis,
            'nisn' => $request->nisn,
            'email' => $request->email,
            'phone' => $request->phone,
            'institution' => $request->institution,
            'department' => $request->department,
            'pkl_start_date' => $request->pkl_start_date,
            'pkl_end_date' => $request->pkl_end_date,
            'password' => Hash::make($request->password),
            'role' => 'member', // Default role for registration
            'is_active' => true,
        ]);

        // Log the registration
        AuditLog::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_role' => $user->role,
            'auditable_type' => 'App\\Models\\User',
            'auditable_id' => $user->id,
            'action' => 'created',
            'new_values' => [
                'name' => $user->name,
                'nis' => $user->nis,
                'nisn' => $user->nisn,
                'email' => $user->email,
            ],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('member.dashboard'));
    }
}
