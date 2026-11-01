<?php
// sidebar.php (ADMIN)
?>
<nav class="sidebar bg-dark text-white p-3 d-flex flex-column">
    <div class="d-flex align-items-center mb-4">
        <div class="auth-logo me-2">
            <i class="fas fa-water"></i>
        </div>
        <div>
            <div class="fw-bold">SIGAP AIR DINGIN</div>
            <small class="text-white-50">Admin Panel</small>
        </div>
    </div>

    <ul class="nav nav-pills flex-column gap-1 mb-auto">
        <li class="nav-item">
            <a href="dashboard.php"
               class="nav-link <?= basename($_SERVER['PHP_SELF'])=='dashboard.php'?'active text-white':'text-white' ?>">
                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
            </a>
        </li>

        <li>
            <a href="laporan.php"
               class="nav-link <?= basename($_SERVER['PHP_SELF'])=='laporan.php'?'active text-white':'text-white' ?>">
                <i class="fas fa-list me-2"></i>Data Laporan
            </a>
        </li>

        <li>
            <a href="peta_risiko.php"
               class="nav-link <?= basename($_SERVER['PHP_SELF'])=='peta_risiko.php'?'active text-white':'text-white' ?>">
                <i class="fas fa-map-marked-alt me-2"></i>Peta Risiko
            </a>
        </li>
    </ul>

    <a href="logout.php" class="btn btn-outline-light btn-sm mt-auto">
        <i class="fas fa-sign-out-alt me-1"></i>Logout
    </a>
</nav>
