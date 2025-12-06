<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$lang = isset($_GET['lang']) ? $_GET['lang'] : 'id';
$texts = [
    'id' => ['welcome' => 'Selamat Datang', 'dashboard' => 'Dashboard', 'lapor' => 'Lapor', 'riwayat' => 'Riwayat', 'peta_risiko' => 'Peta Risiko', 'logout' => 'Logout', 'english' => 'English', 'indonesia' => 'Indonesia'],
    'en' => ['welcome' => 'Welcome', 'dashboard' => 'Dashboard', 'lapor' => 'Report', 'riwayat' => 'History', 'peta_risiko' => 'Risk Map', 'logout' => 'Logout', 'english' => 'English', 'indonesia' => 'Indonesia']
];
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sigap Air Dingin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="min-vh-100">
    <div class="d-flex">
        <!-- Sidebar global -->
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
                    <a href="index.php?lang=<?php echo $lang; ?>" class="nav-link active d-flex align-items-center gap-2">
                        <i class="fas fa-home"></i><span>Beranda</span>
                    </a>
                </li>
                <?php if ($_SESSION['role'] != 'user'): ?>
                <li>
                    <a href="dashboard.php?lang=<?php echo $lang; ?>" class="nav-link text-white d-flex align-items-center gap-2">
                        <i class="fas fa-tachometer-alt"></i><span><?php echo $texts[$lang]['dashboard']; ?></span>
                    </a>
                </li>
                <?php endif; ?>
                <li>
                    <a href="lapor.php?lang=<?php echo $lang; ?>" class="nav-link text-white d-flex align-items-center gap-2">
                        <i class="fas fa-exclamation-triangle"></i><span><?php echo $texts[$lang]['lapor']; ?></span>
                    </a>
                </li>
                <li>
                    <a href="riwayat.php?lang=<?php echo $lang; ?>" class="nav-link text-white d-flex align-items-center gap-2">
                        <i class="fas fa-history"></i><span><?php echo $texts[$lang]['riwayat']; ?></span>
                    </a>
                </li>
                <li>
                    <a href="peta_risiko.php?lang=<?php echo $lang; ?>" class="nav-link text-white d-flex align-items-center gap-2">
                        <i class="fas fa-map-marked-alt"></i><span><?php echo $texts[$lang]['peta_risiko']; ?></span>
                    </a>
                </li>
            </ul>
            <hr class="text-secondary">
            <div class="mb-3">
                <div class="small text-white-50 mb-1"><i class="fas fa-globe me-1"></i>Language</div>
                <div class="d-flex gap-2">
                    <a href="?lang=id" class="btn btn-sm btn-outline-light flex-fill">ID</a>
                    <a href="?lang=en" class="btn btn-sm btn-outline-light flex-fill">EN</a>
                </div>
            </div>
            <a href="logout.php" class="btn btn-outline-light btn-sm mt-auto d-flex align-items-center justify-content-center gap-2">
                <i class="fas fa-sign-out-alt"></i> <span><?php echo $texts[$lang]['logout']; ?></span>
            </a>
        </nav>

        <!-- Main content -->
        <div class="flex-grow-1 bg-light d-flex flex-column">
            <header class="py-3 px-4 border-bottom bg-white d-flex justify-content-between align-items-center">
                <div class="page-title mb-0">
                    <i class="fas fa-home"></i>
                    <span><?php echo $texts[$lang]['welcome']; ?></span>
                </div>
                <span class="small text-muted">
                    Masuk sebagai <strong><?php echo $_SESSION['role']; ?></strong>
                </span>
            </header>
            <main class="flex-grow-1 p-4">
                <div class="card">
                    <div class="card-body">
                        <p class="mb-0">
                            Selamat datang di portal utama Sigap Air Dingin. Gunakan menu di sisi kiri untuk melakukan pelaporan,
                            melihat riwayat, dan memantau peta risiko di wilayah Anda.
                        </p>
                    </div>
                </div>
            </main>
            <footer class="footer mt-auto py-3">
                <div class="container">
                    <span class="text-muted">&copy; 2023 Sigap Air Dingin. All rights reserved.</span>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>