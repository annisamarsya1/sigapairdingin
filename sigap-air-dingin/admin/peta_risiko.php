<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

require_once __DIR__ . '/../config/db.php';
$lang = $_GET['lang'] ?? 'id';

// Ambil data pengaduan
$map_data = [];
$stmt = $pdo->query("SELECT id, lokasi, judul FROM pengaduan WHERE lokasi IS NOT NULL");
$pengaduan_rows = [];
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    $parts = explode(',', $row['lokasi']);
    if(count($parts)==2){
        $pengaduan_rows[] = [
            'id'=>$row['id'],
            'lat'=>trim($parts[0]),
            'lng'=>trim($parts[1]),
            'judul'=>$row['judul']
        ];
    }
}
$map_data['pengaduan'] = $pengaduan_rows;
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Peta Risiko - SIGAP AIR DINGIN</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css" rel="stylesheet">
<link href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" rel="stylesheet">
<link href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" rel="stylesheet" />
<link href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" rel="stylesheet" />

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>

<style>
body{background:#f4f6f9}
.sidebar{width:250px;min-height:100vh}
.auth-logo{width:42px;height:42px;border-radius:50%;background:#0d6efd;color:#fff;display:flex;align-items:center;justify-content:center;}
.legend {
    background:white;
    padding:10px;
    border-radius:5px;
    box-shadow:0 0 10px rgba(0,0,0,0.2);
    line-height:1.5;
}
</style>
</head>
<body>
<div class="d-flex">

<?php include 'sidebar.php'; ?>

<div class="flex-grow-1">
<header class="bg-white border-bottom p-3 d-flex justify-content-between">
    <h5 class="mb-0"><i class="fas fa-map-marked-alt me-2"></i>Peta Risiko</h5>
    <span class="text-muted small">Login sebagai <strong><?= $_SESSION['role'] ?></strong></span>
</header>

<main class="p-4">
<div class="card">
    <div class="card-header fw-bold">Peta Risiko - Pengaduan</div>
    <div class="card-body">
        <div id="riskMap" style="height:400px"></div>
    </div>
</div>
</main>
</div>
</div>

<script>
const map = L.map('riskMap').setView([-0.8, 113.9], 5);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

const mapData = <?= json_encode($map_data) ?>;

// Cluster group
const markers = L.markerClusterGroup();

// Tambah marker pengaduan dengan popup
mapData.pengaduan.forEach(d=>{
    const marker = L.circleMarker([parseFloat(d.lat), parseFloat(d.lng)], {
        color:'#0d6efd',
        radius:8,
        fillOpacity:0.8
    });
    marker.bindPopup(`<strong>ID:</strong> ${d.id}<br><strong>Judul:</strong> ${d.judul}`);
    markers.addLayer(marker);
});

map.addLayer(markers);

// Zoom agar semua marker terlihat
if(mapData.pengaduan.length){
    const group = new L.featureGroup(mapData.pengaduan.map(d => L.latLng(d.lat,d.lng)));
    map.fitBounds(group.getBounds().pad(0.2));
}

// Legend
const legend = L.control({position:'bottomright'});
legend.onAdd = function(map){
    const div = L.DomUtil.create('div','legend');
    div.innerHTML = '<strong>Legenda:</strong><br>';
    div.innerHTML += '<i style="background:#0d6efd;width:12px;height:12px;display:inline-block;margin-right:5px;border-radius:50%;"></i> Pengaduan';
    return div;
}
legend.addTo(map);
</script>
</body>
</html>
