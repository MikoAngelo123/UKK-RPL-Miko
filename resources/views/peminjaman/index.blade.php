@extends('layouts.app')

@section('title', 'Data Peminjaman Alat - Sarpras UKK')
@section('page_title', Auth::user()->role === 'peminjam' ? 'Riwayat Peminjaman Saya' : 'Manajemen Peminjaman & Pengembalian')
@section('page_subtitle', 'Pantau alur persetujuan, batas pengembalian, status alat, dan denda keterlambatan')

@section('content')
<div class="space-y-6">

    <!-- Top Multi-Criteria Filter Bar -->
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs">
        <form action="{{ route('peminjaman.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 text-xs">
            
            <!-- Search -->
            <div class="lg:col-span-2 relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 text-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari kode transaksi, nama peminjam, alat..." 
                    class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500">
            </div>

            <!-- Status Filter -->
            <div>
                <select name="status" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500">
                    <option value="">-- Semua Status --</option>
                    <option value="Menunggu Konfirmasi" {{ $status == 'Menunggu Konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                    <option value="Sedang Dipinjam" {{ $status == 'Sedang Dipinjam' ? 'selected' : '' }}>Sedang Dipinjam</option>
                    <option value="Dikembalikan" {{ $status == 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                    <option value="Ditolak" {{ $status == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <!-- Date From -->
            <div>
                <input type="date" name="tgl_mulai" value="{{ $tglMulai }}" title="Dari Tanggal"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500">
            </div>

            <!-- Date To -->
            <div class="flex gap-2">
                <input type="date" name="tgl_selesai" value="{{ $tglSelesai }}" title="Sampai Tanggal"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500">
                <button type="submit" class="px-4 py-2 bg-slate-900 text-white font-bold text-xs rounded-xl hover:bg-slate-800 transition-all flex items-center">
                    <i class="fa-solid fa-filter"></i>
                </button>
            </div>

        </form>

        <div class="flex items-center justify-between mt-4 pt-4 border-t border-slate-100 text-xs">
            <div class="text-slate-400">
                Menampilkan <span class="font-bold text-slate-700">{{ $peminjamans->total() }}</span> total transaksi
            </div>
            <div class="flex items-center space-x-2">
                @if($search || $status || $tglMulai || $tglSelesai || $userId)
                <a href="{{ route('peminjaman.index') }}" class="px-3 py-1.5 bg-slate-100 text-slate-600 font-bold rounded-lg hover:bg-slate-200">
                    Reset Filter
                </a>
                @endif
                <a href="{{ route('peminjaman.create') }}" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-xl shadow-md shadow-brand-600/30 transition-all flex items-center space-x-1.5">
                    <i class="fa-solid fa-plus-circle"></i>
                    <span>Ajukan Peminjaman</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Peminjaman Table -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 text-[11px] uppercase font-bold tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5">Kode Transaksi</th>
                        <th class="px-5 py-3.5">Peminjam</th>
                        <th class="px-5 py-3.5">Alat yang Dipinjam</th>
                        <th class="px-5 py-3.5">Jadwal Pinjam - Kembali</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5">Denda</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($peminjamans as $p)
                    <tr class="hover:bg-slate-50/80 transition-all">
                        
                        <!-- Kode Transaksi -->
                        <td class="px-5 py-4">
                            <div class="font-bold text-slate-900">{{ $p->kode_peminjaman }}</div>
                            <div class="text-[11px] text-slate-400">{{ $p->created_at->format('d/m/Y H:i') }}</div>
                        </td>

                        <!-- Peminjam -->
                        <td class="px-5 py-4">
                            <div class="font-bold text-slate-800">{{ $p->user->name ?? 'User Terhapus' }}</div>
                            <div class="text-[11px] text-slate-500">{{ $p->user->username ?? '-' }} &bull; {{ $p->user->no_telp ?? '-' }}</div>
                        </td>

                        <!-- Alat -->
                        <td class="px-5 py-4">
                            <div class="font-semibold text-slate-900">{{ $p->alat->nama_alat ?? 'Alat Dihapus' }}</div>
                            <div class="text-[11px] text-brand-600 font-bold">
                                {{ $p->jumlah_pinjam }} Unit &bull; <span class="text-slate-400">{{ $p->alat->kategori->nama_kategori ?? '-' }}</span>
                            </div>
                        </td>

                        <!-- Jadwal -->
                        <td class="px-5 py-4">
                            <div class="text-slate-700">
                                <i class="fa-regular fa-calendar-check text-emerald-500 mr-1"></i> {{ $p->tgl_pinjam->format('d M Y') }}
                            </div>
                            <div class="text-slate-500 text-[11px] mt-0.5">
                                <i class="fa-regular fa-calendar-xmark text-rose-500 mr-1"></i> Rencana: {{ $p->tgl_kembali_rencana->format('d M Y') }}
                            </div>
                            @if($p->tgl_kembali_aktual)
                            <div class="text-emerald-700 font-semibold text-[11px] mt-0.5">
                                <i class="fa-solid fa-check-double mr-1"></i> Aktual: {{ $p->tgl_kembali_aktual->format('d M Y') }}
                            </div>
                            @endif
                        </td>

                        <!-- Status Badge -->
                        <td class="px-5 py-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $p->status_badge }}">
                                {{ $p->status }}
                            </span>
                            @if($p->catatan_petugas)
                            <div class="text-[10px] text-slate-500 mt-1 max-w-[150px] truncate" title="{{ $p->catatan_petugas }}">
                                <i class="fa-regular fa-comment-dots mr-0.5"></i> {{ $p->catatan_petugas }}
                            </div>
                            @endif
                        </td>

                        <!-- Denda -->
                        <td class="px-5 py-4">
                            @if($p->denda > 0)
                                <span class="font-bold text-rose-600">Rp {{ number_format($p->denda, 0, ',', '.') }}</span>
                            @else
                                <span class="text-slate-400">Rp 0</span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="px-5 py-4 text-right space-x-1 whitespace-nowrap">
                            <a href="{{ route('peminjaman.show', $p->id_peminjaman) }}" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-lg text-xs transition-all" title="Lihat Detail & Cetak Bukti">
                                <i class="fa-solid fa-eye mr-0.5"></i> Detail
                            </a>

                            @if(Auth::user()->isAdmin() || Auth::user()->isPetugas())
                                <!-- Approval Buttons -->
                                @if($p->status === 'Menunggu Konfirmasi')
                                <button type="button" onclick="openApproveModal({{ $p->id_peminjaman }}, '{{ $p->kode_peminjaman }}', '{{ addslashes($p->user->name ?? '') }}', '{{ addslashes($p->alat->nama_alat ?? '') }}', {{ $p->jumlah_pinjam }})"
                                    class="px-2.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg text-xs transition-all shadow-xs" title="Setujui Peminjaman">
                                    <i class="fa-solid fa-check mr-0.5"></i> Setujui
                                </button>
                                <button type="button" onclick="openRejectModal({{ $p->id_peminjaman }}, '{{ $p->kode_peminjaman }}')"
                                    class="px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold rounded-lg text-xs transition-all" title="Tolak Pengajuan">
                                    <i class="fa-solid fa-xmark mr-0.5"></i> Tolak
                                </button>
                                @endif

                                <!-- Return Button -->
                                @if($p->status === 'Sedang Dipinjam' || $p->status === 'Disetujui')
                                <button type="button" onclick="openReturnModal({{ $p->id_peminjaman }}, '{{ $p->kode_peminjaman }}', '{{ addslashes($p->alat->nama_alat ?? '') }}', '{{ $p->tgl_kembali_rencana->format('Y-m-d') }}')"
                                    class="px-2.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg text-xs transition-all shadow-xs" title="Proses Pengembalian Barang">
                                    <i class="fa-solid fa-arrow-rotate-left mr-0.5"></i> Kembalikan
                                </button>
                                @endif
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                            <i class="fa-solid fa-handshake-slash text-4xl mb-2 text-slate-300"></i>
                            <p>Tidak ada data peminjaman yang cocok dengan filter pencarian.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($peminjamans->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $peminjamans->links() }}
        </div>
        @endif
    </div>

</div>

<!-- APPROVE MODAL -->
<div id="approveModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 animate-scale-in">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
            <h3 class="font-bold text-slate-900 text-base">Konfirmasi Persetujuan Peminjaman</h3>
            <button onclick="closeModal('approveModal')" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        <form id="approveForm" method="POST" class="space-y-4">
            @csrf
            @method('PATCH')
            <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-xs text-emerald-800" id="approveSummary">
                <!-- Javascript will inject summary -->
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Catatan Petugas (Opsional)</label>
                <textarea name="catatan_petugas" rows="2" placeholder="Contoh: Kondisi barang diambil lengkap..."
                    class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500"></textarea>
            </div>
            <div class="flex justify-end space-x-2 pt-2">
                <button type="button" onclick="closeModal('approveModal')" class="px-4 py-2 bg-slate-100 text-slate-700 font-bold text-xs rounded-xl">Batal</button>
                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md">Setujui & Serahkan Barang</button>
            </div>
        </form>
    </div>
</div>

<!-- REJECT MODAL -->
<div id="rejectModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 animate-scale-in">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
            <h3 class="font-bold text-slate-900 text-base">Tolak Pengajuan Peminjaman</h3>
            <button onclick="closeModal('rejectModal')" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        <form id="rejectForm" method="POST" class="space-y-4">
            @csrf
            @method('PATCH')
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Alasan Penolakan <span class="text-rose-500">*</span></label>
                <textarea name="alasan_penolakan" rows="3" required placeholder="Contoh: Alat sedang dipesan untuk kegiatan ujian sekolah..."
                    class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500"></textarea>
            </div>
            <div class="flex justify-end space-x-2 pt-2">
                <button type="button" onclick="closeModal('rejectModal')" class="px-4 py-2 bg-slate-100 text-slate-700 font-bold text-xs rounded-xl">Batal</button>
                <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-md">Tolak Pengajuan</button>
            </div>
        </form>
    </div>
</div>

<!-- RETURN PROCESS MODAL -->
<div id="returnModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 animate-scale-in">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
            <h3 class="font-bold text-slate-900 text-base">Proses Pengembalian Alat</h3>
            <button onclick="closeModal('returnModal')" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        <form id="returnForm" method="POST" class="space-y-4">
            @csrf
            @method('PATCH')
            <div class="p-3 bg-blue-50 border border-blue-200 rounded-xl text-xs text-blue-900" id="returnSummary">
                <!-- Javascript will inject return summary and automatic late fee calculation -->
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Denda Tambahan Kerusakan/Kehilangan (Rp)</label>
                <input type="number" name="denda_tambahan" value="0" min="0" step="1000"
                    class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500">
                <p class="text-[10px] text-slate-400 mt-1">Denda keterlambatan sistem dihitung otomatis Rp 5.000/hari.</p>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Catatan Kondisi Barang Saat Kembali</label>
                <textarea name="catatan_petugas" rows="2" placeholder="Contoh: Dikembalikan tepat waktu dalam kondisi fisik baik dan berfungsi normal."
                    class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500"></textarea>
            </div>
            <div class="flex justify-end space-x-2 pt-2">
                <button type="button" onclick="closeModal('returnModal')" class="px-4 py-2 bg-slate-100 text-slate-700 font-bold text-xs rounded-xl">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-md">Konfirmasi Pengembalian</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openApproveModal(id, kode, user, alat, qty) {
        document.getElementById('approveForm').action = "/peminjaman/" + id + "/approve";
        document.getElementById('approveSummary').innerHTML = `
            <strong>Transaksi:</strong> ${kode}<br>
            <strong>Peminjam:</strong> ${user}<br>
            <strong>Alat:</strong> ${alat} (${qty} Unit)<br>
            <span class="text-[11px] text-emerald-600 mt-1 block">Stok gudang akan otomatis dikurangi ${qty} unit.</span>
        `;
        document.getElementById('approveModal').classList.remove('hidden');
    }
    function openRejectModal(id, kode) {
        document.getElementById('rejectForm').action = "/peminjaman/" + id + "/reject";
        document.getElementById('rejectModal').classList.remove('hidden');
    }
    function openReturnModal(id, kode, alat, tglRencana) {
        document.getElementById('returnForm').action = "/peminjaman/" + id + "/return";
        
        let today = new Date();
        let planDate = new Date(tglRencana);
        let timeDiff = today.getTime() - planDate.getTime();
        let daysLate = Math.ceil(timeDiff / (1000 * 3600 * 24)) - 1;
        let fine = 0;
        let lateInfo = "Pengembalian Tepat Waktu (Tidak ada denda keterlambatan)";
        
        if (daysLate > 0) {
            fine = daysLate * 5000;
            lateInfo = `<span class="text-rose-600 font-bold">Terlambat ${daysLate} hari. Estimasi Denda Keterlambatan: Rp ${fine.toLocaleString('id-ID')}</span>`;
        }

        document.getElementById('returnSummary').innerHTML = `
            <strong>Transaksi:</strong> ${kode}<br>
            <strong>Alat:</strong> ${alat}<br>
            <strong>Batas Kembali:</strong> ${tglRencana}<br>
            <div class="mt-1">${lateInfo}</div>
        `;
        document.getElementById('returnModal').classList.remove('hidden');
    }
    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }
</script>
@endsection
