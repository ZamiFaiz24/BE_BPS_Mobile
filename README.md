<p align="center">
  <img src="public/images/logo-bps.png" alt="Logo BPS Kebumen" width="200">
</p>
<h1 align="center">Backend API BPS Kebumen Mobile</h1>
<p align="left">
  Repositori backend (API) pendukung aplikasi SILAWET BPS Kabupaten Kebumen.  
  Dibangun menggunakan <strong>Laravel</strong> untuk menyediakan data statistik yang relevan, akurat, dan mudah diakses.
</p>

---

## 📝 Tentang Proyek

Backend ini bertugas untuk:

- 🔄 **Sinkronisasi Data:** Mengambil data secara periodik dari API BPS.  
- 🗄️ **Penyimpanan Data:** Menyimpan data statistik dalam database lokal agar lebih cepat diakses.  
- 📊 **Penyajian Data:** Menyediakan data yang sudah bersih dan terstruktur melalui REST API untuk aplikasi Client Android.

---

## ✨ Fitur Utama

- ⚙️ **Sinkronisasi Otomatis:** Mengambil data dari berbagai dataset BPS berdasarkan daftar target di file konfigurasi.  
- 🧵 **Proses Asinkron (Queue):** Menggunakan sistem antrian Laravel agar proses berat tidak menghambat aplikasi.  
- 🌐 **RESTful API:** Menyediakan endpoint data statistik yang siap digunakan oleh aplikasi mobile.  
- 🧭 **Admin Dashboard:** Memantau dataset, status sinkronisasi, serta menghapus dataset jika diperlukan.  

---

## ⚙️ Arsitektur & Alur Kerja Sinkronisasi

1. **Definisi Target** — Semua dataset yang ingin disinkronkan didefinisikan di file `config/bps_targets.php`.  
2. **Pemicu Sinkronisasi** — Pengguna menekan tombol *Sinkronisasi* pada Dashboard Admin.  
3. **Dispatch Job** — `DashboardController` mengirimkan setiap dataset sebagai `SyncBpsDataJob` ke dalam antrian.  
4. **Eksekusi Worker** — Worker (`php artisan queue:work`) mengambil job satu per satu dan menjalankan `BpsApiService` untuk mengambil data dari API BPS lalu menyimpannya ke database.

---

## 🛠️ Teknologi yang Digunakan

| Komponen | Teknologi |
|-----------|------------|
| Framework | Laravel    |
| CSS       | Tailwind   |
| Bahasa    | PHP        | 
| Database | MySQL / MariaDB |
| Sistem Antrian | Database Queue (atau Redis untuk produksi) |

---

## 🚀 **Panduan Memulai Cepat (Instalasi & Konfigurasi Lokal)**

Ikuti langkah-langkah berikut untuk menjalankan proyek ini di lingkungan lokal Anda.


### 1. Clone Repositori
### Buka terminal dan jalankan perintah berikut:
```bash
git clone [URL_REPO_ANDA]
cd BE_BPS_Mobile
```

### 2. Install Dependencies
### Pastikan Anda sudah menginstal Composer.
```bash
composer install
```

### 3. Siapkan File Environment
### Salin file .env.example menjadi .env
```bash
cp .env.example .env
````

### 4. Konfigurasi .env
### Buka file .env dan sesuaikan variabel berikut dengan konfigurasi lokal Anda:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_anda
DB_USERNAME=root
DB_PASSWORD=
```

### Masukkan API Key dari website BPS
```
BPS_API_KEY=kunci_api_bps_anda
```

### Pastikan koneksi queue adalah 'database'
```
QUEUE_CONNECTION=database
```

### 5. Generate Application Key
### Ini adalah langkah penting untuk keamanan aplikasi Anda.
```bash
php artisan key:generate
```

### 6. Jalankan Migrasi Database
### Perintah ini akan membuat semua tabel yang dibutuhkan
```bash
php artisan migrate
```
▶️ **Menjalankan Aplikasi**

## Untuk menjalankan aplikasi, Anda perlu dua terminal yang berjalan bersamaan.

## Terminal 1: Web Server
```bash
php artisan serve
```
### 🌐 Aplikasi Anda sekarang bisa diakses di http://127.0.0.1:8000

## Terminal 2: Queue Worker
```bash
php artisan queue:work
```
### ⚙️ Worker ini akan memproses semua tugas sinkronisasi di belakang layar.

---

## ✅ Hasil Akhir
Setelah seluruh proses konfigurasi selesai, aplikasi backend telah siap dijalankan.  
Dashboard Admin dapat diakses untuk melakukan pengelolaan dataset dan proses sinkronisasi secara langsung.

Dengan demikian, sistem ini telah mampu:
- Mengambil data dari Web API BPS secara otomatis,  
- Menyimpan hasilnya ke dalam database lokal, dan  
- Menyajikan data terstruktur untuk aplikasi mobile.

Sistem siap digunakan untuk tahap **pengujian** dan **pengembangan lanjutan**.
