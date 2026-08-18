@extends('layouts.app')

@section('title', 'Log Aktivitas Sistem - Sarpras UKK')
@section('page_title', 'Log Aktivitas & Audit Trail')
@section('page_subtitle', 'Rekam jejak seluruh tindakan pengguna dalam sistem untuk keamanan dan akuntabilitas data')

@section('content')
<div class="space-y-6">

    <!-- Search Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-xs flex items-center justify-between">
        <form action="{{ route('log.index') }}" method="GET" class="flex-1 flex gap-2 max-w-md">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 text-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari aktivitas, nama pengguna, IP..." 
                    class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500">
            </div>
            <button type="submit" class="px-4 py-2 bg-slate-900 text-white font-bold text-xs rounded-xl hover:bg-slate-800">
                Cari
            </button>
            @if($search)
            <a href="{{ route('log.index') }}" class="px-3 py-2 bg-slate-100 text-slate-600 font-bold text-xs rounded-xl hover:bg-slate-200 flex items-center">
                Reset
            </a>
            @endif
        </form>
    </div>

    <!-- Logs Table -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 text-[11px] uppercase font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-3.5">Waktu Kejadian</th>
                        <th class="px-6 py-3.5">Pelaku / Pengguna</th>
                        <th class="px-6 py-3.5">Deskripsi Aktivitas</th>
                        <th class="px-6 py-3.5">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($logs as $log)
                    <tr class="hover:bg-slate-50/80 transition-all">
                        <td class="px-6 py-4 whitespace-nowrap text-slate-700">
                            <div class="font-bold">{{ $log->created_at->format('d/m/Y H:i:s') }}</div>
                            <div class="text-[10px] text-slate-400">{{ $log->created_at->diffForHumans() }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($log->user)
                                <div class="font-bold text-slate-900">{{ $log->user->name }}</div>
                                <span class="inline-block text-[10px] font-semibold text-slate-500 bg-slate-100 px-2 py-0.5 rounded">
                                    {{ $log->user->role }} &bull; {{ $log->user->username }}
                                </span>
                            @else
                                <span class="text-slate-400 italic">Sistem / Pengguna Tamu</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-800 font-medium">
                            {{ $log->aktivitas }}
                        </td>
                        <td class="px-6 py-4 font-mono text-slate-500 text-[11px]">
                            {{ $log->ip_address ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                            <i class="fa-solid fa-clock-rotate-left text-4xl mb-2 text-slate-300"></i>
                            <p>Belum ada catatan log aktivitas dalam sistem.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $logs->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
