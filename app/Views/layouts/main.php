<?php
$session = session();
$roleId = $session->get('role_id');
$nama = $session->get('nama');
$email = $session->get('email');
$ukmId = $session->get('ukm_id');

// Tentukan kelas tema berdasarkan role
$themeClass = '';
$roleName = 'Guest';
if ($roleId == 1) {
    $themeClass = '';
    $roleName = 'Admin UKM';
} elseif ($roleId == 2) {
    $themeClass = 'theme-kemahasiswaan';
    $roleName = 'Kemahasiswaan';
} elseif ($roleId == 3) {
    $themeClass = 'theme-wakilrektor3';
    $roleName = 'Wakil Rektor III';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? esc($title) : 'Dashboard' ?> - SI Pemantauan UKM</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom Style Sheet with versioning to prevent caching -->
    <link href="<?= base_url('css/custom.css?v=6') ?>" rel="stylesheet">
</head>
<body class="<?= $themeClass ?>">

<div class="app-wrapper">
    <!-- Sidebar Navigation -->
    <aside class="app-sidebar" id="app-sidebar">
        <div class="sidebar-header">
            <a href="<?= base_url('/') ?>" class="sidebar-brand">
                <?php if (file_exists(FCPATH . 'images/logo Unitomo.png')): ?>
                    <img src="<?= base_url('images/logo Unitomo.png') ?>" alt="Logo" style="height: 38px; width: auto; max-width: 120px; object-fit: contain;">
                <?php elseif (file_exists(FCPATH . 'images/logo.png')): ?>
                    <img src="<?= base_url('images/logo.png') ?>" alt="Logo" style="height: 32px; width: auto; max-width: 120px; object-fit: contain;">
                <?php else: ?>
                    <i class="bi bi-shield-check-fill text-primary"></i>
                <?php endif; ?>
                <span>SI Pemantauan</span>
            </a>
        </div>
        
        <ul class="sidebar-menu">
            <!-- Dashboard Link (Berbeda per Role) -->
            <li>
                <?php if ($roleId == 1): ?>
                    <a href="<?= base_url('ukm/dashboard') ?>" class="menu-item-link <?= url_is('ukm/dashboard') ? 'active' : '' ?>">
                        <i class="bi bi-grid-1x2-fill"></i> Dashboard
                    </a>
                <?php elseif ($roleId == 2): ?>
                    <a href="<?= base_url('kemahasiswaan/dashboard') ?>" class="menu-item-link <?= url_is('kemahasiswaan/dashboard') ? 'active' : '' ?>">
                        <i class="bi bi-grid-1x2-fill"></i> Dashboard
                    </a>
                <?php elseif ($roleId == 3): ?>
                    <a href="<?= base_url('wakilrektor3/dashboard') ?>" class="menu-item-link <?= url_is('wakilrektor3/dashboard') ? 'active' : '' ?>">
                        <i class="bi bi-grid-1x2-fill"></i> Dashboard
                    </a>
                <?php endif; ?>
            </li>
            
            <li class="sidebar-menu-header text-uppercase text-muted px-3 mt-4 mb-2" style="font-size: 0.725rem; font-weight: 700; letter-spacing: 0.5px;">Master Data</li>
            
            <!-- MASTER MENU SESUAI ROLE -->
            <?php if ($roleId == 1): ?>
                <!-- Menu Admin UKM -->
                <li>
                    <a href="<?= base_url('ukm/edit/' . $ukmId) ?>" class="menu-item-link <?= url_is('ukm/edit*') ? 'active' : '' ?>">
                        <i class="bi bi-shop-window"></i> Profil UKM
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('bagian-ukm') ?>" class="menu-item-link <?= url_is('bagian-ukm*') ? 'active' : '' ?>">
                        <i class="bi bi-tags-fill"></i> Divisi / Bagian
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('anggota-ukm') ?>" class="menu-item-link <?= url_is('anggota-ukm*') ? 'active' : '' ?>">
                        <i class="bi bi-people-fill"></i> Data Anggota
                    </a>
                </li>
            <?php elseif ($roleId == 2): ?>
                <!-- Menu Admin Kemahasiswaan -->
                <li>
                    <a href="<?= base_url('user') ?>" class="menu-item-link <?= url_is('user*') ? 'active' : '' ?>">
                        <i class="bi bi-person-gear"></i> Kelola User
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('ukm') ?>" class="menu-item-link <?= url_is('ukm*') ? 'active' : '' ?>">
                        <i class="bi bi-shield-fill"></i> Kelola UKM
                    </a>
                </li>
            <?php elseif ($roleId == 3): ?>
                <!-- Menu WR3 -->
                <li>
                    <a href="<?= base_url('ukm') ?>" class="menu-item-link <?= url_is('ukm*') ? 'active' : '' ?>">
                        <i class="bi bi-shield-fill"></i> Daftar UKM
                    </a>
                </li>
            <?php endif; ?>
            
            <li class="sidebar-menu-header text-uppercase text-muted px-3 mt-4 mb-2" style="font-size: 0.725rem; font-weight: 700; letter-spacing: 0.5px;">Kegiatan & Laporan</li>
            
            <!-- TRANSAKSI MENU -->
            <li>
                <a href="<?= base_url('kegiatan') ?>" class="menu-item-link <?= url_is('kegiatan*') ? 'active' : '' ?>">
                    <i class="bi bi-calendar-event-fill"></i> Kegiatan UKM
                </a>
            </li>
            <li>
                <a href="<?= base_url('proposal') ?>" class="menu-item-link <?= url_is('proposal*') ? 'active' : '' ?>">
                    <i class="bi bi-file-earmark-text-fill"></i> Proposal Kegiatan
                </a>
            </li>
            <li>
                <a href="<?= base_url('lpj') ?>" class="menu-item-link <?= url_is('lpj*') ? 'active' : '' ?>">
                    <i class="bi bi-file-earmark-check-fill"></i> LPJ Kegiatan
                </a>
            </li>
        </ul>
        
        <div class="sidebar-footer">
            <a href="<?= base_url('/logout') ?>" class="menu-item-link text-danger w-100">
                <i class="bi bi-box-arrow-left"></i> Logout
            </a>
        </div>
    </aside>

    <!-- Main Content Panel -->
    <div class="app-content">
        <!-- Top Navbar -->
        <header class="app-navbar">
            <button type="button" class="btn d-lg-none btn-outline-secondary" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>
            <div></div> <!-- Spacer -->
            <div class="dropdown navbar-user-info">
                <a href="#" class="d-flex align-items-center gap-2 text-decoration-none text-dark dropdown-toggle" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="text-end d-none d-sm-block">
                        <div class="fw-bold" style="font-size: 0.9rem; line-height: 1.2;"><?= esc($nama) ?></div>
                        <div class="text-muted" style="font-size: 0.75rem;"><?= esc($roleName) ?></div>
                    </div>
                    <?php $userFoto = session()->get('foto'); ?>
                    <div class="user-avatar text-uppercase" style="overflow: hidden; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background-color: var(--primary-light); color: var(--primary); font-weight: 700; border: 1px solid rgba(0,0,0,0.1);">
                        <?php if (!empty($userFoto) && file_exists(FCPATH . 'uploads/profile/' . $userFoto)): ?>
                            <img src="<?= base_url('uploads/profile/' . $userFoto) ?>" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                        <?php else: ?>
                            <?= strtoupper(substr(esc($nama), 0, 1)) ?>
                        <?php endif; ?>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" aria-labelledby="profileDropdown" style="border-radius: 8px; font-size: 0.875rem;">
                    <li>
                        <a class="dropdown-item py-2 px-3 d-flex align-items-center gap-2" href="<?= base_url('profile') ?>">
                            <i class="bi bi-person-fill text-muted"></i> Profil Saya
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item py-2 px-3 text-danger d-flex align-items-center gap-2" href="<?= base_url('logout') ?>">
                            <i class="bi bi-box-arrow-left"></i> Keluar
                        </a>
                    </li>
                </ul>
            </div>
        </header>

        <!-- Main Body Panel -->
        <main class="content-body">
            <!-- Flash Message Banner -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 12px; background-color: rgba(16, 185, 129, 0.15); color: #065f46;">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 12px; background-color: rgba(239, 68, 68, 0.15); color: #991b1b;">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- Render views content section -->
            <?= $this->renderSection('content') ?>
        </main>
    </div>
</div>

<!-- Bootstrap 5 Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Toggle Sidebar on mobile
    const toggleBtn = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('app-sidebar');
    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            sidebar.classList.toggle('show');
        });
        
        // Close sidebar if clicked outside on mobile
        document.addEventListener('click', function(e) {
            if (window.innerWidth < 992 && sidebar.classList.contains('show') && !sidebar.contains(e.target) && e.target !== toggleBtn) {
                sidebar.classList.remove('show');
            }
        });
    }
</script>
</body>
</html>
