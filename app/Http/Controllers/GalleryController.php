<?php
namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::with('images')->get();
        return view('galleries.index', compact('galleries'));
    }

    public function create()
    {
        return view('galleries.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string',
            'thumbnail_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $gallery = new Gallery();
        $gallery->title = $request->title;
        $gallery->description = $request->description;
        $gallery->status = $request->status;

        if ($request->hasFile('thumbnail_image')) {
            $gallery->thumbnail_image = $request->file('thumbnail_image')->store('thumbnails', 'public');
        }

        $gallery->save();

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $galleryImage = new Image();
                $galleryImage->gallery_id = $gallery->id;
                $galleryImage->image_path = $image->store('galleries', 'public');
                $galleryImage->save();
            }
        }

        return redirect()->route('galleries.index')->with('success', 'Gallery created successfully!');
    }

    public function show($id)
    {
        $gallery = Gallery::with('images')->findOrFail($id);
        return view('galleries.show', compact('gallery'));
    }

    public function edit($id)
    {
        $gallery = Gallery::with('images')->findOrFail($id);
    
        return response()->json($gallery);
    }
    public function update(Request $request, $id)
    {
        $gallery = Gallery::find($id);
    
        // Validate the request (you can adjust the validation as per your needs)
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
            'thumbnail_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        // Update gallery data
        $gallery->title = $request->title;
        $gallery->description = $request->description;
        $gallery->status = $request->status;
    
        // Handle the thumbnail image (if new thumbnail is uploaded)
        if ($request->hasFile('thumbnail_image')) {
            // Delete the old thumbnail image (if it exists)
            if ($gallery->thumbnail_image) {
                Storage::delete('public/' . $gallery->thumbnail_image);
            }
    
            // Store the new thumbnail image
            $gallery->thumbnail_image = $request->file('thumbnail_image')->store('thumbnails', 'public');
        }
    
        // Handle the gallery images (if new images are uploaded)
        if ($request->hasFile('gallery_images')) {
            // Remove old gallery images (if necessary, you can keep them)
            // Loop through and delete old gallery images if needed.
            foreach ($gallery->images as $oldImage) {
                Storage::delete('public/' . $oldImage->image_path);
                $oldImage->delete();
            }
    
            // Store new gallery images
            foreach ($request->file('gallery_images') as $image) {
                $imagePath = $image->store('gallery_images', 'public');
    
                // Create new image entries in the database
                $gallery->images()->create([
                    'image_path' => $imagePath,
                ]);
            }
        }
    
        // Save the gallery data
        $gallery->save();
    
        return redirect()->route('galleries.index')->with('success', 'Gallery updated successfully');
    }
    
    
   

    public function destroy($id)
    {
        $gallery = Gallery::findOrFail($id);

        // Optionally delete the thumbnail image
        if ($gallery->thumbnail_image) {
            \Storage::disk('public')->delete($gallery->thumbnail_image);
        }

        // Delete associated images
        foreach ($gallery->images as $image) {
            \Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        $gallery->delete();

        return redirect()->route('galleries.index')->with('success', 'Gallery deleted successfully!');
    }
}