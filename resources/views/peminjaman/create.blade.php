@extends('layouts.app')

@section('title', 'Form Pengajuan Peminjaman Alat')
@section('page_title', 'Form Peminjaman Alat')
@section('page_subtitle', 'Ajukan peminjaman alat sarana sekolah untuk keperluan kegiatan belajar / praktikum')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-6 md:p-8">
        
        <form action="{{ route('peminjaman.store') }}" method="POST" class="space-y-5">
            @csrf

            <!-- User Selector (Only for Admin/Petugas) -->
            @if(Auth::user()->role !== 'peminjam')
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                    Nama Peminjam (Siswa / Guru) <span class="text-rose-500">*</span>
                </label>
                <select name="user_id" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500">
                    <option value="">-- Pilih Peminjam --</option>
                    @foreach($users as $u)
                    <option value="{{ $u->id }}" {{ old('user_id') == $u->id ? 'selected' : '' }}>
                        {{ $u->name }} ({{ $u->username }}) - {{ $u->alamat ?? 'Kelas/Umum' }}
                    </option>
                    @endforeach
                </select>
            </div>
            @endif

            <!-- Alat Selector -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                    Pilih Alat / Sarana <span class="text-rose-500">*</span>
                </label>
                <select name="id_alat" id="id_alat" required onchange="updateStockInfo()" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500">
                    <option value="">-- Pilih Alat yang Tersedia --</option>
                    @foreach($alats as $a)
                    <option value="{{ $a->id_alat }}" data-stok="{{ $a->stok }}" {{ (old('id_alat', $selectedAlatId) == $a->id_alat) ? 'selected' : '' }}>
                        [{{ $a->kode_alat }}] {{ $a->nama_alat }} (Sisa Stok: {{ $a->stok }} Unit &bull; Kategori: {{ $a->kategori->nama_kategori ?? '-' }})
                    </option>
                    @endforeach
                </select>
                <p id="stokNotice" class="text-[11px] text-brand-600 font-semibold mt-1"></p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                        Jumlah Pinjam <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="jumlah_pinjam" id="jumlah_pinjam" value="{{ old('jumlah_pinjam', 1) }}" min="1" required
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                        Tanggal Pinjam <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="tgl_pinjam" id="tgl_pinjam" value="{{ old('tgl_pinjam', date('Y-m-d')) }}" required
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                        Rencana Kembali <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="tgl_kembali_rencana" value="{{ old('tgl_kembali_rencana', date('Y-m-d', strtotime('+2 days'))) }}" required
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                    Keperluan / Catatan Peminjaman
                </label>
                <textarea name="catatan_peminjam" rows="3" placeholder="Contoh: Digunakan untuk tugas praktikum UKK RPL di Lab Komputer 2..."
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500">{{ old('catatan_peminjam') }}</textarea>
            </div>

            <!-- Rules & Guidance Box -->
            <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl text-amber-800 text-xs space-y-1">
                <div class="font-bold flex items-center space-x-1.5">
                    <i class="fa-solid fa-circle-info"></i>
                    <span>Ketentuan Peminjaman Sarana Sekolah:</span>
                </div>
                <ul class="list-disc list-inside text-[11px] space-y-0.5 text-amber-700">
                    <li>Peminjam bertanggung jawab penuh atas keutuhan dan kebersihan barang.</li>
                    <li>Pengembalian melebihi tanggal rencana akan dikenakan denda keterlambatan Rp 5.000/hari.</li>
                    <li>Barang yang rusak atau hilang wajib diperbaiki atau diganti sesuai spesifikasi yang sama.</li>
                </ul>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                <a href="{{ route('peminjaman.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-all">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-md shadow-brand-600/30 transition-all flex items-center space-x-2">
                    <i class="fa-solid fa-paper-plane"></i>
                    <span>Kirim Pengajuan Peminjaman</span>
                </button>
            </div>
        </form>

    </div>
</div>

<script>
    function updateStockInfo() {
        const select = document.getElementById('id_alat');
        const selected = select.options[select.selectedIndex];
        const stok = selected.getAttribute('data-stok');
        const notice = document.getElementById('stokNotice');
        const qtyInput = document.getElementById('jumlah_pinjam');

        if (stok) {
            notice.innerText = `Maksimal pinjam tersedia: ${stok} unit`;
            qtyInput.max = stok;
        } else {
            notice.innerText = '';
        }
    }
    document.addEventListener('DOMContentLoaded', updateStockInfo);
</script>
@endsection
