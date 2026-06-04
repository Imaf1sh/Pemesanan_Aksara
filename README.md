# Aksara Coffee POS & KDS System

Aksara Coffee POS (Point of Sale) & KDS (Kitchen Display System) adalah sistem kasir dan antrean dapur modern premium yang dirancang khusus untuk bisnis kedai kopi dan makanan (FnB). Aplikasi ini dirancang menggunakan arsitektur **HMVC (Hierarchical Model-View-Controller) / Modular** berbasis framework **CodeIgniter 4**, memberikan struktur kode yang rapi, terisolasi per fitur, dan mudah dirawat.

---

## 🚀 Fitur Utama & Modul

Aplikasi ini dibagi menjadi beberapa modul utama yang saling terintegrasi:

1. **Modul Pelanggan (Home / Customer Menu)**
   * Pemesanan mandiri oleh pelanggan dari meja secara digital.
   * Katalog menu interaktif (Signature Coffee, Non-Coffee, dan Snack).
   * Tampilan katalog yang responsif dan optimal untuk mobile viewports (smartphone).
   * Pilihan catatan pesanan khusus (notes dapur) per item.
   * Dukungan pembayaran cash ke kasir atau pembayaran QRIS instan.

2. **Modul Kasir (POS System)**
   * Tampilan dashboard penjualan harian (termasuk ringkasan transaksi, nominal penjualan, dan indikator stok menipis).
   * Mode Penjualan (Catalog & Cart) dengan kalkulasi diskon, PPN (11%), biaya layanan (5%), kembalian kasir, serta integrasi cetak struk mockup.
   * Manajemen shift kasir (buka shift, set modal awal, hitung kas laci akhir, dan variansi/selisih uang kas).
   * Simulasi pemindaian barcode produk secara otomatis.
   * Manajemen Persediaan Bahan Baku (Inventory) terintegrasi database MySQL untuk pemantauan stok bahan mentah.
   * Pencatatan Pengeluaran Operasional Cafe secara real-time langsung ke database MySQL.
   * Multi-Suite Industries (FnB, Retail, Laundry, Salon, Bengkel) dengan skema pewarnaan dinamis.

3. **Modul Dapur (Kitchen Display System - KDS)**
   * Antrean KDS real-time dengan pemutaran bunyi chime (audio chimes) otomatis via Web Audio API saat ada pesanan masuk.
   * Indikator warna tingkat keterlambatan pembuatan pesanan (Hijau: < 8 mnt, Kuning: 8-15 mnt, Merah berkedip: > 15 mnt).
   * Checklist item pesanan mandiri sebelum status pesanan diselesaikan.
   * Fitur batal selesai (restore) pesanan dapur.

4. **Modul Absensi Pekerja (Attendance)**
   * Absensi masuk/keluar bagi karyawan menggunakan kamera (webcam) langsung di perangkat kasir.
   * Unggah berkas gambar berbasis enkoding Base64.
   * Pencatatan riwayat absensi harian secara real-time yang tersimpan ke server.

---

## 🛠️ Stack Teknologi

* **Backend Framework**: CodeIgniter 4 (PHP 8.2+)
* **Database**: MySQL/MariaDB (Skema relational)
* **Frontend**: HTML5, Vanilla JavaScript (ES6+), Vanilla CSS (tanpa framework eksternal untuk fleksibilitas performa optimal)
* **API Audio**: Web Audio API (untuk sintesis chime & klik responsif)
* **Webcam API**: MediaDevices.getUserMedia() (untuk absensi kamera lokal)

---

## 📁 Struktur Folder Modular (HMVC)

Proyek ini menggunakan pola organisasi modular. Setiap modul di bawah direktori `app/Modules/` membungkus logika bisnisnya sendiri (Controller, Model, dan View):

```
app/
  Modules/
    Auth/                  # Modul login, logout, dan manajemen sesi
    POS/                   # Modul sistem kasir (POS), checkout, dan API transaksi
    KDS/                   # Modul Kitchen Display System (antrean dapur)
    Attendance/            # Modul absensi pekerja menggunakan kamera
    Home/                  # Modul menu pelanggan (katalog pemesanan meja)
```

Dengan struktur ini, modul dapat ditambahkan, dihapus, atau dimodifikasi tanpa merusak kode di modul lainnya.

---

## 💻 Panduan Instalasi & Konfigurasi

### Prasyarat
* PHP Versi 8.2 atau lebih tinggi (Pastikan ekstensi `intl`, `mbstring`, `json`, `mysqlnd`, dan `curl` aktif).
* Composer installed.
* Web Server (Apache/Nginx) seperti WampServer, Laragon, atau XAMPP.

### Langkah-langkah Menjalankan Proyek dengan WampServer

