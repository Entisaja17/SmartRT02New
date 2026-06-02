# 🏘️ SmartRT - Sistem Informasi Rukun Tetangga

Aplikasi web untuk manajemen Rukun Tetangga (RT) modern dengan fitur:
- 👥 Manajemen Data Warga
- 💰 Kelola Iuran & Keuangan
- 📋 Buku Kas RT
- 📢 Pengumuman & Berita
- 🆘 Sistem Keluhan Warga
- 📄 Layanan Surat Digital

---

## 🚀 Deployment

### Opsi 1: Development (Localhost)
```bash
# 1. Buka XAMPP Control Panel
# 2. Mulai Apache & MySQL
# 3. Akses: http://localhost/SmartRT02
# 4. Login: admin / 123
```

### Opsi 2: Production (Online)
📖 **Lihat file**: [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md)

**Quick Summary:**
1. Push frontend ke GitHub Pages
2. Upload backend ke Shared Hosting
3. Update `config.js` dengan domain backend
4. Selesai! ✅

---

## 📁 File Structure

```
SmartRT02/
├── index.html           # UI aplikasi (frontend)
├── kode.js              # Logika aplikasi (frontend)
├── config.js            # Konfigurasi API URL ⭐
├── api.php              # Backend API handler
├── koneksi.php          # Database connection
├── mysql_setup.sql      # SQL schema
├── DEPLOYMENT_GUIDE.md  # Panduan deployment
└── README.md            # File ini
```

---

## 🔧 Konfigurasi

### For Development (Localhost)
Tidak perlu setup apapun, `config.js` sudah default ke localhost.

### For Production (Online)
Edit `config.js`:
```javascript
const API_BASE_URL = 'https://yourdomain.com/api';
```

---

## 📦 Requirements

### Development
- XAMPP (Apache + PHP 7.4+ + MySQL)
- Modern browser (Chrome, Firefox, Edge)

### Production
- Shared hosting dengan PHP 7.4+ & MySQL 5.7+
- Domain/subdomain
- GitHub account (untuk frontend)

---

## 🔐 Security Notes

⚠️ **JANGAN:**
- ❌ Commit database credentials ke GitHub
- ❌ Share `koneksi.php` credentials
- ❌ Gunakan password yang mudah ditebak

✅ **HARUS:**
- ✅ Update `DB_PASS` dengan password kuat
- ✅ Gunakan HTTPS di production
- ✅ Regular backup database

---

## 📞 Support

Jika ada pertanyaan:
1. Check [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md)
2. Check browser console (F12)
3. Check server error logs

---

**Made with ❤️ for RT Communities**

Versi: 1.0.0 | Last Updated: June 2, 2026
