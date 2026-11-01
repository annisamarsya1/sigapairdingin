<?php
require_once __DIR__ . '/admin_guard.php';
require_once __DIR__ . '/../config/db.php';

$id = $_POST['id'];
$status = $_POST['status'];
$jenis = $_POST['jenis'];

$allowed = ['pengaduan','aspirasi','administrasi'];
if (!in_array($jenis, $allowed)) {
    die('Invalid table');
}

$stmt = $pdo->prepare("UPDATE `$jenis` SET status=? WHERE id=?");
$stmt->execute([$status, $id]);

header("Location: laporan.php?jenis=$jenis");
exit;
