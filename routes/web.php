<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubcategoryController;


Route::get('/',function(){
    return view('welcome');
});

//for Categories



Route::resource('categories', CategoryController::class);



Route::get('/subcategories', [SubcategoryController::class, 'index'])->name('subcategories.index'); // Show the list of subcategories
Route::post('/subcategories', [SubcategoryController::class, 'store'])->name('subcategories.store'); // Store a new subcategory
Route::put('/subcategories/{subcategory}', [SubcategoryController::class, 'update'])->name('subcategories.update'); // Update an existing subcategory
Route::delete('/subcategories/{subcategory}', [SubcategoryController::class, 'destroy'])->name('subcategories.destroy'); // Delete a subcategory


Route::get('/products',[ProductController::class,'index'])->name('products.index');
Route::post('/products',[ProductController::class,'store'])->name('products.store');
Route::put('/products/{product}',[ProductController::class,'update'])->name('products.update');
Route::delete('products/{product}',[ProductController::class,'destroy'])->name('products.destroy');



// Gallery Routes
Route::get('galleries', [GalleryController::class, 'index'])->name('galleries.index');
Route::get('galleries/create', [GalleryController::class, 'create'])->name('galleries.create');
Route::post('galleries', [GalleryController::class, 'store'])->name('galleries.store');
Route::get('galleries/{gallery}', [GalleryController::class, 'show'])->name('galleries.show');
Route::get('galleries/{gallery}/edit', [GalleryController::class, 'edit'])->name('galleries.edit');
Route::put('/galleries/{gallery}', [GalleryController::class, 'update'])->name('galleries.update');
Route::delete('galleries/{gallery}', [GalleryController::class, 'destroy'])->name('galleries.destroy');
