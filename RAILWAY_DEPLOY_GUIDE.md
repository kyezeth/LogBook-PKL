# 🚂 Panduan Deploy Laravel ke Railway + MySQL

## ✅ Status Keamanan Project

Project ini **AMAN** untuk di-deploy ke production:

| Item | Status | Keterangan |
|------|--------|------------|
| `.env` file | ✅ Gitignored | Tidak akan ter-upload ke GitHub |
| `APP_DEBUG` | ✅ Default `false` | Tidak expose error detail |
| Database Config | ✅ Via Environment | Semua kredensial via env vars |
| Session Driver | ✅ Database | Aman untuk production |
| Encryption | ✅ AES-256-CBC | Standard encryption |

---

## 📋 Langkah 1: Persiapan Repository GitHub

### 1.1 Pastikan `.gitignore` Sudah Benar
File-file sensitif yang TIDAK boleh ter-upload:
```
.env
.env.backup
.env.production
/vendor
/node_modules
```

### 1.2 Push ke GitHub
```bash
# Jika belum punya repo
git init
git add .
git commit -m "Initial commit - Logbook PKL"

# Buat repository baru di GitHub, lalu:
git remote add origin https://github.com/USERNAME/logbook-pkl.git
git branch -M main
git push -u origin main
```

---

## 📋 Langkah 2: Buat Akun Railway

