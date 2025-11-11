<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

Route::Get('/lang/{locale}', function ($locale) {
    $supportedLocales = ['en', 'tr'];
    if (in_array($locale, $supportedLocales)) {
        Session::put('locale', $locale);
        App::setLocale($locale);
        return redirect()->back()->with('success', "Dil $locale değiştirildi.");
    }
    return redirect()->back()->with('error', "Desteklenmeyen dil seçimi.");
})->name('lang.switch');


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
