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
            <div class="col-12 grid-margin">
              <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                  <h3 class="font-weight-bold mb-1"><?= htmlspecialchars($store['nama_store']) ?></h3>
                  <p class="text-muted mb-0">ID Store: #<?= $store['id_store'] ?></p>
                </div>
                <div class="btn-group" role="group">
                  <a href="index.php?controller=store&action=edit&id=<?= $store['id_store'] ?>" class="btn btn-warning">
                    <i class="mdi mdi-pencil"></i> Edit
                  </a>
                  <a href="index.php?controller=store" class="btn btn-secondary">
                    <i class="mdi mdi-arrow-left"></i> Kembali
                  </a>
                </div>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-8 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title mb-4">Informasi Store</h4>
                  
                  <div class="row">
                    <div class="col-sm-6 mb-3">
                      <label class="text-muted">Status Store</label>
                      <div class="mt-1">
                        <?php if ($store['status_store'] == 'aktif'): ?>
                          <span class="badge badge-success">
                            <i class="mdi mdi-check-circle me-1"></i>Aktif
                          </span>
                        <?php else: ?>
                          <span class="badge badge-danger">
                            <i class="mdi mdi-close-circle me-1"></i>Tidak Aktif
                          </span>
                        <?php endif; ?>
                      </div>
                    </div>
                    
                    <div class="col-sm-6 mb-3">
                      <label class="text-muted">Manajer Store</label>
                      <p class="mb-0 font-weight-medium">
                        <?= htmlspecialchars($store['manajer_store'] ?: 'Belum ditentukan') ?>
                      </p>
                    </div>
                  </div>
                  
                  <div class="mb-3">
                    <label class="text-muted">Alamat Lengkap</label>
                    <div class="address-box mt-1">
                      <?= nl2br(htmlspecialchars($store['alamat_store'])) ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="col-md-4 grid-margin stretch-card">
              <div class="card">
                <div class="card-body text-center">
                  <div class="stat-icon mb-3">
                    <i class="mdi mdi-package-variant"></i>
                  </div>
                  <h2 class="stat-number"><?= $total_produk ?></h2>
                  <p class="text-muted mb-0">Total Produk</p>
                </div>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title mb-4">Aksi Cepat</h4>
                  
                  <div class="action-grid">
                    <?php if ($store['status_store'] == 'aktif'): ?>
                      <a href="index.php?controller=produk&store_id=<?= $store['id_store'] ?>" class="action-card">
                        <div class="action-icon">
                          <i class="mdi mdi-format-list-bulleted"></i>
                        </div>
                        <div class="action-content">
                          <h6>Lihat Produk</h6>
                          <p>Daftar produk di store ini</p>
                        </div>
                      </a>
                      
                      <a href="index.php?controller=produk&action=add&store_id=<?= $store['id_store'] ?>" class="action-card">
                        <div class="action-icon success">
                          <i class="mdi mdi-plus"></i>
                        </div>
                        <div class="action-content">
                          <h6>Tambah Produk</h6>
                          <p>Tambah produk baru ke store</p>
                        </div>
                      </a>
                      
                      <a href="index.php?controller=transaksi&store_id=<?= $store['id_store'] ?>" class="action-card">
                        <div class="action-icon info">
                          <i class="mdi mdi-chart-line"></i>
                        </div>
                        <div class="action-content">
                          <h6>Laporan Penjualan</h6>
                          <p>Data transaksi store ini</p>
                        </div>
                      </a>
                    <?php else: ?>
                      <div class="inactive-notice">
                        <i class="fas fa-exclamation-triangle"></i>
                        <h6>Store Tidak Aktif</h6>
                        <p>Aktifkan store untuk menggunakan fitur lengkap</p>
                        <a href="index.php?controller=store&action=edit&id=<?= $store['id_store'] ?>" class="btn btn-sm btn-warning">
                          Aktifkan Store
                        </a>
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
  </div>
  
  <style>
    .card {
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      border: none;
    }
    
    .badge {
      font-size: 0.85em;
      padding: 8px 12px;
      border-radius: 20px;
    }
    
    .badge-success {
      background: linear-gradient(45deg, #28a745, #20c997);
      color: white;
    }
    
    .badge-danger {
      background: linear-gradient(45deg, #dc3545, #fd7e14);
      color: white;
    }
    
    .address-box {
      background: #f8f9fa;
      padding: 15px;
      border-radius: 8px;
      border-left: 4px solid #007bff;
      line-height: 1.6;
    }
    
    .stat-icon {
      width: 60px;
      height: 60px;
      background: linear-gradient(45deg, #007bff, #0056b3);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto;
      color: white;
      font-size: 24px;
    }
    
    .stat-number {
      font-size: 3rem;
      font-weight: bold;
      color: #007bff;
      margin: 10px 0 5px 0;
    }
    
    .action-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
    }
    
    .action-card {
      display: flex;
      align-items: center;
      padding: 20px;
      background: white;
      border: 2px solid #e9ecef;
      border-radius: 10px;
      text-decoration: none;
      color: inherit;
      transition: all 0.3s ease;
    }
    
    .action-card:hover {
      border-color: #007bff;
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(0,123,255,0.2);
      color: inherit;
      text-decoration: none;
    }
    
    .action-icon {
      width: 50px;
      height: 50px;
      background: #007bff;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 20px;
      margin-right: 15px;
    }
    
    .action-icon.success {
      background: linear-gradient(45deg, #28a745, #20c997);
    }
    
    .action-icon.info {
      background: linear-gradient(45deg, #17a2b8, #007bff);
    }
    
    .action-content h6 {
      margin: 0 0 5px 0;
      font-weight: 600;
      color: #2c3e50;
    }
    
    .action-content p {
      margin: 0;
      color: #6c757d;
      font-size: 0.9em;
    }
    
    .inactive-notice {
      grid-column: 1 / -1;
      text-align: center;
      padding: 40px 20px;
      background: linear-gradient(135deg, #fff3cd, #ffeaa7);
      border-radius: 10px;
      border: 2px dashed #ffc107;
    }
    
    .inactive-notice i {
      font-size: 48px;
      color: #ffc107;
      margin-bottom: 15px;
    }
    
    .inactive-notice h6 {
      color: #856404;
      font-weight: 600;
      margin-bottom: 10px;
    }
    
    .inactive-notice p {
      color: #856404;
      margin-bottom: 20px;
    }
    
    .btn-group .btn {
      border-radius: 6px;
    }
    
    .btn-group .btn:not(:last-child) {
      margin-right: 8px;
    }
    
    .font-weight-medium {
      font-weight: 500;
    }
    
    @media (max-width: 768px) {
      .btn-group {
        width: 100%;
        margin-top: 15px;
      }
      
      .btn-group .btn {
        flex: 1;
        margin-right: 5px;
        font-size: 0.85em;
      }
      
      .action-grid {
        grid-template-columns: 1fr;
      }
      
      .action-card {
        padding: 15px;
      }
      
      .action-icon {
        width: 40px;
        height: 40px;
        font-size: 16px;
        margin-right: 12px;
      }
      
      .stat-number {
        font-size: 2.5rem;
      }
      
      .stat-icon {
        width: 50px;
        height: 50px;
        font-size: 20px;
      }
    }
    
    @media (max-width: 576px) {
      .d-flex.justify-content-between {
        flex-direction: column;
        align-items: flex-start;
      }
      
      .address-box {
        padding: 12px;
        font-size: 0.9em;
      }
      
      .inactive-notice {
        padding: 30px 15px;
      }
      
      .inactive-notice i {
        font-size: 36px;
      }
    }
  </style>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const actionCards = document.querySelectorAll('.action-card');
      
      actionCards.forEach(card => {
        card.addEventListener('click', function(e) {
          const icon = this.querySelector('.action-icon');
          icon.style.transform = 'scale(0.95)';
          setTimeout(() => {
            icon.style.transform = 'scale(1)';
          }, 150);
        });
      });
      
      const statNumber = document.querySelector('.stat-number');
      if (statNumber) {
        const targetNumber = parseInt(statNumber.textContent);
        let currentNumber = 0;
        const increment = targetNumber / 30;
        
        const counter = setInterval(() => {
          currentNumber += increment;
          if (currentNumber >= targetNumber) {
            statNumber.textContent = targetNumber;
            clearInterval(counter);
          } else {
            statNumber.textContent = Math.floor(currentNumber);
          }
        }, 50);
      }
    });
  </script>
  
  <?php include 'template/script.php'; ?>
</body>

</html>