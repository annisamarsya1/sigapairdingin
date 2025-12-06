<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] == 'user') {
    header('Location: login.php');
}
include 'db.php';

$lang = isset($_GET['lang']) ? $_GET['lang'] : 'id';
$texts = [
    'id' => ['register' => 'Register', 'login' => 'Login', /* tambah semua teks */],
    'en' => ['register' => 'Register', 'login' => 'Login', /* English */]
];

// Data untuk grafik tren
$stmt = $pdo->query("SELECT DATE_FORMAT(created_at, '%Y-%m') as bulan, COUNT(*) as count FROM laporan GROUP BY bulan");
$tren_data = $stmt->fetchAll();

// Data untuk heatmap (lokasi)
$stmt_heat = $pdo->query("SELECT lokasi_lat, lokasi_lng FROM laporan WHERE lokasi_lat IS NOT NULL");
$heat_data = $stmt_heat->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Sigap Air Dingin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet.heat.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
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
                    <small class="text-muted text-white-50"><?php echo ucfirst($_SESSION['role']); ?> Panel</small>
                </div>
            </div>
            <ul class="nav nav-pills flex-column mb-auto gap-1">
                <li class="nav-item">
                    <a href="dashboard.php?lang=<?php echo $lang; ?>" class="nav-link active d-flex align-items-center gap-2">
                        <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="riwayat.php?lang=<?php echo $lang; ?>" class="nav-link text-white d-flex align-items-center gap-2">
                        <i class="fas fa-list"></i><span>Data Laporan</span>
                    </a>
                </li>
                <li>
                    <a href="peta_risiko.php?lang=<?php echo $lang; ?>" class="nav-link text-white d-flex align-items-center gap-2">
                        <i class="fas fa-map-marked-alt"></i><span>Peta Risiko</span>
                    </a>
                </li>
                <li>
                    <a href="lapor.php?lang=<?php echo $lang; ?>" class="nav-link text-white d-flex align-items-center gap-2">
                        <i class="fas fa-exclamation-triangle"></i><span>Input Laporan</span>
                    </a>
                </li>
            </ul>
            <hr class="text-secondary">
            <a href="logout.php" class="btn btn-outline-light btn-sm mt-auto d-flex align-items-center justify-content-center gap-2">
                <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
            </a>
        </nav>

        <!-- Main Content -->
        <div class="flex-grow-1 bg-light">
            <!-- Topbar -->
            <header class="py-3 px-4 border-bottom bg-white d-flex justify-content-between align-items-center">
                <div class="page-title mb-0">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard Admin</span>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="small text-muted">
                        Masuk sebagai <strong><?php echo $_SESSION['role']; ?></strong>
                    </span>
                    <a href="index.php" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-home me-1"></i>Beranda User
                    </a>
                </div>
            </header>

            <main class="p-4">
                <div class="row g-4 mb-4">
                    <!-- Placeholder statistik, bisa diisi data real nanti -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="text-muted text-uppercase small mb-1">Total Laporan</div>
                                        <div class="h4 mb-0">
                                            <?php
                                            $total = $pdo->query("SELECT COUNT(*) FROM laporan")->fetchColumn();
                                            echo $total;
                                            ?>
                                        </div>
                                    </div>
                                    <span class="auth-logo">
                                        <i class="fas fa-file-alt"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="text-muted text-uppercase small mb-1">Sedang Diproses</div>
                                        <div class="h4 mb-0">
                                            <?php
                                            $proses = $pdo->query("SELECT COUNT(*) FROM laporan WHERE status = 'diproses'")->fetchColumn();
                                            echo $proses;
                                            ?>
                                        </div>
                                    </div>
                                    <span class="auth-logo">
                                        <i class="fas fa-spinner"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="text-muted text-uppercase small mb-1">Selesai</div>
                                        <div class="h4 mb-0">
                                            <?php
                                            $selesai = $pdo->query("SELECT COUNT(*) FROM laporan WHERE status = 'selesai'")->fetchColumn();
                                            echo $selesai;
                                            ?>
                                        </div>
                                    </div>
                                    <span class="auth-logo">
                                        <i class="fas fa-check-circle"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="card h-100">
                            <div class="card-header">
                                <h4 class="mb-0"><i class="fas fa-chart-line me-2"></i>Grafik Tren Laporan</h4>
                            </div>
                            <div class="card-body">
                                <canvas id="trenChart" style="max-height: 320px;"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card h-100">
                            <div class="card-header">
                                <h4 class="mb-0"><i class="fas fa-fire-alt me-2"></i>Heatmap Lokasi</h4>
                            </div>
                            <div class="card-body">
                                <div id="heatMap" style="height: 320px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modul RT/RW: List laporan -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h4 class="mb-0"><i class="fas fa-list me-2"></i>Laporan di Wilayah Anda</h4>
                    </div>
                    <div class="card-body">
                        <?php
                        $wilayah = 'Air Dingin';  // Asumsi, ambil dari user
                        $stmt_laporan = $pdo->prepare("SELECT * FROM laporan WHERE wilayah = ?");
                        $stmt_laporan->execute([$wilayah]);
                        $laporans = $stmt_laporan->fetchAll();
                        ?>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Judul</th>
                                        <th>Status</th>
                                        <th class="text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($laporans as $l): ?>
                                    <tr>
                                        <td><?php echo $l['id']; ?></td>
                                        <td><?php echo $l['judul']; ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $l['status'] === 'selesai' ? 'success' : 'warning text-dark'; ?>">
                                                <?php echo ucfirst($l['status']); ?>
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <form method="POST" action="update_status.php" class="d-inline-flex align-items-center gap-2">
                                                <input type="hidden" name="id" value="<?php echo $l['id']; ?>">
                                                <select name="status" class="form-select form-select-sm" style="max-width: 140px;">
                                                    <option value="diproses" <?php echo $l['status']=='diproses'?'selected':''; ?>>Proses</option>
                                                    <option value="selesai" <?php echo $l['status']=='selesai'?'selected':''; ?>>Selesai</option>
                                                </select>
                                                <button type="submit" class="btn btn-sm btn-info">
                                                    <i class="fas fa-save me-1"></i>Update
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script>
        // Grafik Tren
        const ctx = document.getElementById('trenChart').getContext('2d');
        const trenChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_column($tren_data, 'bulan')); ?>,
                datasets: [{
                    label: 'Jumlah Laporan',
                    data: <?php echo json_encode(array_column($tren_data, 'count')); ?>,
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.18)',
                    tension: 0.35,
                    fill: true
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Heatmap
        var heatMap = L.map('heatMap').setView([-0.7893, 113.9213], 5);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(heatMap);
        var heatPoints = <?php echo json_encode(array_map(function($row) { return [$row['lokasi_lat'], $row['lokasi_lng'], 1]; }, $heat_data)); ?>;
        L.heatLayer(heatPoints, {radius: 25}).addTo(heatMap);
    </script>
</body>
</html>