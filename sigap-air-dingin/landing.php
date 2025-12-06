<?php
session_start();
$lang = isset($_GET['lang']) ? $_GET['lang'] : 'id';
$texts = [
    'id' => [
        'title' => 'Sigap Air Dingin',
        'tagline' => 'Laporkan lebih cepat, tangani lebih tepat.',
        'subtitle' => 'Platform pelaporan keluhan masyarakat terkait masalah air dan lingkungan di wilayah Air Dingin.',
        'cta_report' => 'Mulai Lapor',
        'cta_login' => 'Masuk Dashboard',
        'cta_register' => 'Daftar Akun',
        'feature_1_title' => 'Pelaporan Cepat',
        'feature_1_desc' => 'Sampaikan keluhan dan aspirasi Anda dalam hitungan menit, lengkap dengan lokasi dan foto pendukung.',
        'feature_2_title' => 'Pantau Proses',
        'feature_2_desc' => 'Lihat status penanganan laporan Anda secara transparan dari tahap diterima hingga selesai.',
        'feature_3_title' => 'Peta Risiko',
        'feature_3_desc' => 'Visualisasi titik-titik laporan di peta untuk melihat area dengan potensi risiko tinggi.',
        'how_title' => 'Bagaimana Cara Kerjanya?',
        'how_1' => 'Daftar atau masuk ke portal Sigap Air Dingin.',
        'how_2' => 'Buat laporan baru, lengkapi deskripsi, lokasi, dan lampiran bila perlu.',
        'how_3' => 'Pantau perkembangan laporan dan terima notifikasi update dari petugas.',
        'footer' => '&copy; ' . date('Y') . ' Sigap Air Dingin. Dibangun untuk masyarakat Air Dingin.'
    ],
    'en' => [
        'title' => 'Sigap Air Dingin',
        'tagline' => 'Report faster, respond better.',
        'subtitle' => 'A reporting platform for citizen complaints about water and environmental issues in Air Dingin.',
        'cta_report' => 'Start Reporting',
        'cta_login' => 'Go to Dashboard',
        'cta_register' => 'Create Account',
        'feature_1_title' => 'Fast Reporting',
        'feature_1_desc' => 'Submit your complaints and aspirations in minutes, complete with location and supporting photos.',
        'feature_2_title' => 'Track Progress',
        'feature_2_desc' => 'See the handling status of your report transparently from received to resolved.',
        'feature_3_title' => 'Risk Map',
        'feature_3_desc' => 'Visualize report points on the map to see areas with higher potential risk.',
        'how_title' => 'How It Works',
        'how_1' => 'Register or log in to the Sigap Air Dingin portal.',
        'how_2' => 'Create a new report, fill in the description, location, and attachments if needed.',
        'how_3' => 'Track your report progress and receive updates from officers.',
        'footer' => '&copy; ' . date('Y') . ' Sigap Air Dingin. Built for the people of Air Dingin.'
    ]
];
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $texts[$lang]['title']; ?> - Landing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="bg-light min-vh-100 d-flex flex-column">
    <!-- Simple top navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="landing.php?lang=<?php echo $lang; ?>">
                <div class="auth-logo me-2">
                    <i class="fas fa-water"></i>
                </div>
                <span class="fw-bold">Sigap Air Dingin</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#landingNav" aria-controls="landingNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="landingNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item me-lg-2">
                        <a class="nav-link" href="#features">Fitur</a>
                    </li>
                    <li class="nav-item me-lg-2">
                        <a class="nav-link" href="#how-it-works"><?php echo $texts[$lang]['how_title']; ?></a>
                    </li>
                    <li class="nav-item me-lg-3">
                        <a class="nav-link" href="peta_risiko.php?lang=<?php echo $lang; ?>">Peta Risiko</a>
                    </li>
                    <li class="nav-item me-lg-2">
                        <a class="btn btn-outline-primary btn-sm" href="login.php?lang=<?php echo $lang; ?>">
                            <?php echo $texts[$lang]['cta_login']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary btn-sm ms-lg-2 mt-2 mt-lg-0" href="register.php?lang=<?php echo $lang; ?>">
                            <?php echo $texts[$lang]['cta_register']; ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero section -->
    <section class="flex-grow-1 d-flex align-items-center py-5">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-6">
                    <h1 class="display-5 fw-bold mb-3">
                        <?php echo $texts[$lang]['tagline']; ?>
                    </h1>
                    <p class="lead mb-4">
                        <?php echo $texts[$lang]['subtitle']; ?>
                    </p>
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <a href="lapor.php?lang=<?php echo $lang; ?>" class="btn btn-primary btn-lg">
                            <i class="fas fa-exclamation-triangle me-2"></i><?php echo $texts[$lang]['cta_report']; ?>
                        </a>
                        <a href="riwayat.php?lang=<?php echo $lang; ?>" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-history me-2"></i>Riwayat Laporan
                        </a>
                    </div>
                    <div class="d-flex align-items-center text-muted small">
                        <i class="fas fa-shield-alt me-2 text-primary"></i>
                        <span>Data laporan Anda tersimpan aman dan hanya digunakan untuk penanganan resmi.</span>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card border-0 shadow-lg">
                        <div class="card-body p-4">
                            <h5 class="mb-3"><i class="fas fa-map-marked-alt me-2 text-primary"></i>Peta Risiko Sekilas</h5>
                            <p class="small text-muted">
                                Lihat gambaran umum titik-titik laporan yang telah masuk untuk mengetahui area yang perlu perhatian khusus.
                            </p>
                            <div id="landingMap" style="height: 260px; border-radius: 1rem; overflow: hidden; border: 3px solid rgba(13,110,253,0.18);">
                                <!-- Map preview via iframe to peta_risiko or static image -->
                                <iframe src="peta_risiko.php?lang=<?php echo $lang; ?>" title="Peta Risiko" style="border:0;width:100%;height:100%;"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section id="features" class="py-5 bg-white">
        <div class="container">
            <div class="text-center mb-4">
                <h2 class="fw-bold mb-2">Mengapa Menggunakan Sigap Air Dingin?</h2>
                <p class="text-muted mb-0">Didesain untuk memudahkan masyarakat menyampaikan keluhan dan memantau tindak lanjutnya.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 text-center">
                        <div class="card-body">
                            <div class="auth-logo mb-3 mx-auto">
                                <i class="fas fa-bolt"></i>
                            </div>
                            <h5 class="mb-2"><?php echo $texts[$lang]['feature_1_title']; ?></h5>
                            <p class="text-muted small mb-0"><?php echo $texts[$lang]['feature_1_desc']; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 text-center">
                        <div class="card-body">
                            <div class="auth-logo mb-3 mx-auto">
                                <i class="fas fa-eye"></i>
                            </div>
                            <h5 class="mb-2"><?php echo $texts[$lang]['feature_2_title']; ?></h5>
                            <p class="text-muted small mb-0"><?php echo $texts[$lang]['feature_2_desc']; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 text-center">
                        <div class="card-body">
                            <div class="auth-logo mb-3 mx-auto">
                                <i class="fas fa-map"></i>
                            </div>
                            <h5 class="mb-2"><?php echo $texts[$lang]['feature_3_title']; ?></h5>
                            <p class="text-muted small mb-0"><?php echo $texts[$lang]['feature_3_desc']; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How it works -->
    <section id="how-it-works" class="py-5">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-6">
                    <h2 class="fw-bold mb-3"><?php echo $texts[$lang]['how_title']; ?></h2>
                    <ol class="text-muted">
                        <li class="mb-2"><?php echo $texts[$lang]['how_1']; ?></li>
                        <li class="mb-2"><?php echo $texts[$lang]['how_2']; ?></li>
                        <li class="mb-2"><?php echo $texts[$lang]['how_3']; ?></li>
                    </ol>
                </div>
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="mb-2"><i class="fas fa-user-check me-2 text-primary"></i>Siap untuk mulai?</h5>
                            <p class="text-muted small mb-3">Buat akun Anda atau langsung masuk jika sudah terdaftar.</p>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="register.php?lang=<?php echo $lang; ?>" class="btn btn-primary">
                                    <i class="fas fa-user-plus me-1"></i><?php echo $texts[$lang]['cta_register']; ?>
                                </a>
                                <a href="login.php?lang=<?php echo $lang; ?>" class="btn btn-outline-primary">
                                    <i class="fas fa-sign-in-alt me-1"></i><?php echo $texts[$lang]['cta_login']; ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer mt-auto py-3">
        <div class="container text-center">
            <span class="text-muted"><?php echo $texts[$lang]['footer']; ?></span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


