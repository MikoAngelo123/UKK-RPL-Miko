<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kategori;
use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\LogAktivitas;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin
        $admin = User::create([
            'name' => 'Administrator Sarpras',
            'username' => 'admin',
            'email' => 'admin@sekolah.sch.id',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'no_telp' => '081234567890',
            'alamat' => 'Ruang Sarpras Lantai 1',
        ]);

        // 2. Akun Petugas
        $petugas = User::create([
            'name' => 'Budi Santoso (Petugas Lab)',
            'username' => 'petugas',
            'email' => 'petugas@sekolah.sch.id',
            'password' => Hash::make('petugas123'),
            'role' => 'petugas',
            'no_telp' => '081234567891',
            'alamat' => 'Ruang Pengelola Alat',
        ]);

        // 3. Akun Peminjam (Siswa / Guru)
        $siswa1 = User::create([
            'name' => 'Ahmad Fauzi (XII RPL 1)',
            'username' => 'siswa1',
            'email' => 'ahmad@siswa.sch.id',
            'password' => Hash::make('siswa123'),
            'role' => 'peminjam',
            'no_telp' => '081298765432',
            'alamat' => 'Jl. Merdeka No. 12',
        ]);

        $siswa2 = User::create([
            'name' => 'Siti Nurhaliza (XII RPL 2)',
            'username' => 'siswa2',
            'email' => 'siti@siswa.sch.id',
            'password' => Hash::make('siswa123'),
            'role' => 'peminjam',
            'no_telp' => '081287654321',
            'alamat' => 'Jl. Pemuda No. 45',
        ]);

        // 4. Kategori Alat
        $kat1 = Kategori::create([
            'nama_kategori' => 'Elektronik & Multimedia',
            'deskripsi' => 'Peralatan proyektor, kabel VGA/HDMI, kamera, speaker audio, mic.',
        ]);

        $kat2 = Kategori::create([
            'nama_kategori' => 'Laboratorium Komputer & Jaringan',
            'deskripsi' => 'Crimping tool, tester LAN kabel, switch hub, router mikrotik, converter.',
        ]);

        $kat3 = Kategori::create([
            'nama_kategori' => 'Peralatan Bengkel & Praktik',
            'deskripsi' => 'Solder listrik, multimeter digital, toolkit set, bor tangan, tang kombinasi.',
        ]);

        $kat4 = Kategori::create([
            'nama_kategori' => 'Sarana Olahraga & Seni',
            'deskripsi' => 'Bola basket, bola voli, raket badminton, matras senam, gitar akustik.',
        ]);

        // 5. Daftar Alat / Barang
        $alat1 = Alat::create([
            'kode_alat' => 'ALT-ELK-001',
            'nama_alat' => 'Proyektor Epson EB-X400 HDMI',
            'id_kategori' => $kat1->id_kategori,
            'stok' => 5,
            'kondisi' => 'Baik',
            'deskripsi' => 'Proyektor 3300 Lumens resolusi XGA lengkap kabel HDMI dan tas.',
        ]);

        $alat2 = Alat::create([
            'kode_alat' => 'ALT-ELK-002',
            'nama_alat' => 'Pointer Wireless Presenter Laser',
            'id_kategori' => $kat1->id_kategori,
            'stok' => 8,
            'kondisi' => 'Baik',
            'deskripsi' => 'Laser pointer presentasi wireless USB receiver 2.4GHz.',
        ]);

        $alat3 = Alat::create([
            'kode_alat' => 'ALT-LAB-001',
            'nama_alat' => 'LAN Cable Tester RJ45 & RJ11',
            'id_kategori' => $kat2->id_kategori,
            'stok' => 12,
            'kondisi' => 'Baik',
            'deskripsi' => 'Tester kabel jaringan RJ45 dengan indikator LED 1-8.',
        ]);

        $alat4 = Alat::create([
            'kode_alat' => 'ALT-LAB-002',
            'nama_alat' => 'Tang Crimping RJ45 RJ11 Cat5/Cat6',
            'id_kategori' => $kat2->id_kategori,
            'stok' => 10,
            'kondisi' => 'Baik',
            'deskripsi' => 'Crimping tool modular 3-in-1 pemotong dan pengupas kabel UTP.',
        ]);

        $alat5 = Alat::create([
            'kode_alat' => 'ALT-BGK-001',
            'nama_alat' => 'Digital Multimeter Sanwa CD800a',
            'id_kategori' => $kat3->id_kategori,
            'stok' => 6,
            'kondisi' => 'Baik',
            'deskripsi' => 'Multitester digital pengukur tegangan AC/DC, resistansi, kontinuitas.',
        ]);

        $alat6 = Alat::create([
            'kode_alat' => 'ALT-OLR-001',
            'nama_alat' => 'Bola Basket Molten GG7X Original',
            'id_kategori' => $kat4->id_kategori,
            'stok' => 4,
            'kondisi' => 'Baik',
            'deskripsi' => 'Bola basket standar FIBA official match ball size 7.',
        ]);

        // 6. Transaksi Peminjaman Contoh
        Peminjaman::create([
            'kode_peminjaman' => 'PINJAM-20260216-001',
            'user_id' => $siswa1->id,
            'id_alat' => $alat1->id_alat,
            'jumlah_pinjam' => 1,
            'tgl_pinjam' => Carbon::now()->subDays(2)->format('Y-m-d'),
            'tgl_kembali_rencana' => Carbon::now()->addDays(1)->format('Y-m-d'),
            'status' => 'Sedang Dipinjam',
            'catatan_peminjam' => 'Untuk presentasi tugas akhir sidang UKK di Aula Lt. 2',
            'catatan_petugas' => 'Kelengkapan kabel power dan HDMI lengkap.',
            'denda' => 0,
        ]);

        Peminjaman::create([
            'kode_peminjaman' => 'PINJAM-20260216-002',
            'user_id' => $siswa2->id,
            'id_alat' => $alat3->id_alat,
            'jumlah_pinjam' => 2,
            'tgl_pinjam' => Carbon::now()->format('Y-m-d'),
            'tgl_kembali_rencana' => Carbon::now()->addDays(2)->format('Y-m-d'),
            'status' => 'Menunggu Konfirmasi',
            'catatan_peminjam' => 'Praktik instalasi jaringan komputer kelas XII RPL 2',
            'catatan_petugas' => null,
            'denda' => 0,
        ]);

        Peminjaman::create([
            'kode_peminjaman' => 'PINJAM-20260210-003',
            'user_id' => $siswa1->id,
            'id_alat' => $alat5->id_alat,
            'jumlah_pinjam' => 1,
            'tgl_pinjam' => Carbon::now()->subDays(6)->format('Y-m-d'),
            'tgl_kembali_rencana' => Carbon::now()->subDays(4)->format('Y-m-d'),
            'tgl_kembali_aktual' => Carbon::now()->subDays(4)->format('Y-m-d'),
            'status' => 'Dikembalikan',
            'catatan_peminjam' => 'Pengujian modul power supply komputer',
            'catatan_petugas' => 'Dikembalikan tepat waktu dalam kondisi baik.',
            'denda' => 0,
        ]);

        // 7. Log Aktivitas
        LogAktivitas::catat($admin->id, 'Inisialisasi sistem aplikasi peminjaman barang dan inventarisasi master data.');
    }
}
