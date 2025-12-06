<?php
session_start();

$lang = isset($_GET['lang']) ? $_GET['lang'] : 'id';
$texts = [
    'id' => ['register' => 'Register', 'login' => 'Login', /* tambah semua teks */],
    'en' => ['register' => 'Register', 'login' => 'Login', /* English */]
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Risiko - Sigap Air Dingin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>
<body class="min-vh-100">
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="bg-dark text-white p-3 d-flex flex-column" style="min-width: 240px; max-width: 260px; min-height: 100vh;">
            <div class="d-flex align-items-center mb-4">
                <div class="auth-logo me-2">
                    <i class="fas fa-water"></i>
                </div>
                <div>
                    <div class="fw-bold">Sigap Air Dingin</div>
                    <small class="text-muted text-white-50">Portal Warga</small>
                </div>
            </div>
            <ul class="nav nav-pills flex-column mb-auto gap-1">
                <li class="nav-item">
                    <a href="index.php?lang=<?php echo $lang; ?>" class="nav-link text-white d-flex align-items-center gap-2">
                        <i class="fas fa-home"></i><span>Beranda</span>
                    </a>
                </li>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] != 'user'): ?>
                <li>
                    <a href="dashboard.php?lang=<?php echo $lang; ?>" class="nav-link text-white d-flex align-items-center gap-2">
                        <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
                    </a>
                </li>
                <?php endif; ?>
                <li>
                    <a href="lapor.php?lang=<?php echo $lang; ?>" class="nav-link text-white d-flex align-items-center gap-2">
                        <i class="fas fa-exclamation-triangle"></i><span>Lapor</span>
                    </a>
                </li>
                <li>
                    <a href="riwayat.php?lang=<?php echo $lang; ?>" class="nav-link text-white d-flex align-items-center gap-2">
                        <i class="fas fa-history"></i><span>Riwayat</span>
                    </a>
                </li>
                <li>
                    <a href="peta_risiko.php?lang=<?php echo $lang; ?>" class="nav-link active d-flex align-items-center gap-2">
                        <i class="fas fa-map-marked-alt"></i><span>Peta Risiko</span>
                    </a>
                </li>
            </ul>
            <hr class="text-secondary">
            <a href="logout.php" class="btn btn-outline-light btn-sm mt-auto d-flex align-items-center justify-content-center gap-2">
                <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
            </a>
        </nav>

        <!-- Main -->
        <div class="flex-grow-1 bg-light d-flex flex-column">
            <header class="py-3 px-4 border-bottom bg-white d-flex justify-content-between align-items-center">
                <div class="page-title mb-0">
                    <i class="fas fa-map-marked-alt"></i>
                    <span>Peta Risiko</span>
                </div>
            </header>

            <main class="p-4">
                <div class="card">
                    <div class="card-body">
                        <div id="risikoMap" style="height: 500px;"></div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script>
        var map = L.map('risikoMap').setView([-0.7893, 113.9213], 5);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);
        fetch('risiko.geojson')
            .then(response => response.json())
            .then(data => {
                L.geoJSON(data, {
                    style: function(feature) {
                        return {color: 'red', weight: 2, fillOpacity: 0.15};
                    }
                }).addTo(map);
            });
    </script>
</body>
</html>