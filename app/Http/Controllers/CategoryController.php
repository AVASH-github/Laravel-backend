<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::paginate(10); // Fetch all categories
        return view('categories.index', compact('categories'));
    }

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'status' => 'required|in:active,inactive',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',  // Validate image
    ]);

    // Handle file upload
    $imagePath = null;
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imagePath = $image->store('categories', 'public'); // Store image in storage/app/public/categories
    }

    // Store category data
    Category::create([
        'name' => $request->name,
        'status' => $request->status,
        'image' => $imagePath,  // Save the image path in the database
    ]);

    return redirect()->route('categories.index')->with('success', 'Category added successfully!');
}


    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        // Delete the image file if it exists
        if ($category->image && Storage::exists($category->image)) {
            Storage::delete($category->image);
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category deleted successfully');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);  // This line will throw a 404 if the category isn't found
        return view('categories.edit', compact('category'));  // Pass the category variable to the view
    }
    

    public function update(Request $request, $id)
{
   

    $category = Category::findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    $category->name = $request->name;

    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('categories', 'public');
        $category->image = $imagePath;
    }

    $category->save();

    return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
}

}
