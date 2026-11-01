<?php
session_start();
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/app.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'auth/login.php');
    exit;
}

$lang = $_GET['lang'] ?? 'id';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $jenis_pengajuan = $_POST['jenis_pengajuan'];

    $bukti = '';
    if (isset($_FILES['bukti']) && $_FILES['bukti']['error'] == 0) {
        $ext = pathinfo($_FILES['bukti']['name'], PATHINFO_EXTENSION);
        if(!is_dir('uploads')) mkdir('uploads', 0777, true);
        $bukti = 'uploads/' . uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['bukti']['tmp_name'], $bukti);
    }

    $stmt = $pdo->prepare("INSERT INTO administrasi 
        (user_id, jenis_pengajuan, nama_anak, jenis_kelamin, tempat_lahir, tanggal_lahir, jam_lahir, nama_ayah, nama_ibu, nik_ayah, nik_ibu, nomor_kk, nik_anak, alamat_orangtua,
         nama_almarhum, nik_almarhum, alamat_terakhir, hari_tanggal_kematian, tempat_kematian, penyebab_kematian,
         nama_pelapor, nik_pelapor, hubungan_pelapor,
         nama_ahli_waris, nik_ahli_waris, hubungan_keluarga, alamat_domisili, bukti, created_at, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'baru')");

    // Initial Params
    $p = [
        'nama_anak' => null, 'jenis_kelamin' => null, 'tempat_lahir' => null, 'tanggal_lahir' => null,
        'jam_lahir' => null, 'nama_ayah' => null, 'nama_ibu' => null, 'nik_ayah' => null,
        'nik_ibu' => null, 'nomor_kk' => null, 'nik_anak' => null, 'alamat_orangtua' => null,
        'nama_almarhum' => null, 'nik_almarhum' => null, 'alamat_terakhir' => null,
        'hari_tanggal_kematian' => null, 'tempat_kematian' => null, 'penyebab_kematian' => null,
        'nama_pelapor' => null, 'nik_pelapor' => null, 'hubungan_pelapor' => null,
        'nama_ahli_waris' => null, 'nik_ahli_waris' => null, 'hubungan_keluarga' => null,
        'alamat_domisili' => null
    ];

    if ($jenis_pengajuan === 'Kelahiran') {
        $p['nama_anak'] = $_POST['kelahiran_nama_anak'] ?? null;
        $p['jenis_kelamin'] = $_POST['kelahiran_jenis_kelamin'] ?? null;
        $p['tempat_lahir'] = $_POST['kelahiran_tempat_lahir'] ?? null;
        $p['tanggal_lahir'] = $_POST['kelahiran_tanggal_lahir'] ?? null;
        $p['jam_lahir'] = $_POST['kelahiran_jam_lahir'] ?? null;
        $p['nama_ayah'] = $_POST['kelahiran_nama_ayah'] ?? null;
        $p['nama_ibu'] = $_POST['kelahiran_nama_ibu'] ?? null;
        $p['nik_ayah'] = $_POST['kelahiran_nik_ayah'] ?? null;
        $p['nik_ibu'] = $_POST['kelahiran_nik_ibu'] ?? null;
        $p['nomor_kk'] = $_POST['kelahiran_nomor_kk'] ?? null;
        $p['alamat_orangtua'] = $_POST['kelahiran_alamat_orangtua'] ?? null;

    } elseif ($jenis_pengajuan === 'AktaKelahiran') {
        $p['nama_anak'] = $_POST['akta_kelahiran_nama_anak'] ?? null;
        $p['tempat_lahir'] = $_POST['akta_kelahiran_tempat_lahir'] ?? null;
        $p['tanggal_lahir'] = $_POST['akta_kelahiran_tanggal_lahir'] ?? null;
        $p['nama_ayah'] = $_POST['akta_kelahiran_nama_ayah'] ?? null;
        $p['nama_ibu'] = $_POST['akta_kelahiran_nama_ibu'] ?? null;
        $p['nomor_kk'] = $_POST['akta_kelahiran_nomor_kk'] ?? null;
        $p['nik_anak'] = $_POST['akta_kelahiran_nik_anak'] ?? null;
        $p['alamat_domisili'] = $_POST['akta_kelahiran_alamat_domisili'] ?? null;

    } elseif ($jenis_pengajuan === 'Kematian') {
        $p['nama_almarhum'] = $_POST['kematian_nama_almarhum'] ?? null;
        $p['tempat_lahir'] = $_POST['kematian_tempat_lahir'] ?? null;
        $p['tanggal_lahir'] = $_POST['kematian_tanggal_lahir'] ?? null;
        $p['nik_almarhum'] = $_POST['kematian_nik_almarhum'] ?? null;
        $p['nomor_kk'] = $_POST['kematian_nomor_kk'] ?? null;
        $p['alamat_terakhir'] = $_POST['kematian_alamat_terakhir'] ?? null;
        $p['jenis_kelamin'] = $_POST['kematian_jenis_kelamin'] ?? null;
        $p['hari_tanggal_kematian'] = $_POST['kematian_hari_tanggal_kematian'] ?? null;
        $p['tempat_kematian'] = $_POST['kematian_tempat_kematian'] ?? null;
        $p['penyebab_kematian'] = $_POST['kematian_penyebab_kematian'] ?? null;

    } elseif ($jenis_pengajuan === 'AktaKematian') {
        $p['nama_almarhum'] = $_POST['akta_kematian_nama_almarhum'] ?? null;
        $p['nik_almarhum'] = $_POST['akta_kematian_nik_almarhum'] ?? null;
        $p['nomor_kk'] = $_POST['akta_kematian_nomor_kk'] ?? null;
        $p['hari_tanggal_kematian'] = $_POST['akta_kematian_hari_tanggal_kematian'] ?? null;
        $p['tempat_kematian'] = $_POST['akta_kematian_tempat_kematian'] ?? null;
        $p['nama_pelapor'] = $_POST['akta_kematian_nama_pelapor'] ?? null;
        $p['nik_pelapor'] = $_POST['akta_kematian_nik_pelapor'] ?? null;
        $p['hubungan_pelapor'] = $_POST['akta_kematian_hubungan_pelapor'] ?? null;

    } elseif ($jenis_pengajuan === 'AhliWaris') {
        $p['nama_almarhum'] = $_POST['ahli_waris_nama_almarhum'] ?? null;
        $p['nik_almarhum'] = $_POST['ahli_waris_nik_almarhum'] ?? null;
        $p['alamat_terakhir'] = $_POST['ahli_waris_alamat_terakhir'] ?? null;
        $p['nama_ahli_waris'] = $_POST['ahli_waris_nama_ahli_waris'] ?? null;
        $p['nik_ahli_waris'] = $_POST['ahli_waris_nik_ahli_waris'] ?? null;
        $p['hubungan_keluarga'] = $_POST['ahli_waris_hubungan_keluarga'] ?? null;
        $p['nomor_kk'] = $_POST['ahli_waris_nomor_kk'] ?? null;
    }

    $execute = $stmt->execute([
        $_SESSION['user_id'],
        $jenis_pengajuan,
        $p['nama_anak'],
        $p['jenis_kelamin'],
        $p['tempat_lahir'],
        $p['tanggal_lahir'],
        $p['jam_lahir'],
        $p['nama_ayah'],
        $p['nama_ibu'],
        $p['nik_ayah'],
        $p['nik_ibu'],
        $p['nomor_kk'],
        $p['nik_anak'],
        $p['alamat_orangtua'],
        $p['nama_almarhum'],
        $p['nik_almarhum'],
        $p['alamat_terakhir'],
        $p['hari_tanggal_kematian'],
        $p['tempat_kematian'],
        $p['penyebab_kematian'],
        $p['nama_pelapor'],
        $p['nik_pelapor'],
        $p['hubungan_pelapor'],
        $p['nama_ahli_waris'],
        $p['nik_ahli_waris'],
        $p['hubungan_keluarga'],
        $p['alamat_domisili'],
        $bukti
    ]);

    if ($execute) {
        $success = "Pengajuan berhasil dikirim!";
    } else {
        $error = "Terjadi kesalahan, coba lagi.";
    }
}
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Administrasi Publik - Sigap Air Dingin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css" rel="stylesheet">
<style>
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
.btn{border-radius:999px;font-weight:700;transition:.2s;}
.btn:hover{transform:translateY(-2px);}
</style>
</head>
<body>

