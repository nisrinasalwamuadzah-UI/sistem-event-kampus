# 💻 Panduan Eksekusi Project (Pindah Laptop / Setup Awal)

Panduan ini dibuat khusus untuk memudahkan Anda saat memindahkan *source code* proyek ini ke laptop atau PC lain. 

Saat proyek dipindahkan, folder `node_modules` dan `vendor` umumnya tidak ikut disalin (karena masuk ke `.gitignore`). Oleh karena itu, Anda harus mengunduh ulang semua *dependencies* (paket pustaka) dari awal.

Ikuti panduan eksekusi ini secara berurutan **dari awal hingga akhir**:

---

## 🛠️ Persiapan Lingkungan (Environment)
Sebelum memulai, pastikan laptop baru Anda sudah terinstal perangkat lunak berikut:
1. **PHP** (Minimal versi 8.1+)
2. **Composer** (Package Manager untuk PHP)
3. **Node.js & NPM** (Package Manager untuk JavaScript/Frontend)
4. **XAMPP / Laragon** (Untuk server database MySQL)

---

## 🚀 Langkah-langkah Setup (Step-by-Step)

### 1. Buka Terminal di Folder Project
Buka VS Code, lalu buka folder proyek ini. Buka terminal bawaan VS Code (`Ctrl` + `~` atau Terminal -> New Terminal).

### 2. Install Dependensi PHP (Laravel Backend)
Karena folder `vendor` hilang, kita harus menginstalnya kembali menggunakan Composer.
Jalankan perintah ini:
```bash
composer install
```
*(Tunggu hingga proses download paket PHP selesai).*

### 3. Install Dependensi Frontend (Node.js)
Karena folder `node_modules` hilang, kita harus menginstalnya kembali menggunakan NPM.
Jalankan perintah ini:
```bash
npm install
```
*(Tunggu hingga proses download paket JavaScript selesai).*

### 4. Setup File Konfigurasi Lingkungan (`.env`)
Proyek Laravel membutuhkan file `.env`. Biasanya saat dipindah, file ini hilang atau belum dikonfigurasi.
1. Salin file `.env.example` dan ubah namanya menjadi `.env`:
   ```bash
   cp .env.example .env
   ```
2. Buka file `.env` tersebut, cari bagian *Database*, dan sesuaikan nama databasenya (misal: `event_kampus`):
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nama_database_anda
   DB_USERNAME=root
   DB_PASSWORD=
   ```
   *(Pastikan Anda juga sudah membuat database kosong dengan nama tersebut di `phpMyAdmin` atau `HeidiSQL`).*

### 5. Generate Application Key
Untuk alasan keamanan (enkripsi session, password, dll), Anda wajib men-generate ulang kunci aplikasi.
Jalankan perintah ini:
```bash
php artisan key:generate
```

### 6. Migrasi Database dan Isi Data Awal (Seeder)
Langkah ini akan membangun seluruh struktur tabel di database yang kosong tadi, sekaligus memasukkan data *dummy* (termasuk data akun admin, pimpinan, dan mahasiswa).
Jalankan perintah ini:
```bash
php artisan migrate:fresh --seed
```

### 7. Tautkan Folder Storage (Wajib untuk Foto/Poster)
Agar gambar poster yang diunggah bisa dibaca oleh sistem (karena tersimpan di `/storage`, sedangkan web membaca dari `/public`), Anda harus menautkannya.
Jalankan perintah ini:
```bash
php artisan storage:link
```

---

## 🏃‍♂️ Cara Menjalankan Aplikasi

Setelah ketujuh langkah di atas sukses tanpa *error*, Anda siap menjalankan sistemnya. Anda akan membutuhkan **Dua Terminal** yang berjalan bersamaan.

**Terminal 1 (Jalankan Server Laravel):**
```bash
php artisan serve
```

**Terminal 2 (Jalankan Server Aset/Vite):**
Buka tab terminal baru (klik tombol `+` di terminal VS Code), lalu jalankan:
```bash
npm run dev
```

🌐 Buka browser Anda dan akses: **http://localhost:8000**

---

## 💡 Trouble-shooting Singkat
- Jika terjadi *error* `1049 Unknown database`, artinya Anda lupa membuat database di XAMPP/phpMyAdmin. Buat dulu databasenya, baru ulangi langkah ke-6.
- Jika gambar poster pecah/tidak muncul, itu artinya Anda belum menjalankan langkah ke-7 (`php artisan storage:link`).
- Jika tampilan CSS hancur atau berantakan, itu artinya Anda belum menjalankan Terminal 2 (`npm run dev`).
