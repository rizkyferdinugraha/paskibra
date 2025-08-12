<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcaraPenilaian extends Model
{
    use HasFactory;

    protected $fillable = [
        'acara_id',
        'user_id',
        'graded_by',
        'fisik',
        'kepedulian',
        'tanggung_jawab',
        'disiplin',
        'kerjasama',
    ];

    protected $casts = [
        'fisik' => 'integer',
        'kepedulian' => 'integer',
        'tanggung_jawab' => 'integer',
        'disiplin' => 'integer',
        'kerjasama' => 'integer',
    ];

    public function acara()
    {
        return $this->belongsTo(Acara::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function grader()
    {
        return $this->belongsTo(User::class, 'graded_by');
    }
}


