<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    //
    use HasFactory;

    protected $table='categories';

    protected $fillable=['name','status','image'];

    public function subcategory(){
        return $this->hasMany(Subcategory::class);
    }
    public function setImage($image)
    {
        if ($image) {
            // Store the image in the 'public/categories' folder
            $path = $image->store('categories', 'public');
            return $path; // Return the path for storing in the database
        }

        return null;
    }
    public function getImageUrlAttribute()
    {
        return $this->image ? Storage::url($this->image) : null;
    }
}
