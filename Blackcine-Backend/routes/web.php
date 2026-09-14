<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\FilmController;
use App\Http\Controllers\Admin\SeriesController;
use App\Http\Controllers\CatalogController;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';

// Route pour changer la langue
Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'fr'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');


Route::prefix('admin')->name('admin.')->middleware(['web','auth','role_permission:SuperAdmin|Admin'])->group(function () {
    // Register orders routes used by dashboard blade
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    // Or as a resource:
    // Route::resource('orders', OrderController::class)->only(['index','show']);

    // Resource routes admin pour Films & Series
    Route::resource('films', FilmController::class);
    Route::resource('series', SeriesController::class);
});

// Pages publiques / catalogue
Route::get('/films', [CatalogController::class, 'films'])->name('catalog.films');
Route::get('/series', [CatalogController::class, 'series'])->name('catalog.series');
Route::get('/catalog/genre/{slug}', [CatalogController::class, 'byGenre'])->name('catalog.genre');
Route::get('/titles/{slug}', [CatalogController::class, 'show'])->name('catalog.title.show');
Route::post('/titles/{slug}/reviews', [CatalogController::class, 'storeReview'])->name('catalog.title.reviews.store')->middleware('auth');
