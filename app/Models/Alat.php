<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Alat extends Model
{
    use HasFactory;

    protected $table = 'alats';

    protected $primaryKey = 'id_alat';

    protected $fillable = [
        'kode_alat',
        'nama_alat',
        'id_kategori',
        'stok',
        'kondisi',
        'foto',
        'deskripsi',
    ];

    /**
     * Relasi ke Kategori.
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    /**
     * Relasi ke transaksi peminjaman alat ini.
     */
    public function peminjamans(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'id_alat', 'id_alat');
    }

    /**
     * Cek apakah alat masih tersedia untuk dipinjam.
     */
    public function isAvailable(): bool
    {
        return $this->stok > 0 && $this->kondisi === 'Baik';
    }
}
