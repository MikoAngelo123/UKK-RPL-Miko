<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategoris';

    protected $primaryKey = 'id_kategori';

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
    ];

    /**
     * Relasi ke daftar alat dalam kategori ini.
     */
    public function alats(): HasMany
    {
        return $this->hasMany(Alat::class, 'id_kategori', 'id_kategori');
    }
}
