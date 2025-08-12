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
            // Ambil data kalender untuk bulan ini atau bulan yang dipilih
            $currentMonth = request('month', now()->month);
            $currentYear = request('year', now()->year);
            
            // Ambil semua acara yang overlap dengan bulan yang dipilih (mendukung multi-hari)
            $monthStart = now()->setYear($currentYear)->setMonth($currentMonth)->startOfMonth();
            $monthEnd = (clone $monthStart)->endOfMonth();
            $calendarEvents = \App\Models\Acara::whereRaw(
                'COALESCE(waktu_mulai, tanggal) <= ? AND COALESCE(waktu_selesai, COALESCE(waktu_mulai, tanggal)) >= ?',
                [$monthEnd, $monthStart]
            )
            ->orderByRaw('COALESCE(waktu_mulai, tanggal) asc')
            ->get();

            // Statistik penilaian user saat ini (rata-rata 1-5)
            $agg = \App\Models\AcaraPenilaian::where('user_id', Auth::id())
                ->selectRaw('AVG(NULLIF(fisik,0)) as avg_fisik')
                ->selectRaw('AVG(NULLIF(kepedulian,0)) as avg_kepedulian')
                ->selectRaw('AVG(NULLIF(tanggung_jawab,0)) as avg_tanggung_jawab')
                ->selectRaw('AVG(NULLIF(disiplin,0)) as avg_disiplin')
                ->selectRaw('AVG(NULLIF(kerjasama,0)) as avg_kerjasama')
                ->selectRaw('COUNT(*) as assessments_count')
                ->first();

            $averages = [
                'avg_fisik' => $agg?->avg_fisik !== null ? (float) $agg->avg_fisik : null,
                'avg_kepedulian' => $agg?->avg_kepedulian !== null ? (float) $agg->avg_kepedulian : null,
                'avg_tanggung_jawab' => $agg?->avg_tanggung_jawab !== null ? (float) $agg->avg_tanggung_jawab : null,
                'avg_disiplin' => $agg?->avg_disiplin !== null ? (float) $agg->avg_disiplin : null,
                'avg_kerjasama' => $agg?->avg_kerjasama !== null ? (float) $agg->avg_kerjasama : null,
            ];
            $nonNull = array_values(array_filter($averages, fn($v) => $v !== null));
            $avg_overall = count($nonNull) ? array_sum($nonNull) / count($nonNull) : null;
            $userStats = array_merge($averages, [
                'avg_overall' => $avg_overall,
                'assessments_count' => (int) ($agg->assessments_count ?? 0),
            ]);

            // Statistik kehadiran berdasarkan acara wajib
            $userId = Auth::id();
            $requiredMonth = \App\Models\Acara::whereHas('wajibHadir', function($q) use ($userId) {
                    $q->where('users.id', $userId);
                })
                ->where('selesai', true)
                ->whereBetween('tanggal', [$monthStart, $monthEnd])
                ->orderBy('tanggal', 'asc')
                ->get(['id','nama','tanggal','waktu_mulai','waktu_selesai']);

            $requiredIds = $requiredMonth->pluck('id');
            $presentCount = \App\Models\AcaraAbsen::whereIn('acara_id', $requiredIds)
                ->where('user_id', $userId)
                ->where('hadir', true)
                ->count();
            $totalRequired = $requiredMonth->count();
            $absentCount = max($totalRequired - $presentCount, 0);
            $attendancePercent = $totalRequired > 0 ? round(($presentCount / $totalRequired) * 100) : null;
            // Statistik kehadiran total (keseluruhan waktu)
            $requiredAll = \App\Models\Acara::whereHas('wajibHadir', function($q) use ($userId) {
                    $q->where('users.id', $userId);
                })
                ->where('selesai', true)
                ->orderBy('tanggal', 'asc')
                ->get(['id']);

            $requiredAllIds = $requiredAll->pluck('id');
            $presentAll = \App\Models\AcaraAbsen::whereIn('acara_id', $requiredAllIds)
                ->where('user_id', $userId)
                ->where('hadir', true)
                ->count();
            $totalAll = $requiredAll->count();
            $percentAll = $totalAll > 0 ? round(($presentAll / $totalAll) * 100) : null;

            $attendanceStats = [
                'present' => $presentCount,
                'absent' => $absentCount,
                'total' => $totalRequired,
                'percent' => $attendancePercent,
                'overall_present' => $presentAll,
                'overall_total' => $totalAll,
                'overall_percent' => $percentAll,
            ];

            // Riwayat terakhir absensi (10 terakhir, acara wajib)
            $recentRequired = \App\Models\Acara::whereHas('wajibHadir', function($q) use ($userId) {
                    $q->where('users.id', $userId);
                })
                ->where('selesai', true)
                ->where('tanggal', '<=', now())
                ->orderBy('tanggal', 'desc')
                ->take(10)
                ->get(['id','nama','tanggal','waktu_mulai','waktu_selesai']);

            $recentIds = $recentRequired->pluck('id');
            $absenMap = \App\Models\AcaraAbsen::whereIn('acara_id', $recentIds)
                ->where('user_id', $userId)
                ->pluck('hadir', 'acara_id');

            $attendanceHistory = $recentRequired->map(function($a) use ($absenMap) {
                $start = $a->waktu_mulai ?? $a->tanggal;
                $hadir = (bool) ($absenMap[$a->id] ?? false);
                return [
                    'nama' => $a->nama,
                    'tanggal' => $start,
                    'hadir' => $hadir,
                ];
            });

            // Jika role bukan Calon Junior atau Junior, tampilkan ringkasan untuk anggota CJ/Junior
            $roleName = optional(Auth::user()->role)->nama_role;
            $isJuniorOrCJ = in_array(strtolower($roleName ?? ''), ['junior', 'calon junior']);
            $membersSummary = collect();

            if (!$isJuniorOrCJ) {
                $members = \App\Models\User::with(['role', 'biodata'])
                    ->whereHas('role', function($q) { $q->whereIn('nama_role', ['Junior', 'Calon Junior']); })
                    ->get();

                $membersSummary = $members->map(function($u) use ($monthStart, $monthEnd) {
                    $uid = $u->id;
                    $agg = \App\Models\AcaraPenilaian::where('user_id', $uid)
                        ->selectRaw('AVG(NULLIF(fisik,0)) as avg_fisik')
                        ->selectRaw('AVG(NULLIF(kepedulian,0)) as avg_kepedulian')
                        ->selectRaw('AVG(NULLIF(tanggung_jawab,0)) as avg_tanggung_jawab')
                        ->selectRaw('AVG(NULLIF(disiplin,0)) as avg_disiplin')
                        ->selectRaw('AVG(NULLIF(kerjasama,0)) as avg_kerjasama')
                        ->selectRaw('COUNT(*) as assessments_count')
                        ->first();

                    $averages = [
                        'avg_fisik' => $agg?->avg_fisik !== null ? (float) $agg->avg_fisik : null,
                        'avg_kepedulian' => $agg?->avg_kepedulian !== null ? (float) $agg->avg_kepedulian : null,
                        'avg_tanggung_jawab' => $agg?->avg_tanggung_jawab !== null ? (float) $agg->avg_tanggung_jawab : null,
                        'avg_disiplin' => $agg?->avg_disiplin !== null ? (float) $agg->avg_disiplin : null,
                        'avg_kerjasama' => $agg?->avg_kerjasama !== null ? (float) $agg->avg_kerjasama : null,
                    ];
                    $nonNull = array_values(array_filter($averages, fn($v) => $v !== null));
                    $avg_overall = count($nonNull) ? array_sum($nonNull) / count($nonNull) : null;

                    // Kehadiran bulan ini
                    $requiredMonth = \App\Models\Acara::whereHas('wajibHadir', function($q) use ($uid) {
                            $q->where('users.id', $uid);
                        })
                        ->where('selesai', true)
                        ->whereBetween('tanggal', [$monthStart, $monthEnd])
                        ->get(['id']);
                    $requiredMonthIds = $requiredMonth->pluck('id');
                    $presentMonth = \App\Models\AcaraAbsen::whereIn('acara_id', $requiredMonthIds)
                        ->where('user_id', $uid)
                        ->where('hadir', true)
                        ->count();
                    $totalMonth = $requiredMonth->count();
                    $percentMonth = $totalMonth > 0 ? round(($presentMonth / $totalMonth) * 100) : null;

                    // Kehadiran total
                    $requiredAll = \App\Models\Acara::whereHas('wajibHadir', function($q) use ($uid) {
                            $q->where('users.id', $uid);
                        })
                        ->where('selesai', true)
                        ->get(['id']);
                    $requiredAllIds = $requiredAll->pluck('id');
                    $presentAll = \App\Models\AcaraAbsen::whereIn('acara_id', $requiredAllIds)
                        ->where('user_id', $uid)
                        ->where('hadir', true)
                        ->count();
                    $totalAll = $requiredAll->count();
                    $percentAll = $totalAll > 0 ? round(($presentAll / $totalAll) * 100) : null;

                    return [
                        'id' => $u->id,
                        'name' => $u->name,
                        'role' => optional($u->role)->nama_role,
                        'avg_overall' => $avg_overall,
                        'avg' => $averages,
                        'assessments_count' => (int) ($agg->assessments_count ?? 0),
                        'attendance_month' => [
                            'present' => $presentMonth,
                            'total' => $totalMonth,
                            'percent' => $percentMonth,
                        ],
                        'attendance_overall' => [
                            'present' => $presentAll,
                            'total' => $totalAll,
                            'percent' => $percentAll,
                        ],
                    ];
                });
            }

            return view('dashboard')
                ->with('status', 'active')
                ->with('biodata', $biodata)
                ->with('calendarEvents', $calendarEvents)
                ->with('currentMonth', $currentMonth)
                ->with('currentYear', $currentYear)
                ->with('userStats', $userStats)
                ->with('attendanceStats', $attendanceStats)
                ->with('attendanceHistory', $attendanceHistory)
                ->with('showMembersSummary', !$isJuniorOrCJ)
                ->with('membersSummary', $membersSummary);
        }

        // Jika biodata sudah diisi tapi belum aktif, tampilkan status pending.
        return view('dashboard')
            ->with('status', 'pending') // Untuk menampilkan status pending
            ->with('biodata', $biodata)
            ->with('jurusan', $biodata->jurusan->nama_jurusan);
    }

    /**
     * Render partial kalender untuk navigasi bulan via AJAX.
     */
    public function calendar(Request $request)
    {
        $currentMonth = (int) $request->get('month', now()->month);
        $currentYear = (int) $request->get('year', now()->year);

        $monthStart = now()->setYear($currentYear)->setMonth($currentMonth)->startOfMonth();
        $monthEnd = (clone $monthStart)->endOfMonth();
        $calendarEvents = \App\Models\Acara::whereRaw(
            'COALESCE(waktu_mulai, tanggal) <= ? AND COALESCE(waktu_selesai, COALESCE(waktu_mulai, tanggal)) >= ?',
            [$monthEnd, $monthStart]
        )
        ->orderByRaw('COALESCE(waktu_mulai, tanggal) asc')
        ->get();

        // Kembalikan hanya komponen kalender tanpa assets (style/script) untuk diinject via JS
        return view('components.calendar', [
            'events' => $calendarEvents,
            'currentMonth' => $currentMonth,
            'currentYear' => $currentYear,
            'withAssets' => true,
        ]);
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
