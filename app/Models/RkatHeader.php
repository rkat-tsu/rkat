<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // <-- PENTING: Import BelongsTo

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

    // Relasi ke Unit
    public function unit(): BelongsTo // <-- Type hint yang benar
    {
        return $this->belongsTo(Unit::class, 'id_unit', 'id_unit');
    }

    // Relasi ke Program Kerja (Asumsi model ProgramKerja ada)
    public function programKerja(): BelongsTo 
    {
        return $this->belongsTo(ProgramKerja::class, 'id_proker', 'id_proker');
    }
}