<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $announcements = Announcement::latest()->paginate(10);
        return view('admin.announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.announcements.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_active' => 'sometimes|boolean',
        ]);

        try {
            $announcement = Announcement::create($validated);

            return redirect()->route('admin.announcements.index')
                ->with('success', 'Announcement created successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to create announcement: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return back()->withInput()
                ->with('error', 'Failed to create announcement: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Announcement $announcement)
    {
        return view('admin.announcements.show', compact('announcement'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.form', compact('announcement'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_active' => 'sometimes|boolean',
        ]);

        try {
            $announcement->update($validated);

            return redirect()->route('admin.announcements.index')
                ->with('success', 'Announcement updated successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to update announcement: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return back()->withInput()
                ->with('error', 'Failed to update announcement: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Announcement $announcement)
    {
        try {
            $announcement->delete();

            return redirect()->route('admin.announcements.index')
                ->with('success', 'Announcement deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to delete announcement: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return back()
                ->with('error', 'Failed to delete announcement: ' . $e->getMessage());
        }
    }

    /**
     * Toggle the active status of the announcement.
     */
    public function toggleStatus(Announcement $announcement)
    {
        try {
            $newStatus = !$announcement->is_active;
            $announcement->update(['is_active' => $newStatus]);

            $statusText = $newStatus ? 'activated' : 'deactivated';

            return redirect()->route('admin.announcements.index')
                ->with('success', "Announcement {$statusText} successfully.");
        } catch (\Exception $e) {
            Log::error('Failed to toggle announcement status: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return back()
                ->with('error', 'Failed to update announcement status: ' . $e->getMessage());
        }
    }
}
