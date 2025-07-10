<?php include('template/header.php'); ?>

<body class="with-welcome-text">
  <div class="container-scroller">
    <?php include 'template/navbar.php'; ?>
    <div class="container-fluid page-body-wrapper">
      <?php include 'template/setting_panel.php'; ?>
      <?php include 'template/sidebar.php'; ?>
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                  <h3 class="font-weight-bold">Detail Membership</h3>
                  <h6 class="font-weight-normal mb-0">Informasi lengkap membership level</h6>
                </div>
                <div class="col-12 col-xl-4">
                  <div class="d-flex justify-content-end">
                    <a href="index.php?controller=membership" class="btn btn-secondary me-2">
                      <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <a href="index.php?controller=membership&action=edit&id=<?= $membership['membership_id'] ?>" class="btn btn-primary">
                      <i class="fas fa-edit"></i> Edit
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-lg-8 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="membership-header">
                    <div class="membership-badge">
                      <?php
                      $colors = ['secondary', 'info', 'warning', 'danger'];
                      $icons = ['fas fa-circle', 'fas fa-star', 'fas fa-crown', 'fas fa-gem'];
                      $colorIndex = ($membership['membership_id'] - 1) % 4;
                      ?>
                      <i class="<?= $icons[$colorIndex] ?> membership-icon"></i>
                      <h2 class="membership-name"><?= htmlspecialchars($membership['nama_membership']) ?></h2>
                    </div>
                  </div>
                  
                  <div class="membership-details">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="detail-item">
                          <div class="detail-label">
                            <i class="fas fa-shopping-cart text-primary"></i>
                            <span>Minimal Pembelian</span>
                          </div>
                          <div class="detail-value">
                            <strong>Rp <?= number_format($membership['minimal_pembelian']) ?></strong>
                          </div>
                        </div>
                      </div>
                      
                      <div class="col-md-6">
                        <div class="detail-item">
                          <div class="detail-label">
                            <i class="fas fa-percentage text-success"></i>
                            <span>Diskon</span>
                          </div>
                          <div class="detail-value">
                            <strong><?= $membership['diskon_persen'] ?>%</strong>
                          </div>
                        </div>
                      </div>
                      
                      <div class="col-md-6">
                        <div class="detail-item">
                          <div class="detail-label">
                            <i class="fas fa-star text-warning"></i>
                            <span>Poin Multiplier</span>
                          </div>
                          <div class="detail-value">
                            <strong><?= $membership['poin_per_pembelian'] ?>x</strong>
                          </div>
                        </div>
                      </div>
                      
                      <div class="col-md-6">
                        <div class="detail-item">
                          <div class="detail-label">
                            <i class="fas fa-users text-info"></i>
                            <span>Total Customer</span>
                          </div>
                          <div class="detail-value">
                            <strong><?= number_format($stats['total_customers']) ?></strong>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    <div class="benefit-section">
                      <div class="benefit-header">
                        <i class="fas fa-gift text-primary"></i>
                        <h5>Benefit</h5>
                      </div>
                      <div class="benefit-content">
                        <p><?= htmlspecialchars($membership['benefit']) ?></p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="col-lg-4 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Statistik</h4>
                  
                  <div class="stat-item">
                    <div class="stat-icon bg-primary">
                      <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                      <h3 class="stat-number"><?= number_format($stats['total_customers']) ?></h3>
                      <p class="stat-label">Total Customer</p>
                    </div>
                  </div>
                  
                  <div class="stat-item">
                    <div class="stat-icon bg-success">
                      <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="stat-content">
                      <h3 class="stat-number">Rp <?= number_format($stats['total_revenue']) ?></h3>
                      <p class="stat-label">Total Revenue</p>
                    </div>
                  </div>
                  
                  <div class="stat-item">
                    <div class="stat-icon bg-warning">
                      <i class="fas fa-calculator"></i>
                    </div>
                    <div class="stat-content">
                      <h3 class="stat-number">
                        Rp <?= $stats['total_customers'] > 0 ? number_format($stats['total_revenue'] / $stats['total_customers']) : 0 ?>
                      </h3>
                      <p class="stat-label">Avg per Customer</p>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Simulasi Benefit</h4>
                  <div class="simulation">
                    <div class="simulation-item">
                      <strong>Pembelian Rp 100.000</strong>
                      <ul>
                        <li>Diskon: Rp <?= number_format(100000 * ($membership['diskon_persen'] / 100)) ?></li>
                        <li>Total Bayar: Rp <?= number_format(100000 - (100000 * ($membership['diskon_persen'] / 100))) ?></li>
                        <li>Poin: <?= 5 * $membership['poin_per_pembelian'] ?> poin</li>
                      </ul>
                    </div>
                    
                    <div class="simulation-item">
                      <strong>Pembelian Rp 500.000</strong>
                      <ul>
                        <li>Diskon: Rp <?= number_format(500000 * ($membership['diskon_persen'] / 100)) ?></li>
                        <li>Total Bayar: Rp <?= number_format(500000 - (500000 * ($membership['diskon_persen'] / 100))) ?></li>
                        <li>Poin: <?= 25 * $membership['poin_per_pembelian'] ?> poin</li>
                      </ul>
                    </div>
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
    .membership-header {
      text-align: center;
      padding: 2rem 0;
      border-bottom: 1px solid #e9ecef;
      margin-bottom: 2rem;
    }
    
    .membership-badge {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 1rem;
    }
    
    .membership-icon {
      font-size: 3rem;
      color: #0d6efd;
    }
    
    .membership-name {
      font-size: 2.5rem;
      font-weight: 700;
      color: #495057;
      margin-bottom: 0;
    }
    
    .membership-details {
      padding: 1rem 0;
    }
    
    .detail-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1rem;
      background: #f8f9fa;
      border-radius: 0.375rem;
      margin-bottom: 1rem;
    }
    
    .detail-label {
      display: flex;
      align-items: center;
      color: #6c757d;
      font-weight: 500;
    }
    
    .detail-label i {
      margin-right: 0.5rem;
    }
    
    .detail-value {
      color: #495057;
      font-size: 1.1rem;
    }
    
    .benefit-section {
      margin-top: 2rem;
      padding: 1.5rem;
      background: #f8f9fa;
      border-radius: 0.375rem;
    }
    
    .benefit-header {
      display: flex;
      align-items: center;
      margin-bottom: 1rem;
    }
    
    .benefit-header i {
      margin-right: 0.5rem;
    }
    
    .benefit-header h5 {
      margin-bottom: 0;
      color: #495057;
    }
    
    .benefit-content {
      color: #6c757d;
      line-height: 1.6;
    }
    
    .stat-item {
      display: flex;
      align-items: center;
      padding: 1rem;
      background: #f8f9fa;
      border-radius: 0.375rem;
      margin-bottom: 1rem;
    }
    
    .stat-icon {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 1.5rem;
      margin-right: 1rem;
      flex-shrink: 0;
    }
    
    .stat-content {
      flex: 1;
    }
    
    .stat-number {
      font-size: 1.5rem;
      font-weight: 700;
      color: #495057;
      margin-bottom: 0.25rem;
    }
    
    .stat-label {
      color: #6c757d;
      font-size: 0.875rem;
      margin-bottom: 0;
    }
    
    .simulation {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }
    
    .simulation-item {
      padding: 1rem;
      background: #f8f9fa;
      border-radius: 0.375rem;
      border-left: 4px solid #0d6efd;
    }
    
    .simulation-item strong {
      color: #495057;
      display: block;
      margin-bottom: 0.5rem;
    }
    
    .simulation-item ul {
      margin-bottom: 0;
      padding-left: 1.5rem;
    }
    
    .simulation-item li {
      color: #6c757d;
      font-size: 0.875rem;
      margin-bottom: 0.25rem;
    }
    
    .card {
      box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
      border: 1px solid rgba(0, 0, 0, 0.125);
    }
    
    .me-2 {
      margin-right: 0.5rem;
    }
    
    @media (max-width: 768px) {
      .membership-name {
        font-size: 2rem;
      }
      
      .membership-icon {
        font-size: 2rem;
      }
      
      .d-flex.justify-content-end {
        justify-content: flex-start;
        margin-top: 1rem;
      }
      
      .detail-item {
        flex-direction: column;
        align-items: flex-start;
        text-align: left;
      }
      
      .detail-value {
        margin-top: 0.5rem;
      }
      
      .stat-item {
        flex-direction: column;
        text-align: center;
      }
      
      .stat-icon {
        margin-right: 0;
        margin-bottom: 1rem;
      }
    }
  </style>
  
  <?php include 'template/script.php'; ?>
</body>

</html>