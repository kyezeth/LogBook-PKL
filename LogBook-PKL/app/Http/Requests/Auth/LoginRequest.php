<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'identifier' => ['required', 'string'], // Can be NISN or Email
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'identifier' => 'NISN atau Email',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'identifier.required' => 'NISN atau Email wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $identifier = $this->input('identifier');
        $password = $this->input('password');
        $remember = $this->boolean('remember');

        // Determine if identifier is email or NISN
        $isEmail = filter_var($identifier, FILTER_VALIDATE_EMAIL);
        
        // Find user by email or NISN
        $user = User::where(function ($query) use ($identifier, $isEmail) {
            if ($isEmail) {
                $query->where('email', $identifier);
            } else {
                // Try NISN first, then NIS
                $query->where('nisn', $identifier)
                      ->orWhere('nis', $identifier);
            }
        })->first();

        // Check if user exists and is active
        if (!$user) {
            $this->handleFailedLogin();
        }

        // Check if account is locked
        if ($user->isLocked()) {
            $minutes = $user->locked_until->diffInMinutes(now());
            throw ValidationException::withMessages([
                'identifier' => "Akun Anda terkunci. Coba lagi dalam {$minutes} menit.",
            ]);
        }

        // Check if account is active
        if (!$user->is_active) {
            throw ValidationException::withMessages([
                'identifier' => 'Akun Anda telah dinonaktifkan. Hubungi administrator.',
            ]);
        }

        // Attempt authentication
        $credentials = ['password' => $password];
        if ($isEmail) {
            $credentials['email'] = $identifier;
        } else {
            // For NISN/NIS login, we already have the user
            if (!Auth::attempt(['id' => $user->id, 'password' => $password], $remember)) {
                $this->incrementFailedAttempts($user);
                $this->handleFailedLogin();
            }
        }

        if ($isEmail && !Auth::attempt($credentials, $remember)) {
            $this->incrementFailedAttempts($user);
            $this->handleFailedLogin();
        }

        // If we reach here using NISN/NIS path, manually login
        if (!$isEmail && !Auth::check()) {
            if (!\Hash::check($password, $user->password)) {
                $this->incrementFailedAttempts($user);
                $this->handleFailedLogin();
            }
            Auth::login($user, $remember);
        }

        // Reset failed attempts on successful login
        $user->update([
            'failed_login_attempts' => 0,
            'locked_until' => null,
        ]);

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Handle failed login attempt
     */
    protected function handleFailedLogin(): void
    {
        RateLimiter::hit($this->throttleKey());

        throw ValidationException::withMessages([
            'identifier' => 'NISN/Email atau password yang Anda masukkan salah.',
        ]);
    }

    /**
     * Increment failed login attempts and lock if necessary
     */
    protected function incrementFailedAttempts(User $user): void
    {
        $attempts = $user->failed_login_attempts + 1;
        $update = ['failed_login_attempts' => $attempts];

        // Lock account after 5 failed attempts for 30 minutes
        if ($attempts >= 5) {
            $update['locked_until'] = now()->addMinutes(30);
        }

        $user->update($update);
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'identifier' => "Terlalu banyak percobaan login. Coba lagi dalam " . ceil($seconds / 60) . " menit.",
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('identifier')) . '|' . $this->ip());
    }
}