1. **Simpan Folder Proyek di Direktori WampServer**
   * Pindahkan atau clone folder proyek `pemesanan.aksara` ke dalam direktori root web server WampServer Anda. Biasanya berlokasi di:
     `C:\wamp64\www\pemesanan.aksara`

2. **Aktifkan WampServer & Pastikan Versi PHP Sesuai**
   * Jalankan aplikasi WampServer di komputer Anda.
   * Pastikan ikon WampServer di taskbar Windows menyala berwarna **Hijau** (artinya Apache, MySQL, dan MariaDB berjalan normal).
   * Klik kiri ikon WampServer, pilih **PHP** -> **Version**, lalu centang versi **PHP 8.2.x** atau **8.3.x** (karena CodeIgniter 4 membutuhkan PHP minimal versi 8.2).

3. **Instalasi Dependensi**
   * Jalankan terminal/CMD di dalam folder `C:\wamp64\www\pemesanan.aksara`, lalu jalankan perintah:
     ```bash
     composer install
     ```

4. **Konfigurasi Environment (.env)**
   * Salin berkas `env` di root proyek dan ubah namanya menjadi `.env`.
   * Buka berkas `.env` tersebut dan sesuaikan baris berikut (sesuaikan konfigurasi URL dan Database WampServer default):
     ```env
     # URL dasar proyek (PENTING: tambahkan /public/ di akhir)
     app.baseURL = 'http://localhost/pemesanan.aksara/public/'
     
     # Konfigurasi koneksi database MySQL WampServer
     database.default.hostname = 'localhost'
     database.default.database = 'pemesanan_aksara'
     database.default.username = 'root'
     database.default.password = ''  # Kosongkan password (default bawaan WampServer)
     database.default.DBDriver = 'MySQLi'
     ```

5. **Import Database**
   * Buka browser Anda dan masuk ke phpMyAdmin di alamat: `http://localhost/phpmyadmin`
   * Masuk dengan username `root` dan kosongkan password.
   * Buat database baru bernama `pemesanan_aksara`.
   * Klik database tersebut, buka tab **Import**, pilih file basis data [pemesanan_aksara.sql](file:///c:/wamp64/www/pemesanan.aksara/pemesanan_aksara.sql) yang berada di dalam folder proyek, lalu klik tombol **Import** (atau **Go**) di bagian bawah.

6. **Jalankan Migrasi Database (PENTING)**
   * Untuk memastikan semua skema tabel terbaru terbuat di database (seperti tabel `expenses` dan `raw_materials`), jalankan perintah migrasi berikut di terminal pada direktori root proyek:
     ```bash
     php spark migrate
     ```

7. **Akses Website di Browser**
   Setelah semua langkah selesai, Anda dapat langsung mengakses modul aplikasi di browser melalui URL berikut:
   * **Halaman Depan Pelanggan (Menu Digital)**: [http://localhost/pemesanan.aksara/public/](http://localhost/pemesanan.aksara/public/)
   * **Halaman Login POS (Kasir/Admin/Owner)**: [http://localhost/pemesanan.aksara/public/login](http://localhost/pemesanan.aksara/public/login)
   * **Halaman Kitchen Display System (KDS)**: [http://localhost/pemesanan.aksara/public/kds](http://localhost/pemesanan.aksara/public/kds)

   **Akun Demo untuk Login POS**:
   * **Kasir**: Username: `kasir` | Password: `kasir123`
   * **Admin**: Username: `admin` | Password: `admin123`
   * **Owner**: Username: `owner` | Password: `owner123`

---

## ⚠️ Status Pekerjaan & Modul yang Belum Selesai (TODO)

Beberapa modul pada sistem saat ini masih berupa mockup (menggunakan simulasi data lokal browser `localStorage` atau alert visual) dan memerlukan pengembangan lebih lanjut untuk diintegrasikan secara penuh ke database backend:

1. **Modul Pengaturan & Printer**: Integrasi riil cetak struk kasir melalui koneksi printer thermal Bluetooth/USB (saat ini hanya berupa alert simulasi).
2. **Modul Inventory Lanjutan (Resep & Otomasi)**: Penyimpanan resep produk, konsumsi otomatis stok bahan baku saat checkout pesanan, serta manajemen PO (Purchase Order) bahan baku ke supplier.
3. **Modul Pelanggan & Konsinyasi**: Pencatatan data pelanggan grosir, loyalitas poin, dan penitipan produk pastri pihak ketiga secara permanen.
4. **Mode Industri Non-FnB**: Implementasi modul retail, laundry, salon, dan bengkel secara penuh di sisi database (saat ini data transaksi mode non-fnb disimpan sementara di memori browser `localStorage` kasir).
5. **Payment Gateway QRIS**: Koneksi API pembayaran QRIS dinamis & otomatis dengan sistem webhooks/payment gateway pihak ketiga untuk memverifikasi pembayaran secara real-time tanpa tombol manual.
