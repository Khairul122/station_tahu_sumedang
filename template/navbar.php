<?php
$userName = $_SESSION['nama_lengkap'] ?? 'User';
$userRole = $_SESSION['role'] ?? 'guest';
$userEmail = $_SESSION['email'] ?? '';
$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;

$hour = date('H');
if ($hour < 12) {
    $greeting = 'Good Morning';
} elseif ($hour < 17) {
    $greeting = 'Good Afternoon';
} else {
    $greeting = 'Good Evening';
}

$roleDisplay = '';
switch ($userRole) {
    case 'admin':
        $roleDisplay = 'Administrator';
        break;
    case 'pimpinan':
        $roleDisplay = 'Pimpinan';
        break;
    case 'member':
        $roleDisplay = 'Member';
        break;
    default:
        $roleDisplay = 'Guest';
}
?>

<nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
  <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
    <div class="me-3">
      <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
        <span class="icon-menu"></span>
      </button>
    </div>
    <div>
      <a class="navbar-brand brand-logo" href="index.php?controller=dashboard">
        <img src="assets/images/logo.svg" alt="logo" />
      </a>
      <a class="navbar-brand brand-logo-mini" href="index.php?controller=dashboard">
        <img src="assets/images/logo-mini.svg" alt="logo" />
      </a>
    </div>
  </div>
  <div class="navbar-menu-wrapper d-flex align-items-top">
    <ul class="navbar-nav">
      <li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
        <h1 class="welcome-text">
          <?= $greeting ?>, <span class="text-black fw-bold"><?= htmlspecialchars($userName) ?></span>
        </h1>
        <h6 class="welcome-sub-text"><?= $roleDisplay ?></h6>
      </li>
    </ul>
    <ul class="navbar-nav ms-auto">
      <?php if ($isLoggedIn): ?>
      <li class="nav-item dropdown d-none d-lg-block">
        <a class="nav-link" id="notificationDropdown" href="#" data-bs-toggle="dropdown">
          <i class="mdi mdi-bell-outline"></i>
          <span class="badge badge-danger navbar-badge">3</span>
        </a>
        <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="notificationDropdown">
          <div class="dropdown-header">
            <h6 class="mb-0">Notifications</h6>
          </div>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="mdi mdi-account-plus text-primary me-2"></i>
            New member registered
          </a>
          <a href="#" class="dropdown-item">
            <i class="mdi mdi-gift text-success me-2"></i>
            New reward claimed
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item text-center">View all notifications</a>
        </div>
      </li>

      <li class="nav-item dropdown d-none d-lg-block user-dropdown">
        <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
          <img class="img-xs rounded-circle" src="assets/images/faces/face8.jpg" alt="Profile image" />
        </a>
        <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
          <div class="dropdown-header text-center">
            <img class="img-md rounded-circle" src="assets/images/faces/face8.jpg" alt="Profile image" />
            <p class="mb-1 mt-3 font-weight-semibold"><?= htmlspecialchars($userName) ?></p>
            <p class="fw-light text-muted mb-0"><?= htmlspecialchars($userEmail) ?></p>
          </div>
          <a class="dropdown-item" href="index.php?controller=auth&action=profile">
            <i class="dropdown-item-icon mdi mdi-account-outline text-primary me-2"></i>My Profile
          </a>
          <?php if ($userRole === 'admin'): ?>
          <a class="dropdown-item" href="index.php?controller=settings">
            <i class="dropdown-item-icon mdi mdi-settings text-primary me-2"></i>Settings
          </a>
          <?php endif; ?>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="index.php?controller=auth&action=logout" onclick="return confirm('Are you sure you want to logout?')">
            <i class="dropdown-item-icon mdi mdi-power text-primary me-2"></i>Sign Out
          </a>
        </div>
      </li>
      <?php else: ?>
      <li class="nav-item">
        <a class="nav-link btn btn-primary btn-sm" href="index.php?controller=auth&action=login">
          <i class="mdi mdi-login me-1"></i>Login
        </a>
      </li>
      <?php endif; ?>
    </ul>
    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-bs-toggle="offcanvas">
      <span class="mdi mdi-menu"></span>
    </button>
  </div>
</nav>