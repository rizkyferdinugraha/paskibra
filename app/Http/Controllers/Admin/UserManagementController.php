<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    private function ensureSuperAdmin(): void
    {
        if (!auth()->check() || !auth()->user()->isSuperAdmin()) {
            abort(403, 'Akses ditolak. Hanya Super Admin yang dapat mengakses halaman ini.');
        }
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->ensureSuperAdmin();
        $users = \App\Models\User::with(['role', 'biodata'])->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(\App\Models\User $user)
    {
        $this->ensureSuperAdmin();
        // Larang edit akun Super Admin dari UI ini
        if ($user->super_admin) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Akun Super Admin tidak dapat diedit dari halaman ini.');
        }
        $roles = \App\Models\Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, \App\Models\User $user)
    {
        $this->ensureSuperAdmin();
        // Larang update akun Super Admin dari UI ini
        if ($user->super_admin) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Akun Super Admin tidak dapat diubah dari halaman ini.');
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role_id' => 'required|exists:roles,id',
            'is_admin' => 'boolean',
        ]);

        // Tidak mengubah field super_admin dari halaman ini

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'is_admin' => $request->boolean('is_admin'),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Reset user password
     */
    public function resetPassword(\App\Models\User $user)
    {
        $this->ensureSuperAdmin();
        if ($user->super_admin) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Akun Super Admin tidak dapat di-reset password dari halaman ini.');
        }
        $newPassword = 'password123';
        $user->update([
            'password' => \Illuminate\Support\Facades\Hash::make($newPassword)
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Password user berhasil direset ke: ' . $newPassword);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(\App\Models\User $user)
    {
        $this->ensureSuperAdmin();
        // Prevent deleting current user
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        if ($user->super_admin) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Akun Super Admin tidak dapat dihapus dari halaman ini.');
        }

        // Cegah sistem kehilangan super admin terakhir saat hapus
        if ($user->super_admin) {
            $superAdminCount = \App\Models\User::where('super_admin', true)->count();
            if ($superAdminCount <= 1) {
                return redirect()->route('admin.users.index')
                    ->with('error', 'Tidak dapat menghapus akun Super Admin terakhir. Tambahkan Super Admin lain terlebih dahulu.');
            }
        }

        // Delete biodata if exists
        if ($user->biodata) {
            $user->biodata->delete();
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }

    /**
     * Toggle user activation status
     */
    public function toggleActive(\App\Models\User $user)
    {
        $this->ensureSuperAdmin();
        if ($user->super_admin) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Status akun Super Admin tidak dapat diubah dari halaman ini.');
        }
        if ($user->biodata) {
            $currentlyActive = (bool) $user->biodata->is_active;
            $user->biodata->update([
                'is_active' => !$currentlyActive
            ]);

            // Log status change
            \App\Models\MemberStatusLog::logAction(
                $user->id,
                $user->biodata->id,
                $currentlyActive ? 'deactivated' : 'activated',
                $currentlyActive ? 'inactive' : 'active',
                $currentlyActive ? 'Dinonaktifkan oleh admin melalui toggle' : 'Diaktifkan oleh admin melalui toggle',
                auth()->id()
            );

            $status = !$currentlyActive ? 'diaktifkan' : 'dinonaktifkan';
            return redirect()->route('admin.users.index')
                ->with('success', 'Status anggota berhasil ' . $status . '.');
        }

        return redirect()->route('admin.users.index')
            ->with('error', 'User belum memiliki biodata.');
    }
}
