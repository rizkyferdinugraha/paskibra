<?php

use App\Http\Controllers\BiodataController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ComplaintController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
});

// Komplain publik (guest)
Route::get('/komplain', [ComplaintController::class, 'create'])->name('complaints.create');
Route::post('/komplain', [ComplaintController::class, 'store'])->name('complaints.store');
Route::get('/komplain/terima-kasih', [ComplaintController::class, 'thanks'])->name('complaints.thanks');

Route::get('/dashboard', [BiodataController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Partial kalender untuk AJAX (tanpa full reload)
Route::get('/dashboard/calendar', [BiodataController::class, 'calendar'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard.calendar');

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

// Kas (hanya Senior)
Route::middleware(['auth', 'verified', 'senior'])->group(function () {
    Route::get('/kas', [App\Http\Controllers\KasController::class, 'index'])->name('kas.index');
    Route::post('/kas', [App\Http\Controllers\KasController::class, 'store'])->name('kas.store');
    Route::delete('/kas/{kas}', [App\Http\Controllers\KasController::class, 'destroy'])->name('kas.destroy');

    // Acara (hanya Senior)
    Route::get('/acara', [App\Http\Controllers\AcaraController::class, 'index'])->name('acara.index');
    Route::get('/acara/create', [App\Http\Controllers\AcaraController::class, 'create'])->name('acara.create');
    Route::post('/acara', [App\Http\Controllers\AcaraController::class, 'store'])->name('acara.store');
    Route::get('/acara/{acara}/edit', [App\Http\Controllers\AcaraController::class, 'edit'])->name('acara.edit');
    Route::put('/acara/{acara}', [App\Http\Controllers\AcaraController::class, 'update'])->name('acara.update');
    Route::delete('/acara/{acara}', [App\Http\Controllers\AcaraController::class, 'destroy'])->name('acara.destroy');
    Route::put('/acara/{acara}/toggle', [App\Http\Controllers\AcaraController::class, 'toggleSelesai'])->name('acara.toggle');
    Route::put('/acara/{acara}/absen', [App\Http\Controllers\AcaraController::class, 'saveAbsen'])->name('acara.absen');
    Route::post('/acara/{acara}/grades', [App\Http\Controllers\AcaraController::class, 'saveGrades'])->name('acara.grades');
    Route::post('/acara/{acara}/photo', [App\Http\Controllers\AcaraController::class, 'uploadPhoto'])->name('acara.photo');
    Route::delete('/photo/{photo}', [App\Http\Controllers\AcaraController::class, 'deletePhoto'])->name('photo.delete');
    Route::put('/acara/{acara}/feedback', [App\Http\Controllers\AcaraController::class, 'saveFeedback'])->name('acara.feedback');
});

// Detail acara dapat dilihat semua role (login saja)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/acara/{acara}', [App\Http\Controllers\AcaraController::class, 'show'])->name('acara.show');
    // Daftar komplain - hanya role tertentu
    Route::get('/komplain/list', [ComplaintController::class, 'index'])
        ->middleware('role_in:Pembina,Pelatih,Senior')
        ->name('complaints.index');
    Route::get('/komplain/{complaint}', [ComplaintController::class, 'show'])
        ->middleware('role_in:Pembina,Pelatih,Senior')
        ->name('complaints.show');
    Route::put('/komplain/{complaint}/selesai', [ComplaintController::class, 'markDone'])
        ->middleware('role_in:Pembina,Pelatih,Senior')
        ->name('complaints.done');
    Route::delete('/komplain/{complaint}', [ComplaintController::class, 'destroy'])
        ->middleware('role_in:Pembina,Pelatih,Senior')
        ->name('complaints.destroy');
    Route::post('/komplain/{complaint}/follow-up', [ComplaintController::class, 'saveFollowUp'])
        ->middleware('role_in:Pembina,Pelatih,Senior')
        ->name('complaints.followup');
});

require __DIR__.'/auth.php';
