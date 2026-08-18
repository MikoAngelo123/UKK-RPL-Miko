# LAPORAN EVALUASI & PENGUJIAN SISTEM (DEBUGGING LOG)
**Pengembangan Aplikasi Peminjaman Sarana Sekolah - UKK RPL 2026**

---

## 1. Hasil Pengujian Fungsionalitas (Test Cases)

| No | Modul / Fitur yang Diuji | Skenario Pengujian | Hasil yang Diharapkan | Status |
| :--- | :--- | :--- | :--- | :---: |
| 1 | **Autentikasi & RBAC** | Login dengan akun Admin, Petugas, dan Siswa | Mengarahkan ke tampilan dashboard sesuai hak akses masing-masing role | **BERHASIL (PASS)** |
| 2 | **Keamanan Middleware** | Siswa mencoba mengakses URL manajemen user (`/user`) | Sistem memblokir dan menampilkan error HTTP 403 Forbidden | **BERHASIL (PASS)** |
| 3 | **Katalog & Stok Alat** | Menampilkan alat dengan ketersediaan stok | Menampilkan badge kondisi fisik, kategori, dan sisa stok secara akurat | **BERHASIL (PASS)** |
| 4 | **Pengajuan Peminjaman** | Siswa mengajukan pinjam melebihi stok yang ada | Sistem menolak dengan pesan error peringatan batas stok maksimal | **BERHASIL (PASS)** |
| 5 | **Alur Persetujuan (Approval)** | Petugas menyetujui pengajuan peminjaman | Status berubah menjadi "Sedang Dipinjam" dan stok alat gudang otomatis berkurang | **BERHASIL (PASS)** |
| 6 | **Kalkulasi Denda Pengembalian** | Mengembalikan alat yang terlambat 2 hari dari rencana | Sistem menghitung denda otomatis Rp 10.000 (2 x Rp 5.000) dan stok bertambah | **BERHASIL (PASS)** |
| 7 | **Filter & Cetak Laporan** | Filter data berdasarkan tanggal/status dan klik Cetak PDF | Membuka format cetak kop surat resmi sekolah dengan rekapitulasi data akurat | **BERHASIL (PASS)** |
| 8 | **Log Audit Aktivitas** | Melakukan aksi tambah/ubah/hapus/login | Sistem secara otomatis mencatat riwayat tindakan beserta waktu dan IP | **BERHASIL (PASS)** |

---

## 2. Catatan Penanganan Masalah & Debugging (Troubleshooting Log)

1. **Penyesuaian Collation MySQL:**
   * *Kendala:* Database server lokal menggunakan MariaDB/MySQL versi standar yang memicu error `Unknown collation: utf8mb4_0900_ai_ci`.
   * *Solusi:* Mengubah konfigurasi collation di `config/database.php` menjadi `utf8mb4_unicode_ci` sehingga kompatibel 100% dengan semua versi MySQL/MariaDB (XAMPP / Laragon).

2. **Sinkronisasi Nama Tabel Relasional:**
   * *Kendala:* Laravel Eloquent secara default menjamakkan nama model bahasa Indonesia `Peminjaman` menjadi `peminjamen`.
   * *Solusi:* Menetapkan atribut eksplisit `protected $table = 'peminjamans';` pada model `Peminjaman`, `Alat`, dan `Kategori`.

3. **Integritas Transaksi Database:**
   * *Kendala:* Potensi inkonsistensi data jika pengurangan stok berhasil namun pencatatan transaksi peminjaman gagal di tengah jalan.
   * *Solusi:* Membungkus setiap aksi persetujuan dan pengembalian barang dalam `DB::transaction(function() { ... })` sehingga jika terjadi kegagalan, database otomatis melakukan rollback.

---

## 3. Kesimpulan Evaluasi
Sistem Aplikasi Peminjaman Sarana Sekolah telah diuji secara menyeluruh dan memenuhi seluruh standar kompetensi yang diujikan dalam Uji Kompetensi Keahlian (UKK) Rekayasa Perangkat Lunak Tahun Ajaran 2025/2026. Kode program tersusun secara modular, bersih (*clean code*), aman dari celah SQL Injection melalui Eloquent ORM, dan dilengkapi antarmuka pengguna yang responsif serta ramah pengguna.
