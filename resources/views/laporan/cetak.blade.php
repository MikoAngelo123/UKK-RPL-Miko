<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Peminjaman Sarana Sekolah - UKK RPL 2026</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 12px; }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; margin: 0; background: #fff !important; }
            @page { margin: 1.5cm; size: A4 portrait; }
        }
    </style>
</head>
<body class="bg-slate-100 p-8 min-h-screen text-slate-800">

    <!-- Print Action Bar -->
    <div class="max-w-4xl mx-auto mb-6 flex items-center justify-between no-print bg-white p-4 rounded-xl shadow-xs border border-slate-200">
        <div class="text-xs text-slate-600">
            <strong>Petunjuk Cetak:</strong> Gunakan opsi cetak browser (Ctrl+P / Command+P) untuk mencetak langsung atau simpan sebagai PDF.
        </div>
        <div class="flex items-center space-x-2">
            <button onclick="window.close()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-lg">
                Tutup
            </button>
            <button onclick="window.print()" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-lg shadow-md">
                Cetak Dokumen (Print)
            </button>
        </div>
    </div>

    <!-- Main Printable Sheet -->
    <div class="max-w-4xl mx-auto bg-white p-10 rounded-2xl shadow-sm border border-slate-200 print:border-none print:shadow-none print:p-0">
        
        <!-- Official School Kop Surat -->
        <div class="text-center border-b-2 border-slate-900 pb-4 mb-6 relative">
            <h1 class="text-lg font-bold uppercase tracking-wider text-slate-900">PEMERINTAH DAERAH PROVINSI JAWA BARAT</h1>
            <h2 class="text-xl font-extrabold uppercase tracking-wide text-slate-900">DINAS PENDIDIKAN DAN KEBUDAYAAN</h2>
            <h3 class="text-2xl font-black text-slate-900 mt-0.5">SMK NEGERI CONTOH INDONESIA</h3>
            <p class="text-[11px] text-slate-600 mt-1">Konsentrasi Keahlian: Rekayasa Perangkat Lunak (RPL) &bull; Uji Kompetensi Keahlian 2026</p>
            <p class="text-[10px] text-slate-500">Jl. Pendidikan No. 123, Telp. (021) 1234567, Email: sarpras@smkindonesia.sch.id</p>
        </div>

        <!-- Document Title -->
        <div class="text-center mb-6">
            <h4 class="text-base font-bold uppercase tracking-wide text-slate-900 underline">LAPORAN REKAPITULASI PEMINJAMAN SARANA SEKOLAH</h4>
            <p class="text-xs text-slate-500 mt-1">
                @if($tglMulai && $tglSelesai)
                    Periode: {{ date('d F Y', strtotime($tglMulai)) }} s.d. {{ date('d F Y', strtotime($tglSelesai)) }}
                @else
                    Periode: Seluruh Riwayat Transaksi (All-Time)
                @endif
                @if($status)
                    &bull; Status: <strong>{{ $status }}</strong>
                @endif
            </p>
        </div>

        <!-- Data Table -->
        <div class="overflow-x-auto mb-6">
            <table class="w-full text-left text-xs border border-slate-300">
                <thead class="bg-slate-100 text-slate-800 font-bold uppercase text-[10px] border-b border-slate-300">
                    <tr>
                        <th class="px-3 py-2.5 border-r border-slate-300 text-center w-10">No</th>
                        <th class="px-3 py-2.5 border-r border-slate-300">Kode Transaksi</th>
                        <th class="px-3 py-2.5 border-r border-slate-300">Nama Peminjam</th>
                        <th class="px-3 py-2.5 border-r border-slate-300">Nama Alat / Barang</th>
                        <th class="px-3 py-2.5 border-r border-slate-300 text-center">Jml</th>
                        <th class="px-3 py-2.5 border-r border-slate-300">Tgl Pinjam</th>
                        <th class="px-3 py-2.5 border-r border-slate-300">Tgl Kembali</th>
                        <th class="px-3 py-2.5 border-r border-slate-300">Status</th>
                        <th class="px-3 py-2.5 text-right">Denda (Rp)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($peminjamans as $index => $p)
                    <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-slate-50/50' }}">
                        <td class="px-3 py-2 border-r border-slate-200 text-center font-semibold">{{ $index + 1 }}</td>
                        <td class="px-3 py-2 border-r border-slate-200 font-mono font-bold">{{ $p->kode_peminjaman }}</td>
                        <td class="px-3 py-2 border-r border-slate-200">{{ $p->user->name ?? 'User Terhapus' }}</td>
                        <td class="px-3 py-2 border-r border-slate-200 font-medium">{{ $p->alat->nama_alat ?? 'Alat Dihapus' }}</td>
                        <td class="px-3 py-2 border-r border-slate-200 text-center font-bold">{{ $p->jumlah_pinjam }}</td>
                        <td class="px-3 py-2 border-r border-slate-200">{{ $p->tgl_pinjam->format('d/m/Y') }}</td>
                        <td class="px-3 py-2 border-r border-slate-200">
                            {{ $p->tgl_kembali_aktual ? $p->tgl_kembali_aktual->format('d/m/Y') : $p->tgl_kembali_rencana->format('d/m/Y') }}
                        </td>
                        <td class="px-3 py-2 border-r border-slate-200 font-bold text-[11px]">{{ $p->status }}</td>
                        <td class="px-3 py-2 text-right font-mono">{{ number_format($p->denda, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-4 py-6 text-center text-slate-400 italic">Tidak ada transaksi yang terdata pada kriteria ini.</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-slate-100 font-bold text-slate-900 border-t-2 border-slate-300 text-xs">
                    <tr>
                        <td colspan="4" class="px-3 py-2 text-right border-r border-slate-300">TOTAL:</td>
                        <td class="px-3 py-2 text-center border-r border-slate-300">{{ $totalAlatDipinjam }} Unit</td>
                        <td colspan="3" class="px-3 py-2 border-r border-slate-300 text-right">{{ $totalPinjam }} Transaksi</td>
                        <td class="px-3 py-2 text-right font-mono text-rose-700">Rp {{ number_format($totalDenda, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Official Signatures -->
        <div class="grid grid-cols-2 gap-12 mt-12 text-center text-xs">
            <div>
                <p class="text-slate-500 mb-16">Mengetahui,<br>Kepala Program Keahlian RPL</p>
                <p class="font-bold text-slate-900 underline">Drs. H. Mulyadi, M.Kom</p>
                <p class="text-[10px] text-slate-400">NIP. 19780512 200312 1 002</p>
            </div>
            <div>
                <p class="text-slate-500 mb-16">{{ date('d F Y') }}<br>Pengelola Sarpras Sekolah,</p>
                <p class="font-bold text-slate-900 underline">Pengurus Sarpras / Tim UKK</p>
                <p class="text-[10px] text-slate-400">NIP. 19850920 201001 2 015</p>
            </div>
        </div>

    </div>

</body>
</html>
