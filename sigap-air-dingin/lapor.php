<?php
session_start();
include 'db.php';

$lang = isset($_GET['lang']) ? $_GET['lang'] : 'id';
$texts = [
    'id' => ['register' => 'Register', 'login' => 'Login', /* tambah semua teks */],
    'en' => ['register' => 'Register', 'login' => 'Login', /* English */]
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $kategori = $_POST['kategori'];
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];
    $anonim = isset($_POST['anonim']) ? true : false;
    $user_id = $anonim ? null : $_SESSION['user_id'];

    $stmt = $pdo->prepare("INSERT INTO laporan (user_id, judul, deskripsi, lokasi_lat, lokasi_lng, kategori, sla_timer) VALUES (?, ?, ?, ?, ?, ?, DATE_ADD(NOW(), INTERVAL 7 DAY))");
    $stmt->execute([$user_id, $judul, $deskripsi, $lat, $lng, $kategori]);

    $laporan_id = $pdo->lastInsertId();

    // Handle uploads
    if (!empty($_FILES['files']['name'][0])) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) mkdir($upload_dir);
        $count = count($_FILES['files']['name']);
        if ($count > 5) $count = 5;  // max 5

        for ($i = 0; $i < $count; $i++) {
            $file_name = basename($_FILES['files']['name'][$i]);
            $target = $upload_dir . time() . '_' . $file_name;
            if (move_uploaded_file($_FILES['files']['tmp_name'][$i], $target)) {
                $stmt_file = $pdo->prepare("INSERT INTO files (laporan_id, file_path) VALUES (?, ?)");
                $stmt_file->execute([$laporan_id, $target]);
            }
        }
    }

    header('Location: index.php');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lapor - Sigap Air Dingin</title>
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
                    <a href="lapor.php?lang=<?php echo $lang; ?>" class="nav-link active d-flex align-items-center gap-2">
                        <i class="fas fa-exclamation-triangle"></i><span>Lapor</span>
                    </a>
                </li>
                <li>
                    <a href="riwayat.php?lang=<?php echo $lang; ?>" class="nav-link text-white d-flex align-items-center gap-2">
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
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Form Pelaporan</span>
                </div>
            </header>
            <main class="p-4">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data" id="formLapor" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label class="form-label">Judul</label>
                                <input type="text" name="judul" class="form-control" required>
                                <div class="invalid-feedback">Judul wajib diisi.</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="4" required></textarea>
                                <div class="invalid-feedback">Deskripsi wajib diisi.</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kategori</label>
                                <select name="kategori" class="form-select" required>
                                    <option value="">Pilih kategori</option>
                                    <option value="pengaduan">Pengaduan</option>
                                    <option value="aspirasi">Aspirasi</option>
                                </select>
                                <div class="invalid-feedback">Kategori wajib dipilih.</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Lokasi (Klik pada peta)</label>
                                <div id="map" style="height: 300px;"></div>
                                <input type="hidden" name="lat" id="lat" required>
                                <input type="hidden" name="lng" id="lng" required>
                                <div class="form-text">Klik titik pada peta untuk menentukan lokasi kejadian.</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Upload File (maksimal 5)</label>
                                <input type="file" name="files[]" multiple class="form-control" id="files" accept="image/*,application/pdf">
                                <div id="preview" class="mt-2 d-flex flex-wrap"></div>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" name="anonim" id="anonim" class="form-check-input">
                                <label class="form-check-label" for="anonim">Lapor sebagai anonim</label>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-1"></i>Kirim Laporan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script>
        // Peta Leaflet
        var map = L.map('map').setView([-0.7893, 113.9213], 5);  // Koordinat default Indonesia
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
        var marker;
        map.on('click', function(e) {
            if (marker) map.removeLayer(marker);
            marker = L.marker(e.latlng).addTo(map);
            document.getElementById('lat').value = e.latlng.lat;
            document.getElementById('lng').value = e.latlng.lng;
        });

        // Preview files
        document.getElementById('files').addEventListener('change', function(e) {
            var preview = document.getElementById('preview');
            preview.innerHTML = '';
            var files = e.target.files;
            if (files.length > 5) alert('Max 5 files!');
            for (let i = 0; i < Math.min(5, files.length); i++) {
                if (files[i].type.startsWith('image/')) {
                    let img = document.createElement('img');
                    img.src = URL.createObjectURL(files[i]);
                    img.style.width = '100px';
                    preview.appendChild(img);
                } else {
                    let p = document.createElement('p');
                    p.textContent = files[i].name;
                    preview.appendChild(p);
                }
            }
        });

        // Validasi form & lokasi
        document.getElementById('formLapor').addEventListener('submit', function(e) {
            if (!document.getElementById('lat').value) {
                alert('Pilih lokasi di peta!');
                e.preventDefault();
                return;
            }
            if (!this.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            this.classList.add('was-validated');
        });
    </script>
</body>
</html>