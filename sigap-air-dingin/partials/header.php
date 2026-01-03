<?php
if (!isset($lang)) {
    $lang = $_GET['lang'] ?? 'id';
}
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $texts[$lang]['title'] ?? 'SIGAP AIR DINGIN' ?></title>

<link rel="stylesheet" href="assets/css/sigap-ui.css">
</head>
<body>
