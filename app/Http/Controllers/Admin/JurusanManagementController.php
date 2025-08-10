<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JurusanManagementController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jurusans = \App\Models\Jurusan::withCount('biodatas')->paginate(10);
        return view('admin.jurusan.index', compact('jurusans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.jurusan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_jurusan' => 'required|string|max:255|unique:jurusans,nama_jurusan',
        ]);

        \App\Models\Jurusan::create([
            'nama_jurusan' => $request->nama_jurusan,
        ]);

        return redirect()->route('admin.jurusan.index')
            ->with('success', 'Jurusan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(\App\Models\Jurusan $jurusan)
    {
        return view('admin.jurusan.edit', compact('jurusan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, \App\Models\Jurusan $jurusan)
    {
        $request->validate([
            'nama_jurusan' => 'required|string|max:255|unique:jurusans,nama_jurusan,' . $jurusan->id,
        ]);

        $jurusan->update([
            'nama_jurusan' => $request->nama_jurusan,
        ]);

        return redirect()->route('admin.jurusan.index')
            ->with('success', 'Jurusan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(\App\Models\Jurusan $jurusan)
    {
        // Check if jurusan is being used
        if ($jurusan->biodatas()->count() > 0) {
            return redirect()->route('admin.jurusan.index')
                ->with('error', 'Jurusan tidak dapat dihapus karena masih digunakan oleh anggota.');
        }

        $jurusan->delete();

        return redirect()->route('admin.jurusan.index')
            ->with('success', 'Jurusan berhasil dihapus.');
    }
}
