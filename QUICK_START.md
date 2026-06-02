# QUICK START - Deploy SmartRT Online

## 📋 Checklist Sebelum Mulai

- [ ] GitHub account (gratis di github.com)
- [ ] Hosting provider (Hostinger/Domainesia/Niagahoster)
- [ ] Domain atau subdomain
- [ ] cPanel access dari hosting
- [ ] Git installed di komputer Anda

---

## ⚡ 5 STEP CEPAT

### STEP 1: GitHub Pages (Frontend)
```bash
cd c:\xampp\htdocs\SmartRT02

git init
git add .
git commit -m "SmartRT - Initial commit"
git remote add origin https://github.com/entisaja17/SmartRT.git
git branch -M main
git push -u origin main
```

Buka di GitHub → Settings → Pages → Source: `main` → Save

✅ Frontend live di: `https://YOUR_USERNAME.github.io/SmartRT`

---

### STEP 2: Upload Backend
1. Di cPanel → File Manager → public_html
2. Buat folder `api`
3. Upload file:
   - `api.php`
   - `koneksi.php`

✅ Backend live di: `https://yourdomain.com/api/api.php`

---

### STEP 3: Setup Database
1. Di cPanel → MySQL Databases
2. Buat database: `smart_rt`
3. Buat user: `smartrt_user` dengan password kuat
4. Assign user ke database dengan ALL PRIVILEGES

---

### STEP 4: Edit koneksi.php
Di hosting, edit file `api/koneksi.php`:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'smart_rt');      // ← Ganti dengan DB name Anda
define('DB_USER', 'smartrt_user');  // ← Ganti dengan user Anda
define('DB_PASS', 'YOUR_PASSWORD'); // ← Ganti dengan password Anda!
```

---

### STEP 5: Update config.js & Push
Edit file `config.js` di repository GitHub:
```javascript
const API_BASE_URL = 'https://yourdomain.com/api';
```

Push ke GitHub:
```bash
git add config.js
git commit -m "Update API URL to production"
git push
```

---

## ✅ Test

1. Buka: `https://YOUR_USERNAME.github.io/SmartRT`
2. Buka browser console (F12)
3. Type: `GAS_URL` → should show domain Anda
4. Coba login

---

## 🎉 DONE!

Aplikasi SmartRT sekarang ONLINE dan dapat diakses oleh siapa saja! 

**URLs:**
- Frontend: `https://YOUR_USERNAME.github.io/SmartRT`
- Backend: `https://yourdomain.com/api/api.php`

---

## 💡 Tips

- GitHub Pages otomatis deploy setiap kali push
- Update settings di cPanel jika perlu ubah database
- Keep `api/koneksi.php` credentials aman (jangan push ke GitHub!)
- Gunakan HTTPS (biasanya auto-generated Let's Encrypt)

---

## ❌ Troubleshoot

| Problem | Solution |
|---------|----------|
| API tidak connect | Check `config.js` API_BASE_URL sudah benar? |
| Login error | Check MySQL user credentials di `koneksi.php` |
| CORS error | Check `Access-Control-Allow-Origin` di `api.php` |
| Frontend blank | Check browser console (F12) untuk JS errors |

---

**Need help?** Lihat file [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md) untuk detail lengkap.

Selamat! 🚀
