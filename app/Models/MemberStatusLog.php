<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberStatusLog extends Model
{
    protected $fillable = [
        'user_id',
        'biodata_id', 
        'action',
        'status',
        'reason',
        'admin_name',
        'admin_id',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function biodata()
    {
        return $this->belongsTo(Biodata::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // Helper methods
    public static function logAction($userId, $biodataId, $action, $status, $reason = null, $adminId = null)
    {
        $admin = $adminId ? User::find($adminId) : null;

        // Prevent duplicate logs for same action/status within a short time window
        $recentDuplicate = self::where([
                'user_id' => $userId,
                'biodata_id' => $biodataId,
                'action' => $action,
                'status' => $status,
            ])
            ->where('created_at', '>=', now()->subMinutes(1))
            ->first();

        if ($recentDuplicate) {
            return $recentDuplicate;
        }

        return self::create([
            'user_id' => $userId,
            'biodata_id' => $biodataId,
            'action' => $action,
            'status' => $status,
            'reason' => $reason,
            'admin_name' => $admin ? $admin->name : null,
            'admin_id' => $adminId,
        ]);
    }

    public function getActionLabelAttribute()
    {
        return match($this->action) {
            'pending' => 'Menunggu Persetujuan',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'activated' => 'Diaktifkan',
            'deactivated' => 'Dinonaktifkan',
            default => ucfirst($this->action)
        };
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'active' => 'Aktif',
            'inactive' => 'Tidak Aktif',
            'rejected' => 'Ditolak',
            'pending' => 'Menunggu',
            default => ucfirst($this->status)
        };
    }
}
