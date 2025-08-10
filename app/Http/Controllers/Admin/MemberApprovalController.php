<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MemberApprovalController extends Controller
{
    /**
     * Display pending members for approval
     */
    public function index()
    {
        $pendingMembers = \App\Models\Biodata::with(['user', 'jurusan'])
            ->where('is_active', false)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.members.index', compact('pendingMembers'));
    }

    /**
     * Show member detail for approval
     */
    public function show(\App\Models\Biodata $biodata)
    {
        $biodata->load(['user', 'jurusan']);
        return view('admin.members.show', compact('biodata'));
    }

    /**
     * Approve member
     */
    public function approve(\App\Models\Biodata $biodata)
    {
        $wasActive = (bool) $biodata->is_active;
        if (!$wasActive) {
            $biodata->update(['is_active' => true]);
            
            // Log the approval action (only when state changes)
            \App\Models\MemberStatusLog::logAction(
                $biodata->user_id,
                $biodata->id,
                'approved',
                'active',
                'Pendaftaran disetujui oleh admin',
                auth()->id()
            );
        }
        
        return redirect()->route('admin.members.index')
            ->with('success', 'Anggota ' . $biodata->nama_lengkap . ' berhasil disetujui dan diaktifkan.');
    }

    /**
     * Show reject form
     */
    public function showRejectForm(\App\Models\Biodata $biodata)
    {
        return view('admin.members.reject', compact('biodata'));
    }

    /**
     * Reject member with reason
     */
    public function reject(\App\Models\Biodata $biodata)
    {
        $reason = request('reason', 'Tidak memenuhi syarat');
        $memberName = $biodata->nama_lengkap;
        $userId = $biodata->user_id;
        
        // Log the rejection before deleting
        \App\Models\MemberStatusLog::logAction(
            $userId,
            $biodata->id,
            'rejected',
            'rejected',
            $reason,
            auth()->id()
        );
        
        // Delete biodata but keep user account
        $biodata->delete();
        
        return redirect()->route('admin.members.index')
            ->with('success', 'Pendaftaran anggota ' . $memberName . ' berhasil ditolak dengan alasan: ' . $reason);
    }

    /**
     * Bulk approve multiple members
     */
    public function bulkApprove()
    {
        $memberIds = request('member_ids', []);
        
        if (empty($memberIds)) {
            return redirect()->route('admin.members.index')
                ->with('error', 'Pilih minimal satu anggota untuk disetujui.');
        }
        
        // Get biodatas that will be updated
        $biodatas = \App\Models\Biodata::whereIn('id', $memberIds)
            ->where('is_active', false)
            ->get();
        
        if ($biodatas->isEmpty()) {
            return redirect()->route('admin.members.index')
                ->with('error', 'Tidak ada anggota yang dapat disetujui.');
        }
        
        // Update each biodata and log the action (idempotent)
        $updatedCount = 0;
        foreach ($biodatas as $biodata) {
            if (!$biodata->is_active) {
                $biodata->update(['is_active' => true]);
                
                // Log the approval action for each member
                \App\Models\MemberStatusLog::logAction(
                    $biodata->user_id,
                    $biodata->id,
                    'approved',
                    'active',
                    'Pendaftaran disetujui secara batch oleh admin',
                    auth()->id()
                );
                $updatedCount++;
            }
        }
        
        $updated = $updatedCount;
        return redirect()->route('admin.members.index')
            ->with('success', $updated . ' anggota berhasil disetujui secara batch.');
    }

    /**
     * Show active members
     */
    public function activeMembers()
    {
        $activeMembers = \App\Models\Biodata::with(['user', 'jurusan'])
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('admin.members.active', compact('activeMembers'));
    }

    /**
     * Show deactivate form
     */
    public function showDeactivateForm(\App\Models\Biodata $biodata)
    {
        return view('admin.members.deactivate', compact('biodata'));
    }

    /**
     * Deactivate member with reason
     */
    public function deactivate(\App\Models\Biodata $biodata)
    {
        $reason = request('reason', 'Tidak aktif dalam kegiatan');

        $memberName = $biodata->nama_lengkap;

        // Only act when currently active (log once)
        if ($biodata->is_active) {
            $biodata->update(['is_active' => false]);

            // Log the deactivation
            \App\Models\MemberStatusLog::logAction(
                $biodata->user_id,
                $biodata->id,
                'deactivated',
                'inactive',
                $reason,
                auth()->id()
            );
        }

        // Remove biodata after deactivation (anggota keluar)
        $biodata->delete();

        return redirect()->route('admin.members.active')
            ->with('success', 'Anggota ' . $memberName . ' dinonaktifkan dan biodata telah dihapus. Alasan: ' . $reason);
    }
}
