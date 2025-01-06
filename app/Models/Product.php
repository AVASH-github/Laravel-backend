<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // Define fillable attributes for mass assignment
    protected $fillable = ['name', 'category_id', 'subcategory_id', 'description', 'status', 'image'];

    // Define relationship with the Category model
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // Define relationship with the Subcategory model
    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    // Accessor to get the full image URL
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}
