<?php include('template/header.php'); ?>

<body class="with-welcome-text">
  <div class="container-scroller">
    <?php include 'template/navbar.php'; ?>
    <div class="container-fluid page-body-wrapper">
      <?php include 'template/setting_panel.php'; ?>
      <?php include 'template/sidebar.php'; ?>
      <div class="main-panel">
        <div class="content-wrapper">
          
          <!-- Header Section -->
          <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
              <h3 class="font-weight-bold">Detail Transaksi</h3>
              <h6 class="font-weight-normal mb-0">Informasi lengkap transaksi penjualan</h6>
            </div>
            <div class="btn-group-responsive">
              <a href="index.php?controller=transaksi_admin" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
              </a>
              <a href="index.php?controller=transaksi_admin&action=edit&id=<?= $transaksi['transaksi_id'] ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-edit"></i> Edit
              </a>
            </div>
          </div>
          
          <!-- Transaction Header Card -->
          <div class="card mb-4">
            <div class="card-body">
              <div class="row">
                <div class="col-md-8">
                  <h4 class="mb-2">Transaksi #<?= $transaksi['transaksi_id'] ?></h4>
                  <p class="text-muted mb-0">
                    <i class="fas fa-calendar me-2"></i>
                    <?= date('d F Y, H:i', strtotime($transaksi['tanggal_transaksi'])) ?>
                  </p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                  <span class="badge badge-<?= $transaksi['metode_pembayaran'] == 'tunai' ? 'success' : 
                    ($transaksi['metode_pembayaran'] == 'transfer' ? 'info' : 'warning') ?> fs-6 px-3 py-2">
                    <?= ucfirst($transaksi['metode_pembayaran']) ?>
                  </span>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Main Content Row -->
          <div class="row">
            <!-- Left Column -->
            <div class="col-lg-8">
              
              <!-- Customer Information -->
              <div class="card mb-4">
                <div class="card-header">
                  <h5 class="mb-0">
                    <i class="fas fa-user text-primary me-2"></i>
                    Informasi Customer
                  </h5>
                </div>
                <div class="card-body">
                  <?php if ($transaksi['customer_id']): ?>
                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <div class="info-item">
                          <small class="text-muted">Nama Customer</small>
                          <p class="fw-bold mb-0"><?= htmlspecialchars($transaksi['nama_customer']) ?></p>
                        </div>
                      </div>
                      <div class="col-md-6 mb-3">
                        <div class="info-item">
                          <small class="text-muted">No Telepon</small>
                          <p class="mb-0"><?= htmlspecialchars($transaksi['no_telepon']) ?></p>
                        </div>
                      </div>
                      <div class="col-md-6 mb-3">
                        <div class="info-item">
                          <small class="text-muted">Email</small>
                          <p class="mb-0"><?= htmlspecialchars($transaksi['email'] ?: '-') ?></p>
                        </div>
                      </div>
                      <div class="col-md-6 mb-3">
                        <div class="info-item">
                          <small class="text-muted">Membership</small>
                          <p class="mb-0">
                            <span class="badge badge-<?= $transaksi['nama_membership'] == 'Bronze' ? 'secondary' : 
                              ($transaksi['nama_membership'] == 'Silver' ? 'info' : 
                              ($transaksi['nama_membership'] == 'Gold' ? 'warning' : 'danger')) ?>">
                              <?= htmlspecialchars($transaksi['nama_membership']) ?>
                            </span>
                          </p>
                        </div>
                      </div>
                    </div>
                  <?php else: ?>
                    <div class="text-center py-4">
                      <span class="badge badge-secondary badge-lg">Customer Umum</span>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
              
              <!-- Products Table -->
              <div class="card">
                <div class="card-header">
                  <h5 class="mb-0">
                    <i class="fas fa-shopping-cart text-primary me-2"></i>
                    Detail Produk
                  </h5>
                </div>
                <div class="card-body p-0">
                  <div class="table-responsive">
                    <table class="table table-hover mb-0">
                      <thead class="table-light">
                        <tr>
                          <th>Produk</th>
                          <th class="text-end d-none d-md-table-cell">Harga</th>
                          <th class="text-center">Qty</th>
                          <th class="text-center d-none d-sm-table-cell">Poin</th>
                          <th class="text-end">Subtotal</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if (!empty($detail)): ?>
                          <?php foreach ($detail as $item): ?>
                          <tr>
                            <td>
                              <div>
                                <h6 class="mb-1"><?= htmlspecialchars($item['nama_produk']) ?></h6>
                                <small class="text-muted"><?= htmlspecialchars($item['kategori']) ?></small>
                                <div class="d-md-none">
                                  <small class="text-muted">Rp <?= number_format($item['harga_satuan'], 0, ',', '.') ?></small>
                                </div>
                              </div>
                            </td>
                            <td class="text-end d-none d-md-table-cell">
                              Rp <?= number_format($item['harga_satuan'], 0, ',', '.') ?>
                            </td>
                            <td class="text-center">
                              <span class="badge bg-light text-dark"><?= $item['jumlah'] ?></span>
                            </td>
                            <td class="text-center d-none d-sm-table-cell">
                              <?php if ($item['total_poin_item'] > 0): ?>
                                <span class="badge badge-primary">+<?= number_format($item['total_poin_item']) ?></span>
                              <?php else: ?>
                                <span class="text-muted">-</span>
                              <?php endif; ?>
                            </td>
                            <td class="text-end fw-bold">
                              Rp <?= number_format($item['subtotal'], 0, ',', '.') ?>
                            </td>
                          </tr>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                              Tidak ada detail produk
                            </td>
                          </tr>
                        <?php endif; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Right Column -->
            <div class="col-lg-4 mt-4 mt-lg-0">
              
              <!-- Transaction Summary -->
              <div class="card mb-4">
                <div class="card-header">
                  <h5 class="mb-0">
                    <i class="fas fa-calculator text-primary me-2"></i>
                    Ringkasan Transaksi
                  </h5>
                </div>
                <div class="card-body">
                  <div class="summary-row">
                    <span>Total Sebelum Diskon</span>
                    <span class="fw-bold">Rp <?= number_format($transaksi['total_sebelum_diskon'], 0, ',', '.') ?></span>
                  </div>
                  
                  <?php if ($transaksi['diskon_membership'] > 0): ?>
                  <div class="summary-row text-success">
                    <span>Diskon Membership</span>
                    <span class="fw-bold">- Rp <?= number_format($transaksi['diskon_membership'], 0, ',', '.') ?></span>
                  </div>
                  <?php endif; ?>
                  
                  <hr>
                  
                  <div class="summary-row total-row">
                    <span>Total Bayar</span>
                    <span class="fw-bold">Rp <?= number_format($transaksi['total_bayar'], 0, ',', '.') ?></span>
                  </div>
                  
                  <?php if ($transaksi['poin_didapat'] > 0): ?>
                  <div class="summary-row text-primary mt-3">
                    <span>Poin Didapat</span>
                    <span class="fw-bold">+<?= number_format($transaksi['poin_didapat']) ?> poin</span>
                  </div>
                  <?php endif; ?>
                </div>
              </div>
              
              <!-- Actions -->
              <div class="card">
                <div class="card-header">
                  <h5 class="mb-0">
                    <i class="fas fa-cogs text-primary me-2"></i>
                    Aksi
                  </h5>
                </div>
                <div class="card-body">
                  <div class="d-grid gap-2">
                    <a href="index.php?controller=transaksi_admin&action=edit&id=<?= $transaksi['transaksi_id'] ?>" 
                       class="btn btn-warning">
                      <i class="fas fa-edit me-1"></i> Edit Transaksi
                    </a>
                    <a href="index.php?controller=transaksi_admin&action=delete&id=<?= $transaksi['transaksi_id'] ?>" 
                       class="btn btn-danger"
                       onclick="return confirm('Yakin ingin menghapus transaksi ini? Stok produk akan dikembalikan.')">
                      <i class="fas fa-trash me-1"></i> Hapus Transaksi
                    </a>
                    <button class="btn btn-info" onclick="window.print()">
                      <i class="fas fa-print me-1"></i> Print Transaksi
                    </button>
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
    /* Simple Responsive Styles */
    .btn-group-responsive {
      display: flex;
      gap: 0.5rem;
    }
    
    .card {
      border: none;
      box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
      border-radius: 0.375rem;
    }
    
    .card-header {
      background-color: #f8f9fa;
      border-bottom: 1px solid #dee2e6;
      padding: 1rem 1.25rem;
    }
    
    .info-item {
      padding: 0.5rem 0;
    }
    
    .info-item small {
      display: block;
      margin-bottom: 0.25rem;
    }
    
    .summary-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0.5rem 0;
    }
    
    .total-row {
      font-size: 1.1rem;
      font-weight: 700;
      color: #2c3e50;
    }
    
    .table th {
      font-weight: 600;
      background-color: #f8f9fa;
      border-bottom: 2px solid #dee2e6;
    }
    
    .table td {
      vertical-align: middle;
      border-bottom: 1px solid #f1f3f4;
    }
    
    .badge-lg {
      font-size: 1rem;
      padding: 0.5rem 1rem;
    }
    
    .fs-6 {
      font-size: 1rem;
    }
    
    .me-1 {
      margin-right: 0.25rem;
    }
    
    .me-2 {
      margin-right: 0.5rem;
    }
    
    .fw-bold {
      font-weight: 700;
    }
    
    .d-grid {
      display: grid;
    }
    
    .gap-2 {
      gap: 0.5rem;
    }
    
    .bg-light {
      background-color: #f8f9fa;
    }
    
    /* Mobile Responsive */
    @media (max-width: 768px) {
      .btn-group-responsive {
        flex-direction: column;
        width: 100%;
      }
      
      .btn-group-responsive .btn {
        width: 100%;
        margin-bottom: 0.5rem;
      }
      
      .d-flex.justify-content-between {
        flex-direction: column;
        gap: 1rem;
      }
      
      .text-md-end {
        text-align: left !important;
      }
      
      .col-lg-4 {
        margin-top: 1rem;
      }
      
      .summary-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
      }
      
      .table {
        font-size: 0.875rem;
      }
    }
    
    @media (max-width: 576px) {
      .btn-group-responsive {
        position: sticky;
        top: 0;
        z-index: 10;
        background: white;
        padding: 0.5rem 0;
        margin-bottom: 1rem;
        border-bottom: 1px solid #dee2e6;
      }
      
      .content-wrapper {
        padding: 1rem;
      }
      
      .card-body {
        padding: 1rem;
      }
      
      .table th,
      .table td {
        padding: 0.5rem;
      }
    }
    
    /* Print Styles */
    @media print {
      .btn,
      .btn-group-responsive {
        display: none !important;
      }
      
      .card {
        box-shadow: none;
        border: 1px solid #ddd;
      }
      
      .d-grid {
        display: none !important;
      }
    }
  </style>
  
  <?php include 'template/script.php'; ?>
</body>