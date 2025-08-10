<?php

namespace App\Http\Controllers;

use App\Models\Biodata;
use Illuminate\Http\Request;
use App\Models\Jurusan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BiodataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $biodata = Biodata::with('jurusan')->where('user_id', Auth::id())->first();

        // Cek riwayat terakhir: jika dinonaktifkan, blok form pendaftaran
        $latestLog = \App\Models\MemberStatusLog::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->first();

        if ($latestLog && $latestLog->action === 'deactivated') {
            return view('dashboard')
                ->with('status', 'deactivated')
                ->with('deactivation_reason', $latestLog->reason)
                ->with('deactivation_admin', $latestLog->admin_name)
                ->with('deactivation_when', $latestLog->created_at);
        }

        // Jika pengguna belum mengisi biodata, tampilkan form pendaftaran.
        if (!$biodata) {
            $jurusans = Jurusan::all();
            return view('dashboard')
                ->with('status', 'not_found') // Untuk menampilkan form
                ->with('jurusans', $jurusans);
        }

        // Jika biodata sudah diisi dan sudah aktif, tampilkan dashboard utama.
        if ($biodata->is_active) {
            return view('dashboard')
                ->with('status', 'active') // Untuk menampilkan dashboard utama
                ->with('biodata', $biodata);
        }

        // Jika biodata sudah diisi tapi belum aktif, tampilkan status pending.
        return view('dashboard')
            ->with('status', 'pending') // Untuk menampilkan status pending
            ->with('biodata', $biodata)
            ->with('jurusan', $biodata->jurusan->nama_jurusan);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Method ini bisa digunakan untuk menampilkan form secara eksplisit,
        // namun saat ini logika tersebut sudah ditangani oleh method index().
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'jurusan_id' => 'required|exists:jurusans,id',
            'tahun_angkatan' => 'required|integer|digits:4',
            'pas_foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'no_telepon' => 'required|string|max:15',
            'alamat' => 'required|string|max:255',
            'riwayat_penyakit' => 'required|string|max:255',
        ]);

        $pasFotoUrl = $request->file('pas_foto')->store('pas_foto', 'public');
        $userId = Auth::id();

        // Update user dengan role_id dan super_admin
        $user = Auth::user();
        $user->role_id = 6; // Role untuk anggota biasa
        $user->super_admin = false; // Default bukan super admin
        $user->save();

        $biodata = Biodata::create(array_merge(
            $validatedData,
            [
                'user_id' => $userId,
                'is_active' => false, // Status default menunggu verifikasi
                'no_kta' => $validatedData['tahun_angkatan'] . str_pad($userId, 4, '0', STR_PAD_LEFT),
                'pas_foto_url' => $pasFotoUrl,
            ]
        ));

        // Log initial pending status
        // Hindari duplikasi pending log jika user submit ulang
        $hasPending = \App\Models\MemberStatusLog::where('user_id', $userId)
            ->where('biodata_id', $biodata->id)
            ->where('action', 'pending')
            ->exists();

        if (!$hasPending) {
            \App\Models\MemberStatusLog::logAction(
                $userId,
                $biodata->id,
                'pending',
                'pending',
                'Pendaftaran disubmit',
                null // No admin yet
            );
        }

        return redirect()->route('dashboard')->with('success', 'Pendaftaran berhasil dikirim. Silakan tunggu konfirmasi dari admin.');
    }

    /**
     * Display the user's KTA.
     */
    public function showKta()
    {
        $biodata = Biodata::with('jurusan')->where('user_id', Auth::id())->first();

        // KTA hanya bisa diakses jika biodata sudah diisi.
        if ($biodata) {
            return view('template.kta')
                ->with('biodata', $biodata)
                ->with('jurusan', $biodata->jurusan->nama_jurusan);
        }

        // Jika belum mengisi biodata, kembalikan ke dashboard.
        return redirect()->route('dashboard')->with('error', 'Anda harus melengkapi biodata terlebih dahulu untuk melihat KTA.');
    }

    public function getBiodataByID()
    {
        return Biodata::with('jurusan')->where('user_id', Auth::id())->first();
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
        // Validasi dulu data yang masuk
        $request->validate([
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|string',
            'no_telepon' => 'required|string|max:15',
            'jurusan_id' => 'required|exists:jurusans,id',
            'tahun_angkatan' => 'required|integer',
            'alamat' => 'required|string',
            'riwayat_penyakit' => 'required|string',
            'pas_foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // maksimal 2MB
        ]);

        // Ambil data biodata sesuai user saat ini
        $biodata = Biodata::where('user_id', Auth::id())->firstOrFail();

        // Update field yang diinputkan
        $biodata->tanggal_lahir = $request->tanggal_lahir;
        $biodata->jenis_kelamin = $request->jenis_kelamin;
        $biodata->no_telepon = $request->no_telepon;
        $biodata->jurusan_id = $request->jurusan_id;
        $biodata->tahun_angkatan = $request->tahun_angkatan;
        $biodata->alamat = $request->alamat;
        $biodata->riwayat_penyakit = $request->riwayat_penyakit;

        // Jika ada file foto yang diupload baru
        if ($request->hasFile('pas_foto')) {
            // Hapus foto lama jika ada
            if ($biodata->pas_foto_url && Storage::exists($biodata->pas_foto_url)) {
                Storage::delete($biodata->pas_foto_url);
            }

            // Simpan foto baru ke storage dan dapatkan path
            $path = $request->file('pas_foto')->store('pas_foto', 'public');

            // Simpan path foto baru ke database
            $biodata->pas_foto_url = $path;
        }

        // Simpan perubahan
        $biodata->save();

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Data biodata berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Biodata $biodata)
    {
        //
    }
}
