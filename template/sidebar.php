<?php
$userRole = $_SESSION['role'] ?? 'guest';
$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
?>

<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    
    <?php if ($isLoggedIn): ?>
      <li class="nav-item">
        <a class="nav-link" href="?controller=dashboard">
          <i class="mdi mdi-view-dashboard menu-icon"></i>
          <span class="menu-title">Dashboard</span>
        </a>
      </li>

      <?php if ($userRole === 'admin' || $userRole === 'manajer'): ?>
          <li class="nav-item">
          <a class="nav-link" href="?controller=Store&action=index">
            <i class="mdi mdi-package-variant menu-icon"></i>
            <span class="menu-title">Store</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="?controller=produk&action=index">
            <i class="mdi mdi-package-variant menu-icon"></i>
            <span class="menu-title">Data Produk</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="?controller=customer&action=index">
            <i class="mdi mdi-account-group menu-icon"></i>
            <span class="menu-title">Data Customer</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="?controller=membership&action=index">
            <i class="mdi mdi-card-account-details menu-icon"></i>
            <span class="menu-title">Data Membership</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="?controller=transaksiadmin&action=index">
            <i class="mdi mdi-clipboard-text menu-icon"></i>
            <span class="menu-title">Data Transaksi</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="?controller=adminreward&action=index">
            <i class="mdi mdi-gift menu-icon"></i>
            <span class="menu-title">Kelola Reward</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="?controller=datamember&action=index">
            <i class="mdi mdi-account menu-icon"></i>
            <span class="menu-title">Data Member</span>
          </a>
        </li>

      <?php elseif ($userRole === 'pimpinan'): ?>
        <li class="nav-item">
          <a class="nav-link" href="?controller=laporan&action=index">
            <i class="mdi mdi-file-document menu-icon"></i>
            <span class="menu-title">Laporan</span>
          </a>
        </li>

      <?php elseif ($userRole === 'member'): ?>
        <li class="nav-item">
          <a class="nav-link" href="?controller=pembelian&action=index">
            <i class="mdi mdi-cart menu-icon"></i>
            <span class="menu-title">Pembelian</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="?controller=pembelian&action=history">
            <i class="mdi mdi-history menu-icon"></i>
            <span class="menu-title">Riwayat Pembelian</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="?controller=reward&action=index">
            <i class="mdi mdi-gift-outline menu-icon"></i>
            <span class="menu-title">Klaim Reward</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="?controller=member&action=index">
            <i class="mdi mdi-account-multiple menu-icon"></i>
            <span class="menu-title">Profile</span>
          </a>
        </li>
      <?php endif; ?>

      <li class="nav-item">
        <a class="nav-link" href="?controller=auth&action=logout" onclick="return confirm('Yakin ingin logout?')">
          <i class="mdi mdi-power menu-icon"></i>
          <span class="menu-title">Logout</span>
        </a>
      </li>

    <?php else: ?>
      <li class="nav-item">
        <a class="nav-link" href="?controller=auth&action=login">
          <i class="mdi mdi-login menu-icon"></i>
          <span class="menu-title">Login</span>
        </a>
      </li>
    <?php endif; ?>

  </ul>
</nav>