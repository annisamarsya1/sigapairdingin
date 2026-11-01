<?php
session_start();

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/app.php';

/**
 * JIKA SUDAH LOGIN
 * - admin → dashboard
 * - selain admin → index
 */
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: ../admin/dashboard.php");
    } else {
        header("Location: ../index.php");
    }
    exit;
}

$lang = $_GET['lang'] ?? 'id';

$texts = [
    'id' => [
        'title' => 'Login',
        'username' => 'Username',
        'password' => 'Password',
        'submit' => 'Masuk',
        'no_account' => 'Belum punya akun?',
        'register' => 'Daftar'
    ],
    'en' => [
        'title' => 'Login',
        'username' => 'Username',
        'password' => 'Password',
        'submit' => 'Login',
        'no_account' => 'No account?',
        'register' => 'Register'
    ]
];

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$_POST['username']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($_POST['password'], $user['password'])) {

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role']    = $user['role'];

        if ($user['role'] === 'admin') {
            header("Location: ../admin/dashboard.php");
        } else {
            header("Location: ../index.php");
        }
        exit;

    } else {
        $error = "Username atau password salah";
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $texts[$lang]['title'] ?> - SIGAP AIR DINGIN</title>

<style>
*{margin:0;padding:0;box-sizing:border-box}
body{
    font-family:system-ui,-apple-system,"Segoe UI",sans-serif;
    background:linear-gradient(180deg,#f0f6ff,#eef2f7);
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:100vh;
}
.card{
    background:#fff;
    padding:2rem;
    border-radius:1.2rem;
    width:100%;
    max-width:380px;
    box-shadow:0 18px 45px rgba(15,63,125,.14);
}
.form-control{
    width:100%;
    padding:.6rem .9rem;
    margin-bottom:1rem;
    border-radius:.6rem;
    border:1px solid #d1d5db;
}
.btn{
    width:100%;
    padding:.7rem;
    border-radius:999px;
    background:#0d6efd;
    color:#fff;
    border:none;
    font-weight:700;
}
.alert{
    background:#fee2e2;
    color:#b91c1c;
    padding:.7rem;
    border-radius:.6rem;
    margin-bottom:1rem;
}
</style>
</head>
<body>

<?php include __DIR__ . '/../partials/navbar.php'; ?>
<div class="card">
    <h3 style="text-align:center;margin-bottom:1rem">SIGAP AIR DINGIN</h3>

    <?php if ($error): ?>
        <div class="alert"><?= $error ?></div>
    <?php endif; ?>

    <?php if (isset($_GET['registered'])): ?>
        <div class="alert" style="background:#d1fae5;color:#065f46;">
            ✅ Registrasi berhasil! Silakan login.
        </div>
    <?php endif; ?>

    <form method="POST">
        <label><?= $texts[$lang]['username'] ?></label>
        <input type="text" name="username" class="form-control" required>

        <label><?= $texts[$lang]['password'] ?></label>
        <input type="password" name="password" class="form-control" required>

        <button class="btn"><?= $texts[$lang]['submit'] ?></button>
    </form>

    <p style="text-align:center;margin-top:1rem;font-size:.9rem;color:#6b7280;">
        <?= $texts[$lang]['no_account'] ?> 
        <a href="register.php?lang=<?= $lang ?>" style="color:#0d6efd;font-weight:700;"><?= $texts[$lang]['register'] ?></a>
    </p>
</div>

</body>
</html>
