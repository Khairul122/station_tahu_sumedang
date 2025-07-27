<?php include('template/header.php'); ?>

<body class="with-welcome-text">
  <div class="container-scroller">
    <?php include 'template/navbar.php'; ?>
    <div class="container-fluid page-body-wrapper">
      <?php include 'template/setting_panel.php'; ?>
      <?php include 'template/sidebar.php'; ?>
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row mb-4">
            <div class="col-12">
              <div class="profile-header">
                <div class="profile-info">
                  <div class="profile-avatar">
                    <i class="fas fa-user-circle"></i>
                  </div>
                  <div class="profile-details">
                    <h2 class="profile-name"><?= htmlspecialchars($memberProfile['nama_customer']) ?></h2>
                    <p class="profile-email"><?= htmlspecialchars($memberProfile['email']) ?></p>
                    <div class="membership-info">
                      <span class="membership-badge <?= strtolower($memberProfile['nama_membership']) ?>">
                        <i class="fas fa-crown"></i>
                        <?= htmlspecialchars($memberProfile['nama_membership']) ?>
                      </span>
                      <span class="member-since">Member sejak <?= date('d M Y', strtotime($memberProfile['tanggal_daftar'])) ?></span>
                    </div>
                  </div>
                </div>
                <div class="profile-stats">
                  <div class="stat-item">
                    <h4><?= number_format($memberProfile['total_poin']) ?></h4>
                    <span>Total Poin</span>
                  </div>
                  <div class="stat-item">
                    <h4>Rp <?= number_format($memberProfile['total_pembelian']) ?></h4>
                    <span>Total Belanja</span>
                  </div>
                  <div class="stat-item">
                    <h4><?= $memberProfile['diskon_persen'] ?>%</h4>
                    <span>Diskon Member</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <?php if (!empty($success)): ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($success) ?>
            <button type="button" class="close" data-dismiss="alert">
              <span>&times;</span>
            </button>
          </div>
          <?php endif; ?>

          <?php if (!empty($error)): ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($error) ?>
            <button type="button" class="close" data-dismiss="alert">
              <span>&times;</span>
            </button>
          </div>
          <?php endif; ?>

          <div class="row">
            <div class="col-lg-8 col-md-12">
              <div class="profile-form-card">
                <div class="profile-form-header">
                  <h4>Informasi Profil</h4>
                </div>
                <div class="profile-form-body">
                  <form method="POST" action="index.php?controller=member&action=updateProfile">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="nama_lengkap">Nama Lengkap</label>
                          <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" 
                                 value="<?= htmlspecialchars($memberProfile['nama_lengkap']) ?>" required>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="email">Email</label>
                          <input type="email" class="form-control" id="email" name="email" 
                                 value="<?= htmlspecialchars($memberProfile['email']) ?>" required>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="no_telepon">No. Telepon</label>
                          <input type="text" class="form-control" id="no_telepon" name="no_telepon" 
                                 value="<?= htmlspecialchars($memberProfile['no_telepon']) ?>">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="username">Username</label>
                          <input type="text" class="form-control" value="<?= htmlspecialchars($memberProfile['username']) ?>" readonly>
                          <small class="form-text text-muted">Username tidak dapat diubah</small>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="alamat">Alamat</label>
                      <textarea class="form-control" id="alamat" name="alamat" rows="3"><?= htmlspecialchars($memberProfile['alamat']) ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">
                      <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                  </form>
                </div>
              </div>

              <div class="password-form-card">
                <div class="password-form-header">
                  <h4>Ubah Password</h4>
                </div>
                <div class="password-form-body">
                  <form method="POST" action="index.php?controller=member&action=changePassword">
                    <div class="form-group">
                      <label for="old_password">Password Lama</label>
                      <input type="password" class="form-control" id="old_password" name="old_password" required>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="new_password">Password Baru</label>
                          <input type="password" class="form-control" id="new_password" name="new_password" required>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="confirm_password">Konfirmasi Password</label>
                          <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                      </div>
                    </div>
                    <button type="submit" class="btn btn-warning">
                      <i class="fas fa-key"></i> Ubah Password
                    </button>
                  </form>
                </div>
              </div>
            </div>

            <div class="col-lg-4 col-md-12">
              <div class="membership-progress-card">
                <div class="membership-progress-header">
                  <h4>Progress Membership</h4>
                </div>
                <div class="membership-progress-body">
                  <?php if ($nextMembership && $nextMembership['next_membership']): ?>
                    <div class="current-membership">
                      <h5>Level Saat Ini</h5>
                      <div class="membership-current">
                        <span class="membership-badge <?= strtolower($nextMembership['current_membership']) ?>">
                          <?= htmlspecialchars($nextMembership['current_membership']) ?>
                        </span>
                        <div class="membership-benefits">
                          <p><i class="fas fa-percentage"></i> Diskon <?= $nextMembership['current_diskon'] ?>%</p>
                          <p><i class="fas fa-coins"></i> Poin <?= $nextMembership['current_multiplier'] ?>x</p>
                        </div>
                      </div>
                    </div>

                    <div class="next-membership">
                      <h5>Level Selanjutnya</h5>
                      <div class="membership-target">
                        <span class="membership-badge <?= strtolower($nextMembership['next_membership']) ?>">
                          <?= htmlspecialchars($nextMembership['next_membership']) ?>
                        </span>
                        <div class="progress-info">
                          <div class="progress-bar-container">
                            <div class="progress-bar" style="width: <?= ($nextMembership['total_pembelian'] / $nextMembership['next_minimal']) * 100 ?>%"></div>
                          </div>
                          <div class="progress-text">
                            <span>Rp <?= number_format($nextMembership['total_pembelian']) ?></span>
                            <span>Rp <?= number_format($nextMembership['next_minimal']) ?></span>
                          </div>
                          <div class="remaining">
                            <p>Sisa pembelian: <strong>Rp <?= number_format($nextMembership['sisa_pembelian']) ?></strong></p>
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php else: ?>
                    <div class="max-membership">
                      <i class="fas fa-trophy"></i>
                      <h5>Level Tertinggi!</h5>
                      <p>Anda sudah mencapai level membership tertinggi</p>
                    </div>
                  <?php endif; ?>
                </div>
              </div>

              <div class="summary-card">
                <div class="summary-header">
                  <h4>Ringkasan Aktivitas</h4>
                </div>
                <div class="summary-body">
                  <div class="summary-item">
                    <div class="summary-icon">
                      <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="summary-content">
                      <h6><?= number_format($transaksiSummary['total_transaksi']) ?></h6>
                      <span>Total Transaksi</span>
                    </div>
                  </div>
                  <div class="summary-item">
                    <div class="summary-icon">
                      <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="summary-content">
                      <h6>Rp <?= number_format($transaksiSummary['total_spending']) ?></h6>
                      <span>Total Pengeluaran</span>
                    </div>
                  </div>
                  <div class="summary-item">
                    <div class="summary-icon">
                      <i class="fas fa-coins"></i>
                    </div>
                    <div class="summary-content">
                      <h6><?= number_format($transaksiSummary['total_poin_earned']) ?></h6>
                      <span>Poin Terkumpul</span>
                    </div>
                  </div>
                  <div class="summary-item">
                    <div class="summary-icon">
                      <i class="fas fa-piggy-bank"></i>
                    </div>
                    <div class="summary-content">
                      <h6>Rp <?= number_format($transaksiSummary['total_hemat']) ?></h6>
                      <span>Total Hemat</span>
                    </div>
                  </div>
                </div>
              </div>

              <div class="membership-history-card">
                <div class="membership-history-header">
                  <h4>Riwayat Membership</h4>
                </div>
                <div class="membership-history-body">
                  <?php if (!empty($membershipHistory)): ?>
                    <?php foreach ($membershipHistory as $history): ?>
                    <div class="history-item">
                      <div class="history-date"><?= date('d M Y', strtotime($history['tanggal_aktivitas'])) ?></div>
                      <div class="history-content"><?= htmlspecialchars($history['catatan']) ?></div>
                    </div>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <div class="no-history">
                      <p>Belum ada perubahan membership</p>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <style>
    :root {
      --primary-color: #667eea;
      --success-color: #28a745;
      --warning-color: #ffc107;
      --info-color: #17a2b8;
      --light-color: #f8f9fa;
      --border-color: #e9ecef;
      --text-primary: #2c3e50;
      --text-secondary: #6c757d;
      --shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .profile-header {
      background: linear-gradient(135deg, var(--primary-color) 0%, #764ba2 100%);
      border-radius: 16px;
      padding: 2rem;
      color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
    }

    .profile-info {
      display: flex;
      align-items: center;
      gap: 1.5rem;
    }

    .profile-avatar {
      font-size: 4rem;
      opacity: 0.9;
    }

    .profile-name {
      font-size: 2rem;
      font-weight: 700;
      margin-bottom: 0.5rem;
    }

    .profile-email {
      opacity: 0.9;
      margin-bottom: 1rem;
    }

    .membership-info {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .membership-badge {
      padding: 0.4rem 1rem;
      border-radius: 20px;
      font-size: 0.9rem;
      font-weight: 600;
      color: white;
    }

    .membership-badge.bronze { background: #cd7f32; }
    .membership-badge.silver { background: #95a5a6; }
    .membership-badge.gold { background: #f39c12; }
    .membership-badge.platinum { background: #9b59b6; }

    .membership-badge i {
      margin-right: 0.5rem;
    }

    .member-since {
      font-size: 0.9rem;
      opacity: 0.8;
    }

    .profile-stats {
      display: flex;
      gap: 2rem;
    }

    .stat-item {
      text-align: center;
    }

    .stat-item h4 {
      font-size: 1.8rem;
      font-weight: 700;
      margin-bottom: 0.3rem;
    }

    .stat-item span {
      font-size: 0.9rem;
      opacity: 0.9;
    }

    .profile-form-card, .password-form-card, .membership-progress-card, .summary-card, .membership-history-card {
      background: white;
      border-radius: 12px;
      box-shadow: var(--shadow);
      border: 1px solid var(--border-color);
      margin-bottom: 2rem;
    }

    .profile-form-header, .password-form-header, .membership-progress-header, .summary-header, .membership-history-header {
      padding: 1.5rem;
      border-bottom: 1px solid var(--border-color);
    }

    .profile-form-header h4, .password-form-header h4, .membership-progress-header h4, .summary-header h4, .membership-history-header h4 {
      margin: 0;
      font-weight: 600;
      color: var(--text-primary);
    }

    .profile-form-body, .password-form-body, .membership-progress-body, .summary-body, .membership-history-body {
      padding: 1.5rem;
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    .form-group label {
      font-weight: 500;
      color: var(--text-primary);
      margin-bottom: 0.5rem;
    }

    .form-control {
      border-radius: 8px;
      border: 1px solid var(--border-color);
      padding: 0.8rem;
      transition: border-color 0.3s ease;
    }

    .form-control:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .btn {
      border-radius: 8px;
      padding: 0.8rem 1.5rem;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .btn:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow);
    }

    .current-membership, .next-membership {
      margin-bottom: 2rem;
    }

    .current-membership h5, .next-membership h5 {
      color: var(--text-primary);
      margin-bottom: 1rem;
      font-weight: 600;
    }

    .membership-current, .membership-target {
      background: var(--light-color);
      padding: 1rem;
      border-radius: 8px;
    }

    .membership-benefits {
      margin-top: 0.8rem;
    }

    .membership-benefits p {
      margin-bottom: 0.3rem;
      font-size: 0.9rem;
      color: var(--text-secondary);
    }

    .membership-benefits i {
      color: var(--primary-color);
      margin-right: 0.5rem;
    }

    .progress-bar-container {
      height: 8px;
      background: #e9ecef;
      border-radius: 4px;
      margin: 1rem 0;
      overflow: hidden;
    }

    .progress-bar {
      height: 100%;
      background: linear-gradient(90deg, var(--primary-color), #764ba2);
      border-radius: 4px;
      transition: width 0.3s ease;
    }

    .progress-text {
      display: flex;
      justify-content: space-between;
      font-size: 0.9rem;
      color: var(--text-secondary);
    }

    .remaining {
      margin-top: 0.5rem;
      text-align: center;
    }

    .remaining p {
      color: var(--text-secondary);
      margin: 0;
    }

    .max-membership {
      text-align: center;
      padding: 2rem;
    }

    .max-membership i {
      font-size: 3rem;
      color: var(--warning-color);
      margin-bottom: 1rem;
    }

    .max-membership h5 {
      color: var(--text-primary);
      margin-bottom: 0.5rem;
    }

    .summary-item {
      display: flex;
      align-items: center;
      gap: 1rem;
      padding: 1rem;
      background: var(--light-color);
      border-radius: 8px;
      margin-bottom: 1rem;
    }

    .summary-item:last-child {
      margin-bottom: 0;
    }

    .summary-icon {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background: var(--primary-color);
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2rem;
    }

    .summary-content h6 {
      font-size: 1.2rem;
      font-weight: 700;
      color: var(--text-primary);
      margin: 0;
    }

    .summary-content span {
      color: var(--text-secondary);
      font-size: 0.9rem;
    }

    .history-item {
      border-left: 3px solid var(--primary-color);
      padding-left: 1rem;
      margin-bottom: 1rem;
    }

    .history-item:last-child {
      margin-bottom: 0;
    }

    .history-date {
      font-size: 0.8rem;
      color: var(--text-secondary);
      margin-bottom: 0.3rem;
    }

    .history-content {
      font-size: 0.9rem;
      color: var(--text-primary);
    }

    .no-history {
      text-align: center;
      padding: 2rem;
      color: var(--text-secondary);
    }

    @media (max-width: 768px) {
      .profile-header {
        flex-direction: column;
        gap: 2rem;
        text-align: center;
      }

      .profile-info {
        flex-direction: column;
      }

      .profile-stats {
        flex-direction: column;
        gap: 1rem;
        width: 100%;
      }

      .membership-info {
        flex-direction: column;
        gap: 0.5rem;
      }
    }
  </style>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const alerts = document.querySelectorAll('.alert');
      alerts.forEach(alert => {
        setTimeout(() => {
          if (alert && alert.parentNode) {
            alert.classList.remove('show');
            setTimeout(() => {
              alert.remove();
            }, 150);
          }
        }, 5000);
      });

      const confirmPasswordField = document.getElementById('confirm_password');
      const newPasswordField = document.getElementById('new_password');
      
      if (confirmPasswordField && newPasswordField) {
        confirmPasswordField.addEventListener('input', function() {
          if (this.value !== newPasswordField.value) {
            this.setCustomValidity('Password tidak cocok');
          } else {
            this.setCustomValidity('');
          }
        });
      }
    });
  </script>

  <?php include 'template/script.php'; ?>
</body>
</html>