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

Route::get('/template/kta', [BiodataController::class, 'showKta'])
    ->middleware(['auth', 'verified'])
    ->name('template.kta');

Route::post('/biodata', [BiodataController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('biodata.store');

Route::get('/profile/edit', [ProfileController::class, 'edit'], [BiodataController::class, 'showEdit'])
    ->middleware(['auth', 'verified'])
    ->name('profile.edit');

Route::put('/profile/update', [ProfileController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('profile.update');

Route::post('/biodata/update', [BiodataController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('biodata.update');

require __DIR__.'/auth.php';
