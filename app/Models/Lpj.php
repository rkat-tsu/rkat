<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lpj extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'lpj';

    protected $fillable = [
        'rkat_id',
        'judul',            // Tambahan (sesuai form UI)
        'tempat',           // Tempat Pelaksanaan
        'tanggal_mulai',
        'tanggal_selesai',
        'anggaran',
        'keterangan',       // keterangan tambahan (opsional)
        'file_lpj',         // Upload file PDF LPJ
        'dibuat_oleh',      // user ID pembuat LPJ
    ];

    protected $casts = [
        'tanggal_mulai'     => 'date',
        'tanggal_selesai'   => 'date',
        'anggaran'          => 'decimal:2'
    ];

    /**
     * Relasi ke RKAT (LPJ harus terkait RKAT)
     */
    public function rkat()
    {
        return $this->belongsTo(RkatHeader::class, 'rkat_id');
    }

    /**
     * Relasi ke User (pembuat LPJ)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    /**
     * Accessor: Format tanggal untuk UI
     */
    public function getTanggalMulaiFormattedAttribute()
    {
        return $this->tanggal_mulai?->format('d-m-Y');
    }

    public function getTanggalSelesaiFormattedAttribute()
    {
        return $this->tanggal_selesai?->format('d-m-Y');
    }

    /**
     * Range tanggal (UI friendly)
     */
    public function getTanggalRangeAttribute()
    {
        if (!$this->tanggal_mulai || !$this->tanggal_selesai) return null;
        return $this->tanggal_mulai->format('d M Y') . ' s/d ' . $this->tanggal_selesai->format('d M Y');
    }

    /**
     * Scope pencarian LPJ
     */
    public function scopeSearch($query, $keyword)
    {
        return $query->where('tempat', 'like', "%{$keyword}%")
            ->orWhere('judul', 'like', "%{$keyword}%")
            ->orWhere('keterangan', 'like', "%{$keyword}%")
            ->orWhereHas('rkat', function ($q) use ($keyword) {
                $q->where('nama_kegiatan', 'like', "%{$keyword}%");
            });
    }

    /**
     * Scope filter LPJ berdasarkan unit dari RKAT
     */
    public function scopeByUnit($query, $unitId)
    {
        return $query->whereHas('rkat', function ($q) use ($unitId) {
            $q->where('unit_id', $unitId);
        });
    }
}
