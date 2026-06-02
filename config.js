// ========================================
// KONFIGURASI API - GANTI SESUAI DOMAIN ANDA
// ========================================

// Ganti 'https://your-domain.com' dengan domain hosting Anda yang sebenarnya
// Contoh: 'https://smartrt.hosting.com' atau 'https://api.smartrt.com'
const API_BASE_URL = 'https://your-domain.com';

// Export untuk digunakan di modul lain
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { API_BASE_URL };
}
