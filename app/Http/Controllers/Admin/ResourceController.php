<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ResourceMaterial;
use App\Models\ResourceCategory;

class ResourceController extends Controller
{
    /**
     * Display a listing of resource materials.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Get the selected quarter filter (default to all)
        $quarterFilter = $request->input('quarter', 'all');

        // Base query with category relationship
        $query = ResourceMaterial::with('category');

        // Apply quarter filter if specified
        if ($quarterFilter !== 'all' && is_numeric($quarterFilter)) {
            $query->where('quarter', $quarterFilter);
        }

        // Get resources ordered by quarter and creation date
        $resources = $query->orderBy('quarter', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get categories with resource counts
        $categories = ResourceCategory::withCount('resources')
            ->orderBy('name')
            ->get();

        // Get statistics
        $totalResources = ResourceMaterial::count();
        $totalCategories = ResourceCategory::count();

        // Get resources count by quarter
        $resourcesByQuarter = ResourceMaterial::selectRaw('quarter, count(*) as count')
            ->groupBy('quarter')
            ->get()
            ->pluck('count', 'quarter')
            ->toArray();

        // Get most used resource
        $mostUsedResource = ResourceMaterial::orderBy('click_count', 'desc')->first();
        $mostUsedCount = $mostUsedResource ? $mostUsedResource->click_count : 0;
        $mostUsedTitle = $mostUsedResource ? $mostUsedResource->title : null;

        return view('admin.resources.index', compact(
            'resources',
            'categories',
            'totalResources',
            'totalCategories',
            'mostUsedCount',
            'mostUsedTitle',
            'quarterFilter',
            'resourcesByQuarter'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:resource_categories,id',
            'quarter' => 'required|integer|min:1|max:4',
            'url' => 'required|url|max:2048',
            'description' => 'nullable|string',
        ]);

        ResourceMaterial::create([
            'title' => $request->title,
            'category_id' => $request->category_id,
            'quarter' => $request->quarter,
            'url' => $request->url,
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
            'click_count' => 0,
        ]);

        return redirect()->route('admin.resources.index')
            ->with('success', 'Resource created successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:resource_categories,id',
            'quarter' => 'required|integer|min:1|max:4',
            'url' => 'required|url|max:2048',
            'description' => 'nullable|string',
        ]);

        $resource = ResourceMaterial::findOrFail($id);
        $resource->update([
            'title' => $request->title,
            'category_id' => $request->category_id,
            'quarter' => $request->quarter,
            'url' => $request->url,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.resources.index')
            ->with('success', 'Resource updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $resource = ResourceMaterial::findOrFail($id);
        $resource->delete();

        return redirect()->route('admin.resources.index')
            ->with('success', 'Resource deleted successfully.');
    }

    /**
     * Toggle the status of the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function toggleStatus(Request $request, $id)
    {
        $resource = ResourceMaterial::findOrFail($id);
        $resource->is_active = $request->status;
        $resource->save();

        return response()->json(['success' => true]);
    }
}