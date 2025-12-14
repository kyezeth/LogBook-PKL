<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class ActivityController extends Controller
{
    /**
     * Display all student activities.
     */
    public function index(Request $request): View
    {
        $query = ActivityLog::with('user')
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $activities = $query->paginate(15);
        
        $stats = [
            'pending' => ActivityLog::where('status', 'submitted')->count(),
            'approved' => ActivityLog::where('status', 'approved')->count(),
            'rejected' => ActivityLog::where('status', 'rejected')->count(),
            'total' => ActivityLog::count(),
        ];

        return view('admin.activities.index', compact('activities', 'stats'));
    }

    /**
     * Show activity detail.
     */
    public function show(ActivityLog $activity): View
    {
        $activity->load(['user', 'reviewer']);
        return view('admin.activities.show', compact('activity'));
    }

    /**
     * Approve an activity.
     */
    public function approve(Request $request, ActivityLog $activity): RedirectResponse
    {
        $request->validate([
            'review_notes' => 'nullable|string|max:500',
        ]);

        $activity->update([
            'status' => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'review_notes' => $request->review_notes,
        ]);

        return back()->with('success', 'Kegiatan berhasil disetujui.');
    }

    /**
     * Reject an activity.
     */
    public function reject(Request $request, ActivityLog $activity): RedirectResponse
    {
        $request->validate([
            'review_notes' => 'required|string|max:500',
        ], [
            'review_notes.required' => 'Catatan alasan penolakan wajib diisi.',
        ]);

        $activity->update([
            'status' => 'rejected',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'review_notes' => $request->review_notes,
        ]);

        return back()->with('success', 'Kegiatan telah ditolak.');
    }
}
