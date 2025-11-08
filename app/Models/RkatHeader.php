<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; 
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RkatHeader extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'id_header';
    protected $table = 'rkat_headers';

    // Pastikan semua kolom yang diisi di Seeder dan Controller ada di $fillable
    protected $fillable = [
        'id_unit',
        'id_proker', // Pastikan kolom ini ada
        'tahun_anggaran',
        'status_persetujuan',
        'total_biaya', // Pastikan kolom ini ada
        'diajukan_oleh', // Pastikan kolom ini ada
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'datetime',
    ];
    
    // Relasi ke TahunAnggaran
    public function tahunAnggaran()
    {
        return $this->belongsTo(TahunAnggaran::class, 'tahun_anggaran', 'tahun_anggaran');
    }
    
    // Relasi ke User (yang mengajukan)
    public function pengaju()
    {
        // Asumsi model User Anda ada di App\Models\User dan primary key-nya id_user
        return $this->belongsTo(User::class, 'diajukan_oleh', 'id_user');
    }

    // Fungsi ini sekarang sudah benar karena 'use' statement di atas
    public function unit(): BelongsTo
    // Relasi ke Unit
    {
        return $this->belongsTo(Unit::class, 'id_unit', 'id_unit');
    }

    // Relasi ke Program Kerja (Asumsi model ProgramKerja ada)
    public function programKerja(): BelongsTo 
    {
        return $this->belongsTo(ProgramKerja::class, 'id_proker', 'id_proker');
    }
}