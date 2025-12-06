<?php
session_start();
include 'db.php';
$lang = isset($_GET['lang']) ? $_GET['lang'] : 'id';
$texts = [
    'id' => ['title' => 'Login', 'username' => 'Username', 'password' => 'Password', 'submit' => 'Login', 'no_account' => 'Belum punya akun?', 'register' => 'Register'],
    'en' => ['title' => 'Login', 'username' => 'Username', 'password' => 'Password', 'submit' => 'Login', 'no_account' => 'No account?', 'register' => 'Register']
];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        header('Location: index.php?lang=' . $lang);
    } else {
        $error = "Login gagal!";
    }
}
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $texts[$lang]['title']; ?> - Sigap Air Dingin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="section-header text-center">
                        <h4><i class="fas fa-sign-in-alt me-2"></i><?php echo $texts[$lang]['title']; ?></h4>
                    </div>
                    <div class="card-body p-4">
                        <?php if (isset($error)) echo '<div class="alert alert-danger">'.$error.'</div>'; ?>
                        <form method="POST" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label class="form-label"><?php echo $texts[$lang]['username']; ?></label>
                                <input type="text" name="username" class="form-control" required>
                                <div class="invalid-feedback">Username diperlukan.</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><?php echo $texts[$lang]['password']; ?></label>
                                <input type="password" name="password" class="form-control" required>
                                <div class="invalid-feedback">Password diperlukan.</div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-sign-in-alt me-2"></i><?php echo $texts[$lang]['submit']; ?></button>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <p class="mb-0"><?php echo $texts[$lang]['no_account']; ?> <a href="register.php?lang=<?php echo $lang; ?>"><?php echo $texts[$lang]['register']; ?></a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function () { 'use strict'; var forms = document.querySelectorAll('.needs-validation'); Array.from(forms).forEach(function (form) { form.addEventListener('submit', function (event) { if (!form.checkValidity()) { event.preventDefault(); event.stopPropagation(); } form.classList.add('was-validated'); }, false); }); })();
    </script>
</body>
</html>