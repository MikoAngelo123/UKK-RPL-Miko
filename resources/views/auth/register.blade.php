@extends('layouts.app')

@section('title', 'Daftar Akun Siswa - Peminjaman Sarpras')

@section('content')
<div class="min-h-[80vh] flex flex-col justify-center items-center py-6">
    <div class="w-full max-w-lg bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
        
        <!-- Header Banner -->
        <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 p-6 text-center text-white">
            <h2 class="text-xl font-bold tracking-tight">Registrasi Akun Siswa / Peminjam</h2>
            <p class="text-xs text-slate-300 mt-1">Daftarkan identitas Anda untuk dapat meminjam sarana sekolah</p>
        </div>

        <!-- Form Box -->
        <div class="p-8">
            <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Nama Lengkap <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                        placeholder="Contoh: Budi Pratama (XII RPL 1)"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition-all">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="username" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Username / NIS <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="username" name="username" value="{{ old('username') }}" required
                            placeholder="Contoh: siswa123"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition-all">
                    </div>

                    <div>
                        <label for="no_telp" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Nomor WhatsApp / HP
                        </label>
                        <input type="text" id="no_telp" name="no_telp" value="{{ old('no_telp') }}"
                            placeholder="08123456789"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Password <span class="text-rose-500">*</span>
                        </label>
                        <input type="password" id="password" name="password" required
                            placeholder="Minimal 6 karakter"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition-all">
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Konfirmasi Password <span class="text-rose-500">*</span>
                        </label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            placeholder="Ulangi password"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition-all">
                    </div>
                </div>

                <div>
                    <label for="alamat" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Kelas / Jurusan / Alamat
                    </label>
                    <textarea id="alamat" name="alamat" rows="2"
                        placeholder="Contoh: Kelas XII RPL 1 / Jl. Merak No. 10"
                        class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition-all">{{ old('alamat') }}</textarea>
                </div>

                <button type="submit" class="w-full py-3 px-4 bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-brand-600/30 transition-all flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-user-plus"></i>
                    <span>Buat Akun Sekarang</span>
                </button>
            </form>

            <div class="mt-6 text-center text-xs text-slate-500">
                Sudah memiliki akun? 
                <a href="{{ route('login') }}" class="font-bold text-brand-600 hover:underline">Masuk ke Sini</a>
            </div>
        </div>
    </div>
</div>
@endsection
