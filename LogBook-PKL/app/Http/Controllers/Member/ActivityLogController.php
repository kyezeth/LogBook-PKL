<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of activities
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        
        $query = ActivityLog::where('user_id', $user->id);

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->where('date', '>=', $request->input('start_date'));
        }
        if ($request->filled('end_date')) {
            $query->where('date', '<=', $request->input('end_date'));
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Search by title or description
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $activities = $query->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Get categories for filter dropdown
        $categories = ActivityLog::where('user_id', $user->id)
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category');

        return view('member.activities.index', compact('activities', 'categories'));
    }

    /**
     * Show the form for creating a new activity
     */
    public function create(): View
    {
        return view('member.activities.create');
    }

    /**
     * Store a newly created activity
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'date' => ['required', 'date', 'before_or_equal:today'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category' => ['nullable', 'string', 'max:100'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i', 'after:start_time'],
            'images.*' => ['nullable', 'image', 'max:5120'], // 5MB max per image
        ], [
            'date.required' => 'Tanggal wajib diisi.',
            'date.before_or_equal' => 'Tanggal tidak boleh lebih dari hari ini.',
            'title.required' => 'Judul kegiatan wajib diisi.',
            'description.required' => 'Deskripsi kegiatan wajib diisi.',
            'end_time.after' => 'Waktu selesai harus setelah waktu mulai.',
            'images.*.max' => 'Ukuran gambar maksimal 5MB.',
        ]);

        $user = $request->user();

        // Calculate duration if times provided
        $durationMinutes = null;
        if ($validated['start_time'] && $validated['end_time']) {
            $start = Carbon::parse($validated['start_time']);
            $end = Carbon::parse($validated['end_time']);
            $durationMinutes = $end->diffInMinutes($start);
        }

        // Handle image uploads
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('activities/' . now()->format('Y-m'), 'public');
                $images[] = $path;
            }
        }

        ActivityLog::create([
            'user_id' => $user->id,
            'date' => $validated['date'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'duration_minutes' => $durationMinutes,
            'images' => !empty($images) ? $images : null,
            'status' => 'submitted',
        ]);

        return redirect()->route('member.activities.index')
            ->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    /**
     * Display the specified activity
     */
    public function show(ActivityLog $activity): View|RedirectResponse
    {
        // Ensure user can only view their own activities
        if ($activity->user_id !== auth()->id()) {
            return redirect()->route('member.activities.index')
                ->with('error', 'Anda tidak memiliki akses ke kegiatan ini.');
        }

        return view('member.activities.show', compact('activity'));
    }

    /**
     * Show the form for editing the specified activity
     */
    public function edit(ActivityLog $activity): View|RedirectResponse
    {
        if ($activity->user_id !== auth()->id()) {
            return redirect()->route('member.activities.index')
                ->with('error', 'Anda tidak memiliki akses ke kegiatan ini.');
        }

        // Can only edit if not yet reviewed
        if (!in_array($activity->status, ['draft', 'submitted'])) {
            return redirect()->route('member.activities.show', $activity)
                ->with('warning', 'Kegiatan yang sudah direview tidak dapat diedit.');
        }

        return view('member.activities.edit', compact('activity'));
    }

    /**
     * Update the specified activity
     */
    public function update(Request $request, ActivityLog $activity): RedirectResponse
    {
        if ($activity->user_id !== auth()->id()) {
            return redirect()->route('member.activities.index')
                ->with('error', 'Anda tidak memiliki akses ke kegiatan ini.');
        }

        if (!in_array($activity->status, ['draft', 'submitted'])) {
            return redirect()->route('member.activities.show', $activity)
                ->with('warning', 'Kegiatan yang sudah direview tidak dapat diedit.');
        }

        $validated = $request->validate([
            'date' => ['required', 'date', 'before_or_equal:today'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category' => ['nullable', 'string', 'max:100'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i', 'after:start_time'],
            'images.*' => ['nullable', 'image', 'max:5120'],
            'remove_images' => ['nullable', 'array'],
        ]);

        // Calculate duration
        $durationMinutes = null;
        if ($validated['start_time'] && $validated['end_time']) {
            $start = Carbon::parse($validated['start_time']);
            $end = Carbon::parse($validated['end_time']);
            $durationMinutes = $end->diffInMinutes($start);
        }

        // Handle image removal
        $existingImages = $activity->images ?? [];
        if ($request->filled('remove_images')) {
            foreach ($request->input('remove_images') as $imageToRemove) {
                Storage::disk('public')->delete($imageToRemove);
                $existingImages = array_filter($existingImages, fn($img) => $img !== $imageToRemove);
            }
        }

        // Handle new image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('activities/' . now()->format('Y-m'), 'public');
                $existingImages[] = $path;
            }
        }

        $activity->update([
            'date' => $validated['date'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'duration_minutes' => $durationMinutes,
            'images' => !empty($existingImages) ? array_values($existingImages) : null,
        ]);

        return redirect()->route('member.activities.show', $activity)
            ->with('success', 'Kegiatan berhasil diperbarui.');
    }

    /**
     * Remove the specified activity
     */
    public function destroy(ActivityLog $activity): RedirectResponse
    {
        if ($activity->user_id !== auth()->id()) {
            return redirect()->route('member.activities.index')
                ->with('error', 'Anda tidak memiliki akses ke kegiatan ini.');
        }

        if (!in_array($activity->status, ['draft', 'submitted'])) {
            return redirect()->route('member.activities.index')
                ->with('warning', 'Kegiatan yang sudah direview tidak dapat dihapus.');
        }

        // Delete associated images
        if ($activity->images) {
            foreach ($activity->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $activity->delete();

        return redirect()->route('member.activities.index')
            ->with('success', 'Kegiatan berhasil dihapus.');
    }
}
