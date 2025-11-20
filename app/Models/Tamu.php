<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tamu extends Model
{
    use HasFactory;

    protected $table = 'tamus'; // Opsional, Laravel otomatis mendeteksi jamak, tapi aman ditulis.

    protected $fillable = [
        'nama',
        'instansi',
        'no_hp',
        'id_divisi',   // FK ke Divisi
        'id_status',   // FK ke Status
        'id_layanan',  // FK ke Layanan
        'keperluan',
        'qr_code',
    ];

    /**
     * Relasi ke tabel referensi (Parent)
     */

    // Tamu mengunjungi satu Divisi
    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'id_divisi');
    }

    // Tamu memiliki satu Status saat ini
    public function status()
    {
        return $this->belongsTo(Status::class, 'id_status');
    }

    // Tamu menggunakan satu Layanan
    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'id_layanan');
    }

    /**
     * Relasi ke tabel aktivitas (Children)
     */

    // Tamu memberikan satu Rating (One-to-One)
    public function rating()
    {
        return $this->hasOne(Rating::class, 'id_tamu');
    }

    // Tamu memiliki banyak history scan (One-to-Many)
    public function scanLogs()
    {
        return $this->hasMany(ScanLog::class, 'id_tamu');
    }
}
