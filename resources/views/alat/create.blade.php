@extends('layouts.app')

@section('title', 'Tambah Alat Baru - Sarpras UKK')
@section('page_title', 'Tambah Alat Baru')
@section('page_subtitle', 'Masukkan rincian inventaris alat sekolah baru ke dalam sistem')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-6 md:p-8">
        
        <form action="{{ route('alat.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                        Kode Alat / Barcode <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="kode_alat" value="{{ old('kode_alat', 'ALT-' . strtoupper(substr(uniqid(), -6))) }}" required
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-800 focus:ring-2 focus:ring-brand-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                        Kategori Alat <span class="text-rose-500">*</span>
                    </label>
                    <select name="id_kategori" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoris as $k)
                        <option value="{{ $k->id_kategori }}" {{ old('id_kategori') == $k->id_kategori ? 'selected' : '' }}>
                            {{ $k->nama_kategori }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                    Nama Alat / Barang <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="nama_alat" value="{{ old('nama_alat') }}" required placeholder="Contoh: Proyektor Epson EB-X400 HDMI"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                        Jumlah Stok Awal <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="stok" value="{{ old('stok', 1) }}" min="0" required
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                        Kondisi Fisik Alat <span class="text-rose-500">*</span>
                    </label>
                    <select name="kondisi" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500">
                        <option value="Baik" {{ old('kondisi') == 'Baik' ? 'selected' : '' }}>Baik (Layak Pakai)</option>
                        <option value="Perlu Perbaikan" {{ old('kondisi') == 'Perlu Perbaikan' ? 'selected' : '' }}>Perlu Perbaikan</option>
                        <option value="Rusak" {{ old('kondisi') == 'Rusak' ? 'selected' : '' }}>Rusak (Tidak Dapat Digunakan)</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                    Foto Dokumentasi Alat
                </label>
                <input type="file" name="foto" accept="image/*"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-600 file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-200 file:text-slate-700 hover:file:bg-slate-300">
                <p class="text-[11px] text-slate-400 mt-1">Format: JPG, PNG, WEBP (Maksimal 2MB).</p>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                    Spesifikasi / Deskripsi Alat
                </label>
                <textarea name="deskripsi" rows="4" placeholder="Tuliskan spesifikasi, kelengkapan aksesoris, atau catatan teknis barang..."
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                <a href="{{ route('alat.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-all">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-md shadow-brand-600/30 transition-all flex items-center space-x-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Simpan Data Alat</span>
                </button>
            </div>
        </form>

    </div>
</div>
@endsection
