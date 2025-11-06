<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;

Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');

// Düzenleme (Edit) Formunu Gösteren Rota (GET)
Route::get('/categories/{category:slug}/edit', [CategoryController::class, 'edit'])->name("categories.edit");

// Güncelleme (Update) Verisini Kaydeden Rota (PUT/PATCH)
// Tarayıcılar PUT/PATCH desteklemediği için Blade'de @method('PUT') kullanıyoruz.
Route::put('/categories/{category:slug}', [CategoryController::class, 'update'])->name('categories.update');

Route::delete('/categories/{category:slug}', [CategoryController::class, 'destroy'])->name("categories.destroy");

// Route::put('/categories/{category}', [CategoryController::class, 'update'])->name("categories.update");




// Route::resource('categories', CategoryController::class);
// Route::get('/', function () {
//     return view('categories.index');
// });


// Route::post('/categories', function () {
//     return "gg;";
// })->name('categories.store');
