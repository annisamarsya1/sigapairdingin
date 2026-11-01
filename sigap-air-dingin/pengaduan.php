<?php
session_start();
require 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit;
}

$lang = $_GET['lang'] ?? 'id';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $jenis = $_POST['jenis'];
    $jenis_lainnya = $_POST['jenis_lainnya'] ?? '';
    $lokasi = $_POST['lokasi'] ?? '';
    $bukti = '';

    // Upload file
    if (isset($_FILES['bukti']) && $_FILES['bukti']['error'] == 0) {
        $ext = pathinfo($_FILES['bukti']['name'], PATHINFO_EXTENSION);
        if(!is_dir('uploads')) mkdir('uploads', 0777, true);
        $bukti = 'uploads/' . uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['bukti']['tmp_name'], $bukti);
    }

    $stmt = $pdo->prepare("INSERT INTO pengaduan (user_id, judul, deskripsi, jenis_pengaduan, jenis_lainnya, lokasi, bukti)
                           VALUES (?, ?, ?, ?, ?, ?, ?)");
    if ($stmt->execute([$_SESSION['user_id'], $judul, $deskripsi, $jenis, $jenis_lainnya, $lokasi, $bukti])) {
        $success = "Pengaduan berhasil dikirim!";
    } else {
        $error = "Terjadi kesalahan, coba lagi.";
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pengaduan - Sigap Air Dingin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
body{font-family:system-ui,-apple-system,"Segoe UI",sans-serif;color:#1f2a37;background:radial-gradient(circle at top left,#e3f2fd 0,#f0f6ff 100%);}
.sidebar{background:#0d6efd;color:#fff;min-width:240px;max-width:260px;min-height:100vh;padding:1rem;display:flex;flex-direction:column;}
.sidebar .brand{display:flex;align-items:center;gap:.5rem;margin-bottom:2rem;}
.sidebar .brand i{font-size:1.8rem;}
.sidebar .nav-link{color:#fff;margin-bottom:.3rem;display:flex;align-items:center;gap:.6rem;font-weight:600;padding:.5rem 1rem;border-radius:.6rem;text-decoration:none;transition:.2s;}
.sidebar .nav-link.active, .sidebar .nav-link:hover{background:rgba(255,255,255,.15);}
.sidebar .language-btns{display:flex;gap:.5rem;margin-bottom:1rem;}
.sidebar .language-btns a{flex:1;text-align:center;border:1px solid #fff;border-radius:.6rem;padding:.25rem 0;color:#fff;text-decoration:none;font-size:.8rem;transition:.2s;}
.sidebar .language-btns a:hover{background:rgba(255,255,255,.2);}
.sidebar .logout{margin-top:auto;text-align:center;}
.header{background:#fff;border-radius:1.2rem;box-shadow:0 4px 12px rgba(0,0,0,.05);padding:.75rem 1.5rem;display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;}
.main{flex-grow:1;padding:1.5rem;}
.card{background:#fff;border-radius:1.2rem;padding:2rem;box-shadow:0 18px 45px rgba(15,63,125,.14);transition:.25s;margin-bottom:1.5rem;}
.card:hover{transform:translateY(-4px);}
.card h4{margin-bottom:.8rem;}
.card p{line-height:1.6;}
.btn{border-radius:999px;font-weight:700;transition:.2s;}
.btn:hover{transform:translateY(-2px);}
#map{height:300px;border-radius:1rem;margin-top:1rem;}
</style>
</head>
<body>
<div class="d-flex">
    <!-- SIDEBAR -->
<nav class="sidebar">
    <div class="brand">
        <i class="fas fa-water"></i>
        <div><div class="fw-bold">Sigap Air Dingin</div><small>Portal Warga</small></div>
    </div>
    <a href="index.php?lang=<?= $lang ?>" class="nav-link active"><i class="fas fa-home"></i>Beranda</a>
    <?php if($_SESSION['role']!='user'): ?>
    <a href="dashboard.php?lang=<?= $lang ?>" class="nav-link"><i class="fas fa-tachometer-alt"></i>Dashboard</a>
    <?php endif; ?>

    <!-- Dropdown Lapor -->
    <div class="nav-item">
        <a class="nav-link d-flex justify-content-between align-items-center gap-2" data-bs-toggle="collapse" href="#laporDropdown">
            <span><i class="fas fa-exclamation-triangle me-2"></i>Lapor</span>
            <i class="fas fa-caret-down"></i>
        </a>
        <div class="collapse" id="laporDropdown">
            <a href="pengaduan.php?lang=<?= $lang ?>" class="nav-link ps-4">Pengaduan</a>
            <a href="aspirasi.php?lang=<?= $lang ?>" class="nav-link ps-4">Aspirasi</a>
            <a href="administrasi.php?lang=<?= $lang ?>" class="nav-link ps-4">Administrasi Publik</a>
        </div>
    </div>

    <a href="riwayat.php?lang=<?= $lang ?>" class="nav-link"><i class="fas fa-history"></i>Riwayat</a>
    <a href="peta_risiko.php?lang=<?= $lang ?>" class="nav-link"><i class="fas fa-map-marked-alt"></i>Peta Risiko</a>

    <div class="language-btns mt-3 mb-2">
        <a href="?lang=id">ID</a>
        <a href="?lang=en">EN</a>
    </div>
    <div class="logout">
        <a href="logout.php" class="btn btn-outline-light w-100"><i class="fas fa-sign-out-alt me-1"></i>Logout</a>
    </div>
</nav>

<div class="flex-grow-1 d-flex flex-column">
    <header class="header">
        <div><i class="fas fa-home me-1"></i>Selamat Datang</div>
        <span class="small text-muted">Masuk sebagai <strong><?= $_SESSION['role'] ?></strong></span>
    </header>

    <main class="main">
        <div class="card">
            <h4>Form Pengaduan</h4>
            <?php if($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
            <?php if($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3"><label class="form-label">Judul</label><input type="text" name="judul" class="form-control" required></div>
                <div class="mb-3"><label class="form-label">Deskripsi</label><textarea name="deskripsi" class="form-control" rows="3" required></textarea></div>
                <div class="mb-3">
                    <label class="form-label">Jenis Pengaduan</label>
                    <select name="jenis" id="jenis" class="form-select" required>
                        <option value="">Pilih...</option>
                        <option value="Bencana Alam">Bencana Alam</option>
                        <option value="Bantuan Hukum">Bantuan Hukum</option>
                        <option value="Infrastruktur">Infrastruktur</option>
                        <option value="Lingkungan">Lingkungan</option>
                        <option value="Keamanan">Keamanan</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="mb-3" id="jenisLainnyaDiv" style="display:none;">
                    <label class="form-label">Sebutkan Lainnya</label>
                    <input type="text" name="jenis_lainnya" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Peta Lokasi</label>
                    <div id="map"></div>
                    <input type="hidden" name="lokasi" id="lokasi">
                </div>
                <div class="mb-3">
                    <label class="form-label">Upload Bukti</label>
                    <input type="file" name="bukti" class="form-control">
                </div>
                <button class="btn btn-primary w-100">Kirim</button>
            </form>
        </div>
    </main>

    <footer class="text-center py-3">
        <span class="text-muted">&copy; 2023 Sigap Air Dingin. All rights reserved.</span>
    </footer>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// Tampilkan field "Lainnya" jika dipilih
document.getElementById('jenis').addEventListener('change', function(){
    document.getElementById('jenisLainnyaDiv').style.display = this.value === 'Lainnya' ? 'block' : 'none';
});

// Koordinat Sawahlunto, Sumatera Barat
var defaultLat = -0.6671;
var defaultLng = 100.7831;
var map = L.map('map').setView([defaultLat, defaultLng], 14);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

// Set default ke Sawahlunto dulu
var marker = L.marker([defaultLat, defaultLng]).addTo(map).bindPopup("Kelurahan Air Dingin, Sawahlunto").openPopup();
document.getElementById('lokasi').value = defaultLat + ',' + defaultLng;

// Klik peta untuk pindah marker
map.on('click', function(e) {
    var lat = e.latlng.lat.toFixed(6);
    var lng = e.latlng.lng.toFixed(6);
    marker.setLatLng([lat, lng]).bindPopup("Lokasi dipilih: " + lat + ", " + lng).openPopup();
    document.getElementById('lokasi').value = lat + ',' + lng;
});

// Coba ambil lokasi GPS
if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position){
        var lat = position.coords.latitude;
        var lng = position.coords.longitude;
        map.setView([lat, lng], 16);
        marker.setLatLng([lat, lng]).bindPopup("Lokasi Anda saat ini").openPopup();
        document.getElementById('lokasi').value = lat + ',' + lng;
    }, function() {
        // GPS gagal, tetap pakai default Sawahlunto
    });
}
</script>
</body>
</html>
