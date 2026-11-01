<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    die('BELUM LOGIN');
}

if ($_SESSION['role'] !== 'admin') {
    die('BUKAN ADMIN');
}
