@extends('layouts.app')

@section('title', 'Detail Peminjaman ' . $peminjaman->kode_peminjaman)
@section('page_title', 'Detail Transaksi Peminjaman')
@section('page_subtitle', 'Informasi lengkap jadwal, status persetujuan, dan bukti peminjaman alat')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Top Action Bar -->
    <div class="flex items-center justify-between no-print">
        <a href="{{ route('peminjaman.index') }}" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 font-bold text-xs rounded-xl hover:bg-slate-50 transition-all flex items-center space-x-2">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Daftar</span>
        </a>

        <div class="flex items-center space-x-2">
            <button onclick="window.print()" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center space-x-2">
                <i class="fa-solid fa-print"></i>
                <span>Cetak Bukti Peminjaman</span>
            </button>
        </div>
    </div>

    <!-- Official Transaction Receipt Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 print:border-none print:shadow-none print:p-0">
        
        <!-- Header Receipt -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 border-b border-slate-200 gap-4">
            <div>
                <span class="text-xs font-bold text-emerald-600 uppercase tracking-widest">Bukti Peminjaman Sarpras</span>
                <h3 class="text-2xl font-black text-slate-900 mt-1">{{ $peminjaman->kode_peminjaman }}</h3>
                <p class="text-xs text-slate-400 mt-0.5">Dibuat pada: {{ $peminjaman->created_at->format('d F Y, H:i') }} WIB</p>
            </div>
            <div>
                <span class="px-4 py-1.5 rounded-full text-xs font-bold border {{ $peminjaman->status_badge }}">
                    {{ $peminjaman->status }}
                </span>
            </div>
        </div>

        <!-- 2 Column Information Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 my-6">
            
            <!-- Peminjam Info -->
            <div class="bg-slate-50 p-5 rounded-xl border border-slate-100">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-2">Informasi Peminjam</span>
                <div class="font-bold text-slate-900 text-base">{{ $peminjaman->user->name ?? 'User Terhapus' }}</div>
                <div class="text-xs text-slate-600 mt-1">Username / NIS: <span class="font-mono font-semibold">{{ $peminjaman->user->username ?? '-' }}</span></div>
                <div class="text-xs text-slate-600 mt-0.5">No. Telepon: {{ $peminjaman->user->no_telp ?? '-' }}</div>
                <div class="text-xs text-slate-600 mt-0.5">Kelas / Alamat: {{ $peminjaman->user->alamat ?? '-' }}</div>
            </div>

            <!-- Schedule & Loan Duration -->
            <div class="bg-slate-50 p-5 rounded-xl border border-slate-100">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-2">Jadwal & Waktu Peminjaman</span>
                <div class="text-xs text-slate-700">Tanggal Pinjam: <strong class="text-slate-900">{{ $peminjaman->tgl_pinjam->format('d F Y') }}</strong></div>
                <div class="text-xs text-slate-700 mt-1">Batas Rencana Kembali: <strong class="text-slate-900">{{ $peminjaman->tgl_kembali_rencana->format('d F Y') }}</strong></div>
                <div class="text-xs text-slate-700 mt-1">
                    Tanggal Aktual Pengembalian: 
                    @if($peminjaman->tgl_kembali_aktual)
                        <strong class="text-emerald-700 font-bold">{{ $peminjaman->tgl_kembali_aktual->format('d F Y') }}</strong>
                    @else
                        <span class="text-amber-600 font-medium">Belum Dikembalikan</span>
                    @endif
                </div>
            </div>

        </div>

        <!-- Borrowed Tool Table -->
        <div class="overflow-x-auto my-6">
            <table class="w-full text-left text-xs border border-slate-200 rounded-xl overflow-hidden">
                <thead class="bg-slate-100 text-slate-700 uppercase font-bold text-[10px]">
                    <tr>
                        <th class="px-4 py-3">Kode Alat</th>
                        <th class="px-4 py-3">Nama Alat & Spesifikasi</th>
                        <th class="px-4 py-3">Kategori</th>
                        <th class="px-4 py-3 text-center">Jumlah Pinjam</th>
                        <th class="px-4 py-3 text-right">Denda Keterlambatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr>
                        <td class="px-4 py-3.5 font-mono font-bold text-brand-700">{{ $peminjaman->alat->kode_alat ?? '-' }}</td>
                        <td class="px-4 py-3.5">
                            <div class="font-bold text-slate-900 text-sm">{{ $peminjaman->alat->nama_alat ?? 'Alat Dihapus' }}</div>
                            <div class="text-slate-500 text-[11px]">{{ $peminjaman->alat->deskripsi ?? '-' }}</div>
                        </td>
                        <td class="px-4 py-3.5 text-slate-600">{{ $peminjaman->alat->kategori->nama_kategori ?? '-' }}</td>
                        <td class="px-4 py-3.5 text-center font-bold text-slate-900">{{ $peminjaman->jumlah_pinjam }} Unit</td>
                        <td class="px-4 py-3.5 text-right font-bold {{ $peminjaman->denda > 0 ? 'text-rose-600' : 'text-slate-600' }}">
                            Rp {{ number_format($peminjaman->denda, 0, ',', '.') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Notes Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                <span class="font-bold text-slate-700 block mb-1">Catatan Peminjam:</span>
                <p class="text-slate-600 italic">{{ $peminjaman->catatan_peminjam ?? 'Tidak ada catatan khusus dari peminjam.' }}</p>
            </div>
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                <span class="font-bold text-slate-700 block mb-1">Catatan Verifikasi Petugas:</span>
                <p class="text-slate-600 italic">{{ $peminjaman->catatan_petugas ?? 'Belum ada catatan dari petugas.' }}</p>
            </div>
        </div>

        <!-- Signatures for Official Receipt -->
        <div class="grid grid-cols-2 gap-8 mt-12 pt-8 border-t border-slate-200 text-center text-xs">
            <div>
                <p class="text-slate-500 mb-16">Peminjam yang Mengajukan,</p>
                <p class="font-bold text-slate-900 underline">{{ $peminjaman->user->name ?? 'Peminjam' }}</p>
                <p class="text-[10px] text-slate-400">NIS/Identitas: {{ $peminjaman->user->username ?? '-' }}</p>
            </div>
            <div>
                <p class="text-slate-500 mb-16">Petugas Pengelola Sarpras,</p>
                <p class="font-bold text-slate-900 underline">Pengurus Sarana Sekolah</p>
                <p class="text-[10px] text-slate-400">NIP/Petugas Resmi</p>
            </div>
        </div>

    </div>

</div>
@endsection
