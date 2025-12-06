<?php
include 'db.php';

$lang = isset($_GET['lang']) ? $_GET['lang'] : 'id';
$texts = [
    'id' => ['register' => 'Register', 'login' => 'Login', /* tambah semua teks */],
    'en' => ['register' => 'Register', 'login' => 'Login', /* English */]
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $status = $_POST['status'];
    $stmt = $pdo->prepare("UPDATE laporan SET status = ? WHERE id = ?");
    $stmt->execute([$status, $id]);
    header('Location: dashboard.php');
}

// Notifikasi
if ($laporan['user_id']) {  // Ambil laporan dulu
    // Serupa seperti atas, mail(...)
    $id = $_POST['id'];
    $status = $_POST['status'];
    $stmt = $pdo->prepare("UPDATE laporan SET status = ? WHERE id = ?");
    $stmt->execute([$status, $id]);
    header('Location: dashboard.php');
}
?>