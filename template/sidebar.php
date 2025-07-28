<?php
$userRole   = $_SESSION['role'] ?? 'guest';
$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;

function renderNavItem($controller, $action, $icon, $title)
{
  $url = "?controller={$controller}" . ($action ? "&action={$action}" : "");
  echo "
    <li class='nav-item'>
      <a class='nav-link' href='{$url}'>
        <i class='mdi {$icon} menu-icon'></i>
        <span class='menu-title'>{$title}</span>
      </a>
    </li>";
}

$menus = [
  'admin' => [
    ['Store', 'index', 'mdi-package-variant', 'Store'],
    ['produk', 'index', 'mdi-package-variant', 'Data Produk'],
    ['customer', 'index', 'mdi-account-group', 'Data Customer'],
    ['membership', 'index', 'mdi-card-account-details', 'Data Membership'],
    ['transaksiadmin', 'index', 'mdi-clipboard-text', 'Data Transaksi'],
    ['adminreward', 'index', 'mdi-gift', 'Kelola Reward'],
    ['datamember', 'index', 'mdi-account', 'Data Member']
  ],
  'pimpinan' => [
    ['laporan', 'index', 'mdi-file-document', 'Laporan']
  ],
  'manajer' => [
    ['produk', 'index', 'mdi-package-variant', 'Data Produk'],
    ['transaksiadmin', 'index', 'mdi-clipboard-text', 'Data Transaksi']
  ],
  'member' => [
    ['pembelian', 'index', 'mdi-cart', 'Pembelian'],
    ['pembelian', 'history', 'mdi-history', 'Riwayat Pembelian'],
    ['reward', 'index', 'mdi-gift-outline', 'Klaim Reward'],
    ['member', 'index', 'mdi-account-multiple', 'Profile']
  ]
];
?>

<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">

    <?php if ($isLoggedIn): ?>
      <?php renderNavItem('dashboard', '', 'mdi-view-dashboard', 'Dashboard'); ?>

      <?php if (isset($menus[$userRole])): ?>
        <?php foreach ($menus[$userRole] as $menu): ?>
          <?php renderNavItem($menu[0], $menu[1], $menu[2], $menu[3]); ?>
        <?php endforeach; ?>
      <?php endif; ?>

      <?php renderNavItem('auth', 'logout', 'mdi-power', 'Logout'); ?>

    <?php else: ?>
      <?php renderNavItem('auth', 'login', 'mdi-login', 'Login'); ?>
    <?php endif; ?>

  </ul>
</nav>
