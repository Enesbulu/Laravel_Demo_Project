<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;

Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');

// Route::resource('categories', CategoryController::class);
// Route::get('/', function () {
//     return view('index');
// });
