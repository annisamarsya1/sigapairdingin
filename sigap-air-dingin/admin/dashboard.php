<?php
session_start();

// Proteksi admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

// Koneksi DB
require_once __DIR__ . '/../config/db.php';

// Bahasa
$lang = $_GET['lang'] ?? 'id';

// ========================
// DATA GRAFIK TREND PER JENIS LAPORAN
// ========================
$jenis_laporan = ['pengaduan','aspirasi','administrasi'];
$tren_data = [];

foreach($jenis_laporan as $jenis){
    $stmt = $pdo->prepare("
        SELECT DATE_FORMAT(created_at, '%Y-%m') AS bulan, COUNT(*) AS count
        FROM $jenis
        GROUP BY bulan
        ORDER BY bulan ASC
    ");
    $stmt->execute();
    $tren_data[$jenis] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ========================
// DATA TINDAK LANJUT / STATUS PER JENIS
// ========================
$status_data = [];
foreach($jenis_laporan as $jenis){
    $stmt = $pdo->prepare("SELECT status, COUNT(*) AS jumlah FROM $jenis GROUP BY status");
    $stmt->execute();
    $status_data[$jenis] = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // ['baru'=>10,'ditindak'=>5,...]
}

// ========================
// DATA MAP
// ========================
$map_data = [];

// Pengaduan: lokasi gabungan "lat,lng"
$stmt = $pdo->query("SELECT id, lokasi FROM pengaduan WHERE lokasi IS NOT NULL");
$pengaduan_rows = [];
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    $parts = explode(',', $row['lokasi']);
    if(count($parts)==2){
        $pengaduan_rows[] = [
            'id'=>$row['id'],
            'lat'=>trim($parts[0]),
            'lng'=>trim($parts[1])
        ];
    }
}
$map_data['pengaduan'] = $pengaduan_rows;

// Aspirasi
$stmt = $pdo->query("SELECT id, latitude, longitude FROM aspirasi WHERE latitude IS NOT NULL AND longitude IS NOT NULL");
$map_data['aspirasi'] = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Admin - SIGAP AIR DINGIN</title>

<!-- CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css" rel="stylesheet">
<link href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" rel="stylesheet">

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
body{background:#f4f6f9}
.sidebar{width:250px;min-height:100vh}
.auth-logo{width:42px;height:42px;border-radius:50%;background:#0d6efd;color:#fff;display:flex;align-items:center;justify-content:center;}
</style>
</head>
<body>
<div class="d-flex">

<!-- SIDEBAR -->
<nav class="sidebar bg-dark text-white p-3 d-flex flex-column">
    <div class="d-flex align-items-center mb-4">
        <div class="auth-logo me-2"><i class="fas fa-water"></i></div>
        <div>
            <div class="fw-bold">SIGAP AIR DINGIN</div>
            <small class="text-white-50">Admin Panel</small>
        </div>
    </div>
    <ul class="nav nav-pills flex-column gap-1 mb-auto">
        <li class="nav-item">
            <a href="dashboard.php" class="nav-link active">
                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
            </a>
        </li>
        <li><a href="laporan.php" class="nav-link text-white"><i class="fas fa-list me-2"></i>Data Laporan</a></li>
        <li><a href="peta_risiko.php" class="nav-link text-white"><i class="fas fa-map-marked-alt me-2"></i>Peta Risiko</a></li>
    </ul>
    <a href="../admin/logout.php" class="btn btn-outline-light btn-sm mt-auto"><i class="fas fa-sign-out-alt me-1"></i>Logout</a>
</nav>

<!-- CONTENT -->
<div class="flex-grow-1">

<header class="bg-white border-bottom p-3 d-flex justify-content-between">
    <h5 class="mb-0"><i class="fas fa-tachometer-alt me-2"></i>Dashboard Admin</h5>
    <span class="text-muted small">Login sebagai <strong><?= $_SESSION['role'] ?></strong></span>
</header>

<main class="p-4">

<!-- STATISTIK PER JENIS LAPORAN -->
<div class="row g-3 mb-4">
    <?php foreach($jenis_laporan as $jenis): 
        $total_jumlah = array_sum($status_data[$jenis] ?? []);
        $baru = $status_data[$jenis]['baru'] ?? 0;
        $ditindak = $status_data[$jenis]['ditindak'] ?? 0;
        $ditolak = $status_data[$jenis]['ditolak'] ?? 0;
    ?>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small text-capitalize"><?= $jenis ?> - Total Laporan</div>
                <h4><?= $total_jumlah ?></h4>
                <small class="text-primary">Baru: <?= $baru ?></small> | 
                <small class="text-warning">Ditindak: <?= $ditindak ?></small> | 
                <small class="text-danger">Ditolak: <?= $ditolak ?></small>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- GRAFIK TREND PER JENIS -->
<div class="row g-4 mb-4">
    <?php foreach($jenis_laporan as $jenis): ?>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header fw-bold text-capitalize"><?= $jenis ?> - Tren Laporan</div>
            <div class="card-body">
                <canvas id="<?= $jenis ?>Chart"></canvas>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- MAP -->
<div class="card">
    <div class="card-header fw-bold">Peta Laporan</div>
    <div class="card-body">
        <div id="laporanMap" style="height:400px"></div>
    </div>
</div>

</main>
</div>
</div>

<script>
// GRAFIK TREND
<?php foreach($jenis_laporan as $jenis): 
    $labels = json_encode(array_column($tren_data[$jenis],'bulan'));
    $counts = json_encode(array_column($tren_data[$jenis],'count'));
?>
new Chart(document.getElementById('<?= $jenis ?>Chart'), {
    type:'line',
    data:{
        labels: <?= $labels ?>,
        datasets:[{
            label:'<?= ucfirst($jenis) ?>',
            data: <?= $counts ?>,
            borderColor: '<?= $jenis=='pengaduan'?'#0d6efd':($jenis=='aspirasi'?'#198754':'#fd7e14')?>',
            backgroundColor: 'rgba(0,0,0,0)',
            tension:0.3
        }]
    },
    options:{plugins:{legend:{display:false}}}
});
<?php endforeach; ?>

// MAP
const map = L.map('laporanMap').setView([-0.8, 113.9], 5);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

const mapData = <?= json_encode($map_data) ?>;

// Fungsi buat marker
function addMarkers(data, color){
    data.forEach(d => {
        L.circleMarker([parseFloat(d.lat), parseFloat(d.lng)], {
            color: color,
            radius:6,
            fillOpacity:0.8
        }).addTo(map);
    });
}

addMarkers(mapData.pengaduan,'#0d6efd'); // biru
addMarkers(mapData.aspirasi,'#198754'); // hijau
addMarkers(mapData.administrasi,'#fd7e14'); // oranye
</script>

</body>
</html>
