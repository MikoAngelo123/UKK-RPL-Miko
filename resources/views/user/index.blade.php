@extends('layouts.app')

@section('title', 'Manajemen Pengguna - Sarpras UKK')
@section('page_title', 'Manajemen Pengguna & Hak Akses')
@section('page_subtitle', 'Kelola akun administrator, petugas sarpras, dan peminjam (siswa/guru)')

@section('content')
<div class="space-y-6">

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-xs flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3">
        <form action="{{ route('user.index') }}" method="GET" class="flex-1 flex flex-wrap gap-2">
            <div class="relative flex-1 min-w-[200px]">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 text-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, username, email, telepon..." 
                    class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500">
            </div>

            <select name="role" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500">
                <option value="">-- Semua Role --</option>
                <option value="admin" {{ $role == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="petugas" {{ $role == 'petugas' ? 'selected' : '' }}>Petugas</option>
                <option value="peminjam" {{ $role == 'peminjam' ? 'selected' : '' }}>Peminjam (Siswa)</option>
            </select>

            <button type="submit" class="px-4 py-2 bg-slate-900 text-white font-bold text-xs rounded-xl hover:bg-slate-800 transition-all">
                Cari
            </button>

            @if($search || $role)
            <a href="{{ route('user.index') }}" class="px-3 py-2 bg-slate-100 text-slate-600 font-bold text-xs rounded-xl hover:bg-slate-200 flex items-center">
                Reset
            </a>
            @endif
        </form>

        <a href="{{ route('user.create') }}" class="px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-md shadow-brand-600/30 transition-all flex items-center justify-center space-x-2">
            <i class="fa-solid fa-user-plus"></i>
            <span>Tambah User Baru</span>
        </a>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 text-[11px] uppercase font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-3.5">Nama Pengguna</th>
                        <th class="px-6 py-3.5">Username / NIS</th>
                        <th class="px-6 py-3.5">Role Akses</th>
                        <th class="px-6 py-3.5">Kontak / Telepon</th>
                        <th class="px-6 py-3.5">Kelas / Alamat</th>
                        <th class="px-6 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($users as $u)
                    <tr class="hover:bg-slate-50/80 transition-all">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900 text-sm">{{ $u->name }}</div>
                            <div class="text-[11px] text-slate-400">{{ $u->email ?? 'Tanpa Email' }}</div>
                        </td>
                        <td class="px-6 py-4 font-mono font-semibold text-slate-700">
                            {{ $u->username }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                @if($u->isAdmin()) bg-amber-100 text-amber-800 border border-amber-300
                                @elseif($u->isPetugas()) bg-blue-100 text-blue-800 border border-blue-300
                                @else bg-emerald-100 text-emerald-800 border border-emerald-300 @endif">
                                {{ $u->role }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            {{ $u->no_telp ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-slate-600 max-w-xs truncate">
                            {{ $u->alamat ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-right space-x-1 whitespace-nowrap">
                            <a href="{{ route('user.edit', $u->id) }}" class="px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 font-bold rounded-lg text-xs transition-all">
                                <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                            </a>
                            @if(Auth::id() != $u->id)
                            <form action="{{ route('user.destroy', $u->id) }}" method="POST" class="inline" onsubmit="return confirmDelete(event)">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold rounded-lg text-xs transition-all">
                                    <i class="fa-solid fa-trash mr-1"></i> Hapus
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                            <i class="fa-solid fa-users text-4xl mb-2 text-slate-300"></i>
                            <p>Tidak ada data pengguna yang ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $users->links() }}
        </div>
        @endif
    </div>

</div>

<script>
    function confirmDelete(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Hapus Pengguna Ini?',
            text: "Akun dan riwayat terkait akan terhapus.",
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
