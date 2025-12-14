<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\ShiftSchedule;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    /**
     * Display student's schedule.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $month = $request->input('month', now()->format('Y-m'));
        
        $startDate = Carbon::parse($month)->startOfMonth();
        $endDate = Carbon::parse($month)->endOfMonth();

        $schedules = ShiftSchedule::where('user_id', $user->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'asc')
            ->get();

        // Today's schedule
        $todaySchedule = ShiftSchedule::where('user_id', $user->id)
            ->whereDate('date', today())
            ->first();

        return view('member.schedules.index', compact('schedules', 'month', 'todaySchedule'));
    }
}
