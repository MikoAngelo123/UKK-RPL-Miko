@extends('layouts.app')

@section('title', 'Katalog & Inventaris Alat')
@section('page_title', 'Katalog & Inventaris Alat')
@section('page_subtitle', 'Kelola data sarana, stok ketersediaan, dan kondisi fisik peralatan sekolah')

@section('content')
<div class="space-y-6">

    <!-- Top Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-xs flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3">
        <form action="{{ route('alat.index') }}" method="GET" class="flex-1 flex flex-wrap gap-2">
            <!-- Search -->
            <div class="relative flex-1 min-w-[200px]">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 text-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, kode, deskripsi alat..." 
                    class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500">
            </div>

            <!-- Filter Kategori -->
            <select name="kategori_id" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500">
                <option value="">-- Semua Kategori --</option>
                @foreach($kategoris as $k)
                <option value="{{ $k->id_kategori }}" {{ $kategoriId == $k->id_kategori ? 'selected' : '' }}>
                    {{ $k->nama_kategori }}
                </option>
                @endforeach
            </select>

            <!-- Filter Kondisi -->
            <select name="kondisi" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500">
                <option value="">-- Semua Kondisi --</option>
                <option value="Baik" {{ $kondisi == 'Baik' ? 'selected' : '' }}>Baik</option>
                <option value="Perlu Perbaikan" {{ $kondisi == 'Perlu Perbaikan' ? 'selected' : '' }}>Perlu Perbaikan</option>
                <option value="Rusak" {{ $kondisi == 'Rusak' ? 'selected' : '' }}>Rusak</option>
            </select>

            <button type="submit" class="px-4 py-2 bg-slate-900 text-white font-bold text-xs rounded-xl hover:bg-slate-800 transition-all">
                Filter
            </button>

            @if($search || $kategoriId || $kondisi)
            <a href="{{ route('alat.index') }}" class="px-3 py-2 bg-slate-100 text-slate-600 font-bold text-xs rounded-xl hover:bg-slate-200 flex items-center">
                Reset
            </a>
            @endif
        </form>

        @if(Auth::user()->isAdmin() || Auth::user()->isPetugas())
        <a href="{{ route('alat.create') }}" class="px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-md shadow-brand-600/30 transition-all flex items-center justify-center space-x-2">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Alat Baru</span>
        </a>
        @endif
    </div>

    <!-- Tool Grid Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($alats as $alat)
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden flex flex-col justify-between hover:shadow-md transition-all group">
            <div>
                <!-- Image Box -->
                <div class="h-44 bg-slate-100 relative overflow-hidden flex items-center justify-center border-b border-slate-100">
                    @if($alat->foto)
                        <img src="{{ asset('storage/' . $alat->foto) }}" alt="{{ $alat->nama_alat }}" class="w-full h-full object-cover group-hover:scale-105 transition-all duration-300">
                    @else
                        <div class="text-center text-slate-400">
                            <i class="fa-solid fa-toolbox text-4xl mb-1 text-slate-300"></i>
                            <div class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Foto Belum Diunggah</div>
                        </div>
                    @endif

                    <!-- Condition Badge -->
                    <div class="absolute top-3 right-3">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold shadow-xs
                            @if($alat->kondisi === 'Baik') bg-emerald-500 text-white
                            @elseif($alat->kondisi === 'Perlu Perbaikan') bg-amber-500 text-white
                            @else bg-rose-500 text-white @endif">
                            {{ $alat->kondisi }}
                        </span>
                    </div>

                    <!-- Category Pill -->
                    <div class="absolute bottom-3 left-3">
                        <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-bold bg-slate-900/80 backdrop-blur-xs text-white">
                            {{ $alat->kategori->nama_kategori ?? 'Umum' }}
                        </span>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="p-5">
                    <div class="text-[11px] font-bold text-brand-600 tracking-wider uppercase mb-1">
                        {{ $alat->kode_alat }}
                    </div>
                    <h3 class="font-bold text-slate-900 text-sm line-clamp-1 group-hover:text-brand-600 transition-all">
                        {{ $alat->nama_alat }}
                    </h3>
                    <p class="text-xs text-slate-500 mt-1.5 line-clamp-2">
                        {{ $alat->deskripsi ?? 'Tidak ada deskripsi tambahan.' }}
                    </p>

                    <!-- Stock Counter Bar -->
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                        <span class="text-slate-500">Stok Gudang:</span>
                        <span class="font-black {{ $alat->stok > 0 ? 'text-slate-900' : 'text-rose-600' }}">
                            {{ $alat->stok }} Unit
                        </span>
                    </div>
                </div>
            </div>

            <!-- Footer Action Buttons -->
            <div class="px-5 pb-5 pt-1 flex items-center gap-2">
                <a href="{{ route('alat.show', $alat->id_alat) }}" class="flex-1 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl text-center transition-all">
                    Detail Info
                </a>

                @if(Auth::user()->isPeminjam() && $alat->isAvailable())
                <a href="{{ route('peminjaman.create', ['alat_id' => $alat->id_alat]) }}" class="flex-1 py-2 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl text-center shadow-xs transition-all">
                    Pinjam Alat
                </a>
                @endif

                @if(Auth::user()->isAdmin() || Auth::user()->isPetugas())
                <a href="{{ route('alat.edit', $alat->id_alat) }}" class="p-2 bg-amber-50 hover:bg-amber-100 text-amber-700 rounded-xl text-xs transition-all" title="Edit Alat">
                    <i class="fa-solid fa-pen-to-square"></i>
                </a>
                <form action="{{ route('alat.destroy', $alat->id_alat) }}" method="POST" class="inline" onsubmit="return confirmDelete(event)">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-2 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-xl text-xs transition-all" title="Hapus Alat">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-2xl border border-slate-100 p-12 text-center text-slate-400">
            <i class="fa-solid fa-boxes-stacked text-4xl mb-3 text-slate-300"></i>
            <h4 class="font-bold text-slate-700 text-sm">Tidak ada alat ditemukan</h4>
            <p class="text-xs text-slate-400 mt-1">Coba ubah kata kunci pencarian atau filter kategori.</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($alats->hasPages())
    <div class="p-4 bg-white rounded-2xl border border-slate-100 shadow-xs">
        {{ $alats->links() }}
    </div>
    @endif

</div>

<script>
    function confirmDelete(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Hapus Data Alat?',
            text: "Data alat beserta foto akan dihapus dari inventaris.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                e.target.submit();
            }
        });
    }
</script>
@endsection
