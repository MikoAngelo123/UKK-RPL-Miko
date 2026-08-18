@extends('layouts.app')

@section('title', 'Masuk ke Sistem - Peminjaman Sarpras')

@section('content')
<div class="min-h-[80vh] flex flex-col justify-center items-center py-6">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
        
        <!-- Header Banner -->
        <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 p-8 text-center text-white relative">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-brand-600 to-emerald-400 mx-auto flex items-center justify-center text-white text-2xl shadow-lg shadow-emerald-500/30 mb-4">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
            <h2 class="text-2xl font-black tracking-tight">Sistem Peminjaman</h2>
            <p class="text-xs text-slate-300 mt-1">Uji Kompetensi Keahlian (UKK) RPL 2026</p>
        </div>

        <!-- Form Box -->
        <div class="p-8">
            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="username" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Username <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 text-sm">
                            <i class="fa-solid fa-user"></i>
                        </span>
                        <input type="text" id="username" name="username" value="{{ old('username') }}" required autofocus
                            placeholder="Masukkan username Anda..."
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition-all">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Password <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 text-sm">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <input type="password" id="password" name="password" required
                            placeholder="••••••••"
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition-all">
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs text-slate-600">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded text-brand-600 border-slate-300 focus:ring-brand-500">
                        <span class="ml-2">Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3 px-4 bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-brand-600/30 transition-all flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i>
                    <span>Masuk ke Akun</span>
                </button>
            </form>

            <div class="mt-6 text-center text-xs text-slate-500">
                Belum punya akun peminjam? 
                <a href="{{ route('register') }}" class="font-bold text-brand-600 hover:underline">Daftar Akun Siswa</a>
            </div>

            <!-- Quick Login Credential Pills (Super Helpful for Exam Demonstration) -->
            <div class="mt-8 pt-6 border-t border-slate-100">
                <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-3 text-center">
                    Akses Cepat Pengujian (1-Click Demo)
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <button type="button" onclick="quickFill('admin', 'admin123')" class="px-2 py-1.5 text-[11px] font-semibold bg-amber-50 text-amber-800 border border-amber-200 rounded-lg hover:bg-amber-100 transition-all text-center">
                        <i class="fa-solid fa-shield-halved block text-sm mb-0.5"></i> Admin
                    </button>
                    <button type="button" onclick="quickFill('petugas', 'petugas123')" class="px-2 py-1.5 text-[11px] font-semibold bg-blue-50 text-blue-800 border border-blue-200 rounded-lg hover:bg-blue-100 transition-all text-center">
                        <i class="fa-solid fa-user-gear block text-sm mb-0.5"></i> Petugas
                    </button>
                    <button type="button" onclick="quickFill('siswa1', 'siswa123')" class="px-2 py-1.5 text-[11px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-lg hover:bg-emerald-100 transition-all text-center">
                        <i class="fa-solid fa-graduation-cap block text-sm mb-0.5"></i> Siswa
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    function quickFill(user, pass) {
        document.getElementById('username').value = user;
        document.getElementById('password').value = pass;
    }
</script>
@endsection
