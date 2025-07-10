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
              <div class="member-header">
                <div class="row align-items-center">
                  <div class="col-md-8">
                    <h2 class="member-title">Selamat Datang, <?= htmlspecialchars($memberStats['nama_customer']) ?></h2>
                    <p class="member-subtitle">Member <?= htmlspecialchars($memberStats['nama_membership']) ?> - Station Tahu Sumedang</p>
                  </div>
                  <div class="col-md-4 text-right">
                    <div class="member-info">
                      <div class="membership-badge <?= strtolower($memberStats['nama_membership']) ?>">
                        <i class="fas fa-crown"></i>
                        <?= htmlspecialchars($memberStats['nama_membership']) ?>
                      </div>
                      <span class="last-updated"><?= date('d M Y, H:i') ?></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="row mb-4">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
              <div class="member-card poin-card">
                <div class="card-icon">
                  <i class="fas fa-coins"></i>
                </div>
                <div class="card-content">
                  <h3 class="card-value"><?= number_format($memberStats['total_poin']) ?></h3>
                  <p class="card-label">Total Poin</p>
                  <span class="card-info">Poin/pembelian: <?= $memberStats['poin_per_pembelian'] ?></span>
                </div>
              </div>
            </div>
            
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
              <div class="member-card spending-card">
                <div class="card-icon">
                  <i class="fas fa-shopping-bag"></i>
                </div>
                <div class="card-content">
                  <h3 class="card-value">Rp <?= number_format($memberStats['total_pembelian']) ?></h3>
                  <p class="card-label">Total Pembelian</p>
                  <span class="card-info">Transaksi: <?= count($transaksiHistory) ?></span>
                </div>
              </div>
            </div>
            
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
              <div class="member-card discount-card">
                <div class="card-icon">
                  <i class="fas fa-percent"></i>
                </div>
                <div class="card-content">
                  <h3 class="card-value"><?= $memberStats['diskon_persen'] ?>%</h3>
                  <p class="card-label">Diskon Member</p>
                  <span class="card-info">Hemat: Rp <?= number_format($totalSavings['total_hemat']) ?></span>
                </div>
              </div>
            </div>
            
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
              <div class="member-card savings-card">
                <div class="card-icon">
                  <i class="fas fa-piggy-bank"></i>
                </div>
                <div class="card-content">
                  <h3 class="card-value">Rp <?= number_format($totalSavings['total_hemat']) ?></h3>
                  <p class="card-label">Total Penghematan</p>
                  <span class="card-info">Poin earned: <?= number_format($totalSavings['total_poin_earned']) ?></span>
                </div>
              </div>
            </div>
          </div>
          
          <div class="row mb-4">
            <div class="col-lg-8 col-md-12 mb-4">
              <div class="chart-card">
                <div class="chart-header">
                  <h4 class="chart-title">Pengeluaran Bulanan (12 Bulan Terakhir)</h4>
                </div>
                <div class="chart-body">
                  <canvas id="spendingChart" height="100"></canvas>
                </div>
              </div>
            </div>
            
            <div class="col-lg-4 col-md-12 mb-4">
              <div class="progress-card">
                <div class="progress-header">
                  <h4 class="progress-title">Progress Membership</h4>
                </div>
                <div class="progress-body">
                  <?php if ($nextMembership && $nextMembership['next_membership']): ?>
                    <div class="membership-progress-info">
                      <div class="current-tier">
                        <span class="tier-name"><?= htmlspecialchars($nextMembership['current_membership']) ?></span>
                        <span class="tier-spending">Rp <?= number_format($nextMembership['total_pembelian']) ?></span>
                      </div>
                      <div class="progress-bar-container">
                        <div class="progress-bar-fill" style="width: <?= ($nextMembership['total_pembelian'] / $nextMembership['next_minimal']) * 100 ?>%"></div>
                      </div>
                      <div class="next-tier">
                        <span class="tier-name"><?= htmlspecialchars($nextMembership['next_membership']) ?></span>
                        <span class="tier-target">Target: Rp <?= number_format($nextMembership['next_minimal']) ?></span>
                      </div>
                      <div class="remaining-amount">
                        <p>Sisa pembelian untuk naik tier:</p>
                        <h5>Rp <?= number_format($nextMembership['sisa_pembelian']) ?></h5>
                      </div>
                    </div>
                  <?php else: ?>
                    <div class="max-tier">
                      <i class="fas fa-trophy"></i>
                      <h5>Selamat!</h5>
                      <p>Anda sudah mencapai tier tertinggi</p>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
          
          <div class="row mb-4">
            <div class="col-lg-6 col-md-12 mb-4">
              <div class="history-card">
                <div class="history-header">
                  <h4 class="history-title">Riwayat Transaksi</h4>
                  <a href="index.php?controller=member&action=transaksi" class="view-all-btn">Lihat Semua</a>
                </div>
                <div class="history-body">
                  <?php if (!empty($transaksiHistory)): ?>
                    <?php foreach (array_slice($transaksiHistory, 0, 5) as $transaksi): ?>
                    <div class="history-item">
                      <div class="history-info">
                        <h6>Transaksi #<?= $transaksi['transaksi_id'] ?></h6>
                        <span class="history-date"><?= date('d M Y', strtotime($transaksi['tanggal_transaksi'])) ?></span>
                        <span class="history-items"><?= $transaksi['total_item'] ?> item</span>
                      </div>
                      <div class="history-amount">
                        <span class="amount">Rp <?= number_format($transaksi['total_bayar']) ?></span>
                        <span class="points">+<?= $transaksi['poin_didapat'] ?> poin</span>
                      </div>
                    </div>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <div class="empty-state">
                      <i class="fas fa-shopping-cart"></i>
                      <p>Belum ada transaksi</p>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            
            <div class="col-lg-6 col-md-12 mb-4">
              <div class="favorites-card">
                <div class="favorites-header">
                  <h4 class="favorites-title">Produk Favorit</h4>
                </div>
                <div class="favorites-body">
                  <?php if (!empty($favoriteProducts)): ?>
                    <?php foreach ($favoriteProducts as $index => $product): ?>
                    <div class="favorite-item">
                      <div class="favorite-rank">
                        <?= $index + 1 ?>
                      </div>
                      <div class="favorite-info">
                        <h6><?= htmlspecialchars($product['nama_produk']) ?></h6>
                        <span class="favorite-category"><?= htmlspecialchars($product['kategori']) ?></span>
                        <span class="favorite-price">Rp <?= number_format($product['harga']) ?></span>
                      </div>
                      <div class="favorite-stats">
                        <span class="purchase-count"><?= $product['total_dibeli'] ?>x</span>
                        <span class="frequency"><?= $product['frekuensi_beli'] ?> kali</span>
                      </div>
                    </div>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <div class="empty-state">
                      <i class="fas fa-heart"></i>
                      <p>Belum ada produk favorit</p>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
          
          <div class="row mb-4">
            <div class="col-lg-6 col-md-12 mb-4">
              <div class="recommendations-card">
                <div class="recommendations-header">
                  <h4 class="recommendations-title">Rekomendasi Produk</h4>
                </div>
                <div class="recommendations-body">
                  <?php if (!empty($recommendations)): ?>
                    <?php foreach ($recommendations as $product): ?>
                    <div class="recommendation-item">
                      <div class="recommendation-info">
                        <h6><?= htmlspecialchars($product['nama_produk']) ?></h6>
                        <span class="recommendation-category"><?= htmlspecialchars($product['kategori']) ?></span>
                        <span class="recommendation-price">Rp <?= number_format($product['harga']) ?></span>
                      </div>
                      <div class="recommendation-popularity">
                        <span class="popularity-badge">Popular</span>
                        <span class="popularity-count"><?= $product['popularity'] ?> member suka</span>
                      </div>
                    </div>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <div class="empty-state">
                      <i class="fas fa-lightbulb"></i>
                      <p>Belum ada rekomendasi</p>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            
            <div class="col-lg-6 col-md-12 mb-4">
              <div class="points-card">
                <div class="points-header">
                  <h4 class="points-title">Riwayat Poin</h4>
                  <a href="index.php?controller=member&action=poin" class="view-all-btn">Lihat Semua</a>
                </div>
                <div class="points-body">
                  <?php if (!empty($poinHistory)): ?>
                    <?php foreach (array_slice($poinHistory, 0, 5) as $poin): ?>
                    <div class="point-item">
                      <div class="point-info">
                        <span class="point-date"><?= date('d M Y', strtotime($poin['tanggal_transaksi'])) ?></span>
                        <span class="point-transaction">Transaksi #<?= $poin['transaksi_id'] ?></span>
                      </div>
                      <div class="point-amount">
                        <span class="points-earned">+<?= $poin['poin_didapat'] ?></span>
                        <span class="transaction-amount">Rp <?= number_format($poin['total_bayar']) ?></span>
                      </div>
                    </div>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <div class="empty-state">
                      <i class="fas fa-coins"></i>
                      <p>Belum ada riwayat poin</p>
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
      --member-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      --poin-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
      --spending-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
      --discount-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
      --savings-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
      --card-bg: #ffffff;
      --text-primary: #2c3e50;
      --text-secondary: #7f8c8d;
      --border-color: #e9ecef;
      --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      --bronze: #cd7f32;
      --silver: #95a5a6;
      --gold: #f39c12;
      --platinum: #9b59b6;
    }
    
    .member-header {
      background: var(--member-gradient);
      border-radius: 16px;
      padding: 2rem;
      color: white;
      box-shadow: var(--shadow);
      margin-bottom: 2rem;
    }
    
    .member-title {
      font-size: 2.2rem;
      font-weight: 700;
      margin-bottom: 0.5rem;
    }
    
    .member-subtitle {
      font-size: 1.1rem;
      opacity: 0.9;
      margin-bottom: 0;
    }
    
    .member-info {
      text-align: right;
    }
    
    .membership-badge {
      display: inline-flex;
      align-items: center;
      padding: 0.5rem 1rem;
      border-radius: 20px;
      font-weight: 600;
      margin-bottom: 0.5rem;
      font-size: 0.9rem;
    }
    
    .membership-badge i {
      margin-right: 0.5rem;
    }
    
    .membership-badge.bronze { background: var(--bronze); }
    .membership-badge.silver { background: var(--silver); }
    .membership-badge.gold { background: var(--gold); }
    .membership-badge.platinum { background: var(--platinum); }
    
    .last-updated {
      font-size: 0.8rem;
      opacity: 0.7;
      display: block;
    }
    
    .member-card {
      background: var(--card-bg);
      border-radius: 16px;
      padding: 1.5rem;
      box-shadow: var(--shadow);
      border: 1px solid var(--border-color);
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
    }
    
    .member-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
    }
    
    .card-icon {
      width: 60px;
      height: 60px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      color: white;
      margin-right: 1rem;
      flex-shrink: 0;
    }
    
    .poin-card .card-icon { background: var(--poin-gradient); }
    .spending-card .card-icon { background: var(--spending-gradient); }
    .discount-card .card-icon { background: var(--discount-gradient); }
    .savings-card .card-icon { background: var(--savings-gradient); }
    
    .card-content {
      flex: 1;
    }
    
    .card-value {
      font-size: 1.8rem;
      font-weight: 700;
      color: var(--text-primary);
      margin-bottom: 0.3rem;
    }
    
    .card-label {
      color: var(--text-secondary);
      font-size: 0.9rem;
      margin-bottom: 0.3rem;
    }
    
    .card-info {
      font-size: 0.8rem;
      color: var(--text-secondary);
      font-weight: 500;
    }
    
    .chart-card, .progress-card, .history-card, .favorites-card, .recommendations-card, .points-card {
      background: var(--card-bg);
      border-radius: 16px;
      box-shadow: var(--shadow);
      border: 1px solid var(--border-color);
      height: 100%;
    }
    
    .chart-header, .progress-header, .history-header, .favorites-header, .recommendations-header, .points-header {
      padding: 1.5rem 1.5rem 1rem;
      border-bottom: 1px solid var(--border-color);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    
    .chart-title, .progress-title, .history-title, .favorites-title, .recommendations-title, .points-title {
      font-size: 1.2rem;
      font-weight: 600;
      color: var(--text-primary);
      margin-bottom: 0;
    }
    
    .view-all-btn {
      font-size: 0.9rem;
      color: #667eea;
      text-decoration: none;
      font-weight: 500;
    }
    
    .view-all-btn:hover {
      color: #764ba2;
      text-decoration: none;
    }
    
    .chart-body {
      padding: 1.5rem;
      height: 300px;
      position: relative;
    }
    
    .progress-body {
      padding: 1.5rem;
    }
    
    .membership-progress-info {
      text-align: center;
    }
    
    .current-tier, .next-tier {
      display: flex;
      justify-content: space-between;
      margin-bottom: 0.5rem;
    }
    
    .tier-name {
      font-weight: 600;
      color: var(--text-primary);
    }
    
    .tier-spending, .tier-target {
      font-size: 0.9rem;
      color: var(--text-secondary);
    }
    
    .progress-bar-container {
      height: 8px;
      background: #e9ecef;
      border-radius: 4px;
      margin: 1rem 0;
      overflow: hidden;
    }
    
    .progress-bar-fill {
      height: 100%;
      background: var(--member-gradient);
      border-radius: 4px;
      transition: width 0.3s ease;
    }
    
    .remaining-amount {
      margin-top: 1rem;
      padding: 1rem;
      background: #f8f9fa;
      border-radius: 8px;
    }
    
    .remaining-amount p {
      margin-bottom: 0.5rem;
      color: var(--text-secondary);
      font-size: 0.9rem;
    }
    
    .remaining-amount h5 {
      color: var(--text-primary);
      font-weight: 700;
      margin-bottom: 0;
    }
    
    .max-tier {
      text-align: center;
      padding: 2rem;
    }
    
    .max-tier i {
      font-size: 3rem;
      color: var(--gold);
      margin-bottom: 1rem;
    }
    
    .max-tier h5 {
      color: var(--text-primary);
      margin-bottom: 0.5rem;
    }
    
    .history-body, .favorites-body, .recommendations-body, .points-body {
      padding: 1.5rem;
      max-height: 400px;
      overflow-y: auto;
    }
    
    .history-item, .favorite-item, .recommendation-item, .point-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1rem;
      border-radius: 8px;
      background: #f8f9fa;
      margin-bottom: 1rem;
    }
    
    .history-item:last-child, .favorite-item:last-child, .recommendation-item:last-child, .point-item:last-child {
      margin-bottom: 0;
    }
    
    .history-info h6, .favorite-info h6, .recommendation-info h6 {
      font-size: 1rem;
      font-weight: 600;
      color: var(--text-primary);
      margin-bottom: 0.3rem;
    }
    
    .history-date, .history-items, .favorite-category, .favorite-price, .recommendation-category, .recommendation-price {
      font-size: 0.8rem;
      color: var(--text-secondary);
      margin-right: 0.5rem;
    }
    
    .history-amount, .favorite-stats, .recommendation-popularity, .point-amount {
      text-align: right;
    }
    
    .amount, .points-earned {
      font-weight: 600;
      color: var(--text-primary);
      display: block;
    }
    
    .points, .frequency, .popularity-count, .transaction-amount {
      font-size: 0.8rem;
      color: var(--text-secondary);
    }
    
    .favorite-rank {
      width: 30px;
      height: 30px;
      border-radius: 50%;
      background: var(--member-gradient);
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      margin-right: 1rem;
      flex-shrink: 0;
    }
    
    .favorite-info {
      flex: 1;
    }
    
    .purchase-count {
      font-weight: 600;
      color: var(--text-primary);
      display: block;
    }
    
    .popularity-badge {
      background: var(--poin-gradient);
      color: white;
      padding: 0.2rem 0.5rem;
      border-radius: 12px;
      font-size: 0.7rem;
      font-weight: 600;
      display: block;
      margin-bottom: 0.2rem;
    }
    
    .point-date, .point-transaction {
      font-size: 0.8rem;
      color: var(--text-secondary);
      display: block;
    }
    
    .point-transaction {
      font-weight: 500;
      color: var(--text-primary);
    }
    
    .empty-state {
      text-align: center;
      padding: 2rem;
      color: var(--text-secondary);
    }
    
    .empty-state i {
      font-size: 2rem;
      margin-bottom: 0.5rem;
      opacity: 0.5;
    }
    
    @media (max-width: 768px) {
      .member-title {
        font-size: 1.8rem;
      }
      
      .member-header {
        padding: 1.5rem;
      }
      
      .card-value {
        font-size: 1.5rem;
      }
      
      .member-info {
        text-align: left;
        margin-top: 1rem;
      }
    }
    
    @media (max-width: 576px) {
      .member-card {
        flex-direction: column;
        text-align: center;
      }
      
      .card-icon {
        margin-right: 0;
        margin-bottom: 1rem;
      }
      
      .history-item, .favorite-item, .recommendation-item, .point-item {
        flex-direction: column;
        text-align: center;
      }
      
      .history-amount, .favorite-stats, .recommendation-popularity, .point-amount {
        margin-top: 0.5rem;
        text-align: center;
      }
    }
  </style>
  
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const ctx = document.getElementById('spendingChart').getContext('2d');
      
      const monthlyData = <?= json_encode($monthlySpending) ?>;
      const labels = monthlyData.map(item => item.month);
      const data = monthlyData.map(item => item.spending);
      
      new Chart(ctx, {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [{
            label: 'Pengeluaran',
            data: data,
            backgroundColor: 'rgba(102, 126, 234, 0.8)',
            borderColor: 'rgb(102, 126, 234)',
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
                  return 'Rp ' + value.toLocaleString();
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