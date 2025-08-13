<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    public function create()
    {
        $terlapors = User::with('biodata', 'role')
            ->whereHas('role', function ($q) {
                $q->whereIn('nama_role', ['Calon Junior', 'Junior', 'Senior']);
            })
            ->where('super_admin', false)
            ->orderBy('name')
            ->get();
        return view('complaints.create', compact('terlapors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pelapor' => ['required', 'string', 'max:255'],
            'judul' => ['required', 'string', 'max:255'],
            'terlapor_user_id' => ['required', 'integer', 'exists:users,id'],
            'deskripsi' => ['required', 'string', 'max:5000'],
            'bukti' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
        ]);

        $path = null;
        if ($request->hasFile('bukti')) {
            $path = $request->file('bukti')->store('complaints', 'public');
        }

        Complaint::create([
            'nama_pelapor' => $validated['nama_pelapor'],
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'],
            'terlapor_user_id' => $validated['terlapor_user_id'],
            'bukti_path' => $path,
            'status' => 'baru',
        ]);

        return redirect()->route('complaints.thanks');
    }

    public function thanks()
    {
        return view('complaints.thanks');
    }

    public function index()
    {
        $complaints = Complaint::with(['terlapor.role'])->orderBy('created_at', 'desc')->paginate(15);
        return view('complaints.index', compact('complaints'));
    }

    public function show(Complaint $complaint)
    {
        $complaint->load(['terlapor.role', 'terlapor.biodata']);
        $auth = Auth::user();
        $isAuthorizedRole = $auth && $auth->role && in_array(strtolower($auth->role->nama_role), ['pembina','pelatih','senior']);
        $isTerlapor = $auth && $auth->id === $complaint->terlapor_user_id;
        if (!($isAuthorizedRole || $isTerlapor)) {
            abort(403, 'Anda tidak berhak melihat komplain ini.');
        }
        $maskPelapor = $isTerlapor && !$isAuthorizedRole;
        return view('complaints.show', compact('complaint', 'maskPelapor'));
    }

    // Hapus fitur selesai sesuai permintaan, digantikan dengan delete

    public function saveFollowUp(Request $request, Complaint $complaint)
    {
        $this->authorizeSeniorTrainerGuardian();
        $validated = $request->validate([
            'follow_up' => ['required', 'string', 'max:5000'],
        ]);
        $complaint->follow_up = $validated['follow_up'];
        $complaint->follow_up_by_user_id = Auth::id();
        $complaint->follow_up_at = now();
        $complaint->save();
        return redirect()->route('complaints.show', $complaint)->with('success', 'Tindak lanjut komplain tersimpan.');
    }

    public function destroy(Complaint $complaint)
    {
        $this->authorizeSeniorTrainerGuardian();
        $complaint->delete();
        return redirect()->route('complaints.index')->with('success', 'Laporan komplain telah dihapus.');
    }

    public function markDone(Complaint $complaint)
    {
        $this->authorizeSeniorTrainerGuardian();
        if ($complaint->status !== 'selesai') {
            $complaint->status = 'selesai';
            $complaint->save();
        }
        return redirect()->route('complaints.show', $complaint)->with('success', 'Komplain telah ditandai selesai.');
    }

    private function authorizeSeniorTrainerGuardian(): void
    {
        if (!Auth::check()) abort(403);
        $r = optional(Auth::user()->role)->nama_role;
        if (!in_array(strtolower($r ?? ''), ['pembina','pelatih','senior'])) abort(403);
    }
}