<div class="d-flex">
<nav class="sidebar">
    <div class="brand"><i class="fas fa-water"></i><div><div class="fw-bold">Sigap Air Dingin</div><small>Portal Warga</small></div></div>
    <a href="index.php?lang=<?= $lang ?>" class="nav-link"><i class="fas fa-home"></i>Beranda</a>

    <div class="nav-item">
        <a class="nav-link d-flex justify-content-between align-items-center gap-2" data-bs-toggle="collapse" href="#laporDropdown">
            <span><i class="fas fa-exclamation-triangle me-2"></i>Lapor</span>
            <i class="fas fa-caret-down"></i>
        </a>
        <div class="collapse show" id="laporDropdown">
            <a href="pengaduan.php?lang=<?= $lang ?>" class="nav-link ps-4">Pengaduan</a>
            <a href="aspirasi.php?lang=<?= $lang ?>" class="nav-link ps-4">Aspirasi</a>
            <a href="administrasi.php?lang=<?= $lang ?>" class="nav-link ps-4 active">Administrasi Publik</a>
        </div>
    </div>

    <a href="riwayat.php?lang=<?= $lang ?>" class="nav-link"><i class="fas fa-history"></i>Riwayat</a>
    <a href="peta_risiko.php?lang=<?= $lang ?>" class="nav-link"><i class="fas fa-map-marked-alt"></i>Peta Risiko</a>

    <div class="language-btns mt-3 mb-2">
        <a href="?lang=id">ID</a>
        <a href="?lang=en">EN</a>
    </div>
    <div class="logout"><a href="logout.php" class="btn btn-outline-light w-100"><i class="fas fa-sign-out-alt me-1"></i>Logout</a></div>
