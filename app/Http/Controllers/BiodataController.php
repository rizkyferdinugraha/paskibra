<?php

namespace App\Http\Controllers;

use App\Models\Biodata;
use Illuminate\Http\Request;
use App\Models\Jurusan;
use Illuminate\Support\Facades\Auth;

class BiodataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $userId = Auth::id();
        $biodata = Biodata::where('user_id', $userId)->first();
        $jurusans = Jurusan::all();

        if ($biodata) {
            $jurusanId = $biodata->jurusan_id;
            $namaJurusan = Jurusan::find($jurusanId)->nama_jurusan;

            if (!$biodata->is_active) {
                return view('dashboard')
                    ->with('status', 'pending')
                    ->with('biodata', $biodata)
                    ->with('jurusan', $namaJurusan);
            } else {
                return view('dashboard')
                    ->with('status', 'active')
                    ->with('jurusan', $namaJurusan)
                    ->with('biodata', $biodata);
            }
        }

        return view('dashboard')
            ->with('status', 'not_found')
            ->with('jurusans', $jurusans);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $data = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'no_telepon' => 'required|string|max:15',
            'jurusan_id' => 'required|exists:jurusans,id',
            'tahun_angkatan' => 'required|integer',
            'alamat' => 'required|string|max:255',
            'riwayat_penyakit' => 'required|string|max:255',
            'pas_foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // dd($data);

        $data['user_id'] = Auth::id();
        $data['is_active'] = false;
        $data['no_kta'] = $data['tahun_angkatan'] . str_pad($data['user_id'], 4, '0', STR_PAD_LEFT);
        $data['role_id'] = 6;
        $data['super_admin'] = false;
        $data['pas_foto_url'] = $request->file('pas_foto')->store('pas_foto', 'public');


        Biodata::create($data);

        return redirect()->route('dashboard')->with('success', 'Pendaftaran berhasil dikirim. Silakan tunggu konfirmasi dari admin.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Biodata $biodata)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Biodata $biodata)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Biodata $biodata)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Biodata $biodata)
    {
        //
    }
}
