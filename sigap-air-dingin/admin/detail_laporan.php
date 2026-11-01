<?php
session_start();
require_once __DIR__ . '/../config/db.php';

// Proteksi admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

// Tangkap ID dan jenis
$id    = $_GET['id'] ?? '';
$jenis = $_GET['jenis'] ?? 'pengaduan';

$allowed = ['pengaduan','aspirasi','administrasi'];
if (!in_array($jenis, $allowed)) $jenis = 'pengaduan';

if (!$id) {
    die('Laporan tidak ditemukan.');
}

// Ambil data
$stmt = $pdo->prepare("SELECT * FROM $jenis WHERE id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) die('Laporan tidak ditemukan.');
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detail Laporan - Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css" rel="stylesheet">
<style>
body{background:#f4f6f9}
.sidebar{width:250px;min-height:100vh}
.auth-logo{width:42px;height:42px;border-radius:50%;background:#0d6efd;color:#fff;display:flex;align-items:center;justify-content:center;}
.card{margin-bottom:1.5rem;}
</style>
</head>
<body>
<div class="d-flex">

<!-- SIDEBAR -->
<nav class="sidebar bg-dark text-white p-3 d-flex flex-column">
    <div class="d-flex align-items-center mb-4">
        <div class="auth-logo me-2"><i class="fas fa-water"></i></div>
        <div>
            <div class="fw-bold">SIGAP AIR DINGIN</div>
            <small class="text-white-50">Admin Panel</small>
        </div>
    </div>
    <ul class="nav nav-pills flex-column gap-1 mb-auto">
        <li class="nav-item"><a href="dashboard.php" class="nav-link text-white"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
        <li><a href="laporan.php" class="nav-link active text-white"><i class="fas fa-list me-2"></i>Data Laporan</a></li>
        <li><a href="peta_risiko.php" class="nav-link text-white"><i class="fas fa-map-marked-alt me-2"></i>Peta Risiko</a></li>
    </ul>
    <a href="../auth/logout.php" class="btn btn-outline-light btn-sm mt-auto"><i class="fas fa-sign-out-alt me-1"></i>Logout</a>
</nav>

<!-- CONTENT -->
<div class="flex-grow-1 p-4">

<h4>Detail Laporan (<?= ucfirst($jenis) ?>)</h4>

<div class="card p-3">
    <h5>Informasi Umum</h5>
    <p><strong>ID:</strong> <?= $data['id'] ?></p>
    <p><strong>Judul / Jenis:</strong> <?= htmlspecialchars($data['judul'] ?? $data['jenis_pengajuan']) ?></p>
    <p><strong>Status:</strong> <?= ucfirst($data['status']) ?></p>
    <p><strong>Tanggal:</strong> <?= $data['created_at'] ?></p>
</div>

<?php if($jenis === 'administrasi'): ?>
<div class="card p-3">
    <h5>Detail Administrasi</h5>
    <?php if($data['jenis_pengajuan'] === 'Kelahiran'): ?>
        <p><strong>Nama Anak:</strong> <?= $data['nama_anak'] ?></p>
        <p><strong>Jenis Kelamin:</strong> <?= $data['jenis_kelamin'] ?></p>
        <p><strong>Tempat / Tanggal Lahir:</strong> <?= $data['tempat_lahir'] ?> / <?= $data['tanggal_lahir'] ?></p>
        <p><strong>Jam Lahir:</strong> <?= $data['jam_lahir'] ?></p>
        <p><strong>Nama Ayah:</strong> <?= $data['nama_ayah'] ?></p>
        <p><strong>Nama Ibu:</strong> <?= $data['nama_ibu'] ?></p>
        <p><strong>NIK Ayah:</strong> <?= $data['nik_ayah'] ?></p>
        <p><strong>NIK Ibu:</strong> <?= $data['nik_ibu'] ?></p>
        <p><strong>Nomor KK Orang Tua:</strong> <?= $data['nomor_kk'] ?></p>
        <p><strong>Alamat Orang Tua:</strong> <?= $data['alamat_orangtua'] ?></p>
    <?php elseif($data['jenis_pengajuan'] === 'AktaKelahiran'): ?>
        <p><strong>Nama Anak:</strong> <?= $data['nama_anak'] ?></p>
        <p><strong>Tempat / Tanggal Lahir:</strong> <?= $data['tempat_lahir'] ?> / <?= $data['tanggal_lahir'] ?></p>
        <p><strong>Nama Ayah:</strong> <?= $data['nama_ayah'] ?></p>
        <p><strong>Nama Ibu:</strong> <?= $data['nama_ibu'] ?></p>
        <p><strong>Nomor KK:</strong> <?= $data['nomor_kk'] ?></p>
        <p><strong>NIK Anak:</strong> <?= $data['nik_anak'] ?></p>
        <p><strong>Alamat Domisili:</strong> <?= $data['alamat_domisili'] ?></p>
    <?php elseif($data['jenis_pengajuan'] === 'Kematian'): ?>
        <p><strong>Nama Almarhum:</strong> <?= $data['nama_almarhum'] ?></p>
        <p><strong>Tempat / Tanggal Lahir:</strong> <?= $data['tempat_lahir'] ?> / <?= $data['tanggal_lahir'] ?></p>
        <p><strong>NIK:</strong> <?= $data['nik_almarhum'] ?></p>
        <p><strong>Nomor KK:</strong> <?= $data['nomor_kk'] ?></p>
        <p><strong>Jenis Kelamin:</strong> <?= $data['jenis_kelamin'] ?></p>
        <p><strong>Alamat Terakhir:</strong> <?= $data['alamat_terakhir'] ?></p>
        <p><strong>Hari / Tanggal Kematian:</strong> <?= $data['hari_tanggal_kematian'] ?></p>
        <p><strong>Tempat Kematian:</strong> <?= $data['tempat_kematian'] ?></p>
        <p><strong>Penyebab Kematian:</strong> <?= $data['penyebab_kematian'] ?></p>
    <?php elseif($data['jenis_pengajuan'] === 'AktaKematian'): ?>
        <p><strong>Nama Almarhum:</strong> <?= $data['nama_almarhum'] ?></p>
        <p><strong>NIK:</strong> <?= $data['nik_almarhum'] ?></p>
        <p><strong>Nomor KK:</strong> <?= $data['nomor_kk'] ?></p>
        <p><strong>Hari / Tanggal Kematian:</strong> <?= $data['hari_tanggal_kematian'] ?></p>
        <p><strong>Tempat Kematian:</strong> <?= $data['tempat_kematian'] ?></p>
        <p><strong>Nama Pelapor:</strong> <?= $data['nama_pelapor'] ?></p>
        <p><strong>NIK Pelapor:</strong> <?= $data['nik_pelapor'] ?></p>
        <p><strong>Hubungan Pelapor:</strong> <?= $data['hubungan_pelapor'] ?></p>
    <?php elseif($data['jenis_pengajuan'] === 'AhliWaris'): ?>
        <p><strong>Nama Almarhum:</strong> <?= $data['nama_almarhum'] ?></p>
        <p><strong>NIK Almarhum:</strong> <?= $data['nik_almarhum'] ?></p>
        <p><strong>Alamat Terakhir:</strong> <?= $data['alamat_terakhir'] ?></p>
        <p><strong>Nama Ahli Waris:</strong> <?= $data['nama_ahli_waris'] ?></p>
        <p><strong>NIK Ahli Waris:</strong> <?= $data['nik_ahli_waris'] ?></p>
        <p><strong>Hubungan Keluarga:</strong> <?= $data['hubungan_keluarga'] ?></p>
        <p><strong>Nomor KK:</strong> <?= $data['nomor_kk'] ?></p>
    <?php endif; ?>

    <?php if(!empty($data['bukti'])): ?>
        <hr>
        <p><strong>Bukti Terlampir:</strong> <a href="../<?= $data['bukti'] ?>" download class="btn btn-sm btn-outline-primary"><i class="fas fa-download me-1"></i>Unduh</a></p>
    <?php endif; ?>
</div>

<?php else: ?>
<div class="card p-3">
    <h5>Deskripsi Laporan</h5>
    <p><?= nl2br(htmlspecialchars($data['deskripsi'] ?? '-')) ?></p>
    <?php if(!empty($data['bukti'])): ?>
        <hr>
        <p><strong>Bukti Terlampir:</strong> <a href="../<?= $data['bukti'] ?>" download class="btn btn-sm btn-outline-primary"><i class="fas fa-download me-1"></i>Unduh</a></p>
    <?php endif; ?>
</div>
<?php endif; ?>

<a href="laporan.php?jenis=<?= $jenis ?>" class="btn btn-secondary mt-3"><i class="fas fa-arrow-left me-1"></i>Kembali</a>

</div>
</div>
</body>
</html>
