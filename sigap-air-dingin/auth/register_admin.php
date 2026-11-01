<?php
session_start();
require_once __DIR__ . '/../config/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'admin')");
    if ($stmt->execute([$username, $hash])) {
        header('Location: login.php'); // langsung ke login admin
        exit;
    } else {
        $error = "Username sudah dipakai atau terjadi kesalahan.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register Admin - SIGAP AIR DINGIN</title>
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

<div class="card">
    <h3 style="text-align:center;margin-bottom:1rem">REGISTER ADMIN</h3>

    <?php if ($error): ?>
        <div class="alert"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Username</label>
        <input type="text" name="username" class="form-control" required>

        <label>Password</label>
        <input type="password" name="password" class="form-control" required>

        <button class="btn">Daftar</button>
    </form>
</div>

</body>
</html>
