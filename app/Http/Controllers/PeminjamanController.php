<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    /**
     * Tampilkan daftar transaksi peminjaman (dengan filter multi-kriteria).
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $status = $request->get('status');
        $tglMulai = $request->get('tgl_mulai');
        $tglSelesai = $request->get('tgl_selesai');
        $search = $request->get('search');
        $userId = $request->get('user_id');

        $query = Peminjaman::with(['user', 'alat.kategori'])->latest();

        // Jika peminjam biasa, hanya lihat miliknya sendiri
        if ($user->role === 'peminjam') {
            $query->where('user_id', $user->id);
        } elseif ($userId) {
            $query->where('user_id', $userId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($tglMulai && $tglSelesai) {
            $query->whereBetween('tgl_pinjam', [$tglMulai, $tglSelesai]);
        } elseif ($tglMulai) {
            $query->whereDate('tgl_pinjam', '>=', $tglMulai);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('kode_peminjaman', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($sub) use ($search) {
                      $sub->where('name', 'like', "%{$search}%")
                          ->orWhere('username', 'like', "%{$search}%");
                  })
                  ->orWhereHas('alat', function ($sub) use ($search) {
                      $sub->where('nama_alat', 'like', "%{$search}%")
                          ->orWhere('kode_alat', 'like', "%{$search}%");
                  });
            });
        }

        $peminjamans = $query->paginate(10)->withQueryString();
        $peminjamList = ($user->role !== 'peminjam') ? User::where('role', 'peminjam')->orderBy('name')->get() : [];

        return view('peminjaman.index', compact(
            'peminjamans',
            'status',
            'tglMulai',
            'tglSelesai',
            'search',
            'userId',
            'peminjamList'
        ));
    }

    /**
     * Form pengajuan peminjaman alat baru.
     */
    public function create(Request $request)
    {
        $selectedAlatId = $request->get('alat_id');
        $alats = Alat::with('kategori')
            ->where('stok', '>', 0)
            ->where('kondisi', 'Baik')
            ->orderBy('nama_alat')
            ->get();

        $users = (Auth::user()->role !== 'peminjam') 
            ? User::where('role', 'peminjam')->orderBy('name')->get() 
            : [];

        return view('peminjaman.create', compact('alats', 'selectedAlatId', 'users'));
    }

    /**
     * Simpan pengajuan peminjaman baru.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'id_alat' => ['required', 'exists:alats,id_alat'],
            'jumlah_pinjam' => ['required', 'integer', 'min:1'],
            'tgl_pinjam' => ['required', 'date', 'after_or_equal:today'],
            'tgl_kembali_rencana' => ['required', 'date', 'after_or_equal:tgl_pinjam'],
            'catatan_peminjam' => ['nullable', 'string', 'max:500'],
        ];

        // Jika admin/petugas yang menginput atas nama siswa lain
        if ($user->role !== 'peminjam') {
            $rules['user_id'] = ['required', 'exists:users,id'];
        }

        $validated = $request->validate($rules, [
            'id_alat.required' => 'Pilih alat yang ingin dipinjam.',
            'jumlah_pinjam.required' => 'Jumlah pinjam wajib diisi.',
            'jumlah_pinjam.min' => 'Jumlah pinjam minimal 1 unit.',
            'tgl_pinjam.required' => 'Tanggal pinjam wajib ditentukan.',
            'tgl_pinjam.after_or_equal' => 'Tanggal pinjam tidak boleh sebelum hari ini.',
            'tgl_kembali_rencana.required' => 'Tanggal rencana pengembalian wajib ditentukan.',
            'tgl_kembali_rencana.after_or_equal' => 'Tanggal kembali harus sama atau setelah tanggal pinjam.',
        ]);

        $targetUserId = ($user->role !== 'peminjam') ? $validated['user_id'] : $user->id;
        $alat = Alat::findOrFail($validated['id_alat']);

        if ($alat->stok < $validated['jumlah_pinjam']) {
            return back()->withInput()->with('error', "Stok alat tidak mencukupi! Sisa stok saat ini hanya {$alat->stok} unit.");
        }

        // Generate Kode Transaksi Unik
        $prefix = 'PINJAM-' . date('Ymd');
        $random = strtoupper(substr(uniqid(), -4));
        $kodePeminjaman = $prefix . '-' . $random;

        $statusAwal = ($user->role === 'peminjam') ? 'Menunggu Konfirmasi' : 'Sedang Dipinjam';

        DB::transaction(function () use ($validated, $targetUserId, $alat, $kodePeminjaman, $statusAwal) {
            // Jika langsung disetujui/dipinjamkan oleh admin/petugas, kurangi stok
            if ($statusAwal === 'Sedang Dipinjam') {
                $alat->decrement('stok', $validated['jumlah_pinjam']);
            }

            Peminjaman::create([
                'kode_peminjaman' => $kodePeminjaman,
                'user_id' => $targetUserId,
                'id_alat' => $alat->id_alat,
                'jumlah_pinjam' => $validated['jumlah_pinjam'],
                'tgl_pinjam' => $validated['tgl_pinjam'],
                'tgl_kembali_rencana' => $validated['tgl_kembali_rencana'],
                'status' => $statusAwal,
                'catatan_peminjam' => $validated['catatan_peminjam'] ?? null,
                'catatan_petugas' => (Auth::user()->role !== 'peminjam') ? 'Disetujui langsung oleh ' . Auth::user()->name : null,
                'denda' => 0,
            ]);

            LogAktivitas::catat(Auth::id(), "Pengajuan peminjaman [{$kodePeminjaman}] untuk alat {$alat->nama_alat} sejumlah {$validated['jumlah_pinjam']} unit.");
        });

        $msg = ($statusAwal === 'Menunggu Konfirmasi')
            ? 'Pengajuan peminjaman berhasil dikirim. Menunggu persetujuan petugas sarpras.'
            : 'Transaksi peminjaman berhasil dibuat dan stok telah diperbarui.';

        return redirect()->route('peminjaman.index')->with('success', $msg);
    }

    /**
     * Tampilkan detail transaksi peminjaman.
     */
    public function show($id)
    {
        $peminjaman = Peminjaman::with(['user', 'alat.kategori'])->findOrFail($id);

        // Validasi akses peminjam
        if (Auth::user()->role === 'peminjam' && $peminjaman->user_id !== Auth::id()) {
            abort(403, 'Akses tidak diizinkan.');
        }

        return view('peminjaman.show', compact('peminjaman'));
    }

    /**
     * Persetujuan peminjaman oleh Petugas/Admin (Approval).
     */
    public function approve(Request $request, $id)
    {
        $peminjaman = Peminjaman::with('alat')->findOrFail($id);

        if ($peminjaman->status !== 'Menunggu Konfirmasi') {
            return back()->with('error', 'Status peminjaman ini sudah tidak dalam antrean konfirmasi.');
        }

        $alat = $peminjaman->alat;
        if ($alat->stok < $peminjaman->jumlah_pinjam) {
            return back()->with('error', "Gagal menyetujui! Stok alat saat ini ({$alat->stok}) tidak mencukupi kebutuhan ({$peminjaman->jumlah_pinjam}).");
        }

        DB::transaction(function () use ($peminjaman, $alat, $request) {
            // Kurangi stok
            $alat->decrement('stok', $peminjaman->jumlah_pinjam);

            $peminjaman->update([
                'status' => 'Sedang Dipinjam',
                'catatan_petugas' => $request->input('catatan_petugas', 'Disetujui oleh petugas sarpras.'),
            ]);

            LogAktivitas::catat(Auth::id(), "Menyetujui peminjaman [{$peminjaman->kode_peminjaman}] untuk {$peminjaman->user->name}.");
        });

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil disetujui. Alat sedang dipinjam.');
    }

    /**
     * Penolakan peminjaman oleh Petugas/Admin.
     */
    public function reject(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->status !== 'Menunggu Konfirmasi') {
            return back()->with('error', 'Peminjaman ini tidak dapat ditolak karena statusnya bukan menunggu konfirmasi.');
        }

        $request->validate([
            'alasan_penolakan' => ['required', 'string', 'max:255'],
        ], [
            'alasan_penolakan.required' => 'Mohon sertakan alasan penolakan peminjaman.',
        ]);

        $peminjaman->update([
            'status' => 'Ditolak',
            'catatan_petugas' => 'DITOLAK: ' . $request->input('alasan_penolakan'),
        ]);

        LogAktivitas::catat(Auth::id(), "Menolak peminjaman [{$peminjaman->kode_peminjaman}]. Alasan: {$request->input('alasan_penolakan')}");

        return redirect()->route('peminjaman.index')->with('success', 'Pengajuan peminjaman telah ditolak.');
    }

    /**
     * Proses Pengembalian Alat (Return Process) & Kalkulasi Denda Keterlambatan.
     */
    public function returnItem(Request $request, $id)
    {
        $peminjaman = Peminjaman::with('alat')->findOrFail($id);

        if (!in_array($peminjaman->status, ['Sedang Dipinjam', 'Disetujui'])) {
            return back()->with('error', 'Transaksi ini tidak dalam status sedang dipinjam.');
        }

        $tglKembaliAktual = Carbon::today();
        $tglRencana = Carbon::parse($peminjaman->tgl_kembali_rencana);

        // Tarif denda: Rp 5.000 per hari terlambat
        $denda = 0;
        if ($tglKembaliAktual->greaterThan($tglRencana)) {
            $hariTerlambat = $tglRencana->diffInDays($tglKembaliAktual);
            $denda = $hariTerlambat * 5000;
        }

        // Jika ada denda tambahan kondisi barang rusak / hilang
        $dendaTambahan = (float) $request->input('denda_tambahan', 0);
        $totalDenda = $denda + $dendaTambahan;

        $catatanPetugas = $request->input('catatan_petugas', 'Alat telah dikembalikan.');
        if ($denda > 0) {
            $catatanPetugas .= " (Terlambat " . $tglRencana->diffInDays($tglKembaliAktual) . " hari, denda Rp " . number_format($denda, 0, ',', '.') . ")";
        }

        DB::transaction(function () use ($peminjaman, $tglKembaliAktual, $totalDenda, $catatanPetugas) {
            // Kembalikan stok alat
            $peminjaman->alat->increment('stok', $peminjaman->jumlah_pinjam);

            $peminjaman->update([
                'status' => 'Dikembalikan',
                'tgl_kembali_aktual' => $tglKembaliAktual->format('Y-m-d'),
                'denda' => $totalDenda,
                'catatan_petugas' => $catatanPetugas,
            ]);

            LogAktivitas::catat(Auth::id(), "Memproses pengembalian peminjaman [{$peminjaman->kode_peminjaman}]. Total denda: Rp " . number_format($totalDenda, 0, ',', '.'));
        });

        return redirect()->route('peminjaman.index')->with('success', 'Pengembalian alat berhasil diproses. Stok telah dikembalikan ke gudang.');
    }
}
