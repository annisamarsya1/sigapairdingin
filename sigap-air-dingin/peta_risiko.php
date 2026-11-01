<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit;
}

require_once 'config/db.php';

$lang = $_GET['lang'] ?? 'id';
$texts = [
    'id' => [
        'peta_risiko' => 'Peta Risiko',
        'logout' => 'Logout',
        'welcome' => 'Selamat Datang',
        'lapor' => 'Lapor'
    ],
    'en' => [
        'peta_risiko' => 'Risk Map',
        'logout' => 'Logout',
        'welcome' => 'Welcome',
        'lapor' => 'Report'
    ]
];

// Ambil seluruh laporan bencana
$stmt = $pdo->prepare("SELECT id, judul, deskripsi, lokasi, status, created_at 
                       FROM pengaduan 
                       WHERE jenis_pengaduan = 'Bencana Alam'");
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $texts[$lang]['peta_risiko'] ?> - Sigap Air Dingin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:system-ui,-apple-system,"Segoe UI",sans-serif;color:#1f2a37;background:radial-gradient(circle at top left,#e3f2fd 0,#f0f6ff 100%);}
.sidebar{background:#0d6efd;color:#fff;min-width:240px;max-width:260px;min-height:100vh;padding:1rem;display:flex;flex-direction:column;}
.sidebar .brand{display:flex;align-items:center;gap:.5rem;margin-bottom:2rem;}
.sidebar .brand i{font-size:1.8rem;}
.sidebar .nav-link{color:#fff;margin-bottom:.3rem;display:flex;align-items:center;gap:.6rem;font-weight:600;padding:.5rem 1rem;border-radius:.6rem;text-decoration:none;transition:.2s;}
.sidebar .nav-link.active,.sidebar .nav-link:hover{background:rgba(255,255,255,.15);}
.sidebar .language-btns{display:flex;gap:.5rem;margin-bottom:1rem;}
.sidebar .language-btns a{flex:1;text-align:center;border:1px solid #fff;border-radius:.6rem;padding:.25rem 0;color:#fff;text-decoration:none;font-size:.8rem;transition:.2s;}
.sidebar .language-btns a:hover{background:rgba(255,255,255,.2);}
.sidebar .logout{margin-top:auto;text-align:center;}
.header{background:#fff;border-radius:1.2rem;box-shadow:0 4px 12px rgba(0,0,0,.05);padding:.75rem 1.5rem;display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;}
.main{flex-grow:1;padding:1.5rem;}
#map{height:600px;border-radius:1.2rem;box-shadow:0 18px 45px rgba(15,63,125,.14);}
</style>
</head>
<body>
<div class="d-flex">
    <!-- SIDEBAR -->
    <nav class="sidebar">
        <div class="brand">
            <i class="fas fa-water"></i>
            <div>
                <div class="fw-bold">Sigap Air Dingin</div>
                <small>Portal Warga</small>
            </div>
        </div>
        <a href="index.php?lang=<?= $lang ?>" class="nav-link">
            <i class="fas fa-home"></i>Beranda
        </a>
        <div class="nav-item">
            <a class="nav-link d-flex justify-content-between align-items-center gap-2" 
               data-bs-toggle="collapse" href="#laporDropdown" role="button" aria-expanded="false" aria-controls="laporDropdown">
                <span><i class="fas fa-exclamation-triangle me-2"></i><?= $texts[$lang]['lapor'] ?></span>
                <i class="fas fa-caret-down"></i>
            </a>
            <div class="collapse" id="laporDropdown">
                <a href="pengaduan.php" class="nav-link ps-4">Laporan</a>
                <a href="aspirasi.php" class="nav-link ps-4">Aspirasi</a>
                <a href="administrasi.php" class="nav-link ps-4">Layanan Administrasi Publik</a>
            </div>
        </div>
        <a href="riwayat.php?lang=<?= $lang ?>" class="nav-link">
            <i class="fas fa-history"></i>Riwayat
        </a>
        <a href="peta_risiko.php?lang=<?= $lang ?>" class="nav-link active">
            <i class="fas fa-map-marked-alt"></i><?= $texts[$lang]['peta_risiko'] ?>
        </a>
        <div class="language-btns mt-3 mb-2">
            <a href="?lang=id">ID</a>
            <a href="?lang=en">EN</a>
        </div>
        <div class="logout">
            <a href="logout.php" class="btn btn-outline-light w-100">
                <i class="fas fa-sign-out-alt me-1"></i><?= $texts[$lang]['logout'] ?>
            </a>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <div class="flex-grow-1 d-flex flex-column">
        <header class="header">
            <div><i class="fas fa-map-marked-alt me-1"></i><?= $texts[$lang]['peta_risiko'] ?></div>
            <span class="small text-muted">Masuk sebagai <strong><?= $_SESSION['role'] ?></strong></span>
        </header>

        <main class="main">
            <div id="map"></div>
        </main>

        <footer class="text-center py-3">
            <span class="text-muted">&copy; 2023 Sigap Air Dingin. All rights reserved.</span>
        </footer>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// Default map ke Kelurahan Air Dingin, Sawahlunto, Sumatera Barat
var map = L.map('map').setView([-0.6671, 100.7831], 15);

// Layer dasar: OpenStreetMap
var osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

// Layer citra satelit (ESRI)
var satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
    attribution: 'Tiles &copy; Esri'
});

// Layer control
var baseMaps = {
    "Peta Biasa": osmLayer,
    "Satelit": satelliteLayer
};
L.control.layers(baseMaps).addTo(map);

// Lokasi pengguna (opsional, tidak menggeser peta jika gagal)
map.locate({maxZoom: 16});

function onLocationFound(e) {
    var radius = e.accuracy / 2;
    L.marker(e.latlng).addTo(map)
     .bindPopup("Lokasi Anda (±" + Math.round(radius) + " m)").openPopup();
    L.circle(e.latlng, radius).addTo(map);
}
map.on('locationfound', onLocationFound);

map.on('locationerror', function(e){
    console.log("Lokasi tidak tersedia, menampilkan Sawahlunto.");
});

// Tambah marker laporan bencana
<?php foreach($data as $d): ?>
    <?php 
        if (!empty($d['lokasi'])) {
            list($lat, $lng) = explode(',', $d['lokasi']); 
    ?>
    L.marker([<?= $lat ?>, <?= $lng ?>])
     .addTo(map)
     .bindPopup("<b><?= addslashes($d['judul']) ?></b><br><?= addslashes($d['deskripsi']) ?>");
    <?php } ?>
<?php endforeach; ?>
</script>
</body>
</html>
