<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    //
    use HasFactory;

    protected $fillable=['title','description','status','thumbnail_image'];

    public function images(){
        
        return $this->hasMany(Image::class);
    }
}
