<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ResourceMaterial;
use App\Models\ResourceCategory;

class ResourceController extends Controller
{
    /**
     * Display a listing of resources.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Get the selected quarter filter (default to all)
        $quarterFilter = $request->input('quarter', 'all');

        // Base query with active resources and category relationship
        $query = ResourceMaterial::where('is_active', true)
            ->with('category');

        // Apply quarter filter if specified
        if ($quarterFilter !== 'all' && is_numeric($quarterFilter)) {
            $query->where('quarter', $quarterFilter);
        }

        // Get resources ordered by quarter and creation date
        $resources = $query->orderBy('quarter', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get categories with resource counts
        $categories = ResourceCategory::with(['resources' => function($query) {
                $query->where('is_active', true);
            }])
            ->withCount(['resources' => function($query) {
                $query->where('is_active', true);
            }])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        // Get resources count by quarter for statistics
        $resourcesByQuarter = ResourceMaterial::where('is_active', true)
            ->selectRaw('quarter, count(*) as count')
            ->groupBy('quarter')
            ->get()
            ->pluck('count', 'quarter')
            ->toArray();

        return view('teacher.resources.index', compact(
            'resources',
            'categories',
            'quarterFilter',
            'resourcesByQuarter'
        ));
    }
}