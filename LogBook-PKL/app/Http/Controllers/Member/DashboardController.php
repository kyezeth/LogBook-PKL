<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ActivityLog;
use App\Models\ActivityPlan;
use App\Models\Message;
use App\Models\ShiftSchedule;
use App\Models\PerformanceAssessment;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the member dashboard.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $today = Carbon::today();
        $startOfMonth = $today->copy()->startOfMonth();
        $endOfMonth = $today->copy()->endOfMonth();

        // Today's attendance
        $todayAttendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        // Attendance statistics for this month
        $attendanceStats = [
            'present' => Attendance::where('user_id', $user->id)
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->where('status', 'present')
                ->count(),
            'late' => Attendance::where('user_id', $user->id)
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->where('status', 'late')
                ->count(),
            'absent' => Attendance::where('user_id', $user->id)
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->where('status', 'absent')
                ->count(),
            'leave' => Attendance::where('user_id', $user->id)
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->whereIn('status', ['leave', 'sick'])
                ->count(),
        ];

        // Recent activities
        $recentActivities = ActivityLog::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Upcoming plans
        $upcomingPlans = ActivityPlan::where('user_id', $user->id)
            ->where('planned_date', '>=', $today)
            ->orderBy('planned_date')
            ->limit(3)
            ->get();

        // Today's schedule
        $todaySchedule = ShiftSchedule::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        // Unread messages count
        $unreadMessagesCount = Message::where('recipient_id', $user->id)
            ->where('is_read', false)
            ->whereNull('deleted_by_recipient_at')
            ->count();

        // Latest announcement
        $latestAnnouncement = Message::where('type', 'announcement')
            ->where(function ($q) use ($user) {
                $q->whereNull('recipient_id')
                  ->orWhere('recipient_id', $user->id);
            })
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->orderBy('created_at', 'desc')
            ->first();

        // Latest performance assessment
        $latestAssessment = PerformanceAssessment::where('user_id', $user->id)
            ->whereIn('status', ['published', 'acknowledged'])
            ->orderBy('period_end', 'desc')
            ->first();

        // Calculate working days in month
        $workingDaysInMonth = $this->getWorkingDaysInMonth($startOfMonth, $today);
        $attendanceRate = $workingDaysInMonth > 0 
            ? round(($attendanceStats['present'] + $attendanceStats['late']) / $workingDaysInMonth * 100, 1)
            : 0;

        return view('member.dashboard', compact(
            'user',
            'todayAttendance',
            'attendanceStats',
            'recentActivities',
            'upcomingPlans',
            'todaySchedule',
            'unreadMessagesCount',
            'latestAnnouncement',
            'latestAssessment',
            'attendanceRate'
        ));
    }

    /**
     * Calculate working days in a date range (excluding weekends)
     */
    private function getWorkingDaysInMonth(Carbon $start, Carbon $end): int
    {
        $days = 0;
        $current = $start->copy();
        
        while ($current <= $end) {
            if (!$current->isWeekend()) {
                $days++;
            }
            $current->addDay();
        }

        return $days;
    }
}
