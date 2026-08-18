@extends('layouts.app')

@section('title', 'Tambah Pengguna Baru - Sarpras UKK')
@section('page_title', 'Tambah Pengguna Baru')
@section('page_subtitle', 'Tambahkan akun admin, petugas sarpras, atau siswa/peminjam baru')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-6 md:p-8">
        
        <form action="{{ route('user.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                    Nama Lengkap <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Rian Pratama (XII RPL 1)"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                        Username / NIS <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="username" value="{{ old('username') }}" required placeholder="Contoh: rian123"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                        Role / Hak Akses <span class="text-rose-500">*</span>
                    </label>
                    <select name="role" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500">
                        <option value="peminjam" {{ old('role') == 'peminjam' ? 'selected' : '' }}>Peminjam (Siswa / Guru)</option>
                        <option value="petugas" {{ old('role') == 'petugas' ? 'selected' : '' }}>Petugas Sarpras</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                        Password <span class="text-rose-500">*</span>
                    </label>
                    <input type="password" name="password" required placeholder="Minimal 6 karakter"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                        Nomor HP / WhatsApp
                    </label>
                    <input type="text" name="no_telp" value="{{ old('no_telp') }}" placeholder="08123456789"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                    Email
                </label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="user@sekolah.sch.id"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                    Kelas / Ruangan / Alamat
                </label>
                <textarea name="alamat" rows="2" placeholder="Contoh: Kelas XII RPL 1 / Ruang Guru Lt. 2"
                    class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500">{{ old('alamat') }}</textarea>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                <a href="{{ route('user.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl">Batal</a>
                <button type="submit" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-md">Simpan Pengguna</button>
            </div>
        </form>

    </div>
</div>
@endsection
