<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acara extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'deskripsi',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'lokasi',
        'seragam',
        'perlengkapan',
        'created_by',
        'selesai',
        'feedback',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'perlengkapan' => 'array',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function wajibHadir()
    {
        return $this->belongsToMany(User::class, 'acara_wajib_users');
    }

    public function absens()
    {
        return $this->hasMany(AcaraAbsen::class);
    }

    public function photos()
    {
        return $this->hasMany(AcaraPhoto::class);
    }

    /**
     * Cek apakah acara sudah dimulai
     */
    public function hasStarted()
    {
        $start = $this->waktu_mulai ?: $this->tanggal;
        return $start && $start <= now();
    }

    /**
     * Cek apakah acara belum dimulai
     */
    public function hasNotStarted()
    {
        return !$this->hasStarted();
    }

    /**
     * Cek apakah acara sudah berakhir berdasarkan waktu_selesai
     */
    public function hasEnded(): bool
    {
        return $this->waktu_selesai ? $this->waktu_selesai <= now() : false;
    }

    /**
     * Cek apakah bisa melakukan absen (acara sudah dimulai)
     */
    public function canTakeAttendance()
    {
        return $this->hasStarted();
    }

    /**
     * Cek apakah bisa upload foto (acara sudah dimulai)
     */
    public function canUploadPhoto()
    {
        return $this->hasStarted();
    }

    /**
     * Cek apakah bisa memberikan feedback (acara sudah dimulai)
     */
    public function canGiveFeedback()
    {
        return $this->hasStarted();
    }
}


