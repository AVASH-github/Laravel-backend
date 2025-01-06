<?php

namespace App\Http\Controllers;

use App\Models\Subcategory;
use App\Models\Category;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    public function index()
    {
        $subcategories = Subcategory::all();
        $categories = Category::where('status','active')->get(); // Fetch all categories to populate select dropdown
        return view('subcategories.index', compact('subcategories', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id,status,active',
            'status' => 'required|in:active,inactive',
        ]);

        // Create a new subcategory
        Subcategory::create($request->all());

        // Redirect back to the index with a success message
        return redirect()->route('subcategories.index')->with('success', 'Subcategory added successfully');
    }

    public function update(Request $request, Subcategory $subcategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id,status,active',
            'status' => 'required|in:active,inactive',
        ]);

        // Update the subcategory
        $subcategory->update($request->all());

        // Redirect back to the index with a success message
        return redirect()->route('subcategories.index')->with('success', 'Subcategory updated successfully');
    }

    public function destroy(Subcategory $subcategory)
    {
        // Delete the subcategory
        $subcategory->delete();

        // Redirect back to the index with a success message
        return redirect()->route('subcategories.index')->with('success', 'Subcategory deleted successfully');
    }
}
