<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcaraPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'acara_id', 'path', 'caption', 'uploaded_by',
    ];

    public function acara()
    {
        return $this->belongsTo(Acara::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}


