<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ResourceCategory;

class ResourceCategoryController extends Controller
{
    /**
     * Store a newly created category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'required|string|max:50',
            'color' => 'required|string|max:50',
        ]);

        ResourceCategory::create([
            'name' => $request->name,
            'description' => $request->description,
            'icon' => $request->icon,
            'color' => $request->color,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.resources.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Update the specified category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'required|string|max:50',
            'color' => 'required|string|max:50',
        ]);

        $category = ResourceCategory::findOrFail($id);
        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'icon' => $request->icon,
            'color' => $request->color,
        ]);

        return redirect()->route('admin.resources.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = ResourceCategory::findOrFail($id);
        
        // Set category_id to null for resources in this category before deleting
        $category->resources()->update(['category_id' => null]);
        
        $category->delete();

        return redirect()->route('admin.resources.index')
            ->with('success', 'Category deleted successfully. Associated resources have been uncategorized.');
    }

    /**
     * Toggle the status of the specified category.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function toggleStatus(Request $request, $id)
    {
        $category = ResourceCategory::findOrFail($id);
        $category->is_active = $request->status;
        $category->save();

        return response()->json(['success' => true]);
    }
} 