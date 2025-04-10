<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementViewController extends Controller
{
    /**
     * Get active announcements for display on the login page
     */
    public function getActiveAnnouncements()
    {
        $announcements = Announcement::where('is_active', true)
                                    ->latest()
                                    ->get();

        return response()->json($announcements);
    }
}
