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
                    <h2 class="executive-title">Manager Dashboard</h2>
                    <p class="executive-subtitle">Kelola Produk & Penjualan - Station Tahu Sumedang</p>
                  </div>
                  <div class="col-md-4 text-right">
                    <div class="executive-info">
                      <span class="executive-role">Manager</span>
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
              <div class="summary-card products-card">
                <div class="card-icon">
                  <i class="fas fa-box"></i>
                </div>
                <div class="card-content">
                  <h3 class="card-value"><?= number_format($stats['total_produk']) ?></h3>
                  <p class="card-label">Total Produk</p>
                  <span class="card-trend up">+2.1%</span>
                </div>
              </div>
            </div>
            
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
              <div class="summary-card stock-card">
                <div class="card-icon">
                  <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="card-content">
                  <h3 class="card-value"><?= number_format($stats['produk_stok_rendah']) ?></h3>
                  <p class="card-label">Stok Rendah</p>
                  <span class="card-trend down">Perlu Perhatian</span>
                </div>
              </div>
            </div>
            
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
              <div class="summary-card sales-card">
                <div class="card-icon">
                  <i class="fas fa-chart-line"></i>
                </div>
                <div class="card-content">
                  <h3 class="card-value">Rp <?= number_format($stats['penjualan_hari_ini']) ?></h3>
                  <p class="card-label">Penjualan Hari Ini</p>
                  <span class="card-trend up">+8.5%</span>
                </div>
              </div>
            </div>
            
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
              <div class="summary-card transactions-card">
                <div class="card-icon">
                  <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="card-content">
                  <h3 class="card-value"><?= number_format($stats['transaksi_hari_ini']) ?></h3>
                  <p class="card-label">Transaksi Hari Ini</p>
                  <span class="card-trend up">+5.2%</span>
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
              <div class="top-products-card">
                <div class="top-products-header">
                  <h4 class="top-products-title">Top 5 Produk</h4>
                </div>
                <div class="top-products-body">
                  <?php if (!empty($topProducts)): ?>
                    <?php foreach ($topProducts as $index => $product): ?>
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
              <div class="categories-card">
                <div class="categories-header">
                  <h4 class="categories-title">Kategori Produk</h4>
                </div>
                <div class="categories-body">
                  <?php if (!empty($productCategories)): ?>
                    <?php foreach ($productCategories as $category): ?>
                    <div class="category-item">
                      <div class="category-info">
                        <span class="category-name"><?= htmlspecialchars($category['kategori']) ?></span>
                        <span class="category-count"><?= number_format($category['total_produk']) ?> produk</span>
                      </div>
                      <div class="category-stock">
                        <span class="stock-label">Stok:</span>
                        <span class="stock-value"><?= number_format($category['total_stok']) ?></span>
                      </div>
                    </div>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <div class="empty-state">
                      <p>Tidak ada data kategori</p>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            
            <div class="col-lg-6 col-md-12 mb-4">
              <div class="stock-alert-card">
                <div class="stock-alert-header">
                  <h4 class="stock-alert-title">Peringatan Stok Rendah</h4>
                </div>
                <div class="stock-alert-body">
                  <?php if (!empty($stokRendah)): ?>
                    <?php foreach (array_slice($stokRendah, 0, 8) as $product): ?>
                    <div class="stock-item">
                      <div class="stock-info">
                        <span class="stock-product"><?= htmlspecialchars($product['nama_produk']) ?></span>
                        <div class="stock-level">
                          <span class="stock-number"><?= $product['stok'] ?></span>
                          <span class="stock-unit">unit</span>
                        </div>
                      </div>
                      <div class="stock-status <?= $product['stok'] < 10 ? 'critical' : 'warning' ?>">
                        <?= $product['stok'] < 10 ? 'Kritis' : 'Rendah' ?>
                      </div>
                    </div>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <div class="empty-state">
                      <i class="fas fa-check-circle text-success"></i>
                      <p>Semua produk stok aman</p>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
          
          <div class="row mb-4">
            <div class="col-12">
              <div class="quick-actions-card">
                <div class="quick-actions-header">
                  <h4 class="quick-actions-title">Aksi Cepat</h4>
                </div>
                <div class="quick-actions-body">
                  <div class="actions-grid">
                    <a href="index.php?controller=produk&action=index" class="action-btn manage-products-btn">
                      <i class="fas fa-boxes"></i>
                      <span>Kelola Produk</span>
                    </a>
                    <a href="index.php?controller=produk&action=create" class="action-btn add-product-btn">
                      <i class="fas fa-plus-circle"></i>
                      <span>Tambah Produk</span>
                    </a>
                    <a href="index.php?controller=laporan&action=penjualan" class="action-btn sales-report-btn">
                      <i class="fas fa-chart-bar"></i>
                      <span>Laporan Penjualan</span>
                    </a>
                    <a href="index.php?controller=laporan&action=stok" class="action-btn stock-report-btn">
                      <i class="fas fa-clipboard-list"></i>
                      <span>Laporan Stok</span>
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
      --products-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
      --stock-gradient: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
      --sales-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
      --transactions-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
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
    
    .products-card .card-icon {
      background: var(--products-gradient);
    }
    
    .stock-card .card-icon {
      background: var(--stock-gradient);
    }
    
    .sales-card .card-icon {
      background: var(--sales-gradient);
    }
    
    .transactions-card .card-icon {
      background: var(--transactions-gradient);
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
    }
    
    .card-trend.up {
      background: #e8f5e8;
      color: #27ae60;
    }
    
    .card-trend.down {
      background: #ffeaa7;
      color: #e17055;
    }
    
    .chart-card, .top-products-card, .categories-card, .stock-alert-card, .quick-actions-card {
      background: var(--card-bg);
      border-radius: 16px;
      box-shadow: var(--shadow);
      border: 1px solid var(--border-color);
    }
    
    .chart-header, .top-products-header, .categories-header, .stock-alert-header, .quick-actions-header {
      padding: 1.5rem 1.5rem 1rem;
      border-bottom: 1px solid var(--border-color);
    }
    
    .chart-title, .top-products-title, .categories-title, .stock-alert-title, .quick-actions-title {
      font-size: 1.2rem;
      font-weight: 600;
      color: var(--text-primary);
      margin-bottom: 0;
    }
    
    .chart-body, .top-products-body, .categories-body, .stock-alert-body, .quick-actions-body {
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
    .product-rank.rank-4 { background: #3498db; }
    .product-rank.rank-5 { background: #9b59b6; }
    
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
    
    .category-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1rem;
      border-radius: 8px;
      background: #f8f9fa;
      margin-bottom: 1rem;
    }
    
    .category-name {
      font-weight: 600;
      color: var(--text-primary);
      display: block;
      margin-bottom: 0.2rem;
    }
    
    .category-count {
      font-size: 0.8rem;
      color: var(--text-secondary);
    }
    
    .category-stock {
      text-align: right;
    }
    
    .stock-label {
      font-size: 0.8rem;
      color: var(--text-secondary);
    }
    
    .stock-value {
      font-weight: 600;
      color: var(--text-primary);
      margin-left: 0.3rem;
    }
    
    .stock-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0.8rem;
      border-radius: 8px;
      background: #f8f9fa;
      margin-bottom: 0.8rem;
    }
    
    .stock-product {
      font-weight: 500;
      color: var(--text-primary);
      display: block;
      margin-bottom: 0.2rem;
    }
    
    .stock-level {
      font-size: 0.9rem;
    }
    
    .stock-number {
      font-weight: 600;
      color: var(--text-primary);
    }
    
    .stock-unit {
      color: var(--text-secondary);
      margin-left: 0.2rem;
    }
    
    .stock-status {
      padding: 0.3rem 0.8rem;
      border-radius: 12px;
      font-size: 0.8rem;
      font-weight: 600;
    }
    
    .stock-status.critical {
      background: #ffebee;
      color: #c62828;
    }
    
    .stock-status.warning {
      background: #fff3e0;
      color: #ef6c00;
    }
    
    .actions-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 1rem;
    }
    
    .action-btn {
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 1.5rem 1rem;
      border-radius: 12px;
      text-decoration: none;
      color: white;
      transition: all 0.3s ease;
      font-weight: 500;
      text-align: center;
    }
    
    .action-btn:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow);
      color: white;
      text-decoration: none;
    }
    
    .manage-products-btn { background: var(--products-gradient); }
    .add-product-btn { background: var(--sales-gradient); }
    .sales-report-btn { background: var(--transactions-gradient); }
    .stock-report-btn { background: var(--stock-gradient); }
    
    .action-btn i {
      font-size: 2rem;
      margin-bottom: 0.8rem;
    }
    
    .action-btn span {
      font-size: 0.9rem;
    }
    
    .empty-state {
      text-align: center;
      padding: 2rem;
      color: var(--text-secondary);
    }
    
    .empty-state i {
      font-size: 2rem;
      margin-bottom: 1rem;
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
      
      .actions-grid {
        grid-template-columns: repeat(2, 1fr);
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
      
      .actions-grid {
        grid-template-columns: 1fr;
      }
      
      .category-item, .stock-item {
        flex-direction: column;
        text-align: center;
      }
      
      .category-stock, .stock-status {
        margin-top: 0.5rem;
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