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
              <div class="dashboard-header">
                <div class="row align-items-center">
                  <div class="col-md-8">
                    <h2 class="dashboard-title">CRM Dashboard</h2>
                    <p class="dashboard-subtitle">Sistem Informasi Penjualan - Station Tahu Sumedang</p>
                  </div>
                  <div class="col-md-4 text-right">
                    <div class="user-info">
                      <span class="user-role">Administrator</span>
                      <h5 class="user-name"><?= htmlspecialchars($user['nama_lengkap']) ?></h5>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="row mb-4">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
              <div class="metric-card metric-primary">
                <div class="metric-icon">
                  <i class="fas fa-users"></i>
                </div>
                <div class="metric-content">
                  <h3 class="metric-number"><?= number_format($stats['total_customers']) ?></h3>
                  <p class="metric-label">Total Customers</p>
                  <div class="metric-trend">
                    <i class="fas fa-arrow-up"></i>
                    <span>+12% dari bulan lalu</span>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
              <div class="metric-card metric-success">
                <div class="metric-icon">
                  <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="metric-content">
                  <h3 class="metric-number"><?= number_format($stats['total_transaksi']) ?></h3>
                  <p class="metric-label">Total Transaksi</p>
                  <div class="metric-trend">
                    <i class="fas fa-arrow-up"></i>
                    <span>+8% dari bulan lalu</span>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
              <div class="metric-card metric-warning">
                <div class="metric-icon">
                  <i class="fas fa-box"></i>
                </div>
                <div class="metric-content">
                  <h3 class="metric-number"><?= number_format($stats['total_produk']) ?></h3>
                  <p class="metric-label">Total Produk</p>
                  <div class="metric-trend">
                    <i class="fas fa-arrow-up"></i>
                    <span>+3 produk baru</span>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
              <div class="metric-card metric-info">
                <div class="metric-icon">
                  <i class="fas fa-chart-line"></i>
                </div>
                <div class="metric-content">
                  <h3 class="metric-number">Rp <?= number_format($stats['total_pendapatan']) ?></h3>
                  <p class="metric-label">Total Revenue</p>
                  <div class="metric-trend">
                    <i class="fas fa-arrow-up"></i>
                    <span>+15% dari bulan lalu</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="row mb-4">
            <div class="col-lg-8 col-md-12 mb-4">
              <div class="modern-card">
                <div class="card-header-modern">
                  <h4 class="card-title-modern">CRM Management System</h4>
                  <span class="card-subtitle-modern">Kelola semua aspek customer relationship</span>
                </div>
                <div class="card-body-modern">
                  <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                      <div class="action-card customer-management">
                        <div class="action-icon">
                          <i class="fas fa-users"></i>
                        </div>
                        <div class="action-content">
                          <h5>Customer Management</h5>
                          <p>Kelola data customer dan membership</p>
                          <a href="index.php?controller=customer" class="btn btn-modern btn-primary">
                            Kelola Customer
                          </a>
                        </div>
                      </div>
                    </div>
                    
                    <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                      <div class="action-card sales-management">
                        <div class="action-icon">
                          <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="action-content">
                          <h5>Sales Management</h5>
                          <p>Kelola transaksi dan penjualan</p>
                          <a href="index.php?controller=transaksi" class="btn btn-modern btn-success">
                            Kelola Transaksi
                          </a>
                        </div>
                      </div>
                    </div>
                    
                    <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                      <div class="action-card product-management">
                        <div class="action-icon">
                          <i class="fas fa-box"></i>
                        </div>
                        <div class="action-content">
                          <h5>Product Management</h5>
                          <p>Kelola produk dan inventory</p>
                          <a href="index.php?controller=produk" class="btn btn-modern btn-warning">
                            Kelola Produk
                          </a>
                        </div>
                      </div>
                    </div>
                    
                    <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                      <div class="action-card membership-management">
                        <div class="action-icon">
                          <i class="fas fa-star"></i>
                        </div>
                        <div class="action-content">
                          <h5>Membership System</h5>
                          <p>Kelola program loyalitas customer</p>
                          <a href="index.php?controller=membership" class="btn btn-modern btn-info">
                            Kelola Membership
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="col-lg-4 col-md-12 mb-4">
              <div class="modern-card">
                <div class="card-header-modern">
                  <h4 class="card-title-modern">Recent Activities</h4>
                  <span class="card-subtitle-modern">Real-time customer activities</span>
                </div>
                <div class="card-body-modern">
                  <div class="activity-timeline">
                    <?php if (!empty($activities)): ?>
                      <?php foreach ($activities as $activity): ?>
                      <div class="timeline-item">
                        <div class="timeline-marker <?= $activity['type'] == 'transaksi' ? 'success' : 'primary' ?>">
                          <i class="fas fa-<?= $activity['type'] == 'transaksi' ? 'shopping-cart' : 'user-plus' ?>"></i>
                        </div>
                        <div class="timeline-content">
                          <h6 class="timeline-title">
                            <?= $activity['type'] == 'transaksi' ? 'New Transaction' : 'New Customer' ?>
                          </h6>
                          <p class="timeline-text">
                            <?= htmlspecialchars($activity['customer']) ?>
                            <?php if ($activity['type'] == 'transaksi'): ?>
                              <span class="amount">Rp <?= number_format($activity['amount']) ?></span>
                            <?php endif; ?>
                          </p>
                          <small class="timeline-time">
                            <?= date('d M Y, H:i', strtotime($activity['time'])) ?>
                          </small>
                        </div>
                      </div>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <div class="empty-state">
                        <i class="fas fa-info-circle"></i>
                        <p>No recent activities</p>
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="row mb-4">
            <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
              <div class="modern-card analytics-card">
                <div class="card-header-modern">
                  <h4 class="card-title-modern">Today's Analytics</h4>
                </div>
                <div class="card-body-modern">
                  <div class="analytics-grid">
                    <div class="analytics-item">
                      <div class="analytics-icon primary">
                        <i class="fas fa-shopping-cart"></i>
                      </div>
                      <div class="analytics-data">
                        <h4 id="transaksi-hari-ini">0</h4>
                        <p>Transaksi Hari Ini</p>
                      </div>
                    </div>
                    <div class="analytics-item">
                      <div class="analytics-icon success">
                        <i class="fas fa-user-plus"></i>
                      </div>
                      <div class="analytics-data">
                        <h4 id="customer-baru-hari-ini">0</h4>
                        <p>Customer Baru</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
              <div class="modern-card inventory-card">
                <div class="card-header-modern">
                  <h4 class="card-title-modern">Stock Alert</h4>
                </div>
                <div class="card-body-modern">
                  <div class="stock-list" id="stok-rendah">
                    <div class="stock-item">
                      <div class="stock-info">
                        <span class="stock-name">Loading...</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="col-lg-4 col-md-12 col-sm-12 mb-3">
              <div class="modern-card quick-actions-card">
                <div class="card-header-modern">
                  <h4 class="card-title-modern">Quick Actions</h4>
                </div>
                <div class="card-body-modern">
                  <div class="quick-action-grid">
                    <a href="index.php?controller=transaksi&action=add" class="quick-action-btn primary">
                      <i class="fas fa-plus"></i>
                      <span>New Transaction</span>
                    </a>
                    <a href="index.php?controller=customer&action=add" class="quick-action-btn success">
                      <i class="fas fa-user-plus"></i>
                      <span>Add Customer</span>
                    </a>
                    <a href="index.php?controller=produk&action=add" class="quick-action-btn warning">
                      <i class="fas fa-box"></i>
                      <span>Add Product</span>
                    </a>
                    <a href="index.php?controller=laporan" class="quick-action-btn info">
                      <i class="fas fa-chart-bar"></i>
                      <span>View Reports</span>
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
      --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
      --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
      --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
      --dark-bg: #1a1a1a;
      --card-bg: #ffffff;
      --text-primary: #2c3e50;
      --text-secondary: #7f8c8d;
      --border-color: #e9ecef;
      --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
      --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    
    .dashboard-header {
      background: var(--primary-gradient);
      border-radius: 16px;
      padding: 2rem;
      color: white;
      margin-bottom: 2rem;
      box-shadow: var(--shadow-lg);
    }
    
    .dashboard-title {
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 0.5rem;
      background: linear-gradient(45deg, #fff, #f8f9fa);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    
    .dashboard-subtitle {
      font-size: 1.1rem;
      opacity: 0.9;
      margin-bottom: 0;
    }
    
    .user-info {
      text-align: right;
    }
    
    .user-role {
      font-size: 0.9rem;
      opacity: 0.8;
      display: block;
    }
    
    .user-name {
      font-size: 1.3rem;
      font-weight: 600;
      margin-bottom: 0;
    }
    
    .metric-card {
      background: var(--card-bg);
      border-radius: 16px;
      padding: 1.5rem;
      box-shadow: var(--shadow);
      transition: all 0.3s ease;
      border: 1px solid var(--border-color);
      position: relative;
      overflow: hidden;
    }
    
    .metric-card:hover {
      transform: translateY(-4px);
      box-shadow: var(--shadow-lg);
    }
    
    .metric-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: var(--primary-gradient);
    }
    
    .metric-card.metric-primary::before {
      background: var(--primary-gradient);
    }
    
    .metric-card.metric-success::before {
      background: var(--success-gradient);
    }
    
    .metric-card.metric-warning::before {
      background: var(--warning-gradient);
    }
    
    .metric-card.metric-info::before {
      background: var(--info-gradient);
    }
    
    .metric-icon {
      width: 60px;
      height: 60px;
      border-radius: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      color: white;
      background: var(--primary-gradient);
      margin-bottom: 1rem;
    }
    
    .metric-primary .metric-icon {
      background: var(--primary-gradient);
    }
    
    .metric-success .metric-icon {
      background: var(--success-gradient);
    }
    
    .metric-warning .metric-icon {
      background: var(--warning-gradient);
    }
    
    .metric-info .metric-icon {
      background: var(--info-gradient);
    }
    
    .metric-number {
      font-size: 2.2rem;
      font-weight: 700;
      color: var(--text-primary);
      margin-bottom: 0.5rem;
    }
    
    .metric-label {
      color: var(--text-secondary);
      font-size: 0.9rem;
      margin-bottom: 0.8rem;
      font-weight: 500;
    }
    
    .metric-trend {
      color: #27ae60;
      font-size: 0.8rem;
      font-weight: 600;
    }
    
    .metric-trend i {
      margin-right: 0.3rem;
    }
    
    .modern-card {
      background: var(--card-bg);
      border-radius: 16px;
      box-shadow: var(--shadow);
      border: 1px solid var(--border-color);
      transition: all 0.3s ease;
    }
    
    .modern-card:hover {
      box-shadow: var(--shadow-lg);
    }
    
    .card-header-modern {
      padding: 1.5rem 1.5rem 1rem;
      border-bottom: 1px solid var(--border-color);
    }
    
    .card-title-modern {
      font-size: 1.3rem;
      font-weight: 600;
      color: var(--text-primary);
      margin-bottom: 0.3rem;
    }
    
    .card-subtitle-modern {
      color: var(--text-secondary);
      font-size: 0.9rem;
    }
    
    .card-body-modern {
      padding: 1.5rem;
    }
    
    .action-card {
      background: #f8f9fa;
      border-radius: 12px;
      padding: 1.5rem;
      height: 100%;
      transition: all 0.3s ease;
      border: 1px solid var(--border-color);
    }
    
    .action-card:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow);
    }
    
    .action-icon {
      width: 50px;
      height: 50px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2rem;
      color: white;
      background: var(--primary-gradient);
      margin-bottom: 1rem;
    }
    
    .customer-management .action-icon {
      background: var(--primary-gradient);
    }
    
    .sales-management .action-icon {
      background: var(--success-gradient);
    }
    
    .product-management .action-icon {
      background: var(--warning-gradient);
    }
    
    .membership-management .action-icon {
      background: var(--info-gradient);
    }
    
    .action-content h5 {
      font-size: 1.1rem;
      font-weight: 600;
      color: var(--text-primary);
      margin-bottom: 0.5rem;
    }
    
    .action-content p {
      color: var(--text-secondary);
      font-size: 0.9rem;
      margin-bottom: 1rem;
    }
    
    .btn-modern {
      padding: 0.6rem 1.2rem;
      border-radius: 8px;
      font-weight: 500;
      font-size: 0.9rem;
      border: none;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-block;
    }
    
    .btn-modern.btn-primary {
      background: var(--primary-gradient);
      color: white;
    }
    
    .btn-modern.btn-success {
      background: var(--success-gradient);
      color: white;
    }
    
    .btn-modern.btn-warning {
      background: var(--warning-gradient);
      color: white;
    }
    
    .btn-modern.btn-info {
      background: var(--info-gradient);
      color: white;
    }
    
    .btn-modern:hover {
      transform: translateY(-1px);
      box-shadow: var(--shadow);
      color: white;
      text-decoration: none;
    }
    
    .activity-timeline {
      max-height: 400px;
      overflow-y: auto;
    }
    
    .timeline-item {
      display: flex;
      margin-bottom: 1.5rem;
      align-items: flex-start;
    }
    
    .timeline-marker {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 0.9rem;
      margin-right: 1rem;
      flex-shrink: 0;
    }
    
    .timeline-marker.primary {
      background: var(--primary-gradient);
    }
    
    .timeline-marker.success {
      background: var(--success-gradient);
    }
    
    .timeline-content {
      flex: 1;
    }
    
    .timeline-title {
      font-size: 1rem;
      font-weight: 600;
      color: var(--text-primary);
      margin-bottom: 0.3rem;
    }
    
    .timeline-text {
      color: var(--text-secondary);
      font-size: 0.9rem;
      margin-bottom: 0.3rem;
    }
    
    .timeline-text .amount {
      color: #27ae60;
      font-weight: 600;
    }
    
    .timeline-time {
      color: var(--text-secondary);
      font-size: 0.8rem;
    }
    
    .analytics-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
    }
    
    .analytics-item {
      text-align: center;
      padding: 1rem;
      border-radius: 12px;
      background: #f8f9fa;
    }
    
    .analytics-icon {
      width: 50px;
      height: 50px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 1.2rem;
      margin: 0 auto 0.8rem;
    }
    
    .analytics-icon.primary {
      background: var(--primary-gradient);
    }
    
    .analytics-icon.success {
      background: var(--success-gradient);
    }
    
    .analytics-data h4 {
      font-size: 1.8rem;
      font-weight: 700;
      color: var(--text-primary);
      margin-bottom: 0.3rem;
    }
    
    .analytics-data p {
      color: var(--text-secondary);
      font-size: 0.8rem;
      margin-bottom: 0;
    }
    
    .stock-list {
      max-height: 200px;
      overflow-y: auto;
    }
    
    .stock-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0.8rem;
      border-radius: 8px;
      background: #f8f9fa;
      margin-bottom: 0.5rem;
    }
    
    .stock-name {
      font-weight: 500;
      color: var(--text-primary);
    }
    
    .stock-quantity {
      background: #e74c3c;
      color: white;
      padding: 0.3rem 0.6rem;
      border-radius: 12px;
      font-size: 0.8rem;
      font-weight: 600;
    }
    
    .quick-action-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 0.8rem;
    }
    
    .quick-action-btn {
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
    
    .quick-action-btn.primary {
      background: var(--primary-gradient);
    }
    
    .quick-action-btn.success {
      background: var(--success-gradient);
    }
    
    .quick-action-btn.warning {
      background: var(--warning-gradient);
    }
    
    .quick-action-btn.info {
      background: var(--info-gradient);
    }
    
    .quick-action-btn:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow);
      color: white;
      text-decoration: none;
    }
    
    .quick-action-btn i {
      font-size: 1.3rem;
      margin-bottom: 0.5rem;
    }
    
    .quick-action-btn span {
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
      .dashboard-title {
        font-size: 2rem;
      }
      
      .dashboard-header {
        padding: 1.5rem;
      }
      
      .metric-number {
        font-size: 1.8rem;
      }
      
      .analytics-grid {
        grid-template-columns: 1fr;
      }
      
      .quick-action-grid {
        grid-template-columns: 1fr;
      }
    }
    
    @media (max-width: 576px) {
      .dashboard-title {
        font-size: 1.5rem;
      }
      
      .user-info {
        text-align: left;
        margin-top: 1rem;
      }
      
      .metric-card {
        padding: 1rem;
      }
      
      .card-body-modern {
        padding: 1rem;
      }
    }
  </style>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      loadDashboardData();
      setInterval(loadDashboardData, 30000);
    });
    
    function loadDashboardData() {
      fetch('index.php?controller=dashboard&action=getData')
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            document.getElementById('transaksi-hari-ini').textContent = data.transaksi_today || 0;
            document.getElementById('customer-baru-hari-ini').textContent = data.customer_baru_today || 0;
            
            const stokRendahContainer = document.getElementById('stok-rendah');
            if (data.stok_rendah && data.stok_rendah.length > 0) {
              stokRendahContainer.innerHTML = '';
              data.stok_rendah.forEach(item => {
                const stockItem = document.createElement('div');
                stockItem.className = 'stock-item';
                stockItem.innerHTML = `
                  <div class="stock-info">
                    <span class="stock-name">${item.nama_produk}</span>
                  </div>
                  <span class="stock-quantity">${item.stok}</span>
                `;
                stokRendahContainer.appendChild(stockItem);
              });
            } else {
              stokRendahContainer.innerHTML = `
                <div class="empty-state">
                  <i class="fas fa-check-circle"></i>
                  <p>Semua stok aman</p>
                </div>
              `;
            }
          }
        })
        .catch(error => {
          console.error('Error loading dashboard data:', error);
        });
    }
  </script>
  
  <?php include 'template/script.php'; ?>
</body>

</html>