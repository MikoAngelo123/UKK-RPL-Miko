@extends('layouts.app')

@section('title', $alat->nama_alat . ' - Detail Inventaris')
@section('page_title', 'Detail Inventaris Alat')
@section('page_subtitle', 'Spesifikasi detail dan histori riwayat peminjaman barang ini')

@section('content')
<div class="space-y-6">

    <!-- Top Navigation & Actions -->
    <div class="flex items-center justify-between">
        <a href="{{ route('alat.index') }}" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 font-bold text-xs rounded-xl hover:bg-slate-50 transition-all flex items-center space-x-2">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Katalog</span>
        </a>

        <div class="flex items-center space-x-2">
            @if(Auth::user()->isPeminjam() && $alat->isAvailable())
            <a href="{{ route('peminjaman.create', ['alat_id' => $alat->id_alat]) }}" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-md shadow-brand-600/30 transition-all flex items-center space-x-2">
                <i class="fa-solid fa-handshake"></i>
                <span>Ajukan Peminjaman</span>
            </a>
            @endif

            @if(Auth::user()->isAdmin() || Auth::user()->isPetugas())
            <a href="{{ route('alat.edit', $alat->id_alat) }}" class="px-4 py-2 bg-amber-50 hover:bg-amber-100 text-amber-700 font-bold text-xs rounded-xl transition-all flex items-center space-x-2">
                <i class="fa-solid fa-pen-to-square"></i>
                <span>Edit Alat</span>
            </a>
            @endif
        </div>
    </div>

    <!-- Main Detail Card -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left 1 Col: Photo & Main Badge -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-6 flex flex-col items-center justify-center text-center">
            <div class="w-full h-56 bg-slate-100 rounded-xl overflow-hidden mb-4 border border-slate-100 flex items-center justify-center">
                @if($alat->foto)
                    <img src="{{ asset('storage/' . $alat->foto) }}" alt="{{ $alat->nama_alat }}" class="w-full h-full object-cover">
                @else
                    <div class="text-slate-400">
                        <i class="fa-solid fa-toolbox text-5xl mb-2 text-slate-300"></i>
                        <p class="text-xs">Foto belum tersedia</p>
                    </div>
                @endif
            </div>

            <span class="text-xs font-mono font-bold text-brand-600 bg-brand-50 px-3 py-1 rounded-full border border-brand-200">
                {{ $alat->kode_alat }}
            </span>
            <h3 class="font-bold text-slate-900 text-lg mt-2">{{ $alat->nama_alat }}</h3>
            <p class="text-xs text-slate-400 mt-0.5">{{ $alat->kategori->nama_kategori ?? 'Umum' }}</p>

            <div class="grid grid-cols-2 gap-3 w-full mt-6 pt-6 border-t border-slate-100">
                <div class="bg-slate-50 p-3 rounded-xl">
                    <span class="text-[11px] font-bold text-slate-400 uppercase">Stok Gudang</span>
                    <div class="text-xl font-black text-slate-900 mt-0.5">{{ $alat->stok }} Unit</div>
                </div>
                <div class="bg-slate-50 p-3 rounded-xl">
                    <span class="text-[11px] font-bold text-slate-400 uppercase">Kondisi</span>
                    <div class="text-sm font-bold mt-1.5
                        @if($alat->kondisi === 'Baik') text-emerald-600
                        @elseif($alat->kondisi === 'Perlu Perbaikan') text-amber-600
                        @else text-rose-600 @endif">
                        {{ $alat->kondisi }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Right 2 Cols: Description & Loan History -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Description Box -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-6">
                <h4 class="font-bold text-slate-900 text-sm mb-2">Spesifikasi & Informasi Tambahan</h4>
                <p class="text-xs text-slate-600 leading-relaxed whitespace-pre-line">
                    {{ $alat->deskripsi ?? 'Tidak ada catatan atau spesifikasi khusus untuk alat ini.' }}
                </p>
            </div>

            <!-- Loan History Table -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
                <div class="p-5 border-b border-slate-100">
                    <h4 class="font-bold text-slate-900 text-sm">Riwayat Peminjaman Alat Ini</h4>
                    <p class="text-xs text-slate-400">Daftar transaksi peminjaman terbaru untuk {{ $alat->nama_alat }}</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-slate-500 text-[11px] uppercase font-bold tracking-wider">
                            <tr>
                                <th class="px-5 py-3">Kode Pinjam</th>
                                <th class="px-5 py-3">Peminjam</th>
                                <th class="px-5 py-3">Tgl Pinjam - Kembali</th>
                                <th class="px-5 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs">
                            @forelse($alat->peminjamans()->latest()->take(6)->get() as $p)
                            <tr>
                                <td class="px-5 py-3 font-bold text-slate-900">{{ $p->kode_peminjaman }}</td>
                                <td class="px-5 py-3 text-slate-700">{{ $p->user->name ?? 'User' }}</td>
                                <td class="px-5 py-3 text-slate-500">
                                    {{ $p->tgl_pinjam->format('d/m/Y') }} &rarr; {{ $p->tgl_kembali_rencana->format('d/m/Y') }}
                                </td>
                                <td class="px-5 py-3">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $p->status_badge }}">
                                        {{ $p->status }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-slate-400">Belum ada riwayat peminjaman untuk alat ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
