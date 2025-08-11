<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcaraAbsen extends Model
{
    use HasFactory;

    protected $fillable = [
        'acara_id', 'user_id', 'hadir',
    ];

    protected $casts = [
        'hadir' => 'boolean',
    ];

    public function acara()
    {
        return $this->belongsTo(Acara::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


