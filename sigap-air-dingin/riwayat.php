<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'config/db.php';

$lang = isset($_GET['lang']) ? $_GET['lang'] : 'id';
$texts = [
    'id' => [
        'welcome' => 'Selamat Datang',
        'dashboard' => 'Dashboard',
        'lapor' => 'Lapor',
        'riwayat' => 'Riwayat Layanan',
        'peta_risiko' => 'Peta Risiko',
        'logout' => 'Logout',
        'english' => 'English',
        'indonesia' => 'Indonesia',
        'filter' => 'Filter Layanan Publik',
        'jenis' => 'Jenis Layanan',
        'status' => 'Status',
        'baru' => 'Baru',
        'ditindak' => 'Ditindak Lanjuti',
        'ditolak' => 'Ditolak',
        'pengaduan' => 'Pengaduan',
        'aspirasi' => 'Aspirasi',
        'administrasi' => 'Administrasi Publik',
        'tanggal' => 'Tanggal',
        'judul' => 'Judul',
        'nomor' => 'Nomor',
        'semua' => 'Semua'
    ],
    'en' => [
        'welcome' => 'Welcome',
        'dashboard' => 'Dashboard',
        'lapor' => 'Report',
        'riwayat' => 'Public Service History',
        'peta_risiko' => 'Risk Map',
        'logout' => 'Logout',
        'english' => 'English',
        'indonesia' => 'Indonesia',
        'filter' => 'Filter Public Service',
        'jenis' => 'Service Type',
        'status' => 'Status',
        'baru' => 'New',
        'ditindak' => 'In Progress',
        'ditolak' => 'Rejected',
        'pengaduan' => 'Report',
        'aspirasi' => 'Aspiration',
        'administrasi' => 'Public Administration',
        'tanggal' => 'Date',
        'judul' => 'Title',
        'nomor' => 'No.',
        'semua' => 'All'
    ]
];

// Ambil filter dari form
$jenis_filter = $_GET['jenis'] ?? '';
$status_filter = $_GET['status'] ?? '';

// Siapkan query UNION
$queries = [];
$params = [];

// Tambahkan filter status jika ada
$status_sql = '';
if ($status_filter != '') {
    $status_sql = " AND status = ?";
}

// Query per tabel
if ($jenis_filter == '' || $jenis_filter == 'pengaduan') {
    $queries[] = "SELECT 'Pengaduan' AS jenis, id, judul, status, created_at FROM pengaduan WHERE user_id = ? $status_sql";
    $params[] = $_SESSION['user_id'];
    if ($status_filter != '') $params[] = $status_filter;
}
if ($jenis_filter == '' || $jenis_filter == 'aspirasi') {
    $queries[] = "SELECT 'Aspirasi' AS jenis, id, judul, status, created_at FROM aspirasi WHERE user_id = ? $status_sql";
    $params[] = $_SESSION['user_id'];
    if ($status_filter != '') $params[] = $status_filter;
}
if ($jenis_filter == '' || $jenis_filter == 'administrasi') {
    $queries[] = "SELECT 'Administrasi Publik' AS jenis, id, jenis_pengajuan AS judul, status, created_at FROM administrasi WHERE user_id = ? $status_sql";
    $params[] = $_SESSION['user_id'];
    if ($status_filter != '') $params[] = $status_filter;
}

