<nav class="navbar">
    <div class="navbar-container">
        <!-- Logo + Pemerintah + Nama Aplikasi -->
        <div class="navbar-brand-container" style="display:flex; align-items:center; gap:.8rem;">
            <!-- Logo Sawahlunto -->
            <img src="<?= BASE_URL ?>assets/img/logo-sawahlunto.png" alt="Logo Sawahlunto" style="height:40px;">


            <!-- Tulisan Pemerintah Kota Sawahlunto + Kelurahan Air Dingin -->
            <div style="display:flex; flex-direction:column; line-height:1.1;">
                <span style="font-size:.85rem; font-weight:600; color:#6b7280;">Pemerintah Kota Sawahlunto</span>
                <span style="font-size:.75rem; color:#6b7280;">Kelurahan Air Dingin</span>
            </div>

            <!-- Nama Aplikasi -->
            <a href="<?= BASE_URL ?>landing.php" style="font-weight:800; color:#0d6efd; text-decoration:none; font-size:1.2rem;">
                SIGAP AIR DINGIN
            </a>
        </div>

        <!-- Toggle button untuk mobile -->
        <button class="navbar-toggle" id="navToggle">☰</button>

        <!-- Menu -->
        <ul class="navbar-menu" id="navMenu">
            <li><a href="<?= BASE_URL ?>landing.php?#hero">Beranda</a></li>
            <li><a href="<?= BASE_URL ?>landing.php?#fitur">Fitur</a></li>
            <li><a href="<?= BASE_URL ?>landing.php?#alur">Alur</a></li>
            <li><a href="<?= BASE_URL ?>landing.php?#peta">Peta</a></li>

            <li>
                <a class="btn-outline" href="<?= BASE_URL ?>auth/login.php">
                    Masuk Dashboard
                </a>
            </li>
            <li>
                <a class="btn-primary" href="<?= BASE_URL ?>auth/register.php">
                    Daftar Akun
                </a>
            </li>
        </ul>
    </div>
</nav>


<script>
document.getElementById('navToggle').onclick = function(){
    document.getElementById('navMenu').classList.toggle('show');
}
</script>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.getElementById('navToggle');
    const menu = document.getElementById('navMenu');

    toggle.addEventListener('click', () => {
        menu.classList.toggle('show');
    });
});
</script>



<style>
    /* ================= NAVBAR ================= */
.navbar{
    position:fixed;
    top:0;
    width:100%;
    background:#fff;
    z-index:1000;
    box-shadow:0 6px 24px rgba(0,0,0,.08);
}

.navbar-container{
    max-width:1180px;
    margin:auto;
    padding:.9rem 1.2rem;
    display:flex;
    align-items:center;
    justify-content:space-between;
}

.navbar-brand{
    font-weight:800;
    color:#0d6efd;
    text-decoration:none;
    line-height:1.1;
}
.navbar-brand small{
    display:block;
    font-size:.72rem;
    font-weight:600;
    color:#6b7280;
}

.navbar-menu{
    list-style:none;
    display:flex;
    align-items:center;
    gap:.6rem;
}

.navbar-menu a{
    text-decoration:none;
    color:#1f2937;
    font-weight:600;
    padding:.45rem .9rem;
    border-radius:999px;
}

.navbar-menu a:hover{
    background:#eef4ff;
    color:#0d6efd;
}

.navbar-brand-container img {
    display:block;
}

.navbar-brand-container a {
    margin-left: .5rem; /* jarak dari tulisan pemerintah */
}

.btn-primary{
    background:#0d6efd;
    color:#fff !important;
    padding:.5rem 1.2rem;
}
.btn-outline{
    border:2px solid #0d6efd;
    color:#0d6efd !important;
    padding:.45rem 1.1rem;
}

.divider{
    width:1px;
    height:28px;
    background:#e5e7eb;
}

.navbar-toggle{
    display:none;
    font-size:1.4rem;
    background:none;
    border:none;
    cursor:pointer;
}

@media(max-width:900px){
    .navbar-toggle{display:block}

    .navbar-menu{
        position:absolute;
        top:100%;
        right:1rem;
        background:#fff;
        flex-direction:column;
        padding:1rem;
        border-radius:1rem;
        box-shadow:0 20px 40px rgba(0,0,0,.15);
        display:none;
        min-width:220px;
    }

    .navbar-menu.show{
        display:flex;
    }

    .divider{display:none}
}
</style>