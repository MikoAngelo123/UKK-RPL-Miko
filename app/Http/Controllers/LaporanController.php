<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /**
     * Tampilkan filter dan pratinjau laporan peminjaman & pengembalian.
     */
    public function index(Request $request)
    {
        $status = $request->get('status');
        $tglMulai = $request->get('tgl_mulai');
        $tglSelesai = $request->get('tgl_selesai');
        $kategoriId = $request->get('kategori_id');
        $userId = $request->get('user_id');

        $query = Peminjaman::with(['user', 'alat.kategori'])->latest();

        if ($status) {
            $query->where('status', $status);
        }

        if ($tglMulai && $tglSelesai) {
            $query->whereBetween('tgl_pinjam', [$tglMulai, $tglSelesai]);
        } elseif ($tglMulai) {
            $query->whereDate('tgl_pinjam', '>=', $tglMulai);
        }

        if ($kategoriId) {
            $query->whereHas('alat', function ($q) use ($kategoriId) {
                $q->where('id_kategori', $kategoriId);
            });
        }

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $peminjamans = $query->get();

        $totalPinjam = $peminjamans->count();
        $totalAlatDipinjam = $peminjamans->sum('jumlah_pinjam');
        $totalDenda = $peminjamans->sum('denda');

        $kategoris = Kategori::orderBy('nama_kategori')->get();
        $peminjamList = User::where('role', 'peminjam')->orderBy('name')->get();

        return view('laporan.index', compact(
            'peminjamans',
            'status',
            'tglMulai',
            'tglSelesai',
            'kategoriId',
            'userId',
            'kategoris',
            'peminjamList',
            'totalPinjam',
            'totalAlatDipinjam',
            'totalDenda'
        ));
    }

    /**
     * Halaman cetak laporan resmi siap print / simpan sebagai PDF.
     */
    public function cetak(Request $request)
    {
        $status = $request->get('status');
        $tglMulai = $request->get('tgl_mulai');
        $tglSelesai = $request->get('tgl_selesai');
        $kategoriId = $request->get('kategori_id');
        $userId = $request->get('user_id');

        $query = Peminjaman::with(['user', 'alat.kategori'])->orderBy('tgl_pinjam', 'asc');

        if ($status) {
            $query->where('status', $status);
        }

        if ($tglMulai && $tglSelesai) {
            $query->whereBetween('tgl_pinjam', [$tglMulai, $tglSelesai]);
        } elseif ($tglMulai) {
            $query->whereDate('tgl_pinjam', '>=', $tglMulai);
        }

        if ($kategoriId) {
            $query->whereHas('alat', function ($q) use ($kategoriId) {
                $q->where('id_kategori', $kategoriId);
            });
        }

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $peminjamans = $query->get();
        $totalPinjam = $peminjamans->count();
        $totalAlatDipinjam = $peminjamans->sum('jumlah_pinjam');
        $totalDenda = $peminjamans->sum('denda');

        return view('laporan.cetak', compact(
            'peminjamans',
            'status',
            'tglMulai',
            'tglSelesai',
            'totalPinjam',
            'totalAlatDipinjam',
            'totalDenda'
        ));
    }
}
