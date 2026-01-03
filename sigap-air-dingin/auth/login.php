<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/app.php';

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$_POST['username']]);
    $user = $stmt->fetch();

    if ($user && password_verify($_POST['password'], $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        header("Location: ../index.php?lang=$lang");
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
/* RESET */
*{margin:0;padding:0;box-sizing:border-box}
body{
    font-family:system-ui,-apple-system,"Segoe UI",sans-serif;
    background:linear-gradient(180deg,#f0f6ff,#eef2f7);
    color:#1f2a37;
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:100vh;
    padding:1rem;
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
    margin-left:.3rem;
}

/* FORM INPUT */
.form-control{
    width:100%;
    padding:.55rem .9rem;
    margin-top:.25rem;
    margin-bottom:.9rem;
    border-radius:.6rem;
    border:1px solid #d1d5db;
    outline:none;
    transition:.2s;
}
.form-control:focus{
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
</head>
<body>

<?php include __DIR__ . '/../partials/navbar.php'; ?>

<div class="card">
    <div class="card-header text-center">
        <h4>SIGAP AIR DINGIN</h4>
    </div>

    <div class="card-body">
        <?php if (!empty($error)): ?>
            <div class="alert"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <label class="form-label"><?= $texts[$lang]['username'] ?></label>
            <input type="text" name="username" class="form-control" required>

            <label class="form-label"><?= $texts[$lang]['password'] ?></label>
            <input type="password" name="password" class="form-control" required>

            <button class="btn btn-primary"><?= $texts[$lang]['submit'] ?></button>
        </form>
    </div>

    <div class="card-footer">
        <?= $texts[$lang]['no_account'] ?>
        <a href="register.php?lang=<?= $lang ?>"><?= $texts[$lang]['register'] ?></a>
    </div>
</div>

</body>
</html>
