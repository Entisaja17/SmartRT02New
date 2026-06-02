<?php
// ========================================
// API BACKEND - PRODUCTION READY
// Deploy file ini ke shared hosting Anda
// Update DB_HOST, DB_USER, DB_PASS sesuai hosting
// ========================================

// Mencegah output HTML/spasi yang bocor
error_reporting(E_ALL);
ini_set('display_errors', 0); // Ubah ke 1 HANYA saat debugging

// Set Header agar mengembalikan format JSON dan mengizinkan CORS
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Tangani preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// 1. Konfigurasi Database (Sesuaikan dengan server Anda)
$host = 'localhost';
$db   = 'smart_rt'; // Nama database Anda
$user = 'root';     // Username database Anda
$pass = '';         // Password database Anda (kosongkan jika default XAMPP)

// Coba koneksi ke database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Koneksi database gagal.']);
    exit;
}

// 2. Tangkap Data JSON dari Frontend (setara dengan e.postData.contents)
$inputJSON = file_get_contents('php://input');
$body = json_decode($inputJSON, true);

$action = $body['action'] ?? '';
$payload = $body['payload'] ?? [];
$result = [];

// 3. Routing Berdasarkan Action
try {
    if ($action === 'login') {
        $username = $payload['username'] ?? '';
        $password = $payload['password'] ?? '';

        // Cari user berdasarkan Username (frontend mengirimkan `username`)
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        // Cek apakah user ada dan password cocok
        if ($userData && $userData['password'] === $password) {
            $result = [
                'status' => 'success',
                'data' => [
                    'username' => $userData['username'],
                    'nama' => $userData['nama'],
                    'role' => strtolower($userData['role'])
                ]
            ];
        } else {
            $result = ['status' => 'error', 'message' => 'Username atau Password salah!'];
        }

    } elseif ($action === 'register') {
        $nama = $payload['nama'] ?? '';
        $usernameReg = $payload['username'] ?? ''; // frontend mengirimkan 'username'
        $password = $payload['password'] ?? '';

        // Cek apakah username sudah terdaftar
        $stmtCek = $pdo->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
        $stmtCek->execute([$usernameReg]);
        
        if ($stmtCek->fetch()) {
            $result = ['status' => 'error', 'message' => 'Username/NIK sudah terdaftar.'];
        } else {
            // Jika belum ada, masukkan data baru ke kolom `username`
            $stmtInsert = $pdo->prepare("INSERT INTO users (username, password, nama, role) VALUES (?, ?, ?, 'warga')");
            $stmtInsert->execute([$usernameReg, $password, $nama]);
            
            $result = ['status' => 'success', 'message' => 'Pendaftaran berhasil.'];
        }

    } elseif ($action === 'getPengumuman') {
        $stmt = $pdo->query("SELECT id, tgl, judul, konten, penulis FROM pengumuman ORDER BY id DESC");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = ['status' => 'success', 'data' => $rows];

    } elseif ($action === 'addPengumuman') {
        $p = $payload;
        $tgl = $p['tgl'] ?? date('d/m/Y');
        $judul = $p['judul'] ?? '';
        $konten = $p['konten'] ?? '';
        $penulis = $p['penulis'] ?? '';
        $stmt = $pdo->prepare("INSERT INTO pengumuman (tgl, judul, konten, penulis) VALUES (?, ?, ?, ?)");
        $stmt->execute([$tgl, $judul, $konten, $penulis]);
        $result = ['status' => 'success', 'data' => ['id' => $pdo->lastInsertId()]];

        // Backward-compatible alias: frontend may call 'addBerita'
        } elseif ($action === 'addBerita') {
            $p = $payload;
            $tgl = $p['tgl'] ?? date('d/m/Y');
            $judul = $p['judul'] ?? '';
            $konten = $p['konten'] ?? '';
            $penulis = $p['penulis'] ?? '';
            $stmt = $pdo->prepare("INSERT INTO pengumuman (tgl, judul, konten, penulis) VALUES (?, ?, ?, ?)");
            $stmt->execute([$tgl, $judul, $konten, $penulis]);
            $result = ['status' => 'success', 'data' => ['id' => $pdo->lastInsertId()]];

    } elseif ($action === 'getWarga') {
        $stmt = $pdo->query("SELECT * FROM warga ORDER BY id DESC");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = ['status' => 'success', 'data' => $rows];

    } elseif ($action === 'getUsers') {
        $stmt = $pdo->query("SELECT username, nama, role FROM users ORDER BY id DESC");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = ['status' => 'success', 'data' => $rows];

    } elseif ($action === 'addWarga') {
        $p = $payload;
        $no_kk = $p['no_kk'] ?? '';
        $nik = $p['nik'] ?? '';
        $nama = $p['nama'] ?? '';
        $jk = $p['jk'] ?? '';
        $kerja = $p['kerja'] ?? '';
        $hp = $p['hp'] ?? '';
        $alamat = $p['alamat'] ?? '';
        $stmt = $pdo->prepare("INSERT INTO warga (no_kk, nik, nama, jk, kerja, hp, alamat) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$no_kk, $nik, $nama, $jk, $kerja, $hp, $alamat]);
        $result = ['status' => 'success', 'data' => ['id' => $pdo->lastInsertId()]];

    } elseif ($action === 'getKeluhan') {
        $stmt = $pdo->query("SELECT * FROM keluhan ORDER BY id DESC");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = ['status' => 'success', 'data' => $rows];

        } elseif ($action === 'addKeluhan') {
            $p = $payload;
            $tgl = $p['tgl'] ?? date('d/m/Y');
            $user = $p['user'] ?? '';
            $nama = $p['nama'] ?? '';
            $kategori = $p['kategori'] ?? '';
            $desc = $p['desc'] ?? ($p['deskripsi'] ?? '');
            $status = 'Menunggu';
            $tanggapan = '-';
            $stmt = $pdo->prepare("INSERT INTO keluhan (tgl, user, nama, kategori, deskripsi, status, tanggapan) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$tgl, $user, $nama, $kategori, $desc, $status, $tanggapan]);
            $result = ['status' => 'success', 'data' => ['id' => $pdo->lastInsertId()]];

        } elseif ($action === 'updateKeluhan') {
            $p = $payload;
            $id = $p['id'] ?? null;
            $status = $p['status'] ?? null;
            $tanggapan = $p['tanggapan'] ?? null;
            if ($id === null) throw new Exception('Missing id');
            $stmt = $pdo->prepare("UPDATE keluhan SET status = ?, tanggapan = ? WHERE id = ?");
            $stmt->execute([$status, $tanggapan, $id]);
            $result = ['status' => 'success'];

        } elseif ($action === 'delKeluhan') {
            $p = $payload;
            $id = $p['id'] ?? null;
            if ($id === null) throw new Exception('Missing id');
            $stmt = $pdo->prepare("DELETE FROM keluhan WHERE id = ?");
            $stmt->execute([$id]);
            $result = ['status' => 'success'];

    } elseif ($action === 'getSurat') {
        $stmt = $pdo->query("SELECT * FROM surat ORDER BY id DESC");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = ['status' => 'success', 'data' => $rows];
    } elseif ($action === 'addSurat') {
        $p = $payload;
        $tgl = $p['tgl'] ?? date('d/m/Y');
        $user = $p['user'] ?? '';
        $nama = $p['nama'] ?? '';
        $jenis = $p['jenis'] ?? '';
        $ket = $p['ket'] ?? '';
        $status = 'Menunggu';
        $stmt = $pdo->prepare("INSERT INTO surat (tgl, user, nama, jenis, ket, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$tgl, $user, $nama, $jenis, $ket, $status]);
        $result = ['status' => 'success', 'data' => ['id' => $pdo->lastInsertId()]];

    } elseif ($action === 'updateSurat') {
        $p = $payload;
        $id = $p['id'] ?? null;
        $status = $p['status'] ?? null;
        if ($id === null) throw new Exception('Missing id');
        $stmt = $pdo->prepare("UPDATE surat SET status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);
        $result = ['status' => 'success'];

    } elseif ($action === 'getKeuangan') {
        $stmt = $pdo->query("SELECT * FROM keuangan ORDER BY id DESC");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = ['status' => 'success', 'data' => $rows];

    } elseif ($action === 'getIuran') {
        $stmt = $pdo->query("SELECT * FROM iuran ORDER BY id DESC");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = ['status' => 'success', 'data' => $rows];

    } elseif ($action === 'addTrx') {
        $p = $payload;
        $tgl = $p['tgl'] ?? date('d/m/Y');
        $jenis = $p['jenis'] ?? '';
        $kategori = $p['kategori'] ?? '';
        $ket = $p['ket'] ?? '';
        $masuk = intval($p['masuk'] ?? 0);
        $keluar = intval($p['keluar'] ?? 0);
        $stmt = $pdo->prepare("INSERT INTO keuangan (tgl, jenis, kategori, ket, masuk, keluar) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$tgl, $jenis, $kategori, $ket, $masuk, $keluar]);
        $result = ['status' => 'success', 'data' => ['id' => $pdo->lastInsertId()]];

    } elseif ($action === 'addIuran') {
        $p = $payload;
        $user = $p['user'] ?? '';
        $nama = $p['nama'] ?? '';
        $bulan = $p['bulan'] ?? '';
        $nominal = intval($p['nominal'] ?? 0);
        $status = $p['status'] ?? 'Belum Lunas';
        $stmt = $pdo->prepare("INSERT INTO iuran (user, nama, bulan, nominal, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$user, $nama, $bulan, $nominal, $status]);
        $result = ['status' => 'success', 'data' => ['id' => $pdo->lastInsertId()]];

    } elseif ($action === 'updateIuran') {
        $p = $payload;
        $id = $p['id'] ?? null;
        if ($id === null) throw new Exception('Missing id');
        // Tandai iuran sebagai Lunas
        $stmt = $pdo->prepare("UPDATE iuran SET status = 'Lunas' WHERE id = ?");
        $stmt->execute([$id]);

        // Ambil data iuran untuk membuat entri buku kas (keuangan)
        $stmtI = $pdo->prepare("SELECT * FROM iuran WHERE id = ? LIMIT 1");
        $stmtI->execute([$id]);
        $iuran = $stmtI->fetch(PDO::FETCH_ASSOC);
        if ($iuran) {
            $bulan = $iuran['bulan'];
            $nama = $iuran['nama'];
            $nominal = intval($iuran['nominal']);
            $ket = "Iuran $bulan - $nama";

            // Cegah duplikasi: jika sudah ada transaksi yang persis sama, jangan masukkan lagi
            $chk = $pdo->prepare("SELECT id FROM keuangan WHERE kategori = ? AND ket = ? AND masuk = ? LIMIT 1");
            $chk->execute(['Iuran Warga', $ket, $nominal]);
            if (!$chk->fetch()) {
                $tgl = date('d/m/Y');
                $ins = $pdo->prepare("INSERT INTO keuangan (tgl, jenis, kategori, ket, masuk, keluar) VALUES (?, ?, ?, ?, ?, ?)");
                $ins->execute([$tgl, 'Pemasukan', 'Iuran Warga', $ket, $nominal, 0]);
            }
        }

        $result = ['status' => 'success'];

    } elseif ($action === 'delWarga') {
        $p = $payload;
        $id = $p['id'] ?? null;
        if ($id === null) throw new Exception('Missing id');
        $stmt = $pdo->prepare("DELETE FROM warga WHERE id = ?");
        $stmt->execute([$id]);
        $result = ['status' => 'success'];

    } elseif ($action === 'confirmPaymentIuran') {
        // Confirm payment submitted by warga (e.g., via WhatsApp/QRIS) and record to keuangan
        $p = $payload;
        $id = $p['id'] ?? null;
        if ($id === null) throw new Exception('Missing id');

        // Mark as Lunas
        $stmt = $pdo->prepare("UPDATE iuran SET status = 'Lunas' WHERE id = ?");
        $stmt->execute([$id]);

        // Insert to keuangan (avoid duplicates)
        $stmtI = $pdo->prepare("SELECT * FROM iuran WHERE id = ? LIMIT 1");
        $stmtI->execute([$id]);
        $iuran = $stmtI->fetch(PDO::FETCH_ASSOC);
        if ($iuran) {
            $bulan = $iuran['bulan'];
            $nama = $iuran['nama'];
            $nominal = intval($iuran['nominal']);
            $ket = "Iuran $bulan - $nama";

            $chk = $pdo->prepare("SELECT id FROM keuangan WHERE kategori = ? AND ket = ? AND masuk = ? LIMIT 1");
            $chk->execute(['Iuran Warga', $ket, $nominal]);
            if (!$chk->fetch()) {
                $tgl = date('d/m/Y');
                $ins = $pdo->prepare("INSERT INTO keuangan (tgl, jenis, kategori, ket, masuk, keluar) VALUES (?, ?, ?, ?, ?, ?)");
                $ins->execute([$tgl, 'Pemasukan', 'Iuran Warga', $ket, $nominal, 0]);
            }
        }

        $result = ['status' => 'success'];

    } else {
        $result = ['status' => 'error', 'message' => 'Aksi tidak dikenali: ' . $action];
    }
} catch (Exception $e) {
    // Tangkap error jika ada query yang gagal
    $result = ['status' => 'error', 'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()];
}

// 4. Kembalikan Output JSON (setara dengan ContentService di GAS)
echo json_encode($result);