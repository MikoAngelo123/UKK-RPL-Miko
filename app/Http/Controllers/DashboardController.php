<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard utama yang dinamis sesuai peran pengguna.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'peminjam') {
            // Data untuk Peminjam / Siswa
            $myTotalPinjam = Peminjaman::where('user_id', $user->id)->count();
            $mySedangPinjam = Peminjaman::where('user_id', $user->id)->where('status', 'Sedang Dipinjam')->count();
            $myMenunggu = Peminjaman::where('user_id', $user->id)->where('status', 'Menunggu Konfirmasi')->count();
            $mySelesai = Peminjaman::where('user_id', $user->id)->where('status', 'Dikembalikan')->count();

            $recentLoans = Peminjaman::with('alat.kategori')
                ->where('user_id', $user->id)
                ->latest()
                ->take(5)
                ->get();

            $availableAlat = Alat::with('kategori')
                ->where('stok', '>', 0)
                ->where('kondisi', 'Baik')
                ->latest()
                ->take(6)
                ->get();

            return view('dashboard.peminjam', compact(
                'myTotalPinjam',
                'mySedangPinjam',
                'myMenunggu',
                'mySelesai',
                'recentLoans',
                'availableAlat'
            ));
        }

        // Data untuk Admin & Petugas
        $totalAlat = Alat::count();
        $totalStok = Alat::sum('stok');
        $totalKategori = Kategori::count();
        $totalPeminjam = User::where('role', 'peminjam')->count();

        $menungguKonfirmasi = Peminjaman::where('status', 'Menunggu Konfirmasi')->count();
        $sedangDipinjam = Peminjaman::where('status', 'Sedang Dipinjam')->count();
        $selesaiDikembalikan = Peminjaman::where('status', 'Dikembalikan')->count();
        $totalDenda = Peminjaman::sum('denda');

        $recentTransactions = Peminjaman::with(['user', 'alat.kategori'])
            ->latest()
            ->take(6)
            ->get();

        $recentLogs = LogAktivitas::with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.admin', compact(
            'totalAlat',
            'totalStok',
            'totalKategori',
            'totalPeminjam',
            'menungguKonfirmasi',
            'sedangDipinjam',
            'selesaiDikembalikan',
            'totalDenda',
            'recentTransactions',
            'recentLogs'
        ));
    }
}
