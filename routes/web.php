<?php

use App\Http\Controllers\BiodataController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [BiodataController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/template/kta', [BiodataController::class, 'indexKTA'])
    ->middleware(['auth', 'verified'])
    ->name('template.kta');

Route::post('/biodata', [BiodataController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('biodata.store');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
