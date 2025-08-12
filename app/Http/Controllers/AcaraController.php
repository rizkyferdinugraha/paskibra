<?php

namespace App\Http\Controllers;

use App\Models\Acara;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class AcaraController extends Controller
{
    public function index()
    {
        // Auto-finish: tandai selesai jika sudah melewati waktu_selesai
        \App\Models\Acara::whereNotNull('waktu_selesai')
            ->where('selesai', false)
            ->where('waktu_selesai', '<=', now())
            ->update(['selesai' => true]);

        $acaras = Acara::with(['wajibHadir'])->orderBy('tanggal', 'desc')->paginate(15);
        return view('admin.acara.index', compact('acaras'));
    }

    public function create()
    {
        $eligibleUsers = User::with(['role', 'biodata'])
            ->whereHas('role', function ($q) {
                $q->whereIn('nama_role', ['Junior', 'Senior', 'Calon Junior']);
            })
            ->join('biodatas', 'users.id', '=', 'biodatas.user_id')
            ->orderBy('biodatas.no_kta', 'asc')
            ->select('users.*')
            ->get();
        return view('admin.acara.form', [
            'acara' => new Acara(),
            'eligibleUsers' => $eligibleUsers,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'tanggal_date' => ['required', 'date_format:Y-m-d'],
            'waktu_mulai' => ['required', 'date_format:H:i'],
            'tanggal_selesai_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:tanggal_date'],
            'waktu_selesai' => ['required', 'date_format:H:i'],
            'lokasi' => ['nullable', 'string', 'max:255'],
            'seragam' => ['nullable', 'string', 'max:255'],
            'perlengkapan' => ['array'],
            'perlengkapan.*' => ['string', 'max:255'],
            'wajib_hadir' => ['array'],
            'wajib_hadir.*' => ['integer', 'exists:users,id'],
        ]);

        $start = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $validated['tanggal_date'].' '.$validated['waktu_mulai']);
        $end = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $validated['tanggal_selesai_date'].' '.$validated['waktu_selesai']);
        if ($end->lte($start)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'waktu_selesai' => 'Waktu selesai harus setelah waktu mulai.'
            ]);
        }

        DB::transaction(function () use ($validated, $start, $end): void {
            $acara = Acara::create([
                'nama' => $validated['nama'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                // Simpan kolom legacy 'tanggal' sebagai waktu mulai untuk kompatibilitas
                'tanggal' => $start,
                'waktu_mulai' => $start,
                'waktu_selesai' => $end,
                'lokasi' => $validated['lokasi'] ?? null,
                'seragam' => $validated['seragam'] ?? null,
                'perlengkapan' => $validated['perlengkapan'] ?? [],
                'created_by' => Auth::id(),
            ]);
            $acara->wajibHadir()->sync($validated['wajib_hadir'] ?? []);
        });

        return redirect()->route('acara.index')->with('success', 'Acara berhasil dibuat.');
    }

    public function edit(Acara $acara)
    {
        $eligibleUsers = User::with(['role', 'biodata'])
            ->whereHas('role', function ($q) {
                $q->whereIn('nama_role', ['Junior', 'Senior', 'Calon Junior']);
            })
            ->join('biodatas', 'users.id', '=', 'biodatas.user_id')
            ->orderBy('biodatas.no_kta', 'asc')
            ->select('users.*')
            ->get();
        return view('admin.acara.form', compact('acara', 'eligibleUsers'));
    }

    public function update(Request $request, Acara $acara)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'tanggal_date' => ['required', 'date_format:Y-m-d'],
            'waktu_mulai' => ['required', 'date_format:H:i'],
            'tanggal_selesai_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:tanggal_date'],
            'waktu_selesai' => ['required', 'date_format:H:i'],
            'lokasi' => ['nullable', 'string', 'max:255'],
            'seragam' => ['nullable', 'string', 'max:255'],
            'perlengkapan' => ['array'],
            'perlengkapan.*' => ['string', 'max:255'],
            'wajib_hadir' => ['array'],
            'wajib_hadir.*' => ['integer', 'exists:users,id'],
        ]);

        $start = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $validated['tanggal_date'].' '.$validated['waktu_mulai']);
        $end = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $validated['tanggal_selesai_date'].' '.$validated['waktu_selesai']);
        if ($end->lte($start)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'waktu_selesai' => 'Waktu selesai harus setelah waktu mulai.'
            ]);
        }

        DB::transaction(function () use ($validated, $acara, $start, $end): void {
            $acara->update([
                'nama' => $validated['nama'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'tanggal' => $start,
                'waktu_mulai' => $start,
                'waktu_selesai' => $end,
                'lokasi' => $validated['lokasi'] ?? null,
                'seragam' => $validated['seragam'] ?? null,
                'perlengkapan' => $validated['perlengkapan'] ?? [],
            ]);
            $acara->wajibHadir()->sync($validated['wajib_hadir'] ?? []);
        });

        return redirect()->route('acara.index')->with('success', 'Acara berhasil diperbarui.');
    }

    public function destroy(Acara $acara)
    {
        $acara->delete();
        return redirect()->route('acara.index')->with('success', 'Acara berhasil dihapus.');
    }

    public function show(Acara $acara)
    {
        // Load data dengan urutan berdasarkan nomor KTA
        $acara->load([
            'wajibHadir' => function($query) {
                $query->join('biodatas', 'users.id', '=', 'biodatas.user_id')
                      ->orderBy('biodatas.no_kta', 'asc')
                      ->select('users.*');
            }, 
            'absens.user.biodata', 
            'photos'
        ]);
        
        // Build ringkasan hadir/tidak hadir
        $wajibIds = $acara->wajibHadir->pluck('id');
        $absenMap = $acara->absens->keyBy('user_id');
        $hadir = [];
        $tidakHadir = [];
        foreach ($wajibIds as $uid) {
            $record = $absenMap->get($uid);
            if ($record && $record->hadir) {
                $hadir[] = $record->user;
            } else {
                $user = $acara->wajibHadir->firstWhere('id', $uid);
                if ($user) $tidakHadir[] = $user;
            }
        }

        // Ambil penilaian yang sudah ada oleh senior ini (jika ada)
        $existingGrades = [];
        $authUser = Auth::user();
        if ($authUser && $authUser->role && strcasecmp($authUser->role->nama_role, 'Senior') === 0) {
            $existingGrades = \App\Models\AcaraPenilaian::where('acara_id', $acara->id)
                ->where('graded_by', Auth::id())
                ->get()
                ->keyBy('user_id');
        }

        return view('admin.acara.show', compact('acara', 'hadir', 'tidakHadir', 'existingGrades'));
    }

    public function saveGrades(Request $request, Acara $acara)
    {
        $this->authorizeSenior();
        // Hanya saat acara sudah dimulai
        if ($acara->hasNotStarted()) {
            return back()->with('error', 'Penilaian hanya dapat dilakukan setelah acara dimulai.');
        }

        $payload = $request->validate([
            'grades' => ['array'],
            'grades.*.fisik' => ['nullable', 'integer', 'min:1', 'max:5'],
            'grades.*.kepedulian' => ['nullable', 'integer', 'min:1', 'max:5'],
            'grades.*.tanggung_jawab' => ['nullable', 'integer', 'min:1', 'max:5'],
            'grades.*.disiplin' => ['nullable', 'integer', 'min:1', 'max:5'],
            'grades.*.kerjasama' => ['nullable', 'integer', 'min:1', 'max:5'],
        ]);

        $grades = $payload['grades'] ?? [];

        // Hanya nilai peserta dengan role Calon Junior / Junior dan yang hadir
        $eligibleRoles = ['Calon Junior', 'Junior'];
        $wajibIds = $acara->wajibHadir()->pluck('users.id')->all();
        $absenHadir = $acara->absens()->where('hadir', true)->pluck('user_id')->all();

        foreach ($grades as $userId => $g) {
            if (!in_array((int)$userId, $absenHadir, true)) continue;
            if (!in_array((int)$userId, $wajibIds, true)) continue;
            $user = \App\Models\User::with('role')->find($userId);
            if (!$user || !$user->role || !in_array($user->role->nama_role, $eligibleRoles, true)) continue;

            // Skip jika semua nilai null
            $allNull = true;
            foreach (['fisik','kepedulian','tanggung_jawab','disiplin','kerjasama'] as $k) {
                if (isset($g[$k]) && $g[$k] !== null && $g[$k] !== '') { $allNull = false; break; }
            }
            if ($allNull) continue;

            \App\Models\AcaraPenilaian::updateOrCreate(
                [
                    'acara_id' => $acara->id,
                    'user_id' => $userId,
                    'graded_by' => Auth::id(),
                ],
                [
                    'fisik' => (int)($g['fisik'] ?? 0) ?: 0,
                    'kepedulian' => (int)($g['kepedulian'] ?? 0) ?: 0,
                    'tanggung_jawab' => (int)($g['tanggung_jawab'] ?? 0) ?: 0,
                    'disiplin' => (int)($g['disiplin'] ?? 0) ?: 0,
                    'kerjasama' => (int)($g['kerjasama'] ?? 0) ?: 0,
                ]
            );
        }

        return back()->with('success', 'Penilaian peserta berhasil disimpan.');
    }

    public function toggleSelesai(Acara $acara)
    {
        // Cek apakah acara sudah dimulai sebelum bisa ditandai selesai
        if ($acara->hasNotStarted()) {
            return back()->with('error', 'Tidak dapat mengubah status acara. Acara belum dimulai.');
        }
        
        $acara->update(['selesai' => !$acara->selesai]);
        return back()->with('success', 'Status acara diperbarui.');
    }

    public function saveAbsen(Request $request, Acara $acara)
    {
        $this->authorizeSenior();
        
        // Cek apakah acara sudah dimulai
        if ($acara->hasNotStarted()) {
            return back()->with('error', 'Tidak dapat melakukan absen. Acara belum dimulai.');
        }
        
        $validated = $request->validate([
            'absen' => ['array'],
            'absen.*' => ['in:on,1,true'],
        ]);
        $absenInput = $validated['absen'] ?? [];
        $wajibIds = $acara->wajibHadir()->pluck('users.id');
        DB::transaction(function () use ($acara, $wajibIds, $absenInput): void {
            foreach ($wajibIds as $userId) {
                $hadir = array_key_exists((string) $userId, $absenInput);
                \App\Models\AcaraAbsen::updateOrCreate(
                    ['acara_id' => $acara->id, 'user_id' => $userId],
                    ['hadir' => $hadir]
                );
            }
        });
        return back()->with('success', 'Absen acara disimpan.');
    }

    public function uploadPhoto(Request $request, Acara $acara)
    {
        $this->authorizeSenior();
        
        // Cek apakah acara sudah dimulai
        if ($acara->hasNotStarted()) {
            return back()->with('error', 'Tidak dapat upload foto. Acara belum dimulai.');
        }
        
        // Debug info lengkap
        Log::info('Upload attempt', [
            'photos_count' => $request->hasFile('photos') ? count($request->file('photos')) : 0,
            'php_upload_max_filesize' => ini_get('upload_max_filesize'),
            'php_post_max_size' => ini_get('post_max_size'),
            'php_memory_limit' => ini_get('memory_limit'),
            'request_content_length' => $_SERVER['CONTENT_LENGTH'] ?? 'unknown'
        ]);

        // Cek apakah ada file yang diupload
        if (!$request->hasFile('photos')) {
            Log::error('No photos received in request');
            return back()->with('error', 'Tidak ada foto yang diterima. Pastikan ukuran foto tidak melebihi batas server.');
        }

        // Validasi dengan pesan error yang lebih spesifik
        try {
            $request->validate([
                'photos' => ['required', 'array', 'min:1', 'max:20'],
                'photos.*' => ['required', 'file', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // Sesuaikan dengan PHP limit 2MB
            ], [
                'photos.*.max' => 'Ukuran foto terlalu besar. Maksimal 2MB per foto.',
                'photos.*.mimes' => 'Format foto harus JPEG, PNG, JPG, atau GIF.',
                'photos.max' => 'Maksimal 20 foto sekaligus.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);
            throw $e;
        }

        $uploaded = 0;
        $failed = 0;
        
        foreach ($request->file('photos') as $index => $photo) {
            Log::info("Processing photo {$index}", [
                'original_name' => $photo->getClientOriginalName(),
                'size' => $photo->getSize(),
                'mime_type' => $photo->getMimeType(),
                'is_valid' => $photo->isValid(),
                'error' => $photo->getError()
            ]);
            
            if ($photo->isValid()) {
                try {
                    // Kompres dan simpan foto
                    $path = $this->compressAndStore($photo, 'acara_photos');
                    
                    \App\Models\AcaraPhoto::create([
                        'acara_id' => $acara->id,
                        'path' => $path,
                        'uploaded_by' => Auth::id(),
                    ]);
                    $uploaded++;
                    
                    Log::info('Photo uploaded successfully', ['path' => $path]);
                } catch (\Exception $e) {
                    $failed++;
                    Log::error('Photo upload failed', [
                        'error' => $e->getMessage(),
                        'file' => $photo->getClientOriginalName()
                    ]);
                }
            } else {
                $failed++;
                Log::error('Invalid photo file', [
                    'file' => $photo->getClientOriginalName(),
                    'error_code' => $photo->getError()
                ]);
            }
        }
        
        $message = "{$uploaded} foto berhasil diunggah.";
        if ($failed > 0) {
            $message .= " {$failed} foto gagal diupload.";
        }
        
        return back()->with($uploaded > 0 ? 'success' : 'error', $message);
    }

    public function deletePhoto(\App\Models\AcaraPhoto $photo)
    {
        $this->authorizeSenior();
        
        // Hapus file dari storage
        if (Storage::disk('public')->exists($photo->path)) {
            Storage::disk('public')->delete($photo->path);
        }
        
        // Hapus record dari database
        $photo->delete();
        
        return back()->with('success', 'Foto berhasil dihapus.');
    }

    public function saveFeedback(Request $request, Acara $acara)
    {
        $this->authorizeSenior();
        
        // Cek apakah acara sudah dimulai
        if ($acara->hasNotStarted()) {
            return back()->with('error', 'Tidak dapat memberikan feedback. Acara belum dimulai.');
        }
        
        $validated = $request->validate([
            'feedback' => ['required', 'string', 'max:2000'],
        ]);
        
        $acara->update([
            'feedback' => $validated['feedback']
        ]);
        
        return back()->with('success', 'Feedback hasil kegiatan berhasil disimpan.');
    }

    private function compressAndStore($file, $directory)
    {
        // Generate unique filename
        $filename = uniqid() . '_' . time() . '.jpg';
        $path = $directory . '/' . $filename;
        $fullPath = storage_path('app/public/' . $path);
        
        // Pastikan direktori ada
        $dir = dirname($fullPath);
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }
        
        // Load dan kompres gambar
        $manager = new ImageManager(new Driver());
        $image = $manager->read($file->getPathname());
        
        // Resize jika terlalu besar (max width/height 1920px)
        if ($image->width() > 1920 || $image->height() > 1920) {
            $image->scale(width: 1920, height: 1920);
        }
        
        // Encode ke JPEG dengan kualitas 85% (good balance antara kualitas dan size)
        $encoded = $image->encodeByMediaType('image/jpeg', quality: 85);
        
        // Jika masih terlalu besar (>1MB), turunkan kualitas
        $quality = 85;
        while ($encoded->size() > 1048576 && $quality > 30) { // 1MB = 1048576 bytes
            $quality -= 10;
            $encoded = $image->encodeByMediaType('image/jpeg', quality: $quality);
        }
        
        // Simpan file
        file_put_contents($fullPath, $encoded->toString());
        
        Log::info('Image compressed', [
            'original_size' => $file->getSize(),
            'compressed_size' => filesize($fullPath),
            'quality' => $quality,
            'path' => $path
        ]);
        
        return $path;
    }

    private function authorizeSenior(): void
    {
        if (!\Illuminate\Support\Facades\Auth::check()) {
            abort(403, 'Hanya Senior yang dapat melakukan aksi ini.');
        }
        $user = \Illuminate\Support\Facades\Auth::user();
        if (!$user->role || strcasecmp($user->role->nama_role, 'Senior') !== 0) {
            abort(403, 'Hanya Senior yang dapat melakukan aksi ini.');
        }
    }
}


