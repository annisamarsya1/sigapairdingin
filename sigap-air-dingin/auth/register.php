<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/app.php';

$lang = isset($_GET['lang']) ? $_GET['lang'] : 'id';
$texts = [
    'id' => ['title' => 'Register', 'username' => 'Username', 'password' => 'Password', 'role' => 'Role', 'email' => 'Email', 'submit' => 'Register', 'have_account' => 'Sudah punya akun?', 'login' => 'Login'],
    'en' => ['title' => 'Register', 'username' => 'Username', 'password' => 'Password', 'role' => 'Role', 'email' => 'Email', 'submit' => 'Register', 'have_account' => 'Already have an account?', 'login' => 'Login']
];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $email = $_POST['email'];
    $stmt = $pdo->prepare("INSERT INTO users (username, password, role, email) VALUES (?, ?, ?, ?)");
    $stmt->execute([$username, $password, $role, $email]);
    header('Location: login.php?lang=' . $lang);
}
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $texts[$lang]['title'] ?> - SIGAP AIR DINGIN</title>

<style>
/* RESET */
*{margin:0;padding:0;box-sizing:border-box}
body{
    font-family:system-ui,-apple-system,"Segoe UI",sans-serif;
    background:linear-gradient(180deg,#f0f6ff,#eef2f7);
    color:#1f2a37;
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:110vh;
    padding: 20px 1rem;
}

/* CARD LOGIN */
.card{
    background:#fff;
    border-radius:1.2rem;
    padding:2rem;
    box-shadow:0 18px 45px rgba(15,63,125,.14);
    max-width:380px;
    width:100%;
}
.card-header h4{
    margin-bottom:.3rem;
    font-weight:800;
}
.card-header small{
    color:#6b7280;
}
.card-body{
    margin-top:1rem;
}
.card-body .form-label{
    font-weight:600;
}
.card-footer{
    margin-top:1rem;
    text-align:center;
    font-size:.95rem;
    color:#6b7280;
}
.card-footer a{
    color:#0d6efd;
    text-decoration:none;
    font-weight:600;
}

/* FORM INPUT */
.form-control, .form-select{
    width:100%;
    padding:.55rem .9rem;
    margin-top:.25rem;
    margin-bottom:.9rem;
    border-radius:.6rem;
    border:1px solid #d1d5db;
    outline:none;
    transition:.2s;
}
.form-control:focus, .form-select:focus{
    border-color:#0d6efd;
    box-shadow:0 0 0 2px rgba(13,110,253,.2);
}

/* BUTTONS */
.btn{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:.7rem 1.8rem;
    border-radius:999px;
    font-weight:700;
    text-decoration:none;
    transition:.2s;
    width:100%;
    border:none;
    cursor:pointer;
}
.btn-primary{
    background:linear-gradient(135deg,#0d6efd,#1e88ff);
    color:#fff;
    box-shadow:0 10px 24px rgba(13,110,253,.35);
}
.btn-primary:hover{
    transform:translateY(-2px);
}

/* ICONS */
i{
    margin-right:.5rem;
}

/* ALERT */
.alert{
    padding:.7rem 1rem;
    background:#fee2e2;
    color:#b91c1c;
    border-radius:.6rem;
    margin-bottom:.9rem;
    font-size:.95rem;
}
</style>

<link rel="stylesheet" href="assets/css/navbar.css">
</head>
<body>

<?php include __DIR__ . '/../partials/navbar.php'; ?>

<div class="card">
    <div class="card-header text-center">
        <h4><i class="fas fa-user-plus"></i> <?= $texts[$lang]['title'] ?></h4>
    </div>

    <div class="card-body">
        <form method="POST" class="needs-validation" novalidate>
            <label class="form-label"><?= $texts[$lang]['username'] ?></label>
            <input type="text" name="username" class="form-control" required>

            <label class="form-label"><?= $texts[$lang]['password'] ?></label>
            <input type="password" name="password" class="form-control" required>

            <label class="form-label"><?= $texts[$lang]['role'] ?></label>
            <select name="role" class="form-select" required>
                <option value="user">Masyarakat</option>
                <option value="rt_rw">RT/RW</option>
                <option value="admin">Admin/Dinas</option>
            </select>

            <label class="form-label"><?= $texts[$lang]['email'] ?></label>
            <input type="email" name="email" class="form-control">

            <button type="submit" class="btn btn-primary"><i class="fas fa-sign-in-alt"></i> <?= $texts[$lang]['submit'] ?></button>
        </form>
    </div>

    <div class="card-footer">
        <?= $texts[$lang]['have_account'] ?> <a href="login.php?lang=<?= $lang ?>"><?= $texts[$lang]['login'] ?></a>
    </div>
</div>

<script>
(function () {
    'use strict';
    var forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();
</script>
</body>
</html>
