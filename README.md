# 🎟️ CampusEvent (Sistem Manajemen Event Kampus)

CampusEvent adalah sistem informasi berbasis web yang dirancang khusus untuk mengelola kegiatan kampus dan memonitor absensi mahasiswa secara otomatis. Sistem ini dilengkapi dengan pemindai (Scanner) QR Code / Barcode bawaan yang dioptimalkan untuk perangkat *mobile* dan *desktop*.

Aplikasi ini mengusung antarmuka UI/UX berstandar industri dengan gaya **Modern SaaS (Bright Blue Theme)** dan fitur navigasi bergaya aplikasi *native* pada tampilan *mobile*.

---

## ✨ Fitur Utama

### 👑 1. Akses Admin (Panitia)
* **Manajemen Event (CRUD):** Menambah, mengedit, dan menghapus kegiatan kampus beserta detail acara dan poster.
* **Smart Scanner Absensi:** Memindai KTM mahasiswa menggunakan kamera HP/Laptop secara *real-time*. Dilengkapi juga dengan fitur **Upload Screenshot** apabila kamera perangkat bermasalah.
* **Live Attendance Tracking:** Melihat daftar riwayat mahasiswa yang telah melakukan absensi secara langsung.

### 👔 2. Akses Pimpinan (Monitoring)
* **Executive Dashboard:** Memantau ringkasan statistik (Total Event, Total Kehadiran, Total Mahasiswa).
* **Manajemen Status Event:** Mampu menutup kegiatan dengan menekan tombol **Akhiri Event**.
* **Laporan Terperinci (Export to CSV):** Menarik laporan absensi presisi (termasuk NIM lengkap dan *timestamp* detikan) tanpa takut format hancur saat dibuka di Microsoft Excel.

---

## 💻 Teknologi yang Digunakan
* **Framework:** Laravel (PHP)
* **Database:** MySQL
* **Frontend:** Vanilla HTML, Modern CSS (Flexbox/Grid), Blade Templating
* **Ikonografi:** [Phosphor Icons](https://phosphoricons.com/)
* **Library Scanner:** [HTML5-QRCode](https://github.com/mebjas/html5-qrcode)

---

## 🚀 Panduan Instalasi (Local Development)

Ikuti langkah-langkah di bawah ini untuk menjalankan proyek ini di komputer lokal Anda:

### Persyaratan Sistem
* PHP >= 8.1
* Composer
* MySQL / MariaDB (XAMPP/Laragon)
* Node.js & NPM (Opsional untuk aset)

### Langkah-langkah
1. **Clone Repository (atau ekstrak file proyek)**
   Pastikan Anda berada di dalam folder proyek melalui terminal.

2. **Install Dependensi PHP**
   ```bash
   composer install
   ```

3. **Konfigurasi Environment**
   Salin file konfigurasi bawaan.
   ```bash
   cp .env.example .env
   ```
   Buka file `.env` lalu sesuaikan konfigurasi database Anda:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nama_database_anda
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

5. **Migrasi Database & Seeder Data Awal**
   Perintah ini akan membuat struktur tabel beserta data _dummy_ awal (termasuk data Mahasiswa bernama Rosalia).
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Tautkan Storage (Sangat Penting untuk Foto/Poster)**
   ```bash
   php artisan storage:link
   ```

7. **Jalankan Server Lokal**
   Buka dua jendela terminal untuk menjalankan sistem secara penuh:
   ```bash
   # Terminal 1: Menjalankan server Laravel (Backend)
   php artisan serve

   # Terminal 2: Melakukan build aset (Frontend)
   npm run dev
   ```

---

## 🔑 Akun Default (Testing)

Sistem ini tidak menggunakan metode _login hash_ standar untuk mempermudah tahap pengujian. Silakan gunakan kredensial berikut:

| Peran (Role) | Username | Password |
| :--- | :--- | :--- |
| **Admin Panel** | `admin` | `123` |
| **Pimpinan** | `pimpinan` | `123` |

---

## 📱 Catatan Penggunaan (Scanner)
Agar sistem kamera internal (*Smart Scanner*) dapat menyala di *browser* HP (Google Chrome/Safari), aplikasi **wajib** dijalankan di jaringan lokal (Localhost) atau di-*hosting* dengan protokol **HTTPS (SSL)**. Ini adalah kebijakan keamanan standar dari *browser*. 

Jika Anda tidak menggunakan HTTPS/Localhost, Anda tetap bisa mengisi absensi dengan menggunakan fitur opsi **"Upload Screenshot QR Code"**.

---
*Didesain dan dikembangkan dengan ❤️ untuk kebutuhan Manajemen Event Kampus.*
