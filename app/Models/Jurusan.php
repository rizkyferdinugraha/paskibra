<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    //
    protected $fillable = [
        'nama_jurusan'
    ];

    public function biodatas()
    {
        return $this->hasMany(Biodata::class, 'jurusan_id');
    }
}
