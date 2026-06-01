# Aksara Coffee POS & KDS System

Aksara Coffee POS (Point of Sale) & KDS (Kitchen Display System) adalah sistem kasir dan antrean dapur modern premium yang dirancang khusus untuk bisnis kedai kopi dan makanan (FnB). Aplikasi ini dirancang menggunakan arsitektur **HMVC (Hierarchical Model-View-Controller) / Modular** berbasis framework **CodeIgniter 4**, memberikan struktur kode yang rapi, terisolasi per fitur, dan mudah dirawat.

---

## 🚀 Fitur Utama & Modul

Aplikasi ini dibagi menjadi beberapa modul utama yang saling terintegrasi:

1. **Modul Pelanggan (Home / Customer Menu)**
   * Pemesanan mandiri oleh pelanggan dari meja secara digital.
   * Katalog menu interaktif (Signature Coffee, Non-Coffee, dan Snack).
   * Pilihan catatan pesanan khusus (notes dapur) per item.
   * Dukungan pembayaran cash ke kasir atau pembayaran QRIS instan.

2. **Modul Kasir (POS System)**
   * Tampilan dashboard penjualan harian (termasuk ringkasan transaksi, nominal penjualan, dan indikator stok menipis).
   * Mode Penjualan (Catalog & Cart) dengan kalkulasi diskon, PPN (11%), biaya layanan (5%), kembalian kasir, serta integrasi cetak struk mockup.
   * Manajemen shift kasir (buka shift, set modal awal, hitung kas laci akhir, dan variansi/selisih uang kas).
   * Simulasi pemindaian barcode produk secara otomatis.
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

### Langkah-langkah
1. **Clone Repositori & Persiapkan Berkas**
   ```bash
   git clone <repository-url> pemesanan.aksara
   cd pemesanan.aksara
   ```

2. **Instalasi Dependensi**
   ```bash
   composer install
   ```

3. **Konfigurasi Environment**
   * Salin file `.env.example` menjadi `.env`.
   * Sesuaikan pengaturan URL dasar dan koneksi database MySQL:
     ```env
     app.baseURL = 'http://localhost/pemesanan.aksara/public/'
     
     database.default.hostname = 'localhost'
     database.default.database = 'pemesanan_aksara'
     database.default.username = 'root'
     database.default.password = ''
     database.default.DBDriver = 'MySQLi'
     ```

4. **Import Database**
   * Buat database baru di MySQL dengan nama `pemesanan_aksara`.
   * Import file basis data cadangan `pemesanan_aksara.sql` ke dalam database Anda.

5. **Jalankan Aplikasi**
   * Arahkan konfigurasi virtual host web server Anda ke folder `/public`.
   * Atau, Anda dapat menggunakan server bawaan PHP/CodeIgniter dengan menjalankan perintah berikut di terminal:
     ```bash
     php spark serve
     ```
   * Akses aplikasi melalui peramban di alamat `http://localhost:8080`.

---

## ⚠️ Status Pekerjaan & Modul yang Belum Selesai (TODO)

Beberapa modul pada sistem saat ini masih berupa mockup (menggunakan simulasi data lokal browser `localStorage` atau alert visual) dan memerlukan pengembangan lebih lanjut untuk diintegrasikan secara penuh ke database backend:

1. **Modul Pengaturan & Printer**: Integrasi riil cetak struk kasir melalui koneksi printer thermal Bluetooth/USB (saat ini hanya berupa alert simulasi).
2. **Modul Pengeluaran Operasional**: Penyimpanan data pengeluaran kas operasional ke database MySQL.
3. **Modul Inventory & Bahan Baku**: Penyimpanan resep produk, konsumsi stok bahan baku otomatis saat checkout, serta manajemen PO (Purchase Order) bahan baku ke supplier.
4. **Modul Pelanggan & Konsinyasi**: Pencatatan data pelanggan grosir, loyalitas poin, dan penitipan produk pastri pihak ketiga secara permanen.
5. **Mode Industri Non-FnB**: Implementasi modul retail, laundry, salon, dan bengkel secara penuh di sisi database (saat ini data transaksi mode non-fnb disimpan sementara di memori browser `localStorage` kasir).
6. **Payment Gateway QRIS**: Koneksi API pembayaran QRIS dinamis & otomatis dengan sistem webhooks/payment gateway pihak ketiga untuk memverifikasi pembayaran secara real-time tanpa tombol manual.
