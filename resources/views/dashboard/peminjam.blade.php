@extends('layouts.app')

@section('title', 'Portal Peminjaman Siswa')
@section('page_title', 'Portal Siswa / Peminjam')
@section('page_subtitle', 'Ajukan peminjaman fasilitas sekolah dan pantau riwayat pengembalian Anda')

@section('content')
<div class="space-y-6">

    <!-- WELCOME HERO BANNER -->
    <div class="bg-gradient-to-r from-slate-900 via-brand-900 to-slate-900 text-white rounded-3xl p-8 shadow-lg relative overflow-hidden">
        <div class="relative z-10 max-w-2xl">
            <span class="px-3 py-1 bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 rounded-full text-xs font-bold tracking-wide">
                <i class="fa-solid fa-graduation-cap mr-1"></i> Area Peminjam
            </span>
            <h2 class="text-2xl sm:text-3xl font-black mt-3">Halo, {{ Auth::user()->name }}!</h2>
            <p class="text-xs sm:text-sm text-slate-300 mt-2 leading-relaxed">
                Butuh sarana proyektor, alat praktikum laboratorium, atau perlengkapan olahraga sekolah? Ajukan peminjaman secara online dengan cepat dan transparan.
            </p>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('peminjaman.create') }}" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-500 text-white font-bold text-xs rounded-xl shadow-lg shadow-brand-600/30 transition-all flex items-center space-x-2">
                    <i class="fa-solid fa-plus-circle"></i>
                    <span>Ajukan Pinjam Sekarang</span>
                </a>
                <a href="{{ route('alat.index') }}" class="px-5 py-2.5 bg-white/10 hover:bg-white/20 text-white font-bold text-xs rounded-xl transition-all flex items-center space-x-2">
                    <i class="fa-solid fa-boxes-stacked"></i>
                    <span>Eksplor Katalog Alat</span>
                </a>
            </div>
        </div>
    </div>

    <!-- MY METRICS -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-xs">
            <span class="text-[11px] font-bold text-slate-400 uppercase">Total Pengajuan</span>
            <div class="text-2xl font-black text-slate-900 mt-1">{{ $myTotalPinjam }}</div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-xs">
            <span class="text-[11px] font-bold text-amber-500 uppercase">Menunggu Review</span>
            <div class="text-2xl font-black text-amber-600 mt-1">{{ $myMenunggu }}</div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-xs">
            <span class="text-[11px] font-bold text-indigo-500 uppercase">Sedang Dipinjam</span>
            <div class="text-2xl font-black text-indigo-600 mt-1">{{ $mySedangPinjam }}</div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-xs">
            <span class="text-[11px] font-bold text-emerald-500 uppercase">Telah Dikembalikan</span>
            <div class="text-2xl font-black text-emerald-600 mt-1">{{ $mySelesai }}</div>
        </div>
    </div>

    <!-- RECENT LOANS & AVAILABLE TOOLS -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- My Recent Activity -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-5">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                <h3 class="font-bold text-slate-900 text-sm">Status Peminjaman Terkini Saya</h3>
                <a href="{{ route('peminjaman.index') }}" class="text-xs font-bold text-brand-600 hover:underline">Semua &rarr;</a>
            </div>

            <div class="space-y-3">
                @forelse($recentLoans as $loan)
                <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200/60 flex items-center justify-between">
                    <div>
                        <div class="font-bold text-slate-900 text-xs">{{ $loan->alat->nama_alat ?? 'Item' }}</div>
                        <div class="text-[11px] text-slate-500 mt-0.5">
                            {{ $loan->jumlah_pinjam }} Unit &bull; Batas: {{ $loan->tgl_kembali_rencana->format('d M Y') }}
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $loan->status_badge }}">
                            {{ $loan->status }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-slate-400 text-xs">
                    <i class="fa-solid fa-folder-open text-3xl mb-2 text-slate-300"></i>
                    <p>Anda belum memiliki riwayat pengajuan peminjaman.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Available Tools in School -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-5">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                <h3 class="font-bold text-slate-900 text-sm">Alat Siap Dipinjam</h3>
                <a href="{{ route('alat.index') }}" class="text-xs font-bold text-brand-600 hover:underline">Lihat Katalog &rarr;</a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @forelse($availableAlat as $alat)
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/60 flex flex-col justify-between">
                    <div>
                        <span class="text-[10px] font-bold text-brand-600 bg-brand-50 px-2 py-0.5 rounded border border-brand-200/60">
                            {{ $alat->kategori->nama_kategori ?? 'Umum' }}
                        </span>
                        <h4 class="font-bold text-slate-900 text-xs mt-1.5 line-clamp-1">{{ $alat->nama_alat }}</h4>
                        <p class="text-[11px] text-slate-400 mt-0.5">Tersedia {{ $alat->stok }} unit</p>
                    </div>
                    <a href="{{ route('peminjaman.create', ['alat_id' => $alat->id_alat]) }}" class="mt-3 w-full py-1.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-[11px] rounded-lg text-center transition-all">
                        Pinjam Alat Ini
                    </a>
                </div>
                @empty
                <p class="text-xs text-slate-400 col-span-2 text-center py-6">Tidak ada alat yang siap dipinjam saat ini.</p>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection
