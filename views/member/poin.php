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
              <div class="poin-header">
                <div class="header-content">
                  <div class="poin-icon">
                    <i class="fas fa-coins"></i>
                  </div>
                  <div class="poin-info">
                    <h2 class="poin-title">Poin Saya</h2>
                    <h1 class="poin-total"><?= number_format($memberProfile['total_poin']) ?></h1>
                    <p class="poin-subtitle">Poin yang dapat digunakan</p>
                  </div>
                </div>
                <div class="membership-info">
                  <span class="membership-badge <?= strtolower($memberProfile['nama_membership']) ?>">
                    <i class="fas fa-crown"></i>
                    <?= htmlspecialchars($memberProfile['nama_membership']) ?>
                  </span>
                  <p class="multiplier-info">Poin multiplier: <?= $memberProfile['poin_per_pembelian'] ?>x</p>
                </div>
              </div>
            </div>
          </div>

          <div class="row mb-4">
            <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
              <div class="poin-stats-card earned">
                <div class="stats-icon">
                  <i class="fas fa-plus-circle"></i>
                </div>
                <div class="stats-content">
                  <h4><?= number_format($poinStats['total_poin_earned']) ?></h4>
                  <span>Total Poin Earned</span>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
              <div class="poin-stats-card transactions">
                <div class="stats-icon">
                  <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stats-content">
                  <h4><?= number_format($poinStats['total_transaksi_with_poin']) ?></h4>
                  <span>Transaksi dengan Poin</span>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
              <div class="poin-stats-card average">
                <div class="stats-icon">
                  <i class="fas fa-chart-line"></i>
                </div>
                <div class="stats-content">
                  <h4><?= number_format($poinStats['rata_poin_per_transaksi']) ?></h4>
                  <span>Rata-rata Poin/Transaksi</span>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
              <div class="poin-stats-card current">
                <div class="stats-icon">
                  <i class="fas fa-wallet"></i>
                </div>
                <div class="stats-content">
                  <h4><?= number_format($memberProfile['total_poin']) ?></h4>
                  <span>Poin Tersedia</span>
                </div>
              </div>
            </div>
          </div>

          <div class="row mb-4">
            <div class="col-lg-8 col-md-12">
              <div class="chart-card">
                <div class="chart-header">
                  <h4>Tren Perolehan Poin (6 Bulan)</h4>
                </div>
                <div class="chart-body">
                  <canvas id="poinChart" height="100"></canvas>
                </div>
              </div>
            </div>

            <div class="col-lg-4 col-md-12">
              <?php if ($nextMembership && $nextMembership['next_membership']): ?>
              <div class="upgrade-card">
                <div class="upgrade-header">
                  <h4>Upgrade Membership</h4>
                </div>
                <div class="upgrade-body">
                  <div class="current-level">
                    <span class="level-badge <?= strtolower($nextMembership['current_membership']) ?>">
                      <?= htmlspecialchars($nextMembership['current_membership']) ?>
                    </span>
                    <div class="level-benefits">
                      <p><i class="fas fa-percentage"></i> Diskon <?= $nextMembership['current_diskon'] ?>%</p>
                      <p><i class="fas fa-coins"></i> Poin <?= $nextMembership['current_multiplier'] ?>x</p>
                    </div>
                  </div>
                  
                  <div class="upgrade-arrow">
                    <i class="fas fa-arrow-down"></i>
                  </div>
                  
                  <div class="next-level">
                    <span class="level-badge <?= strtolower($nextMembership['next_membership']) ?>">
                      <?= htmlspecialchars($nextMembership['next_membership']) ?>
                    </span>
                    <div class="level-benefits">
                      <p><i class="fas fa-percentage"></i> Diskon <?= $nextMembership['next_diskon'] ?>%</p>
                      <p><i class="fas fa-coins"></i> Poin <?= $nextMembership['next_multiplier'] ?>x</p>
                    </div>
                  </div>
                  
                  <div class="progress-section">
                    <div class="progress-bar-container">
                      <div class="progress-bar" style="width: <?= ($nextMembership['total_pembelian'] / $nextMembership['next_minimal']) * 100 ?>%"></div>
                    </div>
                    <div class="progress-text">
                      <span>Rp <?= number_format($nextMembership['total_pembelian']) ?></span>
                      <span>Rp <?= number_format($nextMembership['next_minimal']) ?></span>
                    </div>
                    <div class="remaining-amount">
                      <p>Sisa belanja untuk upgrade:</p>
                      <h5>Rp <?= number_format($nextMembership['sisa_pembelian']) ?></h5>
                    </div>
                  </div>
                </div>
              </div>
              <?php else: ?>
              <div class="max-level-card">
                <div class="max-level-body">
                  <i class="fas fa-trophy"></i>
                  <h4>Level Tertinggi!</h4>
                  <p>Anda sudah mencapai level membership tertinggi dengan benefit maksimal</p>
                </div>
              </div>
              <?php endif; ?>
            </div>
          </div>

          <div class="row">
            <div class="col-12">
              <div class="poin-history-card">
                <div class="poin-history-header">
                  <h4>Riwayat Poin</h4>
                  <div class="filter-section">
                    <form method="GET" action="index.php" class="filter-form">
                      <input type="hidden" name="controller" value="member">
                      <input type="hidden" name="action" value="poin">
                      <div class="form-row">
                        <div class="form-group">
                          <input type="date" class="form-control form-control-sm" name="start_date" 
                                 value="<?= htmlspecialchars($startDate) ?>" placeholder="Tanggal Mulai">
                        </div>
                        <div class="form-group">
                          <input type="date" class="form-control form-control-sm" name="end_date" 
                                 value="<?= htmlspecialchars($endDate) ?>" placeholder="Tanggal Akhir">
                        </div>
                        <div class="form-group">
                          <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-filter"></i> Filter
                          </button>
                        </div>
                        <div class="form-group">
                          <a href="index.php?controller=member&action=poin" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-refresh"></i> Reset
                          </a>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
                <div class="poin-history-body">
                  <?php if (!empty($poinHistory)): ?>
                    <div class="poin-timeline">
                      <?php foreach ($poinHistory as $history): ?>
                      <div class="timeline-item">
                        <div class="timeline-marker">
                          <i class="fas fa-plus"></i>
                        </div>
                        <div class="timeline-content">
                          <div class="timeline-header">
                            <h6 class="timeline-title">Poin Earned</h6>
                            <span class="timeline-date"><?= date('d M Y, H:i', strtotime($history['tanggal_transaksi'])) ?></span>
                          </div>
                          <div class="timeline-body">
                            <p class="timeline-description"><?= htmlspecialchars($history['keterangan']) ?></p>
                            <div class="timeline-details">
                              <span class="transaction-link">
                                <a href="index.php?controller=member&action=transaksiDetail&id=<?= $history['transaksi_id'] ?>">
                                  Transaksi #<?= $history['transaksi_id'] ?>
                                </a>
                              </span>
                              <span class="transaction-amount">Rp <?= number_format($history['total_bayar']) ?></span>
                            </div>
                          </div>
                          <div class="timeline-points">
                            <span class="points-badge">+<?= number_format($history['poin_didapat']) ?> poin</span>
                          </div>
                        </div>
                      </div>
                      <?php endforeach; ?>
                    </div>

                    <?php if ($hasMore): ?>
                    <div class="load-more-section">
                      <a href="?controller=member&action=poin&page=<?= $currentPage + 1 ?><?= $startDate ? '&start_date=' . $startDate : '' ?><?= $endDate ? '&end_date=' . $endDate : '' ?>" 
                         class="btn btn-outline-primary">
                        <i class="fas fa-chevron-down"></i> Muat Lebih Banyak
                      </a>
                    </div>
                    <?php endif; ?>

                  <?php else: ?>
                    <div class="empty-state">
                      <i class="fas fa-coins"></i>
                      <h5>Belum Ada Riwayat Poin</h5>
                      <?php if (!empty($startDate) || !empty($endDate)): ?>
                        <p>Tidak ditemukan riwayat poin pada periode yang dipilih</p>
                        <a href="index.php?controller=member&action=poin" class="btn btn-primary">Reset Filter</a>
                      <?php else: ?>
                        <p>Mulai berbelanja untuk mendapatkan poin</p>
                        <a href="index.php?controller=pembelian" class="btn btn-primary">Mulai Berbelanja</a>
                      <?php endif; ?>
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
      --poin-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .poin-header {
      background: linear-gradient(135deg, var(--warning-color), #ff8c00);
      border-radius: 16px;
      padding: 2.5rem;
      color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
      box-shadow: var(--shadow);
    }

    .header-content {
      display: flex;
      align-items: center;
      gap: 2rem;
    }

    .poin-icon {
      font-size: 4rem;
      opacity: 0.9;
    }

    .poin-title {
      font-size: 1.5rem;
      font-weight: 600;
      margin-bottom: 0.5rem;
      opacity: 0.9;
    }

    .poin-total {
      font-size: 3.5rem;
      font-weight: 800;
      margin-bottom: 0.3rem;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }

    .poin-subtitle {
      opacity: 0.8;
      margin-bottom: 0;
    }

    .membership-info {
      text-align: right;
    }

    .membership-badge {
      padding: 0.5rem 1.2rem;
      border-radius: 25px;
      font-size: 1rem;
      font-weight: 600;
      color: white;
      display: inline-block;
      margin-bottom: 0.8rem;
    }

    .membership-badge.bronze { background: #cd7f32; }
    .membership-badge.silver { background: #95a5a6; }
    .membership-badge.gold { background: #f39c12; }
    .membership-badge.platinum { background: #9b59b6; }

    .membership-badge i {
      margin-right: 0.5rem;
    }

    .multiplier-info {
      opacity: 0.9;
      margin-bottom: 0;
      font-size: 1rem;
    }

    .poin-stats-card {
      background: white;
      border-radius: 12px;
      padding: 1.5rem;
      box-shadow: var(--shadow);
      border: 1px solid var(--border-color);
      display: flex;
      align-items: center;
      gap: 1rem;
      transition: transform 0.3s ease;
    }

    .poin-stats-card:hover {
      transform: translateY(-3px);
    }

    .poin-stats-card .stats-icon {
      width: 60px;
      height: 60px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      color: white;
    }

    .earned .stats-icon { background: var(--success-color); }
    .transactions .stats-icon { background: var(--primary-color); }
    .average .stats-icon { background: var(--info-color); }
    .current .stats-icon { background: var(--warning-color); }

    .poin-stats-card .stats-content h4 {
      font-size: 1.8rem;
      font-weight: 700;
      color: var(--text-primary);
      margin-bottom: 0.3rem;
    }

    .poin-stats-card .stats-content span {
      color: var(--text-secondary);
      font-size: 0.9rem;
    }

    .chart-card, .upgrade-card, .max-level-card, .poin-history-card {
      background: white;
      border-radius: 12px;
      box-shadow: var(--shadow);
      border: 1px solid var(--border-color);
      margin-bottom: 2rem;
    }

    .chart-header, .upgrade-header, .poin-history-header {
      padding: 1.5rem;
      border-bottom: 1px solid var(--border-color);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .chart-header h4, .upgrade-header h4, .poin-history-header h4 {
      margin: 0;
      font-weight: 600;
      color: var(--text-primary);
    }

    .chart-body, .upgrade-body, .max-level-body, .poin-history-body {
      padding: 1.5rem;
    }

    .chart-body {
      height: 300px;
      position: relative;
    }

    .current-level, .next-level {
      text-align: center;
      margin-bottom: 1.5rem;
    }

    .level-badge {
      padding: 0.5rem 1rem;
      border-radius: 20px;
      font-size: 1rem;
      font-weight: 600;
      color: white;
      display: inline-block;
      margin-bottom: 0.8rem;
    }

    .level-badge.bronze { background: #cd7f32; }
    .level-badge.silver { background: #95a5a6; }
    .level-badge.gold { background: #f39c12; }
    .level-badge.platinum { background: #9b59b6; }

    .level-benefits p {
      margin-bottom: 0.3rem;
      color: var(--text-secondary);
      font-size: 0.9rem;
    }

    .level-benefits i {
      color: var(--primary-color);
      margin-right: 0.5rem;
    }

    .upgrade-arrow {
      text-align: center;
      font-size: 2rem;
      color: var(--primary-color);
      margin: 1rem 0;
    }

    .progress-section {
      margin-top: 2rem;
    }

    .progress-bar-container {
      height: 10px;
      background: #e9ecef;
      border-radius: 5px;
      margin: 1rem 0;
      overflow: hidden;
    }

    .progress-bar {
      height: 100%;
      background: linear-gradient(90deg, var(--primary-color), #764ba2);
      border-radius: 5px;
      transition: width 0.3s ease;
    }

    .progress-text {
      display: flex;
      justify-content: space-between;
      font-size: 0.9rem;
      color: var(--text-secondary);
      margin-bottom: 1rem;
    }

    .remaining-amount {
      text-align: center;
      padding: 1rem;
      background: var(--light-color);
      border-radius: 8px;
    }

    .remaining-amount p {
      color: var(--text-secondary);
      margin-bottom: 0.5rem;
    }

    .remaining-amount h5 {
      color: var(--primary-color);
      font-weight: 700;
      margin: 0;
    }

    .max-level-body {
      text-align: center;
      padding: 3rem 2rem;
    }

    .max-level-body i {
      font-size: 4rem;
      color: var(--warning-color);
      margin-bottom: 1rem;
    }

    .max-level-body h4 {
      color: var(--text-primary);
      margin-bottom: 1rem;
    }

    .filter-section {
      display: flex;
      align-items: center;
    }

    .filter-form {
      display: flex;
      gap: 0.5rem;
      align-items: center;
    }

    .filter-form .form-group {
      margin-bottom: 0;
    }

    .filter-form .form-control {
      width: auto;
      min-width: 140px;
    }

    .poin-timeline {
      position: relative;
    }

    .poin-timeline::before {
      content: '';
      position: absolute;
      left: 20px;
      top: 0;
      bottom: 0;
      width: 2px;
      background: var(--border-color);
    }

    .timeline-item {
      position: relative;
      margin-bottom: 2rem;
      padding-left: 4rem;
    }

    .timeline-item:last-child {
      margin-bottom: 0;
    }

    .timeline-marker {
      position: absolute;
      left: 0;
      top: 0;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: var(--success-color);
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1rem;
      z-index: 1;
    }

    .timeline-content {
      background: white;
      border: 1px solid var(--border-color);
      border-radius: 8px;
      padding: 1.5rem;
      position: relative;
    }

    .timeline-content::before {
      content: '';
      position: absolute;
      left: -8px;
      top: 15px;
      width: 0;
      height: 0;
      border-top: 8px solid transparent;
      border-bottom: 8px solid transparent;
      border-right: 8px solid var(--border-color);
    }

    .timeline-content::after {
      content: '';
      position: absolute;
      left: -7px;
      top: 15px;
      width: 0;
      height: 0;
      border-top: 8px solid transparent;
      border-bottom: 8px solid transparent;
      border-right: 8px solid white;
    }

    .timeline-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1rem;
    }

    .timeline-title {
      font-weight: 600;
      color: var(--text-primary);
      margin: 0;
    }

    .timeline-date {
      color: var(--text-secondary);
      font-size: 0.9rem;
    }

    .timeline-description {
      color: var(--text-secondary);
      margin-bottom: 1rem;
    }

    .timeline-details {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1rem;
    }

    .transaction-link a {
      color: var(--primary-color);
      text-decoration: none;
      font-weight: 500;
    }

    .transaction-link a:hover {
      text-decoration: underline;
    }

    .transaction-amount {
      color: var(--text-secondary);
      font-size: 0.9rem;
    }

    .timeline-points {
      text-align: right;
    }

    .points-badge {
      background: var(--warning-color);
      color: white;
      padding: 0.4rem 0.8rem;
      border-radius: 15px;
      font-weight: 600;
      font-size: 0.9rem;
    }

    .load-more-section {
      text-align: center;
      margin-top: 2rem;
      padding-top: 2rem;
      border-top: 1px solid var(--border-color);
    }

    .empty-state {
      text-align: center;
      padding: 4rem 2rem;
      color: var(--text-secondary);
    }

    .empty-state i {
      font-size: 4rem;
      margin-bottom: 1rem;
      opacity: 0.5;
    }

    .empty-state h5 {
      color: var(--text-primary);
      margin-bottom: 1rem;
    }

    .empty-state p {
      margin-bottom: 2rem;
    }

    @media (max-width: 768px) {
      .poin-header {
        flex-direction: column;
        gap: 2rem;
        text-align: center;
      }

      .header-content {
        flex-direction: column;
        gap: 1rem;
      }

      .poin-total {
        font-size: 2.5rem;
      }

      .membership-info {
        text-align: center;
      }

      .poin-stats-card {
        flex-direction: column;
        text-align: center;
      }

      .poin-stats-card .stats-icon {
        margin-bottom: 1rem;
      }

      .filter-form {
        flex-direction: column;
        gap: 1rem;
      }

      .filter-form .form-control {
        width: 100%;
      }

      .timeline-item {
        padding-left: 3rem;
      }

      .timeline-marker {
        width: 30px;
        height: 30px;
        font-size: 0.8rem;
      }

      .poin-timeline::before {
        left: 15px;
      }

      .timeline-details {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
      }
    }
  </style>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const ctx = document.getElementById('poinChart').getContext('2d');
      const monthlyData = <?= json_encode($monthlyStats) ?>;
      
      const labels = monthlyData.map(item => item.month);
      const data = monthlyData.map(item => item.total_poin);
      
      new Chart(ctx, {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [{
            label: 'Poin Earned',
            data: data,
            backgroundColor: 'rgba(255, 193, 7, 0.8)',
            borderColor: '#ffc107',
            borderWidth: 1,
            borderRadius: 4
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                callback: function(value) {
                  return value + ' poin';
                }
              }
            }
          }
        }
      });
    });
  </script>

  <?php include 'template/script.php'; ?>
</body>
</html>