<?php
session_start();
require 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jenis = $_POST['jenis_pengajuan'];
    $bukti = '';
    if (isset($_FILES['bukti']) && $_FILES['bukti']['error'] == 0) {
        $ext = pathinfo($_FILES['bukti']['name'], PATHINFO_EXTENSION);
        if(!is_dir('uploads')) mkdir('uploads', 0777, true);
        $bukti = 'uploads/' . uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['bukti']['tmp_name'], $bukti);
    }

    $stmt = $pdo->prepare("INSERT INTO administrasi (
        user_id, jenis_pengajuan, nama_anak, jenis_kelamin, tempat_lahir, tanggal_lahir, jam_lahir,
        nama_ayah, nama_ibu, nik_ayah, nik_ibu, nomor_kk, alamat_orangtua, nama_almarhum, nik_almarhum,
        alamat_terakhir, nama_pelapor, nik_pelapor, hubungan_pelapor, nama_ahli_waris, nik_ahli_waris,
        hubungan_keluarga, alamat_domisili, bukti
    ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

    $stmt->execute([
        $_SESSION['user_id'],
        $jenis,
        $_POST['nama_anak'] ?? null,
        $_POST['jenis_kelamin'] ?? null,
        $_POST['tempat_lahir'] ?? null,
        $_POST['tanggal_lahir'] ?? null,
        $_POST['jam_lahir'] ?? null,
        $_POST['nama_ayah'] ?? null,
        $_POST['nama_ibu'] ?? null,
        $_POST['nik_ayah'] ?? null,
        $_POST['nik_ibu'] ?? null,
        $_POST['nomor_kk'] ?? null,
        $_POST['alamat_orangtua'] ?? null,
        $_POST['nama_almarhum'] ?? null,
        $_POST['nik_almarhum'] ?? null,
        $_POST['alamat_terakhir'] ?? null,
        $_POST['nama_pelapor'] ?? null,
        $_POST['nik_pelapor'] ?? null,
        $_POST['hubungan_pelapor'] ?? null,
        $_POST['nama_ahli_waris'] ?? null,
        $_POST['nik_ahli_waris'] ?? null,
        $_POST['hubungan_keluarga'] ?? null,
        $_POST['alamat_domisili'] ?? null,
        $bukti
    ]);
    $success = "Pengajuan administrasi berhasil dikirim!";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Administrasi Publik - Sigap Air Dingin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
/* --- Gaya sama dengan pengaduan & aspirasi --- */
body{font-family:system-ui,-apple-system,"Segoe UI",sans-serif;color:#1f2a37;background:radial-gradient(circle at top left,#e3f2fd 0,#f0f6ff 100%);}
.sidebar{background:#0d6efd;color:#fff;min-width:240px;max-width:260px;min-height:100vh;padding:1rem;display:flex;flex-direction:column;}
.sidebar .brand{display:flex;align-items:center;gap:.5rem;margin-bottom:2rem;}
.sidebar .brand i{font-size:1.8rem;}
.sidebar .nav-link{color:#fff;margin-bottom:.3rem;display:flex;align-items:center;gap:.6rem;font-weight:600;padding:.5rem 1rem;border-radius:.6rem;text-decoration:none;transition:.2s;}
.sidebar .nav-link.active, .sidebar .nav-link:hover{background:rgba(255,255,255,.15);}
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
.btn{border-radius:999px;font-weight:700;transition:.2s;}
.btn:hover{transform:translateY(-2px);}
#map{height:300px;border-radius:1rem;margin-top:1rem;}
</style>
</head>
<body>
<div class="d-flex">
<nav class="sidebar">
    <div class="brand"><i class="fas fa-water"></i><div><div class="fw-bold">Sigap Air Dingin</div><small>Portal Warga</small></div></div>
    <a href="index.php" class="nav-link"><i class="fas fa-home"></i>Beranda</a>
    <div class="nav-item">
        <a class="nav-link d-flex justify-content-between align-items-center gap-2" data-bs-toggle="collapse" href="#laporDropdown">
            <span><i class="fas fa-exclamation-triangle me-2"></i>Lapor</span>
            <i class="fas fa-caret-down"></i>
        </a>
        <div class="collapse show" id="laporDropdown">
            <a href="pengaduan.php" class="nav-link ps-4">Pengaduan</a>
            <a href="aspirasi.php" class="nav-link ps-4">Aspirasi</a>
            <a href="administrasi.php" class="nav-link ps-4 active">Administrasi Publik</a>
        </div>
    </div>
    <a href="riwayat.php" class="nav-link"><i class="fas fa-history"></i>Riwayat</a>
    <a href="peta_risiko.php" class="nav-link"><i class="fas fa-map-marked-alt"></i>Peta Risiko</a>
    <div class="language-btns mt-3 mb-2">
        <a href="?lang=id">ID</a>
        <a href="?lang=en">EN</a>
    </div>
    <div class="logout"><a href="logout.php" class="btn btn-outline-light w-100"><i class="fas fa-sign-out-alt me-1"></i>Logout</a></div>
</nav>

<div class="flex-grow-1 d-flex flex-column">
    <header class="header"><div><i class="fas fa-home me-1"></i>Selamat Datang</div>
    <span class="small text-muted">Masuk sebagai <strong><?= $_SESSION['role'] ?></strong></span></header>

    <main class="main">
        <div class="card">
            <h4>Form Administrasi Publik</h4>
            <?php if($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
            <?php if($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Jenis Pengajuan</label>
                    <select name="jenis_pengajuan" class="form-select" id="jenisPengajuan" required>
                        <option value="">Pilih...</option>
                        <option value="Kelahiran">Surat Keterangan Kelahiran (URGENT)</option>
                        <option value="AktaKelahiran">Surat Pengantar Akta Kelahiran (URGENT)</option>
                        <option value="Kematian">Surat Keterangan Kematian (URGENT)</option>
                        <option value="AktaKematian">Surat Pengantar Akta Kematian (URGENT)</option>
                        <option value="AhliWaris">Surat Keterangan Ahli Waris (KONDISIONAL – URGENT)</option>
                    </select>
                </div>

                <!-- Dynamic Forms -->
                <div id="formContainer">
                    <!-- Fields akan muncul sesuai pilihan -->
                </div>

                <div class="mb-3">
                    <label class="form-label">Upload Bukti</label>
                    <input type="file" name="bukti" class="form-control">
                </div>

                <button class="btn btn-primary w-100">Kirim</button>
            </form>
        </div>
    </main>

    <footer class="text-center py-3">
        <span class="text-muted">&copy; 2023 Sigap Air Dingin. All rights reserved.</span>
    </footer>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
const forms = {
    Kelahiran: `
        <h5>Surat Keterangan Kelahiran</h5>
        <div class="mb-3"><label class="form-label">Nama Anak</label><input type="text" name="nama_anak" class="form-control"></div>
        <div class="mb-3"><label class="form-label">Jenis Kelamin</label>
            <select name="jenis_kelamin" class="form-select">
                <option value="">Pilih...</option>
                <option value="Laki-laki">Laki-laki</option>
                <option value="Perempuan">Perempuan</option>
            </select>
        </div>
        <div class="mb-3"><label class="form-label">Tempat / Tanggal Lahir</label><input type="text" name="tempat_lahir" class="form-control"></div>
        <div class="mb-3"><label class="form-label">Tanggal Lahir</label><input type="date" name="tanggal_lahir" class="form-control"></div>
        <div class="mb-3"><label class="form-label">Jam Lahir</label><input type="time" name="jam_lahir" class="form-control"></div>
        <div class="mb-3"><label class="form-label">Nama Ayah</label><input type="text" name="nama_ayah" class="form-control"></div>
        <div class="mb-3"><label class="form-label">Nama Ibu</label><input type="text" name="nama_ibu" class="form-control"></div>
        <div class="mb-3"><label class="form-label">NIK Ayah</label><input type="text" name="nik_ayah" class="form-control"></div>
        <div class="mb-3"><label class="form-label">NIK Ibu</label><input type="text" name="nik_ibu" class="form-control"></div>
        <div class="mb-3"><label class="form-label">Nomor KK Orang Tua</label><input type="text" name="nomor_kk" class="form-control"></div>
        <div class="mb-3"><label class="form-label">Alamat Orang Tua</label><textarea name="alamat_orangtua" class="form-control"></textarea></div>
    `,
    AktaKelahiran: `
        <h5>Surat Pengantar Akta Kelahiran</h5>
        <div class="mb-3"><label class="form-label">Nama Anak</label><input type="text" name="nama_anak" class="form-control"></div>
        <div class="mb-3"><label class="form-label">Tempat / Tanggal Lahir</label><input type="text" name="tempat_lahir" class="form-control"></div>
        <div class="mb-3"><label class="form-label">Nama Ayah</label><input type="text" name="nama_ayah" class="form-control"></div>
        <div class="mb-3"><label class="form-label">Nama Ibu</label><input type="text" name="nama_ibu" class="form-control"></div>
        <div class="mb-3"><label class="form-label">Nomor KK</label><input type="text" name="nomor_kk" class="form-control"></div>
        <div class="mb-3"><label class="form-label">NIK Anak (jika ada)</label><input type="text" name="nik_anak" class="form-control"></div>
        <div class="mb-3"><label class="form-label">Alamat Domisili</label><textarea name="alamat_domisili" class="form-control"></textarea></div>
    `,
    Kematian: `
        <h5>Surat Keterangan Kematian</h5>
        <div class="mb-3"><label class="form-label">Nama Almarhum / Almarhumah</label><input type="text" name="nama_almarhum" class="form-control"></div>
        <div class="mb-3"><label class="form-label">Tempat / Tanggal Lahir</label><input type="text" name="tempat_lahir" class="form-control"></div>
        <div class="mb-3"><label class="form-label">NIK</label><input type="text" name="nik_almarhum" class="form-control"></div>
        <div class="mb-3"><label class="form-label">Nomor KK</label><input type="text" name="nomor_kk" class="form-control"></div>
        <div class="mb-3"><label class="form-label">Jenis Kelamin</label>
            <select name="jenis_kelamin" class="form-select">
                <option value="">Pilih...</option>
                <option value="Laki-laki">Laki-laki</option>
                <option value="Perempuan">Perempuan</option>
            </select>
        </div>
        <div class="mb-3"><label class="form-label">Alamat Terakhir</label><textarea name="alamat_terakhir" class="form-control"></textarea></div>
        <div class="mb-3"><label class="form-label">Hari / Tanggal Kematian</label><input type="date" name="tanggal_kematian" class="form-control"></div>
        <div class="mb-3"><label class="form-label">Tempat Kematian</label><input type="text" name="tempat_kematian" class="form-control"></div>
        <div class="mb-3"><label class="form-label">Penyebab Kematian</label><input type="text" name="penyebab_kematian" class="form-control"></div>
    `,
    AktaKematian: `
        <h5>Surat Pengantar Akta Kematian</h5>
        <div class="mb-3"><label class="form-label">Nama Almarhum / Almarhumah</label><input type="text" name="nama_almarhum" class="form-control"></div>
        <div class="mb-3"><label class="form-label">NIK</label><input type="text" name="nik_almarhum" class="form-control"></div>
        <div class="mb-3"><label class="form-label">Nomor KK</label><input type="text" name="nomor_kk" class="form-control"></div>
        <div class="mb-3"><label class="form-label">Hari / Tanggal Kematian</label><input type="date" name="tanggal_kematian" class="form-control"></div>
        <div class="mb-3"><label class="form-label">Tempat Kematian</label><input type="text" name="tempat_kematian" class="form-control"></div>
        <div class="mb-3"><label class="form-label">Nama Pelapor</label><input type="text" name="nama_pelapor" class="form-control"></div>
        <div class="mb-3"><label class="form-label">NIK Pelapor</label><input type="text" name="nik_pelapor" class="form-control"></div>
        <div class="mb-3"><label class="form-label">Hubungan dengan Almarhum</label><input type="text" name="hubungan_pelapor" class="form-control"></div>
    `,
    AhliWaris: `
        <h5>Surat Keterangan Ahli Waris</h5>
        <div class="mb-3"><label class="form-label">Nama Almarhum / Almarhumah</label><input type="text" name="nama_almarhum" class="form-control"></div>
        <div class="mb-3"><label class="form-label">NIK Almarhum</label><input type="text" name="nik_almarhum" class="form-control"></div>
        <div class="mb-3"><label class="form-label">Alamat Terakhir</label><textarea name="alamat_terakhir" class="form-control"></textarea></div>
        <div class="mb-3"><label class="form-label">Nama Ahli Waris</label><input type="text" name="nama_ahli_waris" class="form-control"></div>
        <div class="mb-3"><label class="form-label">NIK Ahli Waris</label><input type="text" name="nik_ahli_waris" class="form-control"></div>
        <div class="mb-3"><label class="form-label">Hubungan Keluarga</label><input type="text" name="hubungan_keluarga" class="form-control"></div>
        <div class="mb-3"><label class="form-label">Nomor KK</label><input type="text" name="nomor_kk" class="form-control"></div>
    `
};

document.getElementById('jenisPengajuan').addEventListener('change', function(){
    document.getElementById('formContainer').innerHTML = forms[this.value] || '';
});
</script>
</body>
</html>
