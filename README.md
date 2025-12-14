# Logbook PKL - Injourney Airports

Sistem manajemen Praktik Kerja Lapangan (PKL) digital untuk Injourney Airports. Aplikasi ini memudahkan pencatatan kehadiran, aktivitas harian, dan pemantauan kinerja peserta PKL secara real-time.

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat-square&logo=mysql&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind-3.4-38B2AC?style=flat-square&logo=tailwind-css&logoColor=white)

## Tentang Aplikasi

Logbook PKL adalah platform berbasis web yang dirancang untuk mempermudah pengelolaan kegiatan Praktik Kerja Lapangan. Aplikasi ini menyediakan fitur lengkap untuk peserta PKL dan admin pengelola.

### Fitur Utama

**Untuk Peserta PKL (Member)**
- Absensi digital dengan foto selfie dan validasi waktu
- Pencatatan aktivitas harian dengan dokumentasi
- Perencanaan kegiatan mingguan
- Catatan ide dan inovasi selama PKL
- Pelaporan kendala atau masalah yang dihadapi
- Riwayat kehadiran lengkap dengan statistik

**Untuk Admin**
- Dashboard monitoring real-time
- Manajemen data peserta PKL
- Pengaturan jadwal shift dan jam kerja
- Penilaian performa peserta
- Sistem pesan internal
- Ekspor laporan dalam format PDF dan Excel
- Log audit untuk keamanan sistem

### Teknologi

| Kategori | Teknologi |
|----------|-----------|
| Backend | Laravel 12, PHP 8.2 |
| Frontend | Blade Templates, Alpine.js |
| Styling | Tailwind CSS 3.4 |
| Database | MySQL 8.0 |
| Build Tool | Vite 7 |
| Storage | Cloudinary (Cloud), Local Filesystem |
| PDF | DomPDF |
| Excel | Maatwebsite Excel |

## Persyaratan Sistem

- PHP >= 8.2
- Composer
- Node.js >= 18
- MySQL >= 8.0
- Extension PHP: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML

## Instalasi

```bash
# Clone repository
git clone https://github.com/kyezeth/LogBook-PKL.git
cd LogBook-PKL

# Install dependencies
composer install
npm install

# Konfigurasi environment
cp .env.example .env
php artisan key:generate

# Setup database
php artisan migrate
php artisan db:seed

# Build assets
npm run build

# Jalankan server
php artisan serve
```

## Konfigurasi

### Database
Sesuaikan konfigurasi database di file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=logbook_pkl
DB_USERNAME=root
DB_PASSWORD=
```

### Storage
Untuk penyimpanan file di cloud, konfigurasikan Cloudinary:
```env
CLOUDINARY_URL=cloudinary://API_KEY:API_SECRET@CLOUD_NAME
```

## Struktur Direktori

```
├── app/
│   ├── Http/Controllers/    # Controller untuk Admin dan Member
│   ├── Models/              # Eloquent Models
│   └── Services/            # Business Logic Services
├── database/
│   ├── migrations/          # Database Migrations
│   └── seeders/             # Data Seeders
├── resources/
│   ├── views/               # Blade Templates
│   ├── css/                 # Stylesheet
│   └── js/                  # JavaScript
├── routes/
│   └── web.php              # Web Routes
└── public/
    └── build/               # Compiled Assets
```

## Kontribusi

Kontribusi sangat diterima. Silakan buat pull request atau laporkan issue jika menemukan bug.

## Lisensi

Aplikasi ini dikembangkan untuk keperluan internal Injourney Airports.

---

**Dikembangkan untuk Injourney Airports**
