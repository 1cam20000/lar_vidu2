<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\WelcomeController;


Route::get('/', [WelcomeController::class, 'index'])->name('welcome');


Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('categories', AdminCategoryController::class);
    Route::resource('products', AdminProductController::class);
});
