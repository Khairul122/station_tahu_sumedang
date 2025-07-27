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
                  <h3 class="font-weight-bold">Kelola Customer</h3>
                  <h6 class="font-weight-normal mb-0">Manajemen customer dan membership</h6>
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
                    <h4 class="card-title">Daftar Customer</h4>
                    <div class="d-flex gap-2">
                      <a href="index.php?controller=customer&action=stats" class="btn btn-info">
                        <i class="fas fa-chart-bar"></i> Statistik
                      </a>
                      <a href="index.php?controller=customer&action=add" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Customer
                      </a>
                    </div>
                  </div>
                  
                  <div class="row mb-3">
                    <div class="col-md-4">
                      <form method="GET" action="index.php">
                        <input type="hidden" name="controller" value="customer">
                        <div class="input-group">
                          <input type="text" class="form-control" name="search" 
                                 placeholder="Cari customer..." value="<?= htmlspecialchars($search) ?>">
                          <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-search"></i>
                          </button>
                        </div>
                      </form>
                    </div>
                    <div class="col-md-4">
                      <form method="GET" action="index.php">
                        <input type="hidden" name="controller" value="customer">
                        <select class="form-select" name="membership" onchange="this.form.submit()">
                          <option value="">Semua Membership</option>
                          <?php foreach ($memberships as $membership): ?>
                            <option value="<?= $membership['membership_id'] ?>" 
                                    <?= $membership_filter == $membership['membership_id'] ? 'selected' : '' ?>>
                              <?= htmlspecialchars($membership['nama_membership']) ?>
                            </option>
                          <?php endforeach; ?>
                        </select>
                      </form>
                    </div>
                    <div class="col-md-4">
                      <?php if (!empty($search) || !empty($membership_filter)): ?>
                        <a href="index.php?controller=customer" class="btn btn-outline-secondary">
                          <i class="fas fa-times"></i> Clear Filter
                        </a>
                      <?php endif; ?>
                    </div>
                  </div>
                  
                  <div class="table-responsive">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>Nama Customer</th>
                          <th>No Telepon</th>
                          <th>Email</th>
                          <th>Membership</th>
                          <th>Total Pembelian</th>
                          <th>Poin</th>
                          <th>Status</th>
                          <th>Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if (!empty($customers)): ?>
                          <?php foreach ($customers as $customer): ?>
                          <tr>
                            <td><?= $customer['customer_id'] ?></td>
                            <td>
                              <div class="d-flex align-items-center">
                                <div class="avatar-sm me-2">
                                  <div class="avatar-title bg-primary rounded-circle">
                                    <?= strtoupper(substr($customer['nama_customer'], 0, 1)) ?>
                                  </div>
                                </div>
                                <div>
                                  <strong><?= htmlspecialchars($customer['nama_customer']) ?></strong>
                                  <br>
                                  <small class="text-muted"><?= date('d/m/Y', strtotime($customer['tanggal_daftar'])) ?></small>
                                </div>
                              </div>
                            </td>
                            <td><?= htmlspecialchars($customer['no_telepon']) ?></td>
                            <td><?= htmlspecialchars($customer['email']) ?></td>
                            <td>
                              <span class="badge badge-<?= $customer['membership_id'] == 1 ? 'secondary' : 
                                ($customer['membership_id'] == 2 ? 'info' : 
                                ($customer['membership_id'] == 3 ? 'warning' : 'danger')) ?>">
                                <?= htmlspecialchars($customer['nama_membership']) ?>
                              </span>
                            </td>
                            <td>Rp <?= number_format($customer['total_pembelian']) ?></td>
                            <td>
                              <span class="badge badge-primary"><?= number_format($customer['total_poin']) ?></span>
                            </td>
                            <td>
                              <span class="badge badge-<?= $customer['status_aktif'] == 'aktif' ? 'success' : 'secondary' ?>">
                                <?= ucfirst($customer['status_aktif']) ?>
                              </span>
                            </td>
                            <td>
                              <div class="btn-group" role="group">
                                <a href="index.php?controller=customer&action=view&id=<?= $customer['customer_id'] ?>" 
                                   class="btn btn-sm btn-info" title="Detail">
                                  <i class="mdi mdi-eye"></i>
                                </a>
                                <a href="index.php?controller=customer&action=edit&id=<?= $customer['customer_id'] ?>" 
                                   class="btn btn-sm btn-warning" title="Edit">
                                  <i class="mdi mdi-pencil"></i>
                                </a>
                                <a href="index.php?controller=customer&action=delete&id=<?= $customer['customer_id'] ?>" 
                                   class="btn btn-sm btn-danger" title="Hapus"
                                   onclick="return confirm('Yakin ingin menghapus customer ini?')">
                                 <i class="mdi mdi-delete-forever"></i>
                                </a>
                              </div>
                            </td>
                          </tr>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <tr>
                            <td colspan="9" class="text-center">
                              <div class="py-4">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <p class="text-muted">
                                  <?= !empty($search) ? 'Tidak ada customer yang ditemukan' : 'Belum ada customer' ?>
                                </p>
                                <?php if (empty($search)): ?>
                                <a href="index.php?controller=customer&action=add" class="btn btn-primary">
                                  <i class="fas fa-plus"></i> Tambah Customer Pertama
                                </a>
                                <?php endif; ?>
                              </div>
                            </td>
                          </tr>
                        <?php endif; ?>
                      </tbody>
                    </table>
                  </div>
                  
                  <?php if (!empty($customers)): ?>
                  <div class="mt-3">
                    <small class="text-muted">
                      Total: <?= count($customers) ?> customer
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
    .avatar-sm {
      width: 40px;
      height: 40px;
    }
    
    .avatar-title {
      width: 100%;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 600;
      font-size: 0.875rem;
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
    
    .input-group .form-control {
      border-right: none;
    }
    
    .input-group .btn {
      border-left: none;
    }
    
    .form-select {
      border-radius: 0.375rem;
    }
    
    .d-flex.gap-2 > * {
      margin-right: 0.5rem;
    }
    
    .d-flex.gap-2 > *:last-child {
      margin-right: 0;
    }
    
    .table-responsive {
      border-radius: 0.375rem;
    }
    
    @media (max-width: 768px) {
      .d-flex.gap-2 {
        flex-direction: column;
      }
      
      .d-flex.gap-2 > * {
        margin-right: 0;
        margin-bottom: 0.5rem;
      }
      
      .btn-group {
        flex-direction: column;
      }
      
      .btn-group .btn {
        margin-right: 0;
        margin-bottom: 2px;
      }
    }
  </style>
  
  <?php include 'template/script.php'; ?>
</body>

</html>