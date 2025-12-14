# 🚀 Panduan Deploy Laravel ke Vercel + PlanetScale

## Langkah 1: Persiapan PlanetScale (Database)

### 1.1 Buat Akun PlanetScale
1. Buka [planetscale.com](https://planetscale.com)
2. Klik **"Get Started"** → Sign up dengan GitHub
3. Verifikasi email

### 1.2 Buat Database Baru
1. Klik **"Create a New Database"**
2. Nama database: `logbook-pkl`
3. Region: **Singapore** (terdekat dengan Indonesia)
4. Plan: **Free** (Hobby - 5GB storage)
5. Klik **Create Database**

### 1.3 Dapatkan Connection String
1. Klik database yang baru dibuat
2. Klik **"Connect"** → **"Create password"**
3. Nama: `vercel-production`
4. Role: **Admin**
5. Klik **"Create password"**
6. **SIMPAN** connection details berikut:
   ```
   Host: aws.connect.psdb.cloud
   Username: xxxxxxxxxxxxxx
   Password: pscale_pw_xxxxxxxxxxxxxx
   Database: logbook-pkl
   ```

> ⚠️ **PENTING**: Password hanya ditampilkan SEKALI! Simpan di tempat aman.

---

## Langkah 2: Persiapan Project Laravel

### 2.1 Install Bref (Laravel Serverless)
Buka terminal di folder project:
```bash
composer require bref/bref bref/laravel-bridge --update-with-dependencies
```

### 2.2 Publish Bref Config
```bash
php artisan vendor:publish --tag=serverless-config
```

### 2.3 Build Assets
```bash
npm run build
```

---

## Langkah 3: Buat File Konfigurasi Vercel

### 3.1 File `vercel.json` (sudah dibuat di root project)
File ini mengatur bagaimana Vercel menjalankan Laravel.

### 3.2 File `api/index.php` (sudah dibuat)
Entry point untuk serverless function.

---

## Langkah 4: Push ke GitHub

### 4.1 Inisialisasi Git (jika belum)
```bash
git init
git add .
git commit -m "Initial commit - Logbook PKL"
```

### 4.2 Buat Repository di GitHub
1. Buka [github.com/new](https://github.com/new)
2. Nama: `logbook-pkl`
3. Private/Public sesuai kebutuhan
4. Klik **Create repository**

### 4.3 Push ke GitHub
```bash
git remote add origin https://github.com/USERNAME/logbook-pkl.git
git branch -M main
git push -u origin main
```

---

## Langkah 5: Deploy ke Vercel

### 5.1 Buat Akun Vercel
1. Buka [vercel.com](https://vercel.com)
2. Sign up dengan GitHub

### 5.2 Import Project
1. Klik **"Add New..."** → **"Project"**
2. Pilih repository `logbook-pkl`
3. Klik **"Import"**

### 5.3 Konfigurasi Project
**Framework Preset**: `Other`

**Build & Development Settings**:
- Build Command: `npm run build && php artisan config:cache`
- Output Directory: `public`
- Install Command: `composer install --no-dev && npm install`

### 5.4 Environment Variables
Klik **"Environment Variables"** dan tambahkan:

| Name | Value |
|------|-------|
| `APP_NAME` | Logbook PKL |
| `APP_ENV` | production |
| `APP_KEY` | base64:xxxxx (dari php artisan key:generate --show) |
| `APP_DEBUG` | false |
| `APP_URL` | https://logbook-pkl.vercel.app |
| `DB_CONNECTION` | mysql |
| `DB_HOST` | aws.connect.psdb.cloud |
| `DB_PORT` | 3306 |
| `DB_DATABASE` | logbook-pkl |
| `DB_USERNAME` | (dari PlanetScale) |
| `DB_PASSWORD` | (dari PlanetScale) |
| `MYSQL_ATTR_SSL_CA` | /etc/ssl/certs/ca-certificates.crt |

### 5.5 Deploy
Klik **"Deploy"** dan tunggu proses selesai (3-5 menit)

---

## Langkah 6: Migrasi Database

### 6.1 Via Vercel CLI (Recommended)
Install Vercel CLI:
```bash
npm i -g vercel
```

Login:
```bash
vercel login
```

Jalankan migration:
```bash
vercel env pull .env.production
php artisan migrate --env=production --force
```

### 6.2 Alternatif: Via PlanetScale Console
1. Buka PlanetScale → Database → **Console**
2. Copy-paste SQL dari file migration secara manual

---

## Langkah 7: Setup Storage untuk Foto

Karena Vercel adalah serverless, file upload tidak persisten. Gunakan **Cloudinary**:

### 7.1 Daftar Cloudinary
1. Buka [cloudinary.com](https://cloudinary.com)
2. Sign up gratis
3. Dapatkan **Cloud Name**, **API Key**, **API Secret**

### 7.2 Install Package
```bash
composer require cloudinary-labs/cloudinary-laravel
```

### 7.3 Tambahkan ke Environment Variables di Vercel:
| Name | Value |
|------|-------|
| `CLOUDINARY_URL` | cloudinary://API_KEY:API_SECRET@CLOUD_NAME |

---

## Troubleshooting

### Error: Class not found
```bash
composer dump-autoload
```

### Error: Database connection refused
- Pastikan SSL diaktifkan di PlanetScale
- Cek environment variable `MYSQL_ATTR_SSL_CA`

### Error: 500 Internal Server Error
- Cek Vercel logs: Dashboard → Project → Logs
- Pastikan `APP_DEBUG=true` sementara untuk debug

---

## URLs

Setelah deploy berhasil:
- **Production**: `https://logbook-pkl.vercel.app`
- **Vercel Dashboard**: `https://vercel.com/dashboard`
- **PlanetScale Dashboard**: `https://app.planetscale.com`

---

## Next Steps

1. ✅ Custom domain (opsional)
2. ✅ Setup Cloudinary untuk foto
3. ✅ Monitor logs di Vercel
4. ✅ Enable auto-deploy dari GitHub
