@extends('layouts.app')

@section('title', 'Laporan & Rekapitulasi - Sarpras UKK')
@section('page_title', 'Laporan & Rekapitulasi Peminjaman')
@section('page_subtitle', 'Filter dan cetak laporan resmi sirkulasi sarana dan prasarana sekolah')

@section('content')
<div class="space-y-6">

    <!-- Filter Card -->
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-xs">
        <h3 class="font-bold text-slate-900 text-sm mb-4">Filter Periode & Kriteria Laporan</h3>
        
        <form action="{{ route('laporan.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs">
            
            <!-- Date From -->
            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Dari Tanggal</label>
                <input type="date" name="tgl_mulai" value="{{ $tglMulai }}"
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500">
            </div>

            <!-- Date To -->
            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Sampai Tanggal</label>
                <input type="date" name="tgl_selesai" value="{{ $tglSelesai }}"
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500">
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Status Transaksi</label>
                <select name="status" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500">
                    <option value="">-- Semua Status --</option>
                    <option value="Menunggu Konfirmasi" {{ $status == 'Menunggu Konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                    <option value="Sedang Dipinjam" {{ $status == 'Sedang Dipinjam' ? 'selected' : '' }}>Sedang Dipinjam</option>
                    <option value="Dikembalikan" {{ $status == 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                    <option value="Ditolak" {{ $status == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <!-- Kategori Filter -->
            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Kategori Alat</label>
                <select name="kategori_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500">
                    <option value="">-- Semua Kategori --</option>
                    @foreach($kategoris as $k)
                    <option value="{{ $k->id_kategori }}" {{ $kategoriId == $k->id_kategori ? 'selected' : '' }}>
                        {{ $k->nama_kategori }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="col-span-full flex items-center justify-between pt-3 border-t border-slate-100">
                <div>
                    @if($tglMulai || $tglSelesai || $status || $kategoriId || $userId)
                    <a href="{{ route('laporan.index') }}" class="text-xs text-slate-500 hover:text-slate-700 font-bold">
                        <i class="fa-solid fa-arrow-rotate-left mr-1"></i> Reset Semua Filter
                    </a>
                    @endif
                </div>
                <div class="flex items-center space-x-2">
                    <button type="submit" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl transition-all">
                        <i class="fa-solid fa-filter mr-1.5"></i> Terapkan Filter
                    </button>
                    <a href="{{ route('laporan.cetak', request()->query()) }}" target="_blank" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-md shadow-rose-600/30 transition-all flex items-center space-x-2">
                        <i class="fa-solid fa-file-pdf"></i>
                        <span>Cetak Laporan PDF</span>
                    </a>
                </div>
            </div>

        </form>
    </div>

    <!-- Summary Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs">
            <span class="text-[11px] font-bold text-slate-400 uppercase">Total Transaksi Terfilter</span>
            <div class="text-2xl font-black text-slate-900 mt-1">{{ $totalPinjam }} Transaksi</div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs">
            <span class="text-[11px] font-bold text-brand-600 uppercase">Total Unit Dipinjam</span>
            <div class="text-2xl font-black text-brand-600 mt-1">{{ $totalAlatDipinjam }} Unit</div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs">
            <span class="text-[11px] font-bold text-rose-600 uppercase">Total Denda Diterima</span>
            <div class="text-2xl font-black text-rose-600 mt-1">Rp {{ number_format($totalDenda, 0, ',', '.') }}</div>
        </div>
    </div>

    <!-- Preview Table -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-900 text-sm">Pratinjau Data Laporan</h3>
            <span class="text-xs text-slate-400">Total {{ count($peminjamans) }} baris data</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 text-[11px] uppercase font-bold tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5">No</th>
                        <th class="px-5 py-3.5">Kode Transaksi</th>
                        <th class="px-5 py-3.5">Peminjam</th>
                        <th class="px-5 py-3.5">Nama Alat</th>
                        <th class="px-5 py-3.5">Tgl Pinjam</th>
                        <th class="px-5 py-3.5">Tgl Kembali</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Denda</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($peminjamans as $index => $p)
                    <tr class="hover:bg-slate-50/80 transition-all">
                        <td class="px-5 py-3.5 text-slate-400 font-bold">{{ $index + 1 }}</td>
                        <td class="px-5 py-3.5 font-bold text-slate-900">{{ $p->kode_peminjaman }}</td>
                        <td class="px-5 py-3.5">
                            <div class="font-bold text-slate-800">{{ $p->user->name ?? 'User Terhapus' }}</div>
                            <div class="text-[11px] text-slate-400">{{ $p->user->username ?? '-' }}</div>
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="font-semibold text-slate-900">{{ $p->alat->nama_alat ?? 'Alat Dihapus' }}</div>
                            <div class="text-[11px] text-slate-400">{{ $p->jumlah_pinjam }} Unit &bull; {{ $p->alat->kategori->nama_kategori ?? '-' }}</div>
                        </td>
                        <td class="px-5 py-3.5 text-slate-700">{{ $p->tgl_pinjam->format('d/m/Y') }}</td>
                        <td class="px-5 py-3.5 text-slate-700">
                            {{ $p->tgl_kembali_aktual ? $p->tgl_kembali_aktual->format('d/m/Y') : $p->tgl_kembali_rencana->format('d/m/Y') . ' (Rencana)' }}
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $p->status_badge }}">
                                {{ $p->status }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-right font-bold {{ $p->denda > 0 ? 'text-rose-600' : 'text-slate-500' }}">
                            Rp {{ number_format($p->denda, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-slate-400">
                            <i class="fa-solid fa-file-excel text-4xl mb-2 text-slate-300"></i>
                            <p>Tidak ada data transaksi yang sesuai dengan filter.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
