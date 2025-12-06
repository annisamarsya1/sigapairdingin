<?php
session_start();
include 'db.php';

$lang = isset($_GET['lang']) ? $_GET['lang'] : 'id';
$texts = [
    'id' => ['register' => 'Register', 'login' => 'Login', /* tambah semua teks */],
    'en' => ['register' => 'Register', 'login' => 'Login', /* English */]
];

$filters = [];
$sql = "SELECT * FROM laporan WHERE 1=1";
if (isset($_GET['date_start'])) {
    $sql .= " AND created_at >= :date_start";
    $filters['date_start'] = $_GET['date_start'];
}
if (isset($_GET['kategori'])) {
    $sql .= " AND kategori = :kategori";
    $filters['kategori'] = $_GET['kategori'];
}
// Tambah filter lain serupa untuk status, wilayah
if (isset($_GET['export'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="laporan.csv"');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Judul', 'Deskripsi', 'Status']);
    foreach ($laporans as $l) {
        fputcsv($output, [$l['id'], $l['judul'], $l['deskripsi'], $l['status']]);
    }
    exit;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($filters);
$laporans = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Laporan - Sigap Air Dingin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
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
                    <a href="riwayat.php?lang=<?php echo $lang; ?>" class="nav-link active d-flex align-items-center gap-2">
                        <i class="fas fa-history"></i><span>Riwayat</span>
                    </a>
                </li>
                <li>
                    <a href="peta_risiko.php?lang=<?php echo $lang; ?>" class="nav-link text-white d-flex align-items-center gap-2">
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
                    <i class="fas fa-history"></i>
                    <span>Riwayat Laporan</span>
                </div>
            </header>

            <main class="p-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <form method="GET">
                            <div class="row g-2 align-items-end filter-bar">
                                <div class="col-md-3">
                                    <label class="form-label small mb-1">Tanggal Mulai</label>
                                    <input type="date" name="date_start" class="form-control" placeholder="Tanggal Mulai">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small mb-1">Kategori</label>
                                    <select name="kategori" class="form-select">
                                        <option value="">Semua Kategori</option>
                                        <option value="pengaduan">Pengaduan</option>
                                        <option value="aspirasi">Aspirasi</option>
                                    </select>
                                </div>
                                <!-- Tambah filter lain -->
                                <div class="col-md-3 d-flex gap-2">
                                    <button type="submit" class="btn btn-primary flex-grow-1">
                                        <i class="fas fa-filter me-1"></i>Filter
                                    </button>
                                    <a href="?export=csv" class="btn btn-success">
                                        <i class="fas fa-file-export me-1"></i>CSV
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Judul</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($laporans as $l): ?>
                                    <tr>
                                        <td><?php echo $l['id']; ?></td>
                                        <td><?php echo $l['judul']; ?></td>
                                        <td><?php echo $l['created_at']; ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $l['status'] === 'selesai' ? 'success' : 'warning text-dark'; ?>">
                                                <?php echo ucfirst($l['status']); ?>
                                            </span>
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
</body>
</html>