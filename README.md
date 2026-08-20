# 📊 SI-Mantra

### Sistem Informasi Manajemen dan Monitoring Honor Mitra BPS Kabupaten Kediri

**SI-Mantra** merupakan aplikasi berbasis web yang dikembangkan untuk membantu **BPS Kabupaten Kediri** dalam mengelola data mitra, kegiatan survei, monitoring honor, serta administrasi dokumen secara terintegrasi.

Aplikasi ini menggabungkan proses **input data, import Excel, monitoring honor, dashboard, Surat Tugas, dan Surat Perjanjian Kerja** dalam satu sistem sehingga proses administrasi menjadi lebih terstruktur, cepat, dan mudah dipantau.

---

## 📌 Gambaran Umum

SI-Mantra dirancang untuk mendukung proses administrasi dan monitoring mitra BPS Kabupaten Kediri, khususnya dalam pengelolaan:

- 👥 Data mitra
- 📋 Data kegiatan / survei
- 📝 Data pelaksanaan survei
- 💰 Honor mitra
- 📊 Monitoring akumulasi honor
- 📄 Surat Tugas
- 📑 Surat Perjanjian Kerja
- 📥 Import data Excel
- ⚙️ Pengaturan sistem

---

## ✨ Fitur Utama

### 📊 Dashboard

Halaman utama yang memberikan gambaran kondisi data secara ringkas:

- 📋 **Total Kegiatan**
- 👥 **Total Mitra**
- 💰 **Total Honor**
- ⚠️ **Warning Honor**
- 📈 **Grafik Honor Bulanan**
- 📊 **Status Honor Mitra**
- 🚨 **Daftar Mitra Melebihi Batas Honor**

### 🗂️ Master Data

Modul untuk mengelola data referensi operasional:

- 👨‍💼 **Daftar Pegawai BPS** - Referensi pegawai untuk administrasi kegiatan.
- 👤 **Daftar PML** - Pengelola data Pengawas Lapangan.
- 👥 **Daftar PCL** - Pengelola data Petugas Pendataan Lapangan/mitra.
- 📋 **Daftar Kegiatan / Survei** - Referensi utama survei yang dilaksanakan.

### 📥 Import Data Excel

- 📤 Upload file Excel & preview data
- 🔍 Validasi data & pengecekan kesalahan
- 🚫 Pencegahan data duplikat & penyimpanan ke database

**Alur Proses Import:**

`Upload Excel` ➔ `Preview Data` ➔ `Validasi Data` ➔ `Pemeriksaan Kesalahan` ➔ `Konfirmasi` ➔ `Simpan ke Database`

### 📝 Data Survei

Mengelola pelaksanaan kegiatan survei mencakup data kegiatan, PML, PCL, wilayah tugas, bulan pelaksanaan, dan honor.

### 💰 Monitoring Honor

Memantau akumulasi honor mitra per periode tertentu dengan kriteria:

- ✅ Masih dalam batas honor
- ⚠️ Mendekati batas honor
- 🚨 Melebihi batas honor

### 📄 Dokumen

- 📜 **Surat Tugas** - Pembuatan, pengeditan, pencetakan PDF individual maupun cetak massal per kegiatan.
- 📑 **Surat Perjanjian Kerja (SPK)** - Pengelolaan dan pencetakan dokumen SPK mitra per kegiatan dalam bentuk PDF.

### ⚙️ Sistem

- 👤 Informasi profil & ubah password
- 🔔 Pengaturan notifikasi
- 💰 Pengaturan batas maksimal honor

---

## 🧭 Struktur Menu

```text
SI-Mantra
│
├── 📊 Dashboard
│
├── MASTER DATA
│   ├── 👨‍💼 Daftar Pegawai BPS
│   ├── 👤 Daftar PML
│   ├── 👥 Daftar PCL
│   ├── 📋 Daftar Kegiatan / Survei
│   └── 📥 Import Data Excel
│
├── INPUT DATA
│   └── 📝 Data Survei
│
├── MONITORING
│   └── 💰 Monitoring Honor
│
├── DOKUMEN
│   ├── 📜 Surat Tugas
│   └── 📑 Surat Perjanjian Kerja
│
└── SISTEM
    ├── ⚙️ Pengaturan
    └── 🚪 Logout
```

---

## 🔄 Alur Sistem

```text
┌─────────────────────┐
│    MASTER DATA      │
│ Pegawai / PML / PCL │
│ Kegiatan / Survei   │
└──────────┬──────────┘
           │
           ↓
┌─────────────────────┐
│    DATA SURVEI      │
│ Kegiatan / PCL /    │
│ Wilayah / Honor     │
└──────────┬──────────┘
           │
           ↓
┌─────────────────────┐
│  MONITORING HONOR   │
│ Akumulasi / Batas   │
└──────────┬──────────┘
           │
           ↓
┌─────────────────────┐
│      DASHBOARD      │
│ Grafik & Warning    │
└─────────────────────┘
```

## 📄 Alur Administrasi Dokumen

```text
              Kegiatan / Survei
                     │
           ┌─────────┴─────────┐
           ↓                   ↓
     Surat Tugas       Surat Perjanjian Kerja
           │                   │
           ↓                   ↓
       Cetak PDF           Cetak PDF
```

## 🔐 Keamanan

- 🔑 Authentication & Authorization
- 🔒 Password Protection
- 🧹 Validasi input & file Excel
- 🗄️ Validasi data sebelum import & pencegahan duplikasi
- ⚠️ Warning batas honor otomatis

## 🛠️ Teknologi

| Teknologi | Penggunaan |
|---|---|
| 🐘 **PHP** | Bahasa pemrograman utama |
| 🚀 **Laravel 12** | Framework aplikasi |
| 🎨 **Filament 3** | Admin panel & interface |
| 🌬️ **Tailwind CSS** | Styling & UI responsif |
| 🧩 **Blade** | Template engine |
| 🗄️ **MySQL** | Database |
| 📦 **Composer** | Dependency management |
| 🐙 **Git & GitHub** | Version control & collaboration |

## 🚀 Instalasi

1. **Clone Repository**

```bash
git clone https://github.com/SI-Mantra-BPS/sk-mitra-bps-kab-kediri.git
cd sk-mitra-bps-kab-kediri
```

2. **Install Dependency**

```bash
composer install
npm install
```

3. **Konfigurasi Environment**

```bash
cp .env.example .env
php artisan key:generate
```

Atur konfigurasi database di file `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=root
DB_PASSWORD=
```

4. **Migrasi Database**

```bash
php artisan migrate --seed
```

5. **Jalankan Aplikasi**

```bash
php artisan serve
```

## 💻 Development

Untuk pengembangan frontend dan server backend:

```bash
# Terminal 1 - Frontend
npm run dev

# Terminal 2 - Backend
php artisan serve
```

### 🧹 Perintah Artisan Useful

```bash
php artisan optimize:clear
php artisan route:list
```

## 🌿 Git Workflow

```bash
git status
git add .
git commit -m "[UPDATE] Deskripsi perubahan"
git push origin tria
```

- **Branch Utama:** `main`
- **Branch Development:** `tria`

## 🎯 Tujuan Pengembangan

- ✅ Mengurangi proses administrasi manual & memusatkan data
- ✅ Mempermudah monitoring honor mitra & meningkatkan akurasi
- ✅ Mempercepat pencetakan Surat Tugas dan SPK
- ✅ Mempermudah proses import data massal
- ✅ Meningkatkan transparansi monitoring bagi BPS Kabupaten Kediri

## 📌 Status Project

🚧 **Active Development**

Project ini dikembangkan untuk kebutuhan internal BPS Kabupaten Kediri.

## 📜 Lisensi

Penggunaan, distribusi, atau modifikasi di luar kebutuhan internal BPS Kabupaten Kediri memerlukan izin resmi dari pihak terkait.
