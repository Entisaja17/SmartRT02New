## 🚀 PANDUAN DEPLOYMENT - GITHUB PAGES + SHARED HOSTING

### 📋 Ringkasan Setup
Anda sekarang memiliki:
- **Frontend**: HTML/CSS/JS (untuk GitHub Pages)  
- **Backend**: PHP/MySQL (untuk Shared Hosting)

---

## STEP 1: Setup GitHub Pages (Frontend)

### 1.1 Buat Repository di GitHub
1. Buka https://github.com/new
2. Nama repo: `SmartRT` (atau nama lain)
3. Pilih **Public** (agar bisa diakses online)
4. Klik "Create repository"

### 1.2 Upload Frontend ke GitHub
```bash
# Di folder SmartRT02 Anda
cd c:\xampp\htdocs\SmartRT02

# Inisialisasi git
git init

# Add files
git add .

# Commit
git commit -m "Initial commit - SmartRT frontend"

# Tambah remote
git remote add origin https://github.com/USERNAME/SmartRT.git

# Push ke GitHub
git branch -M main
git push -u origin main
```

### 1.3 Enable GitHub Pages
1. Di repository GitHub Anda, buka **Settings**
2. Scroll ke **Pages**
3. Source: Pilih `main` branch
4. Klik Save

✅ **Frontend sekarang live di**: `https://USERNAME.github.io/SmartRT`

---

## STEP 2: Setup Backend (Shared Hosting)

### 2.1 Pilih Hosting Provider
**Rekomendasi untuk Pemula:**
- **Hostinger** - Rp 25-50rb/bulan
- **Domainesia** - Rp 45-100rb/bulan  
- **Niagahoster** - Rp 50-100rb/bulan
- **Bluehost** - $2.95/bulan

**Syarat yang dibutuhkan:**
- ✅ Support PHP 7.4+
- ✅ MySQL 5.7+
- ✅ cPanel untuk mudah upload file

### 2.2 Upload File Backend ke Hosting
1. Beli hosting & dapatkan:
   - cPanel login
   - FTP/SFTP credentials
   - Domain (atau subdomain gratis)

2. Di cPanel, akses **File Manager** atau gunakan FTP client:
   - Upload file berikut ke `public_html/api/`:
     - `api.php`
     - `koneksi.php`

3. Buat database MySQL di cPanel:
   - Buka **MySQL Databases**
   - Buat database: `smart_rt`
   - Buat user MySQL dengan password kuat
   - **CATAT**: DB name, user, password

### 2.3 Update Database Config
Edit file `koneksi.php` di hosting dengan credentials benar:

```php
<?php
// Update sesuai hosting Anda
define('DB_HOST', 'localhost');  // Biasanya tidak perlu diubah
define('DB_PORT', 3306);
define('DB_NAME', 'smart_rt');     // Ganti dengan DB name Anda
define('DB_USER', 'user_smartrt'); // Ganti dengan MySQL user Anda
define('DB_PASS', 'password123');  // Ganti dengan password Anda
define('DB_CHARSET', 'utf8mb4');
```

### 2.4 Test API
Buka di browser:
```
https://yourdomain.com/api/api.php
```
Seharusnya return JSON response (atau error jika test).

---

## STEP 3: Update Frontend Config

### 3.1 Update config.js
Edit file `config.js` di repository GitHub Anda:

```javascript
// ❌ SEBELUM (localhost)
const API_BASE_URL = 'https://your-domain.com';

// ✅ SESUDAH (domain hosting Anda)
const API_BASE_URL = 'https://yourdomain.com/api';
```

### 3.2 Push Update ke GitHub
```bash
git add config.js
git commit -m "Update API URL to production"
git push
```

---

## STEP 4: Test Koneksi End-to-End

1. Buka frontend: `https://USERNAME.github.io/SmartRT`
2. Di browser console (F12 → Console), cek:
   ```javascript
   console.log(GAS_URL); // Should show: https://yourdomain.com/api/api.php
   ```
3. Coba login dengan credentials database Anda

---

## 🔒 SECURITY CHECKLIST

- [ ] Update `DB_PASS` dengan password kuat (minimal 12 karakter)
- [ ] Jangan commit credentials ke GitHub
- [ ] Aktifkan HTTPS di hosting (Let's Encrypt gratis di cPanel)
- [ ] Set database user hanya untuk database `smart_rt` (jangan ALL PRIVILEGES)
- [ ] Enable firewall di hosting
- [ ] Regular backup database (cPanel punya backup otomatis)

---

## 📱 URLs Setelah Deploy

- **Frontend**: `https://USERNAME.github.io/SmartRT`  
- **Backend API**: `https://yourdomain.com/api/api.php`  
- **Database**: Private (hanya backend yang akses)

---

## ❓ TROUBLESHOOTING

### "API tidak mau connect"
- ❌ Check: `config.js` sudah update? 
- ❌ Check: Hosting API aktif? (buka di browser)
- ❌ Check: CORS headers sudah aktif? (check `api.php`)

### "Database error"
- ❌ Check: DB credentials di `koneksi.php` benar?
- ❌ Check: Database sudah dibuat?
- ❌ Check: MySQL user sudah get permission?

### "GitHub Pages loading lambat"
- ✅ Normal, GitHub Pages punya CDN worldwide
- ✅ First load ~3-5 detik, refresh lebih cepat

---

## 📞 SUPPORT

Jika ada error:
1. Check browser console (F12)
2. Buka hosting control panel → Error logs
3. Coba di incognito mode (clear cache)

Selamat! 🎉 Aplikasi Anda sekarang **ONLINE**!
