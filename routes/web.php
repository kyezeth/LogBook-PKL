<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Member\DashboardController as MemberDashboardController;
use App\Http\Controllers\Member\AttendanceController as MemberAttendanceController;
use App\Http\Controllers\Member\ActivityLogController as MemberActivityLogController;
use App\Http\Controllers\Member\ProfileController as MemberProfileController;
use App\Http\Controllers\Member\ScheduleController as MemberScheduleController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\ActivityController as AdminActivityController;
use App\Http\Controllers\Admin\ScheduleController as AdminScheduleController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Landing page
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isAdmin() 
            ? redirect()->route('admin.dashboard')
            : redirect()->route('member.dashboard');
    }
    return view('welcome');
})->name('home');

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

// =====================================================================
// MEMBER ROUTES (Students)
// =====================================================================
Route::middleware(['auth', 'role:member'])->prefix('member')->name('member.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [MemberDashboardController::class, 'index'])->name('dashboard');

    // Attendance
    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/', [MemberAttendanceController::class, 'index'])->name('index');
        Route::get('/check-in', [MemberAttendanceController::class, 'checkInForm'])->name('check-in');
        Route::post('/check-in', [MemberAttendanceController::class, 'checkIn'])->name('check-in.store');
        Route::get('/check-out', [MemberAttendanceController::class, 'checkOutForm'])->name('check-out');
        Route::post('/check-out', [MemberAttendanceController::class, 'checkOut'])->name('check-out.store');
        Route::get('/history', [MemberAttendanceController::class, 'history'])->name('history');
    });

    // Activity Logs
    Route::resource('activities', MemberActivityLogController::class);

    // Schedule (View Only)
    Route::get('/schedule', [MemberScheduleController::class, 'index'])->name('schedule.index');

    // Profile
    Route::get('/profile', [MemberProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [MemberProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [MemberProfileController::class, 'destroy'])->name('profile.destroy');
});

// =====================================================================
// ADMIN ROUTES (Supervisors)
// =====================================================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Student Management
    Route::resource('students', StudentController::class);

    // Attendance Management
    Route::get('/attendance', [AdminDashboardController::class, 'attendance'])->name('attendance.index');
    Route::get('/attendance/{date}', [AdminDashboardController::class, 'attendanceByDate'])->name('attendance.date');

    // Activity Management
    Route::get('/activities', [AdminActivityController::class, 'index'])->name('activities.index');
    Route::get('/activities/{activity}', [AdminActivityController::class, 'show'])->name('activities.show');
    Route::post('/activities/{activity}/approve', [AdminActivityController::class, 'approve'])->name('activities.approve');
    Route::post('/activities/{activity}/reject', [AdminActivityController::class, 'reject'])->name('activities.reject');

    // Schedule Management
    Route::resource('schedules', AdminScheduleController::class);
    Route::post('/schedules/bulk', [AdminScheduleController::class, 'bulkCreate'])->name('schedules.bulk');

    // Admin Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Fallback dashboard route for Breeze compatibility
Route::get('/dashboard', function () {
    if (auth()->check()) {
        return auth()->user()->isAdmin() 
            ? redirect()->route('admin.dashboard')
            : redirect()->route('member.dashboard');
    }
    return redirect()->route('login');
})->middleware('auth')->name('dashboard');