$data = [];
if ($queries) {
    $stmt = $pdo->prepare(implode(' UNION ALL ', $queries) . " ORDER BY created_at DESC");
    $stmt->execute($params);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Favicon / Logo Kota Sawahlunto -->
<link rel="icon" type="image/png" href="<?= BASE_URL ?>sigap-air-dingin/assets/img/logo-sawahlunto.png" />
<title><?= $texts[$lang]['riwayat'] ?> - Sigap Air Dingin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css" rel="stylesheet">
<style>
/* Sidebar dan style sama persis dengan index.php */
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:system-ui,-apple-system,"Segoe UI",sans-serif;color:#1f2a37;background:radial-gradient(circle at top left,#e3f2fd 0,#f0f6ff 100%);}
.sidebar{background:#0d6efd;color:#fff;min-width:240px;max-width:260px;min-height:100vh;padding:1rem;display:flex;flex-direction:column;}
.sidebar .brand{display:flex;align-items:center;gap:.5rem;margin-bottom:2rem;}
.sidebar .brand i{font-size:1.8rem;}
.sidebar .nav-link{color:#fff;margin-bottom:.3rem;display:flex;align-items:center;gap:.6rem;font-weight:600;padding:.5rem 1rem;border-radius:.6rem;text-decoration:none;transition:.2s;}
.sidebar .nav-link.active,.sidebar .nav-link:hover{background:rgba(255,255,255,.15);}
.sidebar .nav-item .collapse .nav-link{padding-left:2.5rem;}
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
        <!-- Dropdown Lapor -->
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
        <a href="riwayat.php?lang=<?= $lang ?>" class="nav-link active">
            <i class="fas fa-history"></i><?= $texts[$lang]['riwayat'] ?>
        </a>
        <a href="peta_risiko.php?lang=<?= $lang ?>" class="nav-link">
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
            <div><i class="fas fa-history me-1"></i><?= $texts[$lang]['riwayat'] ?></div>
            <span class="small text-muted">Masuk sebagai <strong><?= $_SESSION['role'] ?></strong></span>
        </header>

        <main class="main">
            <div class="card">
                <h4><?= $texts[$lang]['filter'] ?></h4>
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label"><?= $texts[$lang]['jenis'] ?></label>
                        <select name="jenis" class="form-select">
                            <option value=""><?= $texts[$lang]['semua'] ?></option>
                            <option value="pengaduan" <?= ($jenis_filter=='pengaduan')?'selected':'' ?>><?= $texts[$lang]['pengaduan'] ?></option>
                            <option value="aspirasi" <?= ($jenis_filter=='aspirasi')?'selected':'' ?>><?= $texts[$lang]['aspirasi'] ?></option>
                            <option value="administrasi" <?= ($jenis_filter=='administrasi')?'selected':'' ?>><?= $texts[$lang]['administrasi'] ?></option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"><?= $texts[$lang]['status'] ?></label>
                        <select name="status" class="form-select">
                            <option value=""><?= $texts[$lang]['semua'] ?></option>
                            <option value="baru" <?= ($status_filter=='baru')?'selected':'' ?>><?= $texts[$lang]['baru'] ?></option>
                            <option value="ditindak" <?= ($status_filter=='ditindak')?'selected':'' ?>><?= $texts[$lang]['ditindak'] ?></option>
                            <option value="ditolak" <?= ($status_filter=='ditolak')?'selected':'' ?>><?= $texts[$lang]['ditolak'] ?></option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button class="btn btn-primary w-100">Filter</button>
                    </div>
                </form>
            </div>

            <div class="card">
                <h4>Data Layanan Publik</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th><?= $texts[$lang]['nomor'] ?></th>
                                <th><?= $texts[$lang]['judul'] ?></th>
                                <th><?= $texts[$lang]['tanggal'] ?></th>
                                <th><?= $texts[$lang]['jenis'] ?></th>
                                <th><?= $texts[$lang]['status'] ?></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if ($data): ?>
                            <?php foreach ($data as $i => $d): ?>
                                <tr>
                                    <td><?= $i+1 ?></td>
                                    <td><?= htmlspecialchars($d['judul']) ?></td>
                                    <td><?= date('d-m-Y H:i', strtotime($d['created_at'])) ?></td>
                                    <td><?= $d['jenis'] ?></td>
                                    <td><?= ucfirst($d['status']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center">Belum ada data</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>

        <footer class="text-center py-3">
            <span class="text-muted">&copy; 2023 Sigap Air Dingin. All rights reserved.</span>
        </footer>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
