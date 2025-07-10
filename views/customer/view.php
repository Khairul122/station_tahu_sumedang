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
                  <h3 class="font-weight-bold">Detail Customer</h3>
                  <h6 class="font-weight-normal mb-0">Informasi lengkap customer dan riwayat transaksi</h6>
                </div>
                <div class="col-12 col-xl-4">
                  <div class="d-flex justify-content-end">
                    <a href="index.php?controller=customer" class="btn btn-secondary me-2">
                      <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <a href="index.php?controller=customer&action=edit&id=<?= $customer['customer_id'] ?>" class="btn btn-primary">
                      <i class="fas fa-edit"></i> Edit
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-lg-4 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex align-items-center mb-4">
                    <div class="avatar-lg me-3">
                      <div class="avatar-title bg-primary rounded-circle">
                        <?= strtoupper(substr($customer['nama_customer'], 0, 2)) ?>
                      </div>
                    </div>
                    <div>
                      <h4 class="mb-1"><?= htmlspecialchars($customer['nama_customer']) ?></h4>
                      <span class="badge badge-<?= $customer['membership_id'] == 1 ? 'secondary' : 
                        ($customer['membership_id'] == 2 ? 'info' : 
                        ($customer['membership_id'] == 3 ? 'warning' : 'danger')) ?>">
                        <?= htmlspecialchars($customer['nama_membership']) ?>
                      </span>
                    </div>
                  </div>
                  
                  <div class="customer-info">
                    <div class="info-item">
                      <div class="info-label">
                        <i class="fas fa-phone text-primary"></i>
                        <span>No Telepon</span>
                      </div>
                      <div class="info-value"><?= htmlspecialchars($customer['no_telepon']) ?></div>
                    </div>
                    
                    <div class="info-item">
                      <div class="info-label">
                        <i class="fas fa-envelope text-primary"></i>
                        <span>Email</span>
                      </div>
                      <div class="info-value"><?= htmlspecialchars($customer['email'] ?: '-') ?></div>
                    </div>
                    
                    <div class="info-item">
                      <div class="info-label">
                        <i class="fas fa-map-marker-alt text-primary"></i>
                        <span>Alamat</span>
                      </div>
                      <div class="info-value"><?= htmlspecialchars($customer['alamat'] ?: '-') ?></div>
                    </div>
                    
                    <div class="info-item">
                      <div class="info-label">
                        <i class="fas fa-calendar text-primary"></i>
                        <span>Tanggal Daftar</span>
                      </div>
                      <div class="info-value"><?= date('d F Y', strtotime($customer['tanggal_daftar'])) ?></div>
                    </div>
                    
                    <div class="info-item">
                      <div class="info-label">
                        <i class="fas fa-toggle-on text-primary"></i>
                        <span>Status</span>
                      </div>
                      <div class="info-value">
                        <span class="badge badge-<?= $customer['status_aktif'] == 'aktif' ? 'success' : 'secondary' ?>">
                          <?= ucfirst($customer['status_aktif']) ?>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="col-lg-8 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-3">
                      <div class="stat-card">
                        <div class="stat-icon bg-primary">
                          <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="stat-content">
                          <h3 class="stat-value">Rp <?= number_format($customer['total_pembelian']) ?></h3>
                          <p class="stat-label">Total Pembelian</p>
                        </div>
                      </div>
                    </div>
                    
                    <div class="col-md-3">
                      <div class="stat-card">
                        <div class="stat-icon bg-success">
                          <i class="fas fa-star"></i>
                        </div>
                        <div class="stat-content">
                          <h3 class="stat-value"><?= number_format($customer['total_poin']) ?></h3>
                          <p class="stat-label">Total Poin</p>
                        </div>
                      </div>
                    </div>
                    
                    <div class="col-md-3">
                      <div class="stat-card">
                        <div class="stat-icon bg-warning">
                          <i class="fas fa-receipt"></i>
                        </div>
                        <div class="stat-content">
                          <h3 class="stat-value"><?= count($transactions) ?></h3>
                          <p class="stat-label">Total Transaksi</p>
                        </div>
                      </div>
                    </div>
                    
                    <div class="col-md-3">
                      <div class="stat-card">
                        <div class="stat-icon bg-info">
                          <i class="fas fa-crown"></i>
                        </div>
                        <div class="stat-content">
                          <h3 class="stat-value"><?= htmlspecialchars($customer['nama_membership']) ?></h3>
                          <p class="stat-label">Membership</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Riwayat Transaksi (10 Terakhir)</h4>
                  
                  <div class="table-responsive">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>ID Transaksi</th>
                          <th>Tanggal</th>
                          <th>Total Items</th>
                          <th>Total Bayar</th>
                          <th>Diskon</th>
                          <th>Poin</th>
                          <th>Metode Bayar</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if (!empty($transactions)): ?>
                          <?php foreach ($transactions as $transaction): ?>
                          <tr>
                            <td>#<?= $transaction['transaksi_id'] ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($transaction['tanggal_transaksi'])) ?></td>
                            <td><?= $transaction['total_items'] ?> items</td>
                            <td>Rp <?= number_format($transaction['total_bayar']) ?></td>
                            <td>Rp <?= number_format($transaction['diskon_membership']) ?></td>
                            <td>
                              <span class="badge badge-primary">+<?= $transaction['poin_didapat'] ?></span>
                            </td>
                            <td>
                              <span class="badge badge-<?= $transaction['metode_pembayaran'] == 'tunai' ? 'success' : 
                                ($transaction['metode_pembayaran'] == 'transfer' ? 'info' : 'warning') ?>">
                                <?= ucfirst($transaction['metode_pembayaran']) ?>
                              </span>
                            </td>
                          </tr>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <tr>
                            <td colspan="7" class="text-center">
                              <div class="py-4">
                                <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Belum ada transaksi</p>
                              </div>
                            </td>
                          </tr>
                        <?php endif; ?>
                      </tbody>
                    </table>
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
    .avatar-lg {
      width: 80px;
      height: 80px;
    }
    
    .avatar-title {
      width: 100%;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 1.5rem;
      color: white;
    }
    
    .customer-info {
      margin-top: 1rem;
    }
    
    .info-item {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      padding: 1rem 0;
      border-bottom: 1px solid #e9ecef;
    }
    
    .info-item:last-child {
      border-bottom: none;
    }
    
    .info-label {
      display: flex;
      align-items: center;
      font-weight: 500;
      color: #6c757d;
      min-width: 120px;
    }
    
    .info-label i {
      margin-right: 0.5rem;
      width: 16px;
    }
    
    .info-value {
      text-align: right;
      color: #495057;
      font-weight: 500;
      flex: 1;
    }
    
    .stat-card {
      display: flex;
      align-items: center;
      padding: 1rem;
      border-radius: 0.5rem;
      background: #f8f9fa;
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
    }
    
    .stat-content {
      flex: 1;
    }
    
    .stat-value {
      font-size: 1.5rem;
      font-weight: 700;
      color: #495057;
      margin-bottom: 0.25rem;
    }
    
    .stat-label {
      font-size: 0.875rem;
      color: #6c757d;
      margin-bottom: 0;
    }
    
    .card {
      box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
      border: 1px solid rgba(0, 0, 0, 0.125);
    }
    
    .table th {
      background-color: #f8f9fa;
      border-top: none;
      font-weight: 600;
    }
    
    .badge {
      font-size: 0.75rem;
      padding: 0.25rem 0.5rem;
    }
    
    .me-2 {
      margin-right: 0.5rem;
    }
    
    .me-3 {
      margin-right: 1rem;
    }
    
    @media (max-width: 768px) {
      .d-flex.justify-content-end {
        justify-content: flex-start;
        margin-top: 1rem;
      }
      
      .stat-card {
        flex-direction: column;
        text-align: center;
      }
      
      .stat-icon {
        margin-right: 0;
        margin-bottom: 1rem;
      }
      
      .info-item {
        flex-direction: column;
        align-items: flex-start;
      }
      
      .info-label {
        margin-bottom: 0.5rem;
      }
      
      .info-value {
        text-align: left;
      }
    }
  </style>
  
  <?php include 'template/script.php'; ?>
</body>

</html>