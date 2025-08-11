<?php

namespace App\Http\Controllers;

use App\Models\Absen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AbsenController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->query('tanggal', now()->toDateString());

        $eligibleUsers = User::with(['role', 'biodata'])
            ->whereHas('role', function ($q) {
                $q->whereIn('nama_role', ['Junior', 'Senior', 'Calon Junior']);
            })
            ->join('biodatas', 'users.id', '=', 'biodatas.user_id')
            ->orderBy('biodatas.no_kta', 'asc')
            ->select('users.*')
            ->get();

        $existing = Absen::whereDate('tanggal', $tanggal)->get()->keyBy('user_id');

        return view('admin.absen.index', [
            'tanggal' => $tanggal,
            'eligibleUsers' => $eligibleUsers,
            'existing' => $existing,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'absen' => ['array'],
            'absen.*' => ['in:on,1,true'],
        ]);

        $tanggal = $validated['tanggal'];
        $absenInput = $validated['absen'] ?? [];

        $eligibleUserIds = User::whereHas('role', function ($q) {
            $q->whereIn('nama_role', ['Junior', 'Senior', 'Calon Junior']);
        })->pluck('id');

        DB::transaction(function () use ($tanggal, $absenInput, $eligibleUserIds): void {
            foreach ($eligibleUserIds as $userId) {
                $hadir = array_key_exists((string) $userId, $absenInput);
                Absen::updateOrCreate(
                    ['tanggal' => $tanggal, 'user_id' => $userId],
                    ['hadir' => $hadir, 'created_by' => Auth::id()]
                );
            }
        });

        return redirect()->route('absen.index', ['tanggal' => $tanggal])->with('success', 'Absen tersimpan.');
    }
}


