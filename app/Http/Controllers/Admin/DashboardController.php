<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Attendance;
use App\Models\ActivityLog;
use App\Models\ProblemReport;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index(Request $request): View
    {
        $admin = $request->user();
        $today = Carbon::today();
        $startOfMonth = $today->copy()->startOfMonth();
        $endOfMonth = $today->copy()->endOfMonth();
        $startOfWeek = $today->copy()->startOfWeek();
        $endOfWeek = $today->copy()->endOfWeek();

        // User statistics
        $userStats = [
            'total_students' => User::members()->count(),
            'active_students' => User::members()->active()->count(),
            'total_admins' => User::admins()->count(),
        ];

        // Today's attendance overview
        $todayAttendance = [
            'present' => Attendance::whereDate('date', $today)->where('status', 'present')->count(),
            'late' => Attendance::whereDate('date', $today)->where('status', 'late')->count(),
            'absent' => $userStats['active_students'] - Attendance::whereDate('date', $today)->whereIn('status', ['present', 'late', 'leave', 'sick'])->count(),
            'leave' => Attendance::whereDate('date', $today)->whereIn('status', ['leave', 'sick'])->count(),
        ];

        // Ensure absent is not negative
        $todayAttendance['absent'] = max(0, $todayAttendance['absent']);

        // This week attendance trend
        $weeklyAttendance = [];
        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i);
            $weeklyAttendance[] = [
                'date' => $date->format('D'),
                'present' => Attendance::whereDate('date', $date)->where('status', 'present')->count(),
                'late' => Attendance::whereDate('date', $date)->where('status', 'late')->count(),
            ];
        }

        // Pending items that need attention
        $pendingItems = [
            'unverified_attendance' => Attendance::where('is_verified', false)->count(),
            'pending_activities' => ActivityLog::where('status', 'submitted')->count(),
            'open_problems' => ProblemReport::whereIn('status', ['open', 'in_progress'])->count(),
            'urgent_problems' => ProblemReport::open()->urgent()->count(),
        ];

        // Recent activities from all students
        $recentActivities = ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Recent problem reports
        $recentProblems = ProblemReport::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Students who haven't checked in today
        $studentsNotCheckedIn = User::members()
            ->active()
            ->whereDoesntHave('attendances', function ($query) use ($today) {
                $query->whereDate('date', $today);
            })
            ->limit(10)
            ->get();

        // Monthly activity summary
        $monthlyActivityCount = ActivityLog::whereBetween('date', [$startOfMonth, $endOfMonth])->count();

        // Top performers this month (by activity count)
        $topPerformers = User::members()
            ->withCount(['activityLogs' => function ($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('date', [$startOfMonth, $endOfMonth]);
            }])
            ->orderBy('activity_logs_count', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'admin',
            'userStats',
            'todayAttendance',
            'weeklyAttendance',
            'pendingItems',
            'recentActivities',
            'recentProblems',
            'studentsNotCheckedIn',
            'monthlyActivityCount',
            'topPerformers'
        ));
    }

    /**
     * Display all attendance records with photos.
     */
    public function attendance(Request $request): View
    {
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));
        $selectedDate = Carbon::parse($date);

        $attendances = Attendance::with('user')
            ->whereDate('date', $selectedDate)
            ->orderBy('check_in_time', 'asc')
            ->get();

        // Get all active students for reference
        $totalStudents = User::members()->active()->count();
        
        // Stats for the selected date
        $stats = [
            'present' => $attendances->where('status', 'present')->count(),
            'late' => $attendances->where('status', 'late')->count(),
            'absent' => $totalStudents - $attendances->count(),
            'total' => $attendances->count(),
        ];

        // Get dates that have attendance records for calendar
        $monthStart = $selectedDate->copy()->startOfMonth();
        $monthEnd = $selectedDate->copy()->endOfMonth();
        $datesWithRecords = Attendance::whereBetween('date', [$monthStart, $monthEnd])
            ->selectRaw('DATE(date) as date, COUNT(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();

        return view('admin.attendance.index', compact(
            'attendances',
            'selectedDate',
            'stats',
            'totalStudents',
            'datesWithRecords'
        ));
    }

    /**
     * Display attendance for a specific date (JSON for AJAX).
     */
    public function attendanceByDate(Request $request, string $date): View
    {
        $selectedDate = Carbon::parse($date);

        $attendances = Attendance::with('user')
            ->whereDate('date', $selectedDate)
            ->orderBy('check_in_time', 'asc')
            ->get();

        $totalStudents = User::members()->active()->count();
        
        $stats = [
            'present' => $attendances->where('status', 'present')->count(),
            'late' => $attendances->where('status', 'late')->count(),
            'absent' => $totalStudents - $attendances->count(),
            'total' => $attendances->count(),
        ];

        return view('admin.attendance.index', compact(
            'attendances',
            'selectedDate',
            'stats',
            'totalStudents'
        ));
    }
}
