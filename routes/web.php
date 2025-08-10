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

Route::get('/profile/edit', [ProfileController::class, 'edit'])
    ->middleware(['auth', 'verified'])
    ->name('profile.edit');

Route::put('/profile/update', [ProfileController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('profile.update');

Route::post('/biodata/update', [BiodataController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('biodata.update');

Route::get('/category', function () {
    return view('category');
})->middleware(['auth', 'verified'])->name('category');

// Membership Status
Route::get('/membership/status', [App\Http\Controllers\MembershipStatusController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('membership.status');



// Admin Routes (Admin & Super Admin)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\AdminController::class, 'index'])->name('dashboard');

    // Jurusan Management
    Route::resource('jurusan', App\Http\Controllers\Admin\JurusanManagementController::class);

    // Role Management
    Route::resource('roles', App\Http\Controllers\Admin\RoleManagementController::class);

    // Member Approval Management
    Route::get('members', [App\Http\Controllers\Admin\MemberApprovalController::class, 'index'])->name('members.index');
    Route::get('members/active', [App\Http\Controllers\Admin\MemberApprovalController::class, 'activeMembers'])->name('members.active');
    Route::get('members/{biodata}', [App\Http\Controllers\Admin\MemberApprovalController::class, 'show'])->name('members.show');
    Route::post('members/{biodata}/approve', [App\Http\Controllers\Admin\MemberApprovalController::class, 'approve'])->name('members.approve');
    Route::get('members/{biodata}/reject', [App\Http\Controllers\Admin\MemberApprovalController::class, 'showRejectForm'])->name('members.reject.form');
    Route::delete('members/{biodata}/reject', [App\Http\Controllers\Admin\MemberApprovalController::class, 'reject'])->name('members.reject');
    Route::get('members/{biodata}/deactivate', [App\Http\Controllers\Admin\MemberApprovalController::class, 'showDeactivateForm'])->name('members.deactivate.form');
    Route::post('members/{biodata}/deactivate', [App\Http\Controllers\Admin\MemberApprovalController::class, 'deactivate'])->name('members.deactivate');
    Route::post('members/bulk-approve', [App\Http\Controllers\Admin\MemberApprovalController::class, 'bulkApprove'])->name('members.bulk-approve');

    // User Management (Super Admin only)
    Route::middleware('super_admin')->group(function () {
        Route::resource('users', App\Http\Controllers\Admin\UserManagementController::class)->except(['create', 'store', 'show']);
        Route::post('users/{user}/reset-password', [App\Http\Controllers\Admin\UserManagementController::class, 'resetPassword'])->name('users.reset-password');
        Route::post('users/{user}/toggle-active', [App\Http\Controllers\Admin\UserManagementController::class, 'toggleActive'])->name('users.toggle-active');
    });
});

require __DIR__.'/auth.php';
