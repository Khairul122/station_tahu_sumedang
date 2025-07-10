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
                  <h3 class="font-weight-bold">Kelola Produk</h3>
                  <h6 class="font-weight-normal mb-0">Manajemen produk tahu sumedang</h6>
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
                    <h4 class="card-title">Daftar Produk</h4>
                    <a href="index.php?controller=produk&action=add" class="btn btn-primary">
                      <i class="fas fa-plus"></i> Tambah Produk
                    </a>
                  </div>
                  
                  <div class="row mb-3">
                    <div class="col-md-6">
                      <form method="GET" action="index.php">
                        <input type="hidden" name="controller" value="produk">
                        <div class="input-group">
                          <input type="text" class="form-control" name="search" 
                                 placeholder="Cari produk..." value="<?= htmlspecialchars($search) ?>">
                          <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-search"></i>
                          </button>
                        </div>
                      </form>
                    </div>
                    <?php if (!empty($search)): ?>
                    <div class="col-md-6">
                      <a href="index.php?controller=produk" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Clear Search
                      </a>
                    </div>
                    <?php endif; ?>
                  </div>
                  
                  <div class="table-responsive">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>Nama Produk</th>
                          <th>Kategori</th>
                          <th>Harga</th>
                          <th>Stok</th>
                          <th>Poin Reward</th>
                          <th>Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if (!empty($produk)): ?>
                          <?php foreach ($produk as $item): ?>
                          <tr>
                            <td><?= $item['produk_id'] ?></td>
                            <td>
                              <strong><?= htmlspecialchars($item['nama_produk']) ?></strong>
                            </td>
                            <td>
                              <span class="badge badge-info"><?= htmlspecialchars($item['kategori']) ?></span>
                            </td>
                            <td>Rp <?= number_format($item['harga']) ?></td>
                            <td>
                              <?php if ($item['stok'] < 20): ?>
                                <span class="badge badge-warning"><?= $item['stok'] ?></span>
                              <?php else: ?>
                                <span class="badge badge-success"><?= $item['stok'] ?></span>
                              <?php endif; ?>
                            </td>
                            <td>
                              <span class="badge badge-primary"><?= $item['poin_reward'] ?> poin</span>
                            </td>
                            <td>
                              <div class="btn-group" role="group">
                                <a href="index.php?controller=produk&action=edit&id=<?= $item['produk_id'] ?>" 
                                   class="btn btn-sm btn-warning">
                                  <i class="fas fa-edit"></i>
                                </a>
                                <a href="index.php?controller=produk&action=delete&id=<?= $item['produk_id'] ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Yakin ingin menghapus produk ini?')">
                                  <i class="fas fa-trash"></i>
                                </a>
                              </div>
                            </td>
                          </tr>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <tr>
                            <td colspan="7" class="text-center">
                              <div class="py-4">
                                <i class="fas fa-box fa-3x text-muted mb-3"></i>
                                <p class="text-muted">
                                  <?= !empty($search) ? 'Tidak ada produk yang ditemukan' : 'Belum ada produk' ?>
                                </p>
                                <?php if (empty($search)): ?>
                                <a href="index.php?controller=produk&action=add" class="btn btn-primary">
                                  <i class="fas fa-plus"></i> Tambah Produk Pertama
                                </a>
                                <?php endif; ?>
                              </div>
                            </td>
                          </tr>
                        <?php endif; ?>
                      </tbody>
                    </table>
                  </div>
                  
                  <?php if (!empty($produk)): ?>
                  <div class="mt-3">
                    <small class="text-muted">
                      Total: <?= count($produk) ?> produk
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
    .table th {
      background-color: #f8f9fa;
      border-top: none;
    }
    
    .badge {
      font-size: 0.75rem;
    }
    
    .btn-group .btn {
      margin-right: 2px;
    }
    
    .btn-group .btn:last-child {
      margin-right: 0;
    }
    
    .table-responsive {
      border-radius: 0.375rem;
    }
    
    .alert {
      border: none;
      border-radius: 0.5rem;
    }
    
    .card {
      box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
      border: 1px solid rgba(0, 0, 0, 0.125);
    }
    
    .input-group .form-control {
      border-right: none;
    }
    
    .input-group .btn {
      border-left: none;
    }
    
    .empty-state {
      text-align: center;
      padding: 3rem;
    }
    
    .empty-state i {
      opacity: 0.3;
    }
  </style>
  
  <?php include 'template/script.php'; ?>
</body>

</html>