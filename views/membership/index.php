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
                  <h3 class="font-weight-bold">Kelola Membership</h3>
                  <h6 class="font-weight-normal mb-0">Manajemen level dan benefit membership</h6>
                </div>
              </div>
            </div>
          </div>
          
          <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <i class="fas fa-check-circle me-2"></i>
              <?= htmlspecialchars($_GET['success']) ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          <?php endif; ?>
          
          <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <i class="fas fa-exclamation-circle me-2"></i>
              <?= htmlspecialchars($_GET['error']) ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          <?php endif; ?>
          
          <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title">Daftar Membership</h4>
                    <a href="index.php?controller=membership&action=add" class="btn btn-primary">
                      <i class="fas fa-plus"></i> Tambah Membership
                    </a>
                  </div>
                  
                  <div class="table-responsive">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>Nama Membership</th>
                          <th>Minimal Pembelian</th>
                          <th>Diskon</th>
                          <th>Poin</th>
                          <th>Total Customer</th>
                          <th>Total Revenue</th>
                          <th>Benefit</th>
                          <th>Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if (!empty($memberships)): ?>
                          <?php foreach ($memberships as $membership): ?>
                          <tr>
                            <td><?= $membership['membership_id'] ?></td>
                            <td>
                              <div class="d-flex align-items-center">
                                <div class="membership-icon me-2">
                                  <?php
                                  $colors = ['secondary', 'info', 'warning', 'danger'];
                                  $icons = ['fas fa-circle', 'fas fa-star', 'fas fa-crown', 'fas fa-gem'];
                                  $colorIndex = ($membership['membership_id'] - 1) % 4;
                                  ?>
                                  <i class="<?= $icons[$colorIndex] ?> text-<?= $colors[$colorIndex] ?>"></i>
                                </div>
                                <div>
                                  <strong><?= htmlspecialchars($membership['nama_membership']) ?></strong>
                                </div>
                              </div>
                            </td>
                            <td>Rp <?= number_format($membership['minimal_pembelian']) ?></td>
                            <td>
                              <span class="badge badge-success"><?= $membership['diskon_persen'] ?>%</span>
                            </td>
                            <td>
                              <span class="badge badge-primary"><?= $membership['poin_per_pembelian'] ?>x</span>
                            </td>
                            <td>
                              <span class="badge badge-info"><?= number_format($membership['total_customers']) ?></span>
                            </td>
                            <td>Rp <?= number_format($membership['total_revenue']) ?></td>
                            <td>
                              <span class="benefit-text"><?= htmlspecialchars($membership['benefit']) ?></span>
                            </td>
                            <td>
                              <div class="btn-group" role="group">
                                <a href="index.php?controller=membership&action=view&id=<?= $membership['membership_id'] ?>" 
                                   class="btn btn-sm btn-info" title="Detail">
                                 <i class="mdi mdi-eye"></i>
                                </a>
                                <a href="index.php?controller=membership&action=edit&id=<?= $membership['membership_id'] ?>" 
                                   class="btn btn-sm btn-warning" title="Edit">
                                  <i class="mdi mdi-pencil"></i>
                                </a>
                                <?php if ($membership['total_customers'] == 0): ?>
                                <a href="index.php?controller=membership&action=delete&id=<?= $membership['membership_id'] ?>" 
                                   class="btn btn-sm btn-danger" title="Hapus"
                                   onclick="return confirm('Yakin ingin menghapus membership ini?')">
                                  <i class="mdi mdi-delete-forever"></i>
                                </a>
                                <?php else: ?>
                                <button class="btn btn-sm btn-secondary" disabled title="Tidak dapat dihapus karena ada customer">
                                  <i class="mdi mdi-lock"></i>
                                </button>
                                <?php endif; ?>
                              </div>
                            </td>
                          </tr>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <tr>
                            <td colspan="9" class="text-center">
                              <div class="py-4">
                                <i class="fas fa-star fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Belum ada membership</p>
                                <a href="index.php?controller=membership&action=add" class="btn btn-primary">
                                  <i class="fas fa-plus"></i> Tambah Membership Pertama
                                </a>
                              </div>
                            </td>
                          </tr>
                        <?php endif; ?>
                      </tbody>
                    </table>
                  </div>
                  
                  <?php if (!empty($memberships)): ?>
                  <div class="mt-3">
                    <small class="text-muted">
                      Total: <?= count($memberships) ?> membership level
                    </small>
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
    .membership-icon {
      width: 24px;
      text-align: center;
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
    
    .benefit-text {
      font-size: 0.875rem;
      color: #6c757d;
      max-width: 200px;
      display: inline-block;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    
    .btn-group .btn {
      margin-right: 2px;
    }
    
    .btn-group .btn:last-child {
      margin-right: 0;
    }
    
    .card {
      box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
      border: 1px solid rgba(0, 0, 0, 0.125);
    }
    
    .alert {
      border: none;
      border-radius: 0.5rem;
    }
    
    .table-responsive {
      border-radius: 0.375rem;
    }
    
    .btn-close {
      background: none;
      border: none;
      font-size: 1.5rem;
      line-height: 1;
      color: #000;
      opacity: 0.5;
    }
    
    .btn-close:hover {
      opacity: 0.75;
    }
    
    @media (max-width: 768px) {
      .btn-group {
        flex-direction: column;
      }
      
      .btn-group .btn {
        margin-right: 0;
        margin-bottom: 2px;
      }
      
      .benefit-text {
        max-width: 120px;
      }
    }
  </style>
  
  <?php include 'template/script.php'; ?>
</body>

</html>