1. Buka [railway.app](https://railway.app)
2. Klik **"Login"** → **"Login with GitHub"**
3. Authorize Railway untuk mengakses GitHub account kamu
4. Kamu akan masuk ke Railway Dashboard

---

## 📋 Langkah 3: Setup MySQL Database di Railway

### 3.1 Buat Project Baru
1. Di Railway Dashboard, klik **"New Project"**
2. Pilih **"Provision MySQL"**
3. Tunggu beberapa detik sampai database siap

### 3.2 Dapatkan Kredensial Database
1. Klik pada service **MySQL** yang baru dibuat
2. Klik tab **"Variables"**
3. **CATAT** variabel berikut (akan dipakai nanti):
   - `MYSQL_HOST` (contoh: `roundhouse.proxy.rlwy.net`)
   - `MYSQL_PORT` (contoh: `45678`)
   - `MYSQL_DATABASE` (contoh: `railway`)
   - `MYSQL_USER` (contoh: `root`)
   - `MYSQL_PASSWORD` (contoh: `xxxxxxxxxxxxx`)

> 💡 **Tip**: Kamu juga bisa menggunakan `MYSQL_URL` langsung yang formatnya:
> `mysql://user:password@host:port/database`

---

## 📋 Langkah 4: Deploy Laravel dari GitHub

### 4.1 Tambah Service Laravel
1. Di project yang sama (yang sudah ada MySQL), klik **"+ New"**
2. Pilih **"GitHub Repo"**
3. Pilih repository **logbook-pkl** kamu
4. Klik **"Deploy"**

### 4.2 Railway Akan Otomatis Mendeteksi Laravel
Railway menggunakan **Nixpacks** untuk build, yang akan:
- Mendeteksi `composer.json` → Install PHP dependencies
- Mendeteksi `package.json` → Build assets dengan npm
- Menjalankan aplikasi

---

## 📋 Langkah 5: Konfigurasi Environment Variables

### 5.1 Buka Settings Laravel Service
1. Klik pada service Laravel kamu
2. Klik tab **"Variables"**
3. Klik **"+ New Variable"** dan tambahkan:

### 5.2 Variabel yang WAJIB Ditambahkan

| Variable | Value | Keterangan |
|----------|-------|------------|
| `APP_NAME` | `Logbook PKL` | Nama aplikasi |
| `APP_ENV` | `production` | Environment mode |
| `APP_KEY` | `base64:xxxxx` | Lihat cara generate di bawah |
| `APP_DEBUG` | `false` | PENTING: harus false |
| `APP_URL` | `https://xxxx.up.railway.app` | URL dari Railway |
| `DB_CONNECTION` | `mysql` | Tipe database |
| `DB_HOST` | `${{MySQL.MYSQL_HOST}}` | Reference variable |
| `DB_PORT` | `${{MySQL.MYSQL_PORT}}` | Reference variable |
| `DB_DATABASE` | `${{MySQL.MYSQL_DATABASE}}` | Reference variable |
| `DB_USERNAME` | `${{MySQL.MYSQL_USER}}` | Reference variable |
| `DB_PASSWORD` | `${{MySQL.MYSQL_PASSWORD}}` | Reference variable |
| `SESSION_DRIVER` | `database` | Gunakan database session |
| `CACHE_STORE` | `database` | Gunakan database cache |
| `QUEUE_CONNECTION` | `database` | Gunakan database queue |

### 5.3 Generate APP_KEY
Jalankan di terminal lokal:
```bash
php artisan key:generate --show
```
Copy hasilnya (format: `base64:xxxxxxxxxxxxxxx`) dan paste ke Railway.

### 5.4 Menggunakan Variable References
Railway mendukung referensi antar service menggunakan format `${{ServiceName.VARIABLE}}`.

Karena database kamu bernama "MySQL", gunakan:
- `${{MySQL.MYSQL_HOST}}`
- `${{MySQL.MYSQL_PORT}}`
- dst.

---

## 📋 Langkah 6: Konfigurasi Build & Start Command

### 6.1 Buka Settings
1. Klik service Laravel
2. Klik tab **"Settings"**

### 6.2 Konfigurasi Build
Di bagian **"Build Command"**, masukkan:
```bash
composer install --no-dev --optimize-autoloader && npm install && npm run build
```

### 6.3 Konfigurasi Start
Di bagian **"Start Command"**, masukkan:
```bash
php artisan migrate --force && php artisan config:cache && php artisan route:cache && php artisan view:cache && php -S 0.0.0.0:$PORT -t public
```

> ⚠️ **Penjelasan**:
> - `migrate --force` → Jalankan migration di production
> - `config:cache` → Cache konfigurasi untuk performa
> - `route:cache` → Cache routes
> - `view:cache` → Cache views
> - `php -S 0.0.0.0:$PORT -t public` → Jalankan PHP built-in server

---

## 📋 Langkah 7: Generate Domain

### 7.1 Dapatkan URL Public
1. Klik service Laravel
2. Klik tab **"Settings"**
3. Scroll ke bagian **"Networking"**
4. Klik **"Generate Domain"**
5. Kamu akan mendapat URL seperti: `logbook-pkl-xxx.up.railway.app`

### 7.2 Update APP_URL
1. Kembali ke tab **"Variables"**
2. Update `APP_URL` dengan URL yang baru didapat
3. Service akan otomatis redeploy

---

## 📋 Langkah 8: Verifikasi Deployment

### 8.1 Cek Logs
1. Klik service Laravel
2. Klik tab **"Logs"**
3. Pastikan tidak ada error

### 8.2 Test Aplikasi
1. Buka URL aplikasi di browser
2. Coba login/register
3. Pastikan semua fitur berjalan

---

## 🔧 Troubleshooting

### Error: "Class not found"
```bash
# Redeploy dengan command:
composer dump-autoload --optimize
```

### Error: "Database connection refused"
1. Pastikan variabel database sudah benar
2. Check apakah MySQL service aktif
3. Pastikan format host dan port benar

### Error: "Permission denied on storage"
Railway biasanya handle ini otomatis, tapi jika perlu:
```bash
chmod -R 775 storage bootstrap/cache
```

### Error: "APP_KEY tidak valid"
1. Generate ulang APP_KEY di lokal
2. Update variabel di Railway
3. Redeploy

---

## 💡 Tips Tambahan

### Auto-Deploy dari GitHub
Railway akan otomatis redeploy setiap kali kamu push ke branch utama.

### Custom Domain
1. Buka Settings → Networking
2. Klik "Custom Domain"
3. Masukkan domain kamu
4. Update DNS records sesuai instruksi Railway

### Monitoring Usage
- Railway memberikan free tier $5/bulan
- Monitor usage di Railway Dashboard → Usage

---

## 📊 Perbandingan Railway vs Vercel

| Fitur | Railway | Vercel |
|-------|---------|--------|
| Database | ✅ Built-in MySQL | ❌ Perlu PlanetScale |
| PHP Support | ✅ Native | ⚠️ Serverless only |
| Persistent Storage | ✅ Ya | ❌ Tidak |
| Queue Workers | ✅ Bisa | ❌ Tidak bisa |
| Ease of Use | ✅ Sangat mudah | ⚠️ Perlu konfigurasi |
| Free Tier | $5/bulan | ❌ PHP tidak free |

---

## 🎉 Selesai!

Aplikasi Logbook PKL kamu sekarang sudah live di Railway!

**URLs Penting:**
- Aplikasi: `https://[nama-project].up.railway.app`
- Dashboard Railway: `https://railway.app/dashboard`
