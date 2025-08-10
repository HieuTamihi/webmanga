<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

use App\Models\Manga;

Route::get('/', function () {
    $setting = \App\Models\Setting::first();
    $genres = \App\Models\Genre::all();
    $featured = Manga::latest()->take(8)->get();
    $latest = Manga::latest()->paginate(20);
    return view('dashboard', compact('setting', 'genres','featured','latest'));
});

Route::get('/dashboard', function () {
    $setting = \App\Models\Setting::first();
    $genres = \App\Models\Genre::all();
    $featured = Manga::latest()->take(8)->get();
    $latest = Manga::latest()->paginate(20);
    return view('dashboard', compact('setting', 'genres','featured','latest'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

// Public site routes
use App\Http\Controllers\Front\MangaController as FrontManga;
Route::get('/manga/{manga}', [FrontManga::class, 'show'])->name('manga.show');
Route::get('/manga/{manga}/chapter/{chapter}', [FrontManga::class, 'read'])->name('manga.read');
Route::get('/search', [FrontManga::class, 'search'])->name('search');

// Admin routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Settings
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'edit'])->name('settings.edit');
    Route::post('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');

    // Genres
    Route::resource('genres', \App\Http\Controllers\GenreController::class)->names('genres');

    // Mangas
    Route::resource('mangas', \App\Http\Controllers\MangaController::class)->names('mangas');

    // Chapters
    Route::resource('mangas.chapters', \App\Http\Controllers\ChapterController::class)
        ->scoped()
        ->names('chapters');
});
