@extends('layouts.app')

@section('title', 'Dashboard Admin & Petugas')
@section('page_title', 'Dashboard Utama')
@section('page_subtitle', 'Ringkasan operasional peminjaman dan ketersediaan sarana sekolah')

@section('content')
<div class="space-y-6">

    <!-- KPI STAT CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Alat -->
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Item Alat</span>
                <div class="text-2xl font-black text-slate-900 mt-1">{{ $totalAlat }} <span class="text-xs font-semibold text-slate-400">Jenis</span></div>
                <span class="text-xs text-brand-600 font-medium">Total {{ $totalStok }} Unit Fisik</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-toolbox"></i>
            </div>
        </div>

        <!-- Menunggu Konfirmasi -->
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-amber-500 uppercase tracking-wider">Perlu Persetujuan</span>
                <div class="text-2xl font-black text-amber-600 mt-1">{{ $menungguKonfirmasi }} <span class="text-xs font-semibold text-slate-400">Pengajuan</span></div>
                <a href="{{ route('peminjaman.index', ['status' => 'Menunggu Konfirmasi']) }}" class="text-xs text-amber-600 font-medium hover:underline">Lihat Antrean &rarr;</a>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-clock"></i>
            </div>
        </div>

        <!-- Sedang Dipinjam -->
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-indigo-500 uppercase tracking-wider">Sedang Dipinjam</span>
                <div class="text-2xl font-black text-indigo-600 mt-1">{{ $sedangDipinjam }} <span class="text-xs font-semibold text-slate-400">Transaksi</span></div>
                <span class="text-xs text-slate-400 font-medium">Di luar gudang</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-handshake"></i>
            </div>
        </div>

        <!-- Selesai Dikembalikan -->
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-blue-500 uppercase tracking-wider">Total Pengembalian</span>
                <div class="text-2xl font-black text-blue-600 mt-1">{{ $selesaiDikembalikan }} <span class="text-xs font-semibold text-slate-400">Transaksi</span></div>
                <span class="text-xs text-emerald-600 font-medium">Denda: Rp {{ number_format($totalDenda, 0, ',', '.') }}</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>
    </div>

    <!-- MAIN TWO-COLUMN CONTENT -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left 2 Cols: Recent Transactions Table -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-slate-900 text-base">Aktivitas Transaksi Terbaru</h3>
                    <p class="text-xs text-slate-400">Riwayat peminjaman sarana sekolah terkini</p>
                </div>
                <a href="{{ route('peminjaman.index') }}" class="text-xs font-bold text-brand-600 hover:text-brand-700 bg-brand-50 px-3 py-1.5 rounded-lg border border-brand-100 transition-all">
                    Lihat Semua
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500 text-[11px] uppercase font-bold tracking-wider">
                        <tr>
                            <th class="px-5 py-3">Kode / Peminjam</th>
                            <th class="px-5 py-3">Alat / Kategori</th>
                            <th class="px-5 py-3">Batas Kembali</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @forelse($recentTransactions as $p)
                        <tr class="hover:bg-slate-50/80 transition-all">
                            <td class="px-5 py-3.5">
                                <div class="font-bold text-slate-900">{{ $p->kode_peminjaman }}</div>
                                <div class="text-slate-500">{{ $p->user->name ?? 'User Dihapus' }}</div>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="font-semibold text-slate-800">{{ $p->alat->nama_alat ?? 'Alat Dihapus' }}</div>
                                <div class="text-slate-400 text-[11px]">{{ $p->jumlah_pinjam }} Unit &bull; {{ $p->alat->kategori->nama_kategori ?? '-' }}</div>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="text-slate-700">{{ $p->tgl_kembali_rencana->format('d M Y') }}</div>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $p->status_badge }}">
                                    {{ $p->status }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <a href="{{ route('peminjaman.show', $p->id_peminjaman) }}" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg text-[11px] transition-all">
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-slate-400">Belum ada transaksi peminjaman.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right 1 Col: Quick Actions & Audit Trail -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-gradient-to-br from-slate-900 to-slate-800 text-white p-5 rounded-2xl shadow-md">
                <h4 class="font-bold text-sm mb-1">Aksi Cepat Sistem</h4>
                <p class="text-xs text-slate-400 mb-4">Pintasan operasional pengurus sarpras</p>
                <div class="grid grid-cols-2 gap-2">
                    <a href="{{ route('peminjaman.create') }}" class="p-3 bg-white/10 hover:bg-white/20 rounded-xl text-center text-xs font-semibold transition-all">
                        <i class="fa-solid fa-plus-circle block text-lg mb-1 text-emerald-400"></i> Pinjam Alat
                    </a>
                    <a href="{{ route('alat.create') }}" class="p-3 bg-white/10 hover:bg-white/20 rounded-xl text-center text-xs font-semibold transition-all">
                        <i class="fa-solid fa-toolbox block text-lg mb-1 text-blue-400"></i> Tambah Alat
                    </a>
                    <a href="{{ route('laporan.index') }}" class="p-3 bg-white/10 hover:bg-white/20 rounded-xl text-center text-xs font-semibold transition-all">
                        <i class="fa-solid fa-file-pdf block text-lg mb-1 text-amber-400"></i> Rekap Laporan
                    </a>
                    <a href="{{ route('kategori.index') }}" class="p-3 bg-white/10 hover:bg-white/20 rounded-xl text-center text-xs font-semibold transition-all">
                        <i class="fa-solid fa-tags block text-lg mb-1 text-purple-400"></i> Kategori
                    </a>
                </div>
            </div>

            <!-- Recent Activity Audit Trail -->
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs">
                <h4 class="font-bold text-slate-900 text-sm mb-3">Log Aktivitas Terkini</h4>
                <div class="space-y-3">
                    @forelse($recentLogs as $log)
                    <div class="text-xs border-l-2 border-brand-500 pl-3 py-0.5">
                        <div class="text-slate-700 font-medium">{{ $log->aktivitas }}</div>
                        <div class="text-slate-400 text-[10px] mt-0.5">{{ $log->created_at->diffForHumans() }} &bull; {{ $log->ip_address }}</div>
                    </div>
                    @empty
                    <p class="text-xs text-slate-400 text-center py-3">Belum ada catatan log.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
