<?php
// =======================
// DATA LAPORAN - ADMIN (FULL)
// =======================

session_start();

// PROTEKSI ADMIN
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

// KONEKSI DB
require_once __DIR__ . '/../config/db.php';

// FILTER
$jenis  = $_GET['jenis']  ?? 'pengaduan';
$status = $_GET['status'] ?? '';

$allowed = ['pengaduan','aspirasi','administrasi'];
if (!in_array($jenis, $allowed)) {
    $jenis = 'pengaduan';
}

// QUERY DATA
$sql = "SELECT * FROM $jenis";
$params = [];

if ($status !== '') {
    $sql .= " WHERE status = ?";
    $params[] = $status;
}

$sql .= " ORDER BY created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data Laporan - Admin</title>

<!-- CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css" rel="stylesheet">

<style>
body{background:#f4f6f9}
.sidebar{width:250px;min-height:100vh}
.auth-logo{
    width:42px;height:42px;
    border-radius:50%;
    background:#0d6efd;
    color:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
}
</style>
</head>
<body>

<div class="d-flex">

<!-- SIDEBAR -->
<nav class="sidebar bg-dark text-white p-3 d-flex flex-column">
    <div class="d-flex align-items-center mb-4">
        <div class="auth-logo me-2">
            <i class="fas fa-water"></i>
        </div>
        <div>
            <div class="fw-bold">SIGAP AIR DINGIN</div>
            <small class="text-white-50">Admin Panel</small>
        </div>
    </div>

    <ul class="nav nav-pills flex-column gap-1 mb-auto">
        <li class="nav-item">
            <a href="dashboard.php" class="nav-link text-white">
                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
            </a>
        </li>
        <li>
            <a href="laporan.php" class="nav-link active text-white">
                <i class="fas fa-list me-2"></i>Data Laporan
            </a>
        </li>
        <li>
            <a href="peta_risiko.php" class="nav-link text-white">
                <i class="fas fa-map-marked-alt me-2"></i>Peta Risiko
            </a>
        </li>
    </ul>

    <a href="logout.php" class="btn btn-outline-light btn-sm mt-auto">
        <i class="fas fa-sign-out-alt me-1"></i>Logout
    </a>
</nav>

<!-- CONTENT -->
<div class="flex-grow-1">

<header class="bg-white border-bottom p-3 d-flex justify-content-between">
    <h5 class="mb-0">
        <i class="fas fa-list me-2"></i>Data Laporan
    </h5>
    <span class="text-muted small">
        Login sebagai <strong><?= $_SESSION['role'] ?></strong>
    </span>
</header>

<main class="p-4">

<!-- FILTER -->
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-3">
        <select name="jenis" class="form-select" onchange="this.form.submit()">
            <option value="pengaduan" <?= $jenis=='pengaduan'?'selected':'' ?>>Pengaduan</option>
            <option value="aspirasi" <?= $jenis=='aspirasi'?'selected':'' ?>>Aspirasi</option>
            <option value="administrasi" <?= $jenis=='administrasi'?'selected':'' ?>>Administrasi</option>
        </select>
    </div>

    <div class="col-md-3">
        <select name="status" class="form-select" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="baru" <?= $status=='baru'?'selected':'' ?>>Baru</option>
            <option value="ditindak" <?= $status=='ditindak'?'selected':'' ?>>Ditindak</option>
            <option value="ditolak" <?= $status=='ditolak'?'selected':'' ?>>Ditolak</option>
        </select>
    </div>
</form>

<!-- TABEL -->
<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Judul / Jenis</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php if(!$data): ?>
                <tr>
                    <td colspan="5" class="text-center text-muted">Tidak ada data</td>
                </tr>
            <?php endif; ?>

            <?php foreach($data as $d): ?>
                <tr>
                    <td><?= $d['id'] ?></td>
                    <td><?= htmlspecialchars($d['judul'] ?? $d['jenis_pengajuan']) ?></td>
                    <td>
                        <span class="badge bg-<?=
                            $d['status']=='baru'?'secondary':
                            ($d['status']=='ditindak'?'primary':'danger')
                        ?>">
                            <?= ucfirst($d['status']) ?>
                        </span>
                    </td>
                    <td><?= $d['created_at'] ?></td>
                    <td>
                        <div class="d-flex gap-1">
                            <!-- Ubah Status -->
                            <form method="POST" action="update_status.php" class="mb-0">
                                <input type="hidden" name="id" value="<?= $d['id'] ?>">
                                <input type="hidden" name="jenis" value="<?= $jenis ?>">
                                <select name="status" class="form-select form-select-sm"
                                        onchange="this.form.submit()">
                                    <option value="baru" <?= $d['status']=='baru'?'selected':'' ?>>Baru</option>
                                    <option value="ditindak" <?= $d['status']=='ditindak'?'selected':'' ?>>Ditindak</option>
                                    <option value="ditolak" <?= $d['status']=='ditolak'?'selected':'' ?>>Ditolak</option>
                                </select>
                            </form>

                            <!-- Lihat Detail -->
                            <a href="detail_laporan.php?id=<?= $d['id'] ?>&jenis=<?= $jenis ?>"
                               class="btn btn-sm btn-outline-primary">
                               <i class="fas fa-eye me-1"></i>Detail
                            </a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</main>
</div>
</div>

</body>
</html>
