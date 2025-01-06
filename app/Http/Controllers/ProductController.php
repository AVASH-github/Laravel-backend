<?php

namespace App\Http\Controllers;

use App\Mail\ProductAdded;
use App\Mail\ProductUpdated;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class ProductController extends Controller
{
    // Display the products
    public function index() {
        $products = Product::with('category', 'subcategory')->get();
        $categories = Category::where('status', 'active')->get();
        $subcategories = Subcategory::where('status', 'active')->get();

        return view('products.index', compact('products', 'categories', 'subcategories'));
    }

    // Store a new product
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
           'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);

        $data = $request->all();

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = $imagePath;
        }

      $product =  Product::create($data);

            Mail::to('nepalavash20@gmail.com')->send(new ProductAdded($product->name,
            $product->category->name,
            $product->subcategory->name,
            $product->description,
            $product->status
        ));

        return redirect()->route('products.index')->with('success', 'Product added successfully!');
    }

    // Update an existing product
    public function update(Request $request, Product $product) {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image
        ]);

        $data = $request->all();

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($product->image && Storage::exists('public/' . $product->image)) {
                Storage::delete('public/' . $product->image);
            }

            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = $imagePath;
        }

        $product->update($data);
        Mail::to('nepalavash20@gmail.com')->send(new ProductUpdated($product->name,
        $product->category->name,
        $product->subcategory->name,
        $product->description,
        $product->status
    ));
        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    // Delete a product
    public function destroy(Product $product) {
        // Delete the associated image if it exists
        if ($product->image && Storage::exists('public/' . $product->image)) {
            Storage::delete('public/' . $product->image);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }
}
