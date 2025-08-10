<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MembershipStatusController extends Controller
{
    /**
     * Show membership status page
     */
    public function index()
    {
        $user = auth()->user();
        $biodata = $user->biodata;
        
        // Get status logs for this user
        $statusLogs = \App\Models\MemberStatusLog::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get current status
        $currentStatus = $this->getCurrentStatus($user, $biodata);
        $latestLog = $statusLogs->first();
        
        return view('membership.status', compact('user', 'biodata', 'statusLogs', 'currentStatus', 'latestLog'));
    }

    /**
     * Get current membership status
     */
    private function getCurrentStatus($user, $biodata)
    {
        if (!$biodata) {
            return [
                'status' => 'no_biodata',
                'label' => 'Belum Mendaftar',
                'description' => 'Anda belum mengisi biodata untuk mendaftar sebagai anggota.',
                'color' => 'secondary',
                'icon' => 'person-x'
            ];
        }

        if ($biodata->is_active) {
            return [
                'status' => 'active',
                'label' => 'Anggota Aktif',
                'description' => 'Anda adalah anggota aktif Paskibra.',
                'color' => 'success',
                'icon' => 'person-check'
            ];
        }

        // Check if rejected
        $latestLog = \App\Models\MemberStatusLog::where('user_id', $user->id)
            ->where('action', 'rejected')
            ->latest()
            ->first();
            
        if ($latestLog) {
            return [
                'status' => 'rejected',
                'label' => 'Pendaftaran Ditolak',
                'description' => 'Pendaftaran Anda ditolak.',
                'color' => 'danger',
                'icon' => 'person-x',
                'reason' => $latestLog->reason
            ];
        }

        // Check if deactivated
        $deactivatedLog = \App\Models\MemberStatusLog::where('user_id', $user->id)
            ->where('action', 'deactivated')
            ->latest()
            ->first();
            
        if ($deactivatedLog) {
            return [
                'status' => 'deactivated',
                'label' => 'Keanggotaan Dinonaktifkan',
                'description' => 'Keanggotaan Anda telah dinonaktifkan.',
                'color' => 'warning',
                'icon' => 'person-dash',
                'reason' => $deactivatedLog->reason
            ];
        }

        // Default pending
        return [
            'status' => 'pending',
            'label' => 'Menunggu Persetujuan',
            'description' => 'Pendaftaran Anda sedang dalam proses review oleh admin.',
            'color' => 'info',
            'icon' => 'clock'
        ];
    }
}