</nav>

<div class="flex-grow-1 d-flex flex-column">
    <header class="header"><div><i class="fas fa-file-alt me-1"></i>Administrasi Publik</div>
    <span class="small text-muted">Login sebagai <strong><?= $_SESSION['role'] ?></strong></span></header>

    <main class="main">
        <div class="card">
            <h4>Form Administrasi Publik</h4>

            <?php if($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
            <?php if($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Jenis Pengajuan</label>
                    <select id="jenis_pengajuan" name="jenis_pengajuan" class="form-select" required>
                        <option value="">Pilih...</option>
                        <option value="Kelahiran">Surat Keterangan Kelahiran (URGENT)</option>
                        <option value="AktaKelahiran">Surat Pengantar Akta Kelahiran (URGENT)</option>
                        <option value="Kematian">Surat Keterangan Kematian (URGENT)</option>
                        <option value="AktaKematian">Surat Pengantar Akta Kematian (URGENT)</option>
                        <option value="AhliWaris">Surat Keterangan Ahli Waris (KONDISIONAL – URGENT)</option>
                    </select>
                </div>

                <!-- FORM KELAHIRAN -->
                <div class="form-group form-kelahiran" style="display:none;">
                    <hr>
                    <h5>Kelahiran</h5>
                    <div class="mb-3"><label>Nama Anak</label><input type="text" name="kelahiran_nama_anak" class="form-control"></div>
                    <div class="mb-3">
                    <label>Jenis Kelamin</label>
                     <select name="kelahiran_jenis_kelamin" class="form-select">
                        <option value="">Pilih...</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div class="mb-3"><label>Tempat Lahir</label><input type="text" name="kelahiran_tempat_lahir" class="form-control"></div>
                    <div class="mb-3"><label>Tanggal Lahir</label><input type="date" name="kelahiran_tanggal_lahir" class="form-control"></div>
                    <div class="mb-3"><label>Jam Kelahiran</label><input type="time" name="kelahiran_jam_lahir" class="form-control"></div>
                    <div class="mb-3"><label>Nama Ayah</label><input type="text" name="kelahiran_nama_ayah" class="form-control"></div>
                    <div class="mb-3"><label>Nama Ibu</label><input type="text" name="kelahiran_nama_ibu" class="form-control"></div>
                    <div class="mb-3"><label>NIK Ayah</label><input type="text" name="kelahiran_nik_ayah" class="form-control"></div>
                    <div class="mb-3"><label>NIK Ibu</label><input type="text" name="kelahiran_nik_ibu" class="form-control"></div>
                    <div class="mb-3"><label>Nomor KK Orang Tua</label><input type="text" name="kelahiran_nomor_kk" class="form-control"></div>
                    <div class="mb-3"><label>Alamat Orang Tua</label><textarea name="kelahiran_alamat_orangtua" class="form-control"></textarea></div>
                </div>

                <!-- FORM AKTA KELAHIRAN -->
                <div class="form-group form-akta-kelahiran" style="display:none;">
                    <hr>
                    <h5>Akta Kelahiran</h5>
                    <div class="mb-3"><label>Nama Anak</label><input type="text" name="akta_kelahiran_nama_anak" class="form-control"></div>
                    <div class="row">
                        <div class="col-md-6 mb-3"><label>Tempat Lahir</label><input type="text" name="akta_kelahiran_tempat_lahir" class="form-control"></div>
                        <div class="col-md-6 mb-3"><label>Tanggal Lahir</label><input type="date" name="akta_kelahiran_tanggal_lahir" class="form-control"></div>
                    </div>
                    <div class="mb-3"><label>Nama Ayah</label><input type="text" name="akta_kelahiran_nama_ayah" class="form-control"></div>
                    <div class="mb-3"><label>Nama Ibu</label><input type="text" name="akta_kelahiran_nama_ibu" class="form-control"></div>
                    <div class="mb-3"><label>Nomor KK</label><input type="text" name="akta_kelahiran_nomor_kk" class="form-control"></div>
                    <div class="mb-3"><label>NIK Anak (jika sudah ada)</label><input type="text" name="akta_kelahiran_nik_anak" class="form-control"></div>
                    <div class="mb-3"><label>Alamat Domisili</label><textarea name="akta_kelahiran_alamat_domisili" class="form-control"></textarea></div>
                </div>

                <!-- FORM KEMATIAN -->
                <div class="form-group form-kematian" style="display:none;">
                    <hr>
                    <h5>Kematian</h5>
                    <div class="mb-3"><label>Nama Almarhum / Almarhumah</label><input type="text" name="kematian_nama_almarhum" class="form-control"></div>
                    <div class="row">
                        <div class="col-md-6 mb-3"><label>Tempat Lahir</label><input type="text" name="kematian_tempat_lahir" class="form-control"></div>
                        <div class="col-md-6 mb-3"><label>Tanggal Lahir</label><input type="date" name="kematian_tanggal_lahir" class="form-control"></div>
                    </div>
                    <div class="mb-3"><label>NIK</label><input type="text" name="kematian_nik_almarhum" class="form-control"></div>
                    <div class="mb-3"><label>Nomor KK</label><input type="text" name="kematian_nomor_kk" class="form-control"></div>
                    <div class="mb-3"><label>Alamat Terakhir</label><textarea name="kematian_alamat_terakhir" class="form-control"></textarea></div>
                    <div class="mb-3">
                    <label>Jenis Kelamin</label>
                     <select name="kematian_jenis_kelamin" class="form-select">
                        <option value="">Pilih...</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>

                    <div class="mb-3"><label>Hari / Tanggal Kematian</label><input type="date" name="kematian_hari_tanggal_kematian" class="form-control"></div>
                    <div class="mb-3"><label>Tempat Kematian</label><input type="text" name="kematian_tempat_kematian" class="form-control"></div>
                    <div class="mb-3"><label>Penyebab Kematian</label><textarea name="kematian_penyebab_kematian" class="form-control"></textarea></div>
                </div>

                <!-- FORM AKTA KEMATIAN -->
                <div class="form-group form-akta-kematian" style="display:none;">
                    <hr>
                    <h5>Akta Kematian</h5>
                    <div class="mb-3"><label>Nama Almarhum / Almarhumah</label><input type="text" name="akta_kematian_nama_almarhum" class="form-control"></div>
                    <div class="mb-3"><label>NIK</label><input type="text" name="akta_kematian_nik_almarhum" class="form-control"></div>
                    <div class="mb-3"><label>Nomor KK</label><input type="text" name="akta_kematian_nomor_kk" class="form-control"></div>
                    <div class="mb-3"><label>Hari / Tanggal Kematian</label><input type="date" name="akta_kematian_hari_tanggal_kematian" class="form-control"></div>
                    <div class="mb-3"><label>Tempat Kematian</label><input type="text" name="akta_kematian_tempat_kematian" class="form-control"></div>
                    <div class="mb-3"><label>Nama Pelapor</label><input type="text" name="akta_kematian_nama_pelapor" class="form-control"></div>
                    <div class="mb-3"><label>NIK Pelapor</label><input type="text" name="akta_kematian_nik_pelapor" class="form-control"></div>
                    <div class="mb-3"><label>Hubungan dengan Almarhum</label><input type="text" name="akta_kematian_hubungan_pelapor" class="form-control"></div>
                </div>

                <!-- FORM AHLI WARIS -->
                <div class="form-group form-ahli-waris" style="display:none;">
                    <hr>
                    <h5>Ahli Waris</h5>
                    <div class="mb-3"><label>Nama Almarhum / Almarhumah</label><input type="text" name="ahli_waris_nama_almarhum" class="form-control"></div>
                    <div class="mb-3"><label>NIK Almarhum</label><input type="text" name="ahli_waris_nik_almarhum" class="form-control"></div>
                    <div class="mb-3"><label>Alamat Terakhir</label><textarea name="ahli_waris_alamat_terakhir" class="form-control"></textarea></div>
                    <div class="mb-3"><label>Nama Ahli Waris</label><input type="text" name="ahli_waris_nama_ahli_waris" class="form-control"></div>
                    <div class="mb-3"><label>NIK Ahli Waris</label><input type="text" name="ahli_waris_nik_ahli_waris" class="form-control"></div>
                    <div class="mb-3"><label>Hubungan Keluarga</label><input type="text" name="ahli_waris_hubungan_keluarga" class="form-control"></div>
                    <div class="mb-3"><label>Nomor KK</label><input type="text" name="ahli_waris_nomor_kk" class="form-control"></div>
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
document.getElementById('jenis_pengajuan').addEventListener('change', function(){
    let val = this.value;

    // hide all
    document.querySelectorAll('.form-group').forEach(el => el.style.display = 'none');

    if(val === 'Kelahiran') document.querySelector('.form-kelahiran').style.display = 'block';
    if(val === 'AktaKelahiran') document.querySelector('.form-akta-kelahiran').style.display = 'block';
    if(val === 'Kematian') document.querySelector('.form-kematian').style.display = 'block';
    if(val === 'AktaKematian') document.querySelector('.form-akta-kematian').style.display = 'block';
    if(val === 'AhliWaris') document.querySelector('.form-ahli-waris').style.display = 'block';
});
</script>
</body>
</html>
