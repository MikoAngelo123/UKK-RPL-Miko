<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjamans';

    protected $primaryKey = 'id_peminjaman';

    protected $fillable = [
        'kode_peminjaman',
        'user_id',
        'id_alat',
        'jumlah_pinjam',
        'tgl_pinjam',
        'tgl_kembali_rencana',
        'tgl_kembali_aktual',
        'status',
        'catatan_peminjam',
        'catatan_petugas',
        'denda',
    ];

    protected $casts = [
        'tgl_pinjam' => 'date',
        'tgl_kembali_rencana' => 'date',
        'tgl_kembali_aktual' => 'date',
        'denda' => 'decimal:2',
    ];

    /**
     * Relasi ke Peminjam (User).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke Alat yang dipinjam.
     */
    public function alat(): BelongsTo
    {
        return $this->belongsTo(Alat::class, 'id_alat', 'id_alat');
    }

    /**
     * Helper status badge styling.
     */
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'Menunggu Konfirmasi' => 'bg-amber-100 text-amber-800 border-amber-300',
            'Disetujui' => 'bg-blue-100 text-blue-800 border-blue-300',
            'Sedang Dipinjam' => 'bg-indigo-100 text-indigo-800 border-indigo-300',
            'Dikembalikan' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'Ditolak' => 'bg-rose-100 text-rose-800 border-rose-300',
            default => 'bg-slate-100 text-slate-800 border-slate-300',
        };
    }
}
