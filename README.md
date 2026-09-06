# 📦 Sistem Informasi Peminjaman Sarana & Inventaris Sekolah (PinjamSarpras)
> **Uji Kompetensi Keahlian (UKK) Rekayasa Perangkat Lunak (RPL) 2026 — Paket 1**

![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Status](https://img.shields.io/badge/Status-100%25_Completed_%26_Passed-059669?style=for-the-badge)

Aplikasi web modern untuk manajemen sirkulasi peminjaman, pengembalian, inventarisasi alat/sarana sekolah, kalkulasi denda keterlambatan otomatis, dan pencetakan laporan resmi berstandar Dinas Pendidikan dengan Multi-Level Role-Based Access Control (**Admin, Petugas Sarpras, dan Siswa/Peminjam**).

---

## 📑 Daftar Isi
1. [Fitur Utama & Keunggulan](#-fitur-utama--keunggulan)
2. [Hak Akses & Akun Demo](#-hak-akses--akun-demo)
3. [Struktur Basis Data & ERD](#-struktur-basis-data--erd)
4. [Katalog Tangkapan Layar (Screenshots)](#-katalog-tangkapan-layar-screenshots)
5. [Hasil Eksekusi 5 Test Case Wajib UKK](#-hasil-eksekusi-5-test-case-wajib-ukk)
6. [Panduan Instalasi & Menjalankan Sistem](#-panduan-instalasi--menjalankan-sistem)
7. [Checklist Pemenuhan Kompetensi (11 Kriteria)](#-checklist-pemenuhan-kompetensi-11-kriteria)

---

## ✨ Fitur Utama & Keunggulan

* 🔐 **Multi-Level RBAC (Role-Based Access Control):** 3 tingkat hak akses terisolasi (*Admin*, *Petugas*, *Siswa/Peminjam*) dengan middleware proteksi rute & halaman custom error *403 Forbidden*.
* 📦 **Manajemen Inventaris Alat (CRUD Alat):** Pencatatan kode barcode unik, kategori, stok fisik, kondisi fisik (*Baik / Perlu Perbaikan / Rusak*), foto alat, dan spesifikasi teknis.
* 🏷️ **Master Kategori Sarana (CRUD Kategori):** Pengelompokan alat berdasarkan jenis sarana prasarana sekolah.
* 👥 **Manajemen Pengguna (CRUD User):** Pengelolaan akun, peran pengguna, kontak, dan alamat/kelas.
* 🔄 **Sirkulasi Peminjaman & Persetujuan (Approval Workflow):**
  * Siswa mengajukan permohonan peminjaman mandiri melalui katalog alat.
  * Petugas/Admin memverifikasi dan menyetujui (*Approve*) atau menolak (*Reject*) dengan alasan.
  * Stok berkurang otomatis secara aman (*ACID Database Transaction*).
* 💰 **Kalkulasi Denda Keterlambatan Otomatis:** Sistem mendeteksi selisih hari keterlambatan terhadap tanggal rencana kembali dengan tarif denda harian (Rp 5.000/hari) serta opsi denda kerusakan fisik.
* 📄 **Laporan Terpadu & Cetak Surat Resmi (Kop Surat):** Pratinjau filter laporan dinamis dan fitur cetak dokumen format A4 lengkap dengan Kop Surat Sekolah dan kolom tanda tangan pengesahan Kepala Sekolah & Pengelola Sarpras.
* 🛡️ **Audit Trail (Log Aktivitas):** Rekam jejak seluruh aktivitas login, transaksi, modifikasi data, dan alamat IP pengguna.

---

## 👥 Hak Akses & Akun Demo

Aplikasi dilengkapi tombol **1-Click Demo Credentials** di halaman login untuk mempermudah pengujian:

| Role | Username | Password | Deskripsi Kewenangan |
| :--- | :--- | :--- | :--- |
| **Administrator** | `admin` | `admin123` | Akses penuh: Kelola User, Kategori, Alat, Transaksi, Laporan, & Log Audit Sistem. |
| **Petugas Sarpras** | `petugas` | `petugas123` | Kelola Sirkulasi: Approval/Reject Peminjaman, Proses Pengembalian, Denda, & Cetak Laporan. |
| **Siswa / Peminjam** | `siswa1` | `siswa123` | Eksplorasi Katalog Alat, Pengajuan Pinjam Mandiri, & Pemantauan Riwayat Peminjaman. |
| **Siswa 2** | `siswa2` | `siswa123` | Akun peminjam kedua untuk pengujian multi-user. |

---

## 🗄️ Struktur Basis Data & ERD

Diagram Relasi Entitas (*Entity Relationship Diagram*):

```mermaid
erDiagram
    USERS ||--o{ PEMINJAMANS : "mengajukan/meminjam"
    USERS ||--o{ LOG_AKTIVITAS : "melakukan aktivitas"
    KATEGORIS ||--o{ ALATS : "mengelompokkan"
    ALATS ||--o{ PEMINJAMANS : "dipinjam dalam transaksi"

    USERS {
        bigint id PK
        string name "Nama Lengkap"
        string username UK "Username Login"
        string email "Alamat Email"
        string password "Bcrypt Hash"
        enum role "admin, petugas, peminjam"
        string no_telp "Nomor Telepon"
        text alamat "Kelas / Alamat"
        timestamps created_at
    }

    KATEGORIS {
        bigint id_kategori PK
        string nama_kategori "Nama Kategori"
        text deskripsi "Deskripsi Kategori"
        timestamps created_at
    }

    ALATS {
        bigint id_alat PK
        string kode_alat UK "Kode Unik Sarana"
        string nama_alat "Nama Barang/Alat"
        bigint id_kategori FK "Relasi Kategori"
        int stok "Jumlah Stok Tersedia"
        enum kondisi "Baik, Perlu Perbaikan, Rusak"
        string foto "Path Foto Alat"
        text deskripsi "Spesifikasi Teknis"
        timestamps created_at
    }

    PEMINJAMANS {
        bigint id_peminjaman PK
        string kode_peminjaman UK "Format: PINJAM-YYYYMMDD-XXXX"
        bigint user_id FK "Relasi Peminjam"
        bigint id_alat FK "Relasi Alat"
        int jumlah_pinjam "Qty Pinjam"
        date tgl_pinjam "Tanggal Pinjam"
        date tgl_kembali_rencana "Tenggat Waktu Kembali"
        date tgl_kembali_aktual "Tanggal Realisasi Kembali"
        enum status "Menunggu Konfirmasi, Disetujui, Sedang Dipinjam, Dikembalikan, Ditolak"
        text catatan_peminjam "Keperluan Peminjaman"
        text catatan_petugas "Catatan Kondisi / Penolakan"
        decimal denda "Nominal Denda Terhitung"
        timestamps created_at
    }

    LOG_AKTIVITAS {
        bigint id PK
        bigint user_id FK
        text aktivitas "Deskripsi Aksi"
        string ip_address "Alamat IP"
        string user_agent "Info Perangkat"
        timestamps created_at
    }
```

---

## 📸 Katalog Tangkapan Layar (Screenshots)

Semua berkas tangkapan layar tersimpan pada folder [`screenshots/`](screenshots/):

### 0. Bukti Eksekusi Server & Database MySQL (Kriteria 1 & 2)
| Project Laravel Berhasil Dijalankan (`php artisan serve`) | Database MySQL Berhasil Dibuat (`peminjaman_barang`) |
| :---: | :---: |
| ![Server Running](screenshots/00_laravel_server_running.png) | ![Database MySQL](screenshots/00_database_mysql_phpmyadmin.png) |

---

### 1. Autentikasi & Registrasi
| Halaman Login (1-Click Demo) | Halaman Registrasi Akun Siswa |
| :---: | :---: |
| ![Halaman Login](screenshots/01_halaman_login.png) | ![Halaman Register](screenshots/02_halaman_register.png) |

---

### 2. Panel Administrator
| Dashboard Administrator | Manajemen Master Kategori (CRUD) |
| :---: | :---: |
| ![Dashboard Admin](screenshots/03_dashboard_admin.png) | ![Kategori Index](screenshots/04_crud_kategori_index.png) |

| Modal Input Tambah Kategori | Katalog Master Inventaris Alat |
| :---: | :---: |
| ![Modal Tambah Kategori](screenshots/04b_crud_kategori_modal_tambah.png) | ![Katalog Alat](screenshots/05_crud_alat_katalog.png) |

| Form Tambah Alat Baru | Form Edit Data Alat |
| :---: | :---: |
| ![Form Tambah Alat](screenshots/06_crud_alat_tambah_form.png) | ![Form Edit Alat](screenshots/07_crud_alat_edit_form.png) |

| Rincian / Detail Spesifikasi Alat | Manajemen Pengguna & Otoritas Role |
| :---: | :---: |
| ![Detail Alat](screenshots/07b_crud_alat_detail.png) | ![Manajemen User](screenshots/08_crud_user_manajemen.png) |

| Form Tambah Pengguna Baru | Monitoring Seluruh Transaksi Peminjaman |
| :---: | :---: |
| ![Tambah User Form](screenshots/09_crud_user_tambah_form.png) | ![Daftar Peminjaman](screenshots/10_daftar_transaksi_peminjaman.png) |

| Surat Bukti / Kuitansi Peminjaman | Rekapitulasi Laporan Transaksi & Denda |
| :---: | :---: |
| ![Detail Bukti](screenshots/11_detail_bukti_peminjaman.png) | ![Laporan Rekapitulasi](screenshots/12_laporan_rekapitulasi.png) |

| Format Cetak Dokumen Resmi Kop Surat (A4) | Riwayat Log Audit Aktivitas Sistem |
| :---: | :---: |
| ![Cetak Kop Surat](screenshots/13_cetak_laporan_kop_surat.png) | ![Log Audit](screenshots/14_log_audit_aktivitas.png) |

---

### 3. Panel Petugas Sarpras
| Dashboard Petugas Sarpras | Antarmuka Verifikasi Transaksi (Petugas) |
| :---: | :---: |
| ![Dashboard Petugas](screenshots/15_dashboard_petugas.png) | ![Kelola Peminjaman Petugas](screenshots/16_peminjaman_kelola_petugas.png) |

| Modal Persetujuan (Approval) Peminjaman | Modal Penolakan (Reject) Peminjaman |
| :---: | :---: |
| ![Modal Approval](screenshots/16b_modal_persetujuan_approval.png) | ![Modal Penolakan](screenshots/16c_modal_penolakan.png) |

---

### 4. Panel Peminjam (Siswa)
| Dashboard Siswa | Katalog Peminjaman Siswa |
| :---: | :---: |
| ![Dashboard Siswa](screenshots/17_dashboard_siswa.png) | ![Katalog Siswa](screenshots/18_katalog_alat_siswa.png) |

| Form Pengajuan Pinjam Mandiri | Riwayat Peminjaman Siswa |
| :---: | :---: |
| ![Form Pengajuan Siswa](screenshots/19_form_pengajuan_peminjaman.png) | ![Riwayat Peminjaman](screenshots/20_riwayat_peminjaman_siswa.png) |

---

## 🎯 Hasil Eksekusi 5 Test Case Wajib UKK

Pengujian dilakukan secara otomatis dan komprehensif untuk memverifikasi 5 skenario inti pada lembar tugas UKK RPL:

### 🔹 Test Case 1: Login User Sesuai Role
* **Tujuan:** Memastikan autentikasi berhasil memisahkan session dan antarmuka dashboard untuk 3 level pengguna.
* **Hasil:** 🟢 **PASS** (Admin, Petugas, dan Siswa berhasil login dan diarahkan ke dashboard masing-masing).

| Login Admin | Login Petugas | Login Siswa |
| :---: | :---: | :---: |
| ![TC1 Admin](screenshots/testcase_1_login_admin.png) | ![TC1 Petugas](screenshots/testcase_1_login_petugas.png) | ![TC1 Siswa](screenshots/testcase_1_login_siswa.png) |

---

### 🔹 Test Case 2: Tambah Alat (Master Data)
* **Tujuan:** Memastikan data alat baru yang diinput tersimpan secara valid di database MySQL dan langsung muncul di katalog inventaris.
* **Hasil:** 🟢 **PASS** (Kamera Mirrorless Sony Alpha A6400 stok 3 unit berhasil tersimpan dengan notifikasi sukses).

| Form Pengisian Alat Baru | Data Tersimpan di Katalog & Database |
| :---: | :---: |
| ![TC2 Form](screenshots/testcase_2_form_isi_alat.png) | ![TC2 Saved](screenshots/testcase_2_alat_tersimpan.png) |

---

### 🔹 Test Case 3: Pinjam Alat (Pengajuan Mandiri)
* **Tujuan:** Siswa mengajukan peminjaman sarana mandiri; sistem menghasilkan kode transaksi unik `PINJAM-YYYYMMDD-XXXX` berstatus *Menunggu Konfirmasi*.
* **Hasil:** 🟢 **PASS** (Transaksi tercatat di database dan antrean verifikasi petugas).

| Form Pengajuan Peminjaman | Pengajuan Tercatat di Riwayat Transaksi |
| :---: | :---: |
| ![TC3 Form Pinjam](screenshots/testcase_3_form_pengajuan.png) | ![TC3 Pengajuan Tercatat](screenshots/testcase_3_pengajuan_tercatat.png) |

---

### 🔹 Test Case 4: Pengembalian Alat & Kalkulasi Denda
* **Tujuan:** Petugas memproses pengembalian alat yang melewati batas tanggal rencana; sistem menghitung denda otomatis (Rp 5.000/hari terlambat), merestorasi stok fisik ke gudang, dan mengubah status menjadi *Dikembalikan*.
* **Hasil:** 🟢 **PASS** (Terlambat 3 hari terhitung denda Rp 15.000 secara presisi).

| Modal Pengembalian (Denda Terdeteksi Rp 15.000) | Status Dikembalikan & Denda Terakumulasi |
| :---: | :---: |
| ![TC4 Modal Return](screenshots/testcase_4_modal_pengembalian.png) | ![TC4 Status Returned](screenshots/testcase_4_status_dikembalikan.png) |

---

### 🔹 Test Case 5: Privilege User (Role-Based Access Control Security)
* **Tujuan:** Menguji keamanan rute; siswa dilarang mengakses modul manajemen user (`/user`) dan diblokir oleh `RoleMiddleware`.
* **Hasil:** 🟢 **PASS** (Sistem menolak akses dan menampilkan antarmuka keamanan *HTTP 403 Forbidden*).

| Akses Terlarang Diblokir Middleware (HTTP 403) |
| :---: |
| ![TC5 403 Forbidden](screenshots/testcase_5_privilege_blocked_403.png) |

---

## 🚀 Panduan Instalasi & Menjalankan Sistem

### 1. Kebutuhan Sistem
* PHP >= 8.2 (dengan ekstensi `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo`)
* Composer >= 2.x
* MySQL / MariaDB Server (XAMPP / Laragon / Native)
* Web Browser Modern (Google Chrome / Microsoft Edge / Mozilla Firefox)

### 2. Langkah Instalasi Cepat

```bash
# 1. Clone repository project
git clone https://github.com/Artvael/UKK-RPL.git
cd UKK-RPL

# 2. Instal dependensi PHP via Composer
composer install

# 3. Salin konfigurasi environment
cp .env.example .env

# 4. Generate Application Key
php artisan key:generate

# 5. Konfigurasi Database di file .env
# Sesuaikan DB_DATABASE=peminjaman_barang, DB_USERNAME=root, DB_PASSWORD=

# 6. Jalankan Migrasi & Seeder Data Awal
php artisan migrate:fresh --seed

# 7. Jalankan Server Web Lokal
php artisan serve
```

Akses aplikasi di browser: **`http://127.0.0.1:8000`**

---

## 📋 Checklist Pemenuhan Kompetensi (11 Kriteria)

Berdasarkan lembar instrumen penilaian Uji Kompetensi Keahlian (UKK) RPL 2026:

- [x] **1. Project Laravel berhasil dijalankan:** Terverifikasi berjalan mulus pada server lokal.
- [x] **2. Database MySQL berhasil dibuat:** Skema tabel dan relasi foreign key lengkap (Dump: [`database_peminjaman_sarpras.sql`](database_peminjaman_sarpras.sql)).
- [x] **3. Panel Admin berhasil diakses:** Dashboard lengkap dengan visualisasi KPI, grafik status, dan aksi cepat.
- [x] **4. CRUD User / Role:** Tambah, edit, filter role, dan hapus user dengan validasi keamanan.
- [x] **5. CRUD Kategori:** Manajemen kategori sarana prasarana sekolah dengan modal interaktif.
- [x] **6. CRUD Alat:** Katalog inventaris alat, upload foto, status kondisi fisik, dan stok gudang.
- [x] **7. CRUD Peminjaman:** Alur pengajuan mandiri, approval petugas, dan rekam jejak peminjaman.
- [x] **8. Pengembalian & Denda:** Kalkulasi denda otomatis (Rp 5.000/hari) dan pemulihan stok barang.
- [x] **9. Hak Akses 3 Level User:** Pemisahan hak akses Admin, Petugas, dan Siswa via middleware RBAC.
- [x] **10. Pengujian Minimal 5 Skenario:** 5/5 Test Cases teruji 100% *PASS* dengan bukti tangkapan layar.
- [x] **11. Dokumentasi Lengkap:** Dilengkapi ERD, dokumentasi teknis ([`DOKUMENTASI_UKK.md`](DOKUMENTASI_UKK.md)), dan laporan evaluasi ([`LAPORAN_EVALUASI.md`](LAPORAN_EVALUASI.md)).

---
*Dikembangkan dengan dedikasi untuk Uji Kompetensi Keahlian (UKK) Rekayasa Perangkat Lunak 2026.*
