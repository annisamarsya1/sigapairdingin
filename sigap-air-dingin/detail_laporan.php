<?php
session_start();
include 'db.php';
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM laporan WHERE id = ?");
$stmt->execute([$id]);
$laporan = $stmt->fetch();

$lang = isset($_GET['lang']) ? $_GET['lang'] : 'id';
$texts = [
    'id' => ['register' => 'Register', 'login' => 'Login', /* tambah semua teks */],
    'en' => ['register' => 'Register', 'login' => 'Login', /* English */]
];

// Files
$stmt_files = $pdo->prepare("SELECT * FROM files WHERE laporan_id = ?");
$stmt_files->execute([$id]);
$files = $stmt_files->fetchAll();

// Komentar
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['komentar'])) {
    $komentar = $_POST['komentar'];
    $user_id = $_SESSION['user_id'];
    $stmt_kom = $pdo->prepare("INSERT INTO komentar (laporan_id, user_id, komentar) VALUES (?, ?, ?)");
    $stmt_kom->execute([$id, $user_id, $komentar]);

    // Notifikasi email (contoh ke user laporan jika tidak anonim)
    if ($laporan['user_id']) {
        $stmt_user = $pdo->prepare("SELECT email FROM users WHERE id = ?");
        $stmt_user->execute([$laporan['user_id']]);
        $email = $stmt_user->fetchColumn();
        if ($email) {
            mail($email, "Update Komentar", "Ada komentar baru di laporan Anda: $komentar");
        }
    }
}

$stmt_koms = $pdo->prepare("SELECT k.*, u.username FROM komentar k JOIN users u ON k.user_id = u.id WHERE laporan_id = ?");
$stmt_koms->execute([$id]);
$komentars = $stmt_koms->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Laporan #<?php echo $id; ?> - Sigap Air Dingin</title>
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
                    <i class="fas fa-file-alt"></i>
                    <span>Detail Laporan #<?php echo $id; ?></span>
                </div>
            </header>

            <main class="p-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="mb-2"><?php echo $laporan['judul']; ?></h5>
                        <p class="text-muted small mb-3">
                            Status:
                            <span class="badge bg-<?php echo $laporan['status'] === 'selesai' ? 'success' : 'warning text-dark'; ?>">
                                <?php echo ucfirst($laporan['status']); ?>
                            </span>
                        </p>
                        <p><?php echo nl2br($laporan['deskripsi']); ?></p>

                        <hr>
                        <h6 class="mb-2"><i class="fas fa-paperclip me-1"></i>Lampiran</h6>
                        <?php if (count($files)): ?>
                            <?php foreach ($files as $f): ?>
                                <a href="<?php echo $f['file_path']; ?>" class="d-block mb-1" target="_blank">
                                    <i class="fas fa-file me-1"></i>Lihat File
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted small mb-0">Tidak ada lampiran.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0"><i class="fas fa-comments me-2"></i>Komentar / Diskusi</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3" style="max-height: 260px; overflow-y: auto;">
                            <?php if (count($komentars)): ?>
                                <?php foreach ($komentars as $k): ?>
                                    <div class="mb-2">
                                        <strong><?php echo $k['username']; ?>:</strong>
                                        <span><?php echo $k['komentar']; ?></span>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted small mb-0">Belum ada komentar.</p>
                            <?php endif; ?>
                        </div>
                        <form method="POST" class="d-flex gap-2">
                            <textarea name="komentar" class="form-control" rows="2" required placeholder="Tulis komentar..."></textarea>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>