<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoleManagementController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = \App\Models\Role::withCount('users')->paginate(10);
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_role' => 'required|string|max:255|unique:roles,nama_role',
        ]);

        \App\Models\Role::create([
            'nama_role' => $request->nama_role,
        ]);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(\App\Models\Role $role)
    {
        return view('admin.roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, \App\Models\Role $role)
    {
        $request->validate([
            'nama_role' => 'required|string|max:255|unique:roles,nama_role,' . $role->id,
        ]);

        $role->update([
            'nama_role' => $request->nama_role,
        ]);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(\App\Models\Role $role)
    {
        // Check if role is being used
        if ($role->users()->count() > 0) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Role tidak dapat dihapus karena masih digunakan oleh user.');
        }

        // Prevent deleting default roles (1-6)
        if ($role->id <= 6) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Role default tidak dapat dihapus.');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role berhasil dihapus.');
    }
}
