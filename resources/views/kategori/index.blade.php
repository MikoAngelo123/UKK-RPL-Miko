@extends('layouts.app')

@section('title', 'Kategori Alat - Sarpras UKK')
@section('page_title', 'Kategori Alat')
@section('page_subtitle', 'Pengelompokan sarana dan prasarana berdasarkan jenis dan fungsi')

@section('content')
<div class="space-y-6">

    <!-- Top Action & Search Bar -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 bg-white p-4 rounded-2xl border border-slate-100 shadow-xs">
        <form action="{{ route('kategori.index') }}" method="GET" class="flex-1 flex gap-2">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 text-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau deskripsi kategori..." 
                    class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>
            <button type="submit" class="px-4 py-2 bg-slate-900 text-white font-bold text-xs rounded-xl hover:bg-slate-800 transition-all">
                Cari
            </button>
            @if($search)
            <a href="{{ route('kategori.index') }}" class="px-3 py-2 bg-slate-100 text-slate-600 font-bold text-xs rounded-xl hover:bg-slate-200 flex items-center">
                Reset
            </a>
            @endif
        </form>

        <button type="button" onclick="openAddModal()" class="px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-md shadow-brand-600/30 transition-all flex items-center justify-center space-x-2">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Kategori</span>
        </button>
    </div>

    <!-- Category Table -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 text-[11px] uppercase font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-3.5">No</th>
                        <th class="px-6 py-3.5">Nama Kategori</th>
                        <th class="px-6 py-3.5">Deskripsi / Peruntukan</th>
                        <th class="px-6 py-3.5 text-center">Jumlah Alat</th>
                        <th class="px-6 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($kategoris as $index => $kat)
                    <tr class="hover:bg-slate-50/80 transition-all">
                        <td class="px-6 py-4 text-slate-400 font-bold">{{ $kategoris->firstItem() + $index }}</td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900 text-sm">{{ $kat->nama_kategori }}</div>
                            <div class="text-[11px] text-slate-400">ID: #{{ $kat->id_kategori }}</div>
                        </td>
                        <td class="px-6 py-4 text-slate-600 max-w-md">
                            {{ $kat->deskripsi ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2.5 py-1 bg-slate-100 text-slate-700 rounded-full font-bold text-[11px]">
                                {{ $kat->alats_count }} Alat
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-1">
                            <button type="button" onclick="openEditModal({{ $kat->id_kategori }}, '{{ addslashes($kat->nama_kategori) }}', '{{ addslashes($kat->deskripsi ?? '') }}')"
                                class="px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 font-bold rounded-lg text-xs transition-all">
                                <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                            </button>
                            <form action="{{ route('kategori.destroy', $kat->id_kategori) }}" method="POST" class="inline-block" onsubmit="return confirmDelete(event)">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold rounded-lg text-xs transition-all">
                                    <i class="fa-solid fa-trash mr-1"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                            <i class="fa-solid fa-tags text-3xl mb-2 text-slate-300"></i>
                            <p>Tidak ada data kategori ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($kategoris->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $kategoris->links() }}
        </div>
        @endif
    </div>

</div>

<!-- ADD MODAL -->
<div id="addModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 animate-scale-in">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
            <h3 class="font-bold text-slate-900 text-base">Tambah Kategori Baru</h3>
            <button onclick="closeModal('addModal')" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <form action="{{ route('kategori.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Kategori <span class="text-rose-500">*</span></label>
                <input type="text" name="nama_kategori" required placeholder="Contoh: Elektronik & Multimedia"
                    class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Deskripsi</label>
                <textarea name="deskripsi" rows="3" placeholder="Keterangan singkat cakupan kategori..."
                    class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500"></textarea>
            </div>
            <div class="flex justify-end space-x-2 pt-2">
                <button type="button" onclick="closeModal('addModal')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl">Batal</button>
                <button type="submit" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-md">Simpan Kategori</button>
            </div>
        </form>
    </div>
</div>

<!-- EDIT MODAL -->
<div id="editModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 animate-scale-in">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
            <h3 class="font-bold text-slate-900 text-base">Edit Kategori</h3>
            <button onclick="closeModal('editModal')" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <form id="editForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Kategori <span class="text-rose-500">*</span></label>
                <input type="text" id="edit_nama" name="nama_kategori" required
                    class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Deskripsi</label>
                <textarea id="edit_deskripsi" name="deskripsi" rows="3"
                    class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500"></textarea>
            </div>
            <div class="flex justify-end space-x-2 pt-2">
                <button type="button" onclick="closeModal('editModal')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl">Batal</button>
                <button type="submit" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-md">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddModal() {
        document.getElementById('addModal').classList.remove('hidden');
    }
    function openEditModal(id, nama, deskripsi) {
        document.getElementById('editForm').action = "/kategori/" + id;
        document.getElementById('edit_nama').value = nama;
        document.getElementById('edit_deskripsi').value = deskripsi;
        document.getElementById('editModal').classList.remove('hidden');
    }
    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }
    function confirmDelete(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Hapus Kategori?',
            text: "Data yang dihapus tidak dapat dipulihkan.",
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
