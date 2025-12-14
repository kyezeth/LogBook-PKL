<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    protected CloudinaryService $cloudinary;

    public function __construct(CloudinaryService $cloudinary)
    {
        $this->cloudinary = $cloudinary;
    }

    /**
     * Display attendance list
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $month = $request->input('month', now()->format('Y-m'));
        $today = Carbon::today();
        
        $startDate = Carbon::parse($month)->startOfMonth();
        $endDate = Carbon::parse($month)->endOfMonth();

        $attendances = Attendance::where('user_id', $user->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->paginate(20);

        // Today's attendance
        $todayAttendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        // Calculate monthly statistics
        $totalDays = $attendances->count();
        $stats = [
            'present' => $attendances->where('status', 'present')->count(),
            'late' => $attendances->where('status', 'late')->count(),
            'absent' => $attendances->where('status', 'absent')->count(),
            'leave' => $attendances->whereIn('status', ['leave', 'sick'])->count(),
            'rate' => $totalDays > 0 ? round((($attendances->where('status', 'present')->count() + $attendances->where('status', 'late')->count()) / max($totalDays, 1)) * 100, 1) : 0,
        ];

        return view('member.attendance.index', compact('attendances', 'stats', 'month', 'todayAttendance'));
    }

    /**
     * Show check-in form
     */
    public function checkInForm(Request $request): View|RedirectResponse
    {
        $user = $request->user();
        $today = Carbon::today();

        // Check if already checked in today
        $todayAttendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        if ($todayAttendance && $todayAttendance->check_in_time) {
            return redirect()->route('member.attendance.index')
                ->with('warning', 'Anda sudah melakukan check-in hari ini.');
        }

        return view('member.attendance.check-in');
    }

    /**
     * Process check-in with photo
     */
    public function checkIn(Request $request): RedirectResponse
    {
        $request->validate([
            'photo' => ['required', 'string'], // Base64 encoded image
        ], [
            'photo.required' => 'Foto selfie wajib diambil.',
        ]);

        $user = $request->user();
        $today = Carbon::today();
        $now = Carbon::now();

        // Check if already checked in
        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        if ($attendance && $attendance->check_in_time) {
            return back()->with('error', 'Anda sudah melakukan check-in hari ini.');
        }

        // Process and save the photo to Cloudinary
        $photoPath = $this->processPhoto($request->input('photo'), $user->id, 'check-in');

        // Get today's schedule for the user
        $schedule = \App\Models\ShiftSchedule::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        // Determine status based on schedule with 30-minute grace period
        if ($schedule) {
            $scheduleStartTime = Carbon::today()->setTimeFromTimeString($schedule->start_time);
            $graceTime = $scheduleStartTime->copy()->addMinutes(30); // 30 min grace period
            $status = $now->lte($graceTime) ? 'present' : 'late';
        } else {
            // Default: 08:00 start with 30-min grace (until 08:30)
            $workStartTime = Carbon::today()->setHour(8)->setMinute(30);
            $status = $now->lte($workStartTime) ? 'present' : 'late';
        }

        if ($attendance) {
            $attendance->update([
                'check_in_time' => $now->format('H:i:s'),
                'check_in_photo' => $photoPath,
                'status' => $status,
            ]);
        } else {
            Attendance::create([
                'user_id' => $user->id,
                'date' => $today,
                'check_in_time' => $now->format('H:i:s'),
                'check_in_photo' => $photoPath,
                'status' => $status,
            ]);
        }

        $message = $status === 'present' 
            ? 'Check-in berhasil! Selamat bekerja.' 
            : 'Check-in berhasil, tetapi Anda terlambat.';

        return redirect()->route('member.dashboard')->with('success', $message);
    }

    /**
     * Show check-out form
     */
    public function checkOutForm(Request $request): View|RedirectResponse
    {
        $user = $request->user();
        $today = Carbon::today();

        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        if (!$attendance || !$attendance->check_in_time) {
            return redirect()->route('member.attendance.index')
                ->with('warning', 'Anda belum melakukan check-in hari ini.');
        }

        if ($attendance->check_out_time) {
            return redirect()->route('member.attendance.index')
                ->with('warning', 'Anda sudah melakukan check-out hari ini.');
        }

        return view('member.attendance.check-out', compact('attendance'));
    }

    /**
     * Process check-out with photo
     */
    public function checkOut(Request $request): RedirectResponse
    {
        $request->validate([
            'photo' => ['required', 'string'],
        ], [
            'photo.required' => 'Foto selfie wajib diambil.',
        ]);

        $user = $request->user();
        $today = Carbon::today();
        $now = Carbon::now();

        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        if (!$attendance || !$attendance->check_in_time) {
            return back()->with('error', 'Anda belum melakukan check-in hari ini.');
        }

        if ($attendance->check_out_time) {
            return back()->with('error', 'Anda sudah melakukan check-out hari ini.');
        }

        // Process and save the photo to Cloudinary
        $photoPath = $this->processPhoto($request->input('photo'), $user->id, 'check-out');

        $attendance->update([
            'check_out_time' => $now->format('H:i:s'),
            'check_out_photo' => $photoPath,
        ]);

        return redirect()->route('member.dashboard')
            ->with('success', 'Check-out berhasil! Terima kasih atas kerja keras Anda hari ini.');
    }

    /**
     * Display attendance history
     */
    public function history(Request $request): View
    {
        $user = $request->user();
        
        $attendances = Attendance::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->paginate(30);

        return view('member.attendance.history', compact('attendances'));
    }

    /**
     * Process base64 photo and upload to Cloudinary
     */
    private function processPhoto(string $base64Image, int $userId, string $type): string
    {
        $publicId = sprintf('%s_%s_%s', $userId, $type, now()->format('Ymd_His'));
        $folder = 'attendance/' . now()->format('Y-m');
        
        $url = $this->cloudinary->uploadBase64($base64Image, $folder, $publicId);
        
        if (!$url) {
            // Fallback to local storage if Cloudinary fails
            $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $base64Image);
            $imageData = base64_decode($imageData);
            
            $filename = sprintf(
                'attendance/%s/%s_%s_%s.jpg',
                now()->format('Y-m'),
                $userId,
                $type,
                now()->format('Ymd_His')
            );
            
            Storage::disk('public')->put($filename, $imageData);
            return $filename;
        }
        
        return $url;
    }
}
