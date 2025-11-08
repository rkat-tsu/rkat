<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // <-- PENTING: Import BelongsTo
use Illuminate\Database\Eloquent\Relations\HasMany;   // <-- PENTING: Import HasMany

class Unit extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_unit';
    protected $table = 'unit';
    protected $fillable = [
        'kode_unit',
        'nama_unit',
        'tipe_unit',
        'jalur_persetujuan', // <-- Pastikan ini ada di migrasi Anda
        'id_kepala',
        'parent_id',
    ];
    
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'parent_id', 'id_unit');
    }
    
    public function children(): HasMany // <-- Type hint yang benar
    {
        return $this->hasMany(Unit::class, 'parent_id', 'id_unit');
    }

    public function kepala(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_kepala', 'id_user');
    }
    
    public function rkatHeaders(): HasMany
    {
        return $this->hasMany(RkatHeader::class, 'id_unit', 'id_unit');
    }
}