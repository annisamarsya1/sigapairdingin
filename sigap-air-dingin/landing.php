<?php

require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/db.php';
session_start();
$lang = $_GET['lang'] ?? 'id';

$texts = [
    'id' => [
        'title' => 'SIGAP AIR DINGIN',
        'tagline' => 'Laporkan Lebih Cepat, Tangani Lebih Tepat',
        'subtitle' => 'Sistem Informasi Pelaporan dan Pemetaan Permasalahan Air, Lingkungan, dan Pelayanan Publik di Kelurahan Air Dingin.',
        'cta_report' => 'Laporkan Kejadian',
        'cta_login' => 'Masuk Dashboard',
        'cta_register' => 'Daftar Akun',
        'feature_1_title' => 'Pelaporan Terintegrasi',
        'feature_1_desc' => 'Pengaduan, aspirasi, dan administrasi dalam satu sistem.',
        'feature_2_title' => 'Transparan & Terpantau',
        'feature_2_desc' => 'Pantau status laporan dari awal hingga selesai.',
        'feature_3_title' => 'Peta Mitigasi',
        'feature_3_desc' => 'Pemetaan wilayah rawan berbasis laporan masyarakat.',
        'how_title' => 'Alur Penggunaan',
        'how_1' => 'Masuk atau daftar akun SIGAP.',
        'how_2' => 'Buat laporan lengkap dengan lokasi.',
        'how_3' => 'Pantau tindak lanjut petugas.'
    ]
];
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Favicon / Logo Kota Sawahlunto -->
 <link rel="icon" type="image/x-icon" href="<?= BASE_URL ?>favicon-new.ico">
<link rel="shortcut icon" type="image/x-icon" href="<?= BASE_URL ?>favicon-new.ico">
<link rel="apple-touch-icon" sizes="180x180" href="<?= BASE_URL ?>favicon-new.ico">
<title><?= $texts[$lang]['title'] ?></title>

<style>
/* RESET */
*{margin:0;padding:0;box-sizing:border-box}
body{
    font-family:system-ui,-apple-system,"Segoe UI",sans-serif;
    background:linear-gradient(180deg,#f0f6ff,#eef2f7);
    color:#1f2a37;
    padding-top:60px;
}

/* UTIL */
.container{max-width:1180px;margin:auto;padding:0 1.2rem}
.text-center{text-align:center}
.text-muted{color:#6b7280}
.section{padding:4.5rem 0}
.bg-light{background:#f8fafc}

/* NAVBAR */
.navbar{
    position:fixed;
    top:0;width:100%;
    background:#fff;
    padding:.0.5rem 0;
    box-shadow:0 8px 28px rgba(15,63,125,.12);
    z-index:1000;
}
.navbar .container{
    display:flex;
    justify-content:space-between;
    align-items:center;
}
.navbar-brand{
    font-weight:800;
    color:#0d6efd;
    text-decoration:none;
}
.navbar-brand small{
    display:block;
    font-size:.72rem;
    color:#6b7280;
}
.navbar-nav{
    list-style:none;
    display:flex;
    gap:.6rem;
    align-items:center;
}
.nav-link{
    text-decoration:none;
    font-weight:600;
    padding:.55rem 1rem;
    border-radius:999px;
    color:#1f2a37;
}
.nav-link:hover{background:#eef4ff;color:#0d6efd}

/* BUTTON */
.btn{
    display:inline-flex;
    align-items:center;
    padding:.7rem 1.8rem;
    border-radius:999px;
    font-weight:700;
    text-decoration:none;
    transition:.2s;
}
.btn-primary{
    background:linear-gradient(135deg,#0d6efd,#1e88ff);
    color:#fff;
    box-shadow:0 10px 24px rgba(13,110,253,.35);
}
.btn-outline-primary{
    border:2px solid #0d6efd;
    color:#0d6efd;
    background:#fff;
}
.btn:hover{transform:translateY(-2px)}

/* HERO */
.hero{
    min-height:120vh;
    display:flex;
    align-items:center;
    color:#fff;
    background:
      linear-gradient(rgba(15,23,42,.55),rgba(15,23,42,.65)),
      url("<?= BASE_URL ?>assets/img/ombilin.png") center/cover no-repeat;
}
.hero h1{font-size:3rem;font-weight:800;margin-bottom:1rem}
.hero p{
    max-width:760px;
    margin:0 auto 2rem;
    font-size:1.1rem;
    opacity:.95;
}

/* GRID */
.row{display:flex;flex-wrap:wrap;gap:1.6rem}
.col-4{flex:1 1 calc(33.333% - 1rem)}
.col-6{flex:1 1 50%}
@media(max-width:900px){
    .col-4,.col-6{flex:1 1 100%}
}

/* CARD */
.card{
    background:#fff;
    border-radius:1.2rem;
    padding:2rem;
    box-shadow:0 18px 45px rgba(15,63,125,.14);
    transition:.25s;
}
.card:hover{transform:translateY(-6px)}
.card h4{margin-bottom:.6rem}
.card p{line-height:1.6}

/* MAP */
#riskMapInner{
    height:420px;
    border-radius:1.2rem;
    background:linear-gradient(135deg,#e0ecff,#f8fbff);
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:600;
    color:#475569;
    box-shadow: 0 18px 45px rgba(15,63,125,.14);
}


/* ================= ALUR PENGGUNAAN ================= */

/* Container dipersempit */
.section.bg-light .container{
    max-width:960px;
}

/* Grid dirapatkan */
.section.bg-light .row{
    gap:1rem; /* sebelumnya besar */
}

/* Kolom jangan terlalu melebar */
.section.bg-light .col-6{
    flex:1 1 48%;
}

/* Card alur */
.section.bg-light .card{
    padding:1.3rem 1.4rem; /* lebih ringkas */
    box-shadow:0 10px 22px rgba(0,0,0,.08);
}

/* Judul alur */
.section.bg-light h2{
    margin-bottom:.6rem;
}

/* List alur */
.section.bg-light ol{
    margin-top:.6rem;
}

/* Isi list */
.section.bg-light li{
    margin-bottom:.45rem;
    line-height:1.45;
    font-size:.95rem;
}

/* Tombol dirapatkan */
.section.bg-light .btn{
    margin-top:.6rem;
    padding:.55rem 1.4rem;
}

/* RESPONSIVE */
@media(max-width:900px){
    .section.bg-light .col-6{
        flex:1 1 100%;
    }
}

</style>
<link rel="stylesheet" href="assets/css/navbar.css">
</head>
<body>

<?php include __DIR__ . '/partials/navbar.php'; ?>

<!-- HERO -->
<section id="hero" class="hero">
    <div class="container text-center">
        <h1><?= $texts[$lang]['tagline'] ?></h1>
        <p><?= $texts[$lang]['subtitle'] ?></p>
        <a href="<?= BASE_URL ?>auth/register.php" class="btn btn-primary"><?= $texts[$lang]['cta_report'] ?></a>
    </div>
</section>

<!-- FEATURES -->
<section id="fitur" class="section">
    <div class="container">
        <div class="text-center" style="margin-bottom:3rem">
            <h2 style="font-weight:800">Kenapa SIGAP Air Dingin?</h2>
            <p class="text-muted">Satu sistem resmi untuk seluruh layanan masyarakat</p>
        </div>

        <div class="row">
            <div class="col-4">
                <div class="card text-center">
                    <h4><?= $texts[$lang]['feature_1_title'] ?></h4>
                    <p class="text-muted"><?= $texts[$lang]['feature_1_desc'] ?></p>
                </div>
            </div>
            <div class="col-4">
                <div class="card text-center">
                    <h4><?= $texts[$lang]['feature_2_title'] ?></h4>
                    <p class="text-muted"><?= $texts[$lang]['feature_2_desc'] ?></p>
                </div>
            </div>
            <div class="col-4">
                <div class="card text-center">
                    <h4><?= $texts[$lang]['feature_3_title'] ?></h4>
                    <p class="text-muted"><?= $texts[$lang]['feature_3_desc'] ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- HOW -->
<section id="alur" class="section bg-light">
    <div class="container">
        <div class="row">
            <div class="col-6">
                <h2 style="font-weight:800"><?= $texts[$lang]['how_title'] ?></h2>
                <ol class="text-muted" style="margin-top:1rem;padding-left:1.2rem">
                    <li><?= $texts[$lang]['how_1'] ?></li>
                    <li><?= $texts[$lang]['how_2'] ?></li>
                    <li><?= $texts[$lang]['how_3'] ?></li>
                </ol>
            </div>
            <div class="col-6">
                <div class="card">
                    <h4>Siap Berpartisipasi?</h4>
                    <p class="text-muted">Gunakan sistem resmi untuk pelayanan publik.</p>
                    <a href="<?= BASE_URL ?>auth/register.php" class="btn btn-primary">Daftar</a>
                    <a href="<?= BASE_URL ?>auth/login.php" class="btn btn-outline-primary">Masuk</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- MAP -->
<?php
// Ambil data pengaduan bencana alam
$stmt = $pdo->prepare("SELECT id, judul, deskripsi, lokasi 
                       FROM pengaduan 
                       WHERE jenis_pengaduan = 'Bencana Alam'");
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- MAP -->
<section id="peta" class="section">
    <div class="container" style="max-width:920px">
        <div id="riskMap" style="height:420px; border-radius:1.2rem; box-shadow:0 18px 45px rgba(15,63,125,.14);"></div>
    </div>
</section>

<!-- Leaflet JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
var map = L.map('riskMap').setView([-0.6671, 100.7831], 15);

// Layer OpenStreetMap
var osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

// Layer Satelit ESRI
var satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
    attribution: 'Tiles &copy; Esri'
});

// Layer control
L.control.layers({
    "Peta Biasa": osmLayer,
    "Satelit": satelliteLayer
}).addTo(map);

// Tambahkan marker dari data pengaduan
<?php foreach($data as $d):
    if(!empty($d['lokasi'])):
        $coords = array_map('trim', explode(',', $d['lokasi']));
        if(count($coords) == 2):
            $lat = $coords[0];
            $lng = $coords[1];
?>
L.marker([<?= $lat ?>, <?= $lng ?>])
 .addTo(map)
 .bindPopup("<b><?= addslashes($d['judul']) ?></b><br><?= addslashes($d['deskripsi']) ?>");
<?php
            endif;
    endif;
endforeach; ?>
</script>




</body>
</html>
