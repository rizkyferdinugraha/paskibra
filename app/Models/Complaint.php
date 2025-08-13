<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_pelapor',
        'judul',
        'deskripsi',
        'terlapor_user_id',
        'bukti_path',
        'status',
        'follow_up',
        'follow_up_by_user_id',
        'follow_up_at',
    ];

    protected function casts(): array
    {
        return [
            'follow_up_at' => 'datetime',
        ];
    }

    public function terlapor()
    {
        return $this->belongsTo(User::class, 'terlapor_user_id');
    }

    public function followUpBy()
    {
        return $this->belongsTo(User::class, 'follow_up_by_user_id');
    }
}


