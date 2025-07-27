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
              <div class="executive-header">
                <div class="row align-items-center">
                  <div class="col-md-8">
                    <h2 class="executive-title">Executive Summary</h2>
                    <p class="executive-subtitle">Business Overview - Station Tahu Sumedang</p>
                  </div>
                  <div class="col-md-4 text-right">
                    <div class="executive-info">
                      <span class="executive-role">Pimpinan</span>
                      <h5 class="executive-name"><?= htmlspecialchars($user['nama_lengkap']) ?></h5>
                      <span class="last-updated"><?= date('d M Y, H:i') ?></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="row mb-4">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
              <div class="summary-card revenue-card">
                <div class="card-icon">
                  <i class="fas fa-chart-line"></i>
                </div>
                <div class="card-content">
                  <h3 class="card-value">Rp <?= number_format($stats['pendapatan_hari_ini']) ?></h3>
                  <p class="card-label">Pendapatan Hari Ini</p>
                  <span class="card-trend up">+15.2%</span>
                </div>
              </div>
            </div>
            
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
              <div class="summary-card monthly-card">
                <div class="card-icon">
                  <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="card-content">
                  <h3 class="card-value">Rp <?= number_format($stats['pendapatan_bulan_ini']) ?></h3>
                  <p class="card-label">Pendapatan Bulan Ini</p>
                  <span class="card-trend up">+8.7%</span>
                </div>
              </div>
            </div>
            
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
              <div class="summary-card customers-card">
                <div class="card-icon">
                  <i class="fas fa-users"></i>
                </div>
                <div class="card-content">
                  <h3 class="card-value"><?= number_format($stats['customer_aktif']) ?></h3>
                  <p class="card-label">Customer Aktif</p>
                  <span class="card-trend up">+12.3%</span>
                </div>
              </div>
            </div>
            
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
              <div class="summary-card premium-card">
                <div class="card-icon">
                  <i class="fas fa-crown"></i>
                </div>
                <div class="card-content">
                  <h3 class="card-value"><?= number_format($stats['member_premium']) ?></h3>
                  <p class="card-label">Member Premium</p>
                  <span class="card-trend up">+5.1%</span>
                </div>
              </div>
            </div>
          </div>
          
          <div class="row mb-4">
            <div class="col-lg-8 col-md-12 mb-4">
              <div class="chart-card">
                <div class="chart-header">
                  <h4 class="chart-title">Tren Penjualan (7 Hari)</h4>
                </div>
                <div class="chart-body">
                  <canvas id="salesChart" height="100"></canvas>
                </div>
              </div>
            </div>
            
            <div class="col-lg-4 col-md-12 mb-4">
              <div class="products-card">
                <div class="products-header">
                  <h4 class="products-title">Top 3 Produk</h4>
                </div>
                <div class="products-body">
                  <?php if (!empty($topProducts)): ?>
                    <?php foreach (array_slice($topProducts, 0, 3) as $index => $product): ?>
                    <div class="product-item">
                      <div class="product-rank rank-<?= $index + 1 ?>">
                        <?= $index + 1 ?>
                      </div>
                      <div class="product-info">
                        <h6><?= htmlspecialchars($product['nama_produk']) ?></h6>
                        <span class="product-sales"><?= number_format($product['total_terjual']) ?> terjual</span>
                      </div>
                    </div>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <div class="empty-state">
                      <p>Tidak ada data produk</p>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
          
          <div class="row mb-4">
            <div class="col-lg-6 col-md-12 mb-4">
              <div class="membership-card">
                <div class="membership-header">
                  <h4 class="membership-title">Distribusi Membership</h4>
                </div>
                <div class="membership-body">
                  <?php if (!empty($membershipStats)): ?>
                    <?php foreach ($membershipStats as $stat): ?>
                    <div class="membership-item">
                      <div class="membership-info">
                        <span class="membership-name"><?= htmlspecialchars($stat['nama_membership']) ?></span>
                        <span class="membership-count"><?= number_format($stat['total_member']) ?></span>
                      </div>
                      <div class="membership-progress">
                        <div class="progress-bar tier-<?= strtolower($stat['nama_membership']) ?>" 
                             style="width: <?= $stat['percentage'] ?>%"></div>
                      </div>
                      <span class="membership-percentage"><?= $stat['percentage'] ?>%</span>
                    </div>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <div class="empty-state">
                      <p>Tidak ada data membership</p>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            
            <div class="col-lg-6 col-md-12 mb-4">
              <div class="quick-reports-card">
                <div class="quick-reports-header">
                  <h4 class="quick-reports-title">Laporan Cepat</h4>
                </div>
                <div class="quick-reports-body">
                  <div class="report-grid">
                    <a href="index.php?controller=laporan&action=penjualan" class="report-btn sales-btn">
                      <i class="fas fa-chart-bar"></i>
                      <span>Laporan Penjualan</span>
                    </a>
                    <a href="index.php?controller=laporan&action=customer" class="report-btn customer-btn">
                      <i class="fas fa-users"></i>
                      <span>Laporan Customer</span>
                    </a>
                    <a href="index.php?controller=laporan&action=keuangan" class="report-btn financial-btn">
                      <i class="fas fa-money-bill-wave"></i>
                      <span>Laporan Keuangan</span>
                    </a>
                    <a href="index.php?controller=laporan&action=produk" class="report-btn product-btn">
                      <i class="fas fa-box"></i>
                      <span>Laporan Produk</span>
                    </a>
                  </div>
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
      --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      --revenue-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
      --monthly-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
      --customers-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
      --premium-gradient: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
      --card-bg: #ffffff;
      --text-primary: #2c3e50;
      --text-secondary: #7f8c8d;
      --border-color: #e9ecef;
      --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .executive-header {
      background: var(--primary-gradient);
      border-radius: 16px;
      padding: 2rem;
      color: white;
      box-shadow: var(--shadow);
      margin-bottom: 2rem;
    }
    
    .executive-title {
      font-size: 2.2rem;
      font-weight: 700;
      margin-bottom: 0.5rem;
    }
    
    .executive-subtitle {
      font-size: 1.1rem;
      opacity: 0.9;
      margin-bottom: 0;
    }
    
    .executive-info {
      text-align: right;
    }
    
    .executive-role {
      font-size: 0.9rem;
      opacity: 0.8;
      display: block;
    }
    
    .executive-name {
      font-size: 1.3rem;
      font-weight: 600;
      margin-bottom: 0.3rem;
    }
    
    .last-updated {
      font-size: 0.8rem;
      opacity: 0.7;
    }
    
    .summary-card {
      background: var(--card-bg);
      border-radius: 16px;
      padding: 1.5rem;
      box-shadow: var(--shadow);
      border: 1px solid var(--border-color);
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
    }
    
    .summary-card:hover {
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
    
    .revenue-card .card-icon {
      background: var(--revenue-gradient);
    }
    
    .monthly-card .card-icon {
      background: var(--monthly-gradient);
    }
    
    .customers-card .card-icon {
      background: var(--customers-gradient);
    }
    
    .premium-card .card-icon {
      background: var(--premium-gradient);
    }
    
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
      margin-bottom: 0.5rem;
    }
    
    .card-trend {
      font-size: 0.8rem;
      font-weight: 600;
      padding: 0.2rem 0.5rem;
      border-radius: 12px;
      background: #e8f5e8;
      color: #27ae60;
    }
    
    .chart-card, .products-card, .membership-card, .quick-reports-card {
      background: var(--card-bg);
      border-radius: 16px;
      box-shadow: var(--shadow);
      border: 1px solid var(--border-color);
    }
    
    .chart-header, .products-header, .membership-header, .quick-reports-header {
      padding: 1.5rem 1.5rem 1rem;
      border-bottom: 1px solid var(--border-color);
    }
    
    .chart-title, .products-title, .membership-title, .quick-reports-title {
      font-size: 1.2rem;
      font-weight: 600;
      color: var(--text-primary);
      margin-bottom: 0;
    }
    
    .chart-body, .products-body, .membership-body, .quick-reports-body {
      padding: 1.5rem;
    }
    
    .chart-body {
      height: 300px;
      position: relative;
    }
    
    .product-item {
      display: flex;
      align-items: center;
      padding: 1rem;
      border-radius: 8px;
      background: #f8f9fa;
      margin-bottom: 1rem;
    }
    
    .product-rank {
      width: 30px;
      height: 30px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      color: white;
      margin-right: 1rem;
      font-size: 0.9rem;
    }
    
    .product-rank.rank-1 { background: #f39c12; }
    .product-rank.rank-2 { background: #95a5a6; }
    .product-rank.rank-3 { background: #cd7f32; }
    
    .product-info h6 {
      font-size: 1rem;
      font-weight: 600;
      color: var(--text-primary);
      margin-bottom: 0.3rem;
    }
    
    .product-sales {
      font-size: 0.8rem;
      color: var(--text-secondary);
    }
    
    .membership-item {
      display: flex;
      align-items: center;
      margin-bottom: 1rem;
    }
    
    .membership-info {
      flex: 1;
      margin-right: 1rem;
    }
    
    .membership-name {
      font-weight: 600;
      color: var(--text-primary);
      display: block;
      margin-bottom: 0.2rem;
    }
    
    .membership-count {
      font-size: 0.8rem;
      color: var(--text-secondary);
    }
    
    .membership-progress {
      flex: 2;
      height: 6px;
      background: #e9ecef;
      border-radius: 3px;
      margin-right: 1rem;
    }
    
    .membership-progress .progress-bar {
      height: 100%;
      border-radius: 3px;
      transition: width 0.3s ease;
    }
    
    .tier-bronze { background: #cd7f32; }
    .tier-silver { background: #95a5a6; }
    .tier-gold { background: #f39c12; }
    .tier-platinum { background: #9b59b6; }
    
    .membership-percentage {
      font-weight: 600;
      color: var(--text-primary);
      font-size: 0.9rem;
      min-width: 35px;
    }
    
    .report-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
    }
    
    .report-btn {
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 1rem;
      border-radius: 12px;
      text-decoration: none;
      color: white;
      transition: all 0.3s ease;
      font-weight: 500;
    }
    
    .report-btn:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow);
      color: white;
      text-decoration: none;
    }
    
    .sales-btn { background: var(--revenue-gradient); }
    .customer-btn { background: var(--customers-gradient); }
    .financial-btn { background: var(--premium-gradient); }
    .product-btn { background: var(--monthly-gradient); }
    
    .report-btn i {
      font-size: 1.5rem;
      margin-bottom: 0.5rem;
    }
    
    .report-btn span {
      font-size: 0.9rem;
      text-align: center;
    }
    
    .empty-state {
      text-align: center;
      padding: 2rem;
      color: var(--text-secondary);
    }
    
    @media (max-width: 768px) {
      .executive-title {
        font-size: 1.8rem;
      }
      
      .executive-header {
        padding: 1.5rem;
      }
      
      .card-value {
        font-size: 1.5rem;
      }
      
      .report-grid {
        grid-template-columns: 1fr;
      }
    }
    
    @media (max-width: 576px) {
      .executive-info {
        text-align: left;
        margin-top: 1rem;
      }
      
      .summary-card {
        flex-direction: column;
        text-align: center;
      }
      
      .card-icon {
        margin-right: 0;
        margin-bottom: 1rem;
      }
    }
  </style>
  
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const ctx = document.getElementById('salesChart').getContext('2d');
      
      const chartData = <?= json_encode($salesChart) ?>;
      const labels = chartData.map(item => item.day);
      const data = chartData.map(item => item.total);
      
      new Chart(ctx, {
        type: 'line',
        data: {
          labels: labels,
          datasets: [{
            label: 'Penjualan',
            data: data,
            borderColor: 'rgb(102, 126, 234)',
            backgroundColor: 'rgba(102, 126, 234, 0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4
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