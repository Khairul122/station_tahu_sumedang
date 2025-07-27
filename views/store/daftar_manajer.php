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
                  <h3 class="font-weight-bold"><?= $title ?></h3>
                  <h6 class="font-weight-normal mb-0">Kelola semua manajer store Station Tahu Sumedang</h6>
                </div>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title">Daftar Manajer</h4>
                    <div class="btn-group" role="group">
                      <a href="index.php?controller=manajer&action=registrasi" class="btn btn-primary">
                        <i class="mdi mdi-account-plus"></i> Tambah Manajer
                      </a>
                      <a href="index.php?controller=store" class="btn btn-info">
                        <i class="mdi mdi-store"></i> Kelola Store
                      </a>
                    </div>
                  </div>
                  
                  <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success">
                      <i class="mdi mdi-check-circle me-2"></i>
                      <?= htmlspecialchars($_GET['success']) ?>
                    </div>
                  <?php endif; ?>
                  
                  <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger">
                      <i class="mdi mdi-alert-circle me-2"></i>
                      <?= htmlspecialchars($_GET['error']) ?>
                    </div>
                  <?php endif; ?>
                  
                  <div class="row mb-3">
                    <div class="col-md-6">
                      <form method="GET" class="d-flex">
                        <input type="hidden" name="controller" value="manajer">
                        <input type="hidden" name="action" value="daftar">
                        <input type="text" class="form-control me-2" name="search" 
                               value="<?= htmlspecialchars($search) ?>" 
                               placeholder="Cari manajer...">
                        <button type="submit" class="btn btn-outline-primary">
                          <i class="mdi mdi-magnify"></i>
                        </button>
                        <?php if (!empty($search)): ?>
                          <a href="index.php?controller=manajer&action=daftar" class="btn btn-outline-secondary ms-2">
                            <i class="mdi mdi-close"></i>
                          </a>
                        <?php endif; ?>
                      </form>
                    </div>
                    <div class="col-md-6">
                      <form method="GET" class="d-flex">
                        <input type="hidden" name="controller" value="manajer">
                        <input type="hidden" name="action" value="daftar">
                        <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
                        <select class="form-control me-2" name="store_id" onchange="this.form.submit()">
                          <option value="">-- Semua Store --</option>
                          <?php foreach ($stores as $store): ?>
                            <option value="<?= $store['id_store'] ?>" 
                                    <?= ($store_filter == $store['id_store']) ? 'selected' : '' ?>>
                              <?= htmlspecialchars($store['nama_store']) ?>
                            </option>
                          <?php endforeach; ?>
                        </select>
                        <?php if (!empty($store_filter)): ?>
                          <a href="index.php?controller=manajer&action=daftar<?= !empty($search) ? '&search=' . urlencode($search) : '' ?>" class="btn btn-outline-secondary">
                            <i class="mdi mdi-close"></i>
                          </a>
                        <?php endif; ?>
                      </form>
                    </div>
                  </div>
                  
                  <div class="table-responsive">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Username</th>
                          <th>Nama Lengkap</th>
                          <th>Email</th>
                          <th>Store</th>
                          <th>Role</th>
                          <th>Status</th>
                          <th>Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if (!empty($managers)): ?>
                          <?php $no = 1; ?>
                          <?php foreach ($managers as $manager): ?>
                            <tr>
                              <td><?= $no++ ?></td>
                              <td>
                                <strong><?= htmlspecialchars($manager['username']) ?></strong>
                                <small class="text-muted d-block">ID: <?= $manager['user_id'] ?></small>
                              </td>
                              <td><?= htmlspecialchars($manager['nama_lengkap']) ?></td>
                              <td><?= htmlspecialchars($manager['email'] ?: '-') ?></td>
                              <td>
                                <?php if ($manager['nama_store']): ?>
                                  <span class="badge bg-info">
                                    <?= htmlspecialchars($manager['nama_store']) ?>
                                  </span>
                                  <small class="text-muted d-block"><?= htmlspecialchars($manager['alamat_store']) ?></small>
                                <?php else: ?>
                                  <span class="badge bg-warning text-dark">Tidak Ada Store</span>
                                <?php endif; ?>
                              </td>
                              <td>
                                <?php 
                                  $roleColors = [
                                    'admin' => 'bg-danger',
                                    'manajer' => 'bg-primary', 
                                    'member' => 'bg-success'
                                  ];
                                  $roleColor = $roleColors[$manager['role']] ?? 'bg-secondary';
                                ?>
                                <span class="badge <?= $roleColor ?>">
                                  <?= ucfirst($manager['role']) ?>
                                </span>
                              </td>
                              <td>
                                <?php if ($manager['status'] == 'aktif'): ?>
                                  <span class="badge bg-success">
                                    <i class="mdi mdi-check"></i> Aktif
                                  </span>
                                <?php else: ?>
                                  <span class="badge bg-danger">
                                    <i class="mdi mdi-close"></i> Tidak Aktif
                                  </span>
                                <?php endif; ?>
                              </td>
                              <td>
                                <div class="btn-group" role="group">
                                  <a href="index.php?controller=manajer&action=edit&id=<?= $manager['user_id'] ?>" 
                                     class="btn btn-sm btn-warning" title="Edit">
                                    <i class="mdi mdi-pencil"></i>
                                  </a>
                                  <a href="index.php?controller=manajer&action=toggleStatus&id=<?= $manager['user_id'] ?>" 
                                     class="btn btn-sm <?= $manager['status'] == 'aktif' ? 'btn-secondary' : 'btn-success' ?>" 
                                     title="<?= $manager['status'] == 'aktif' ? 'Nonaktifkan' : 'Aktifkan' ?>"
                                     onclick="return confirm('Yakin ingin mengubah status manajer ini?')">
                                    <i class="mdi mdi-<?= $manager['status'] == 'aktif' ? 'pause' : 'play' ?>"></i>
                                  </a>
                                  <button type="button" class="btn btn-sm btn-danger" 
                                          onclick="confirmDelete(<?= $manager['user_id'] ?>, '<?= htmlspecialchars($manager['nama_lengkap']) ?>')"
                                          title="Hapus">
                                    <i class="mdi mdi-delete"></i>
                                  </button>
                                </div>
                              </td>
                            </tr>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <tr>
                            <td colspan="8" class="text-center py-4">
                              <div class="empty-state">
                                <i class="mdi mdi-account-group-outline text-muted" style="font-size: 3rem;"></i>
                                <h5 class="text-muted mt-2">
                                  <?php if (!empty($search) || !empty($store_filter)): ?>
                                    Tidak ada manajer yang ditemukan
                                  <?php else: ?>
                                    Belum ada data manajer
                                  <?php endif; ?>
                                </h5>
                                <?php if (!empty($search) || !empty($store_filter)): ?>
                                  <p class="text-muted">
                                    <?php if (!empty($search)): ?>
                                      dengan kata kunci "<?= htmlspecialchars($search) ?>"
                                    <?php endif ?>
                                    <?php if (!empty($search) && !empty($store_filter)): ?> dan <?php endif ?>
                                    <?php if (!empty($store_filter)): ?>
                                      di store yang dipilih
                                    <?php endif ?>
                                  </p>
                                  <a href="index.php?controller=manajer&action=daftar" class="btn btn-outline-primary">
                                    <i class="mdi mdi-arrow-left"></i> Tampilkan Semua
                                  </a>
                                <?php else: ?>
                                  <p class="text-muted">Mulai dengan menambahkan manajer pertama</p>
                                  <a href="index.php?controller=manajer&action=registrasi" class="btn btn-primary">
                                    <i class="mdi mdi-account-plus"></i> Tambah Manajer
                                  </a>
                                <?php endif; ?>
                              </div>
                            </td>
                          </tr>
                        <?php endif; ?>
                      </tbody>
                    </table>
                  </div>
                  
                  <?php if (!empty($managers)): ?>
                    <div class="mt-3 d-flex justify-content-between align-items-center">
                      <small class="text-muted">
                        Menampilkan <?= count($managers) ?> manajer
                        <?php if (!empty($search)): ?>
                          dari pencarian "<?= htmlspecialchars($search) ?>"
                        <?php endif; ?>
                        <?php if (!empty($store_filter)): ?>
                          <?php 
                            $selectedStore = array_filter($stores, function($s) use ($store_filter) { 
                              return $s['id_store'] == $store_filter; 
                            });
                            $selectedStore = reset($selectedStore);
                          ?>
                          di store "<?= htmlspecialchars($selectedStore['nama_store']) ?>"
                        <?php endif; ?>
                      </small>
                      
                      <div class="d-flex gap-2">
                        <small class="text-muted">
                          <span class="badge bg-success">Aktif: <?= count(array_filter($managers, function($m) { return $m['status'] == 'aktif'; })) ?></span>
                        </small>
                        <small class="text-muted">
                          <span class="badge bg-primary">Manajer: <?= count(array_filter($managers, function($m) { return $m['role'] == 'manajer'; })) ?></span>
                        </small>
                        <small class="text-muted">
                          <span class="badge bg-danger">Admin: <?= count(array_filter($managers, function($m) { return $m['role'] == 'admin'; })) ?></span>
                        </small>
                      </div>
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
    .card {
      box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
      border: 1px solid rgba(0, 0, 0, 0.125);
    }
    
    .table th {
      background-color: #f8f9fa;
      font-weight: 600;
      border-bottom: 2px solid #dee2e6;
    }
    
    .btn-group .btn {
      margin-right: 0;
    }
    
    .badge {
      font-size: 0.75em;
      padding: 0.375rem 0.5rem;
    }
    
    .bg-success {
      background-color: #198754 !important;
      color: white;
    }
    
    .bg-danger {
      background-color: #dc3545 !important;
      color: white;
    }
    
    .bg-primary {
      background-color: #0d6efd !important;
      color: white;
    }
    
    .bg-warning {
      background-color: #ffc107 !important;
      color: #000;
    }
    
    .bg-info {
      background-color: #0dcaf0 !important;
      color: #000;
    }
    
    .bg-secondary {
      background-color: #6c757d !important;
      color: white;
    }
    
    .alert {
      border: none;
      border-radius: 0.5rem;
    }
    
    .btn {
      border-radius: 0.375rem;
    }
    
    .btn-primary {
      background-color: #0d6efd;
      border-color: #0d6efd;
    }
    
    .btn-info {
      background-color: #0dcaf0;
      border-color: #0dcaf0;
      color: #000;
    }
    
    .btn-warning {
      background-color: #ffc107;
      border-color: #ffc107;
      color: #000;
    }
    
    .btn-danger {
      background-color: #dc3545;
      border-color: #dc3545;
    }
    
    .table-responsive {
      border-radius: 0.5rem;
    }
    
    .empty-state {
      padding: 2rem;
    }
    
    .gap-2 {
      gap: 0.5rem;
    }
    
    .d-flex {
      display: flex;
    }
    
    .justify-content-between {
      justify-content: space-between;
    }
    
    .align-items-center {
      align-items: center;
    }
    
    @media (max-width: 992px) {
      .d-flex.justify-content-between .btn-group {
        flex-direction: column;
        width: 100%;
        margin-top: 1rem;
      }
      
      .d-flex.justify-content-between .btn-group .btn {
        margin-bottom: 0.25rem;
        border-radius: 0.375rem !important;
      }
    }
    
    @media (max-width: 768px) {
      .btn-group {
        flex-direction: column;
      }
      
      .btn-group .btn {
        margin-bottom: 0.25rem;
        border-radius: 0.375rem !important;
      }
      
      .row.mb-3 .col-md-6 {
        margin-bottom: 1rem;
      }
      
      .table-responsive {
        font-size: 0.875rem;
      }
      
      .badge {
        font-size: 0.7em;
        padding: 0.25rem 0.4rem;
      }
      
      .d-flex.justify-content-between:last-child {
        flex-direction: column;
        gap: 1rem;
      }
    }
  </style>
  
  <script>
    function confirmDelete(id, nama) {
      if (confirm(`Apakah Anda yakin ingin menghapus manajer "${nama}"?\n\nPerhatian: Data yang terkait akan ikut terhapus.`)) {
        window.location.href = `index.php?controller=manajer&action=delete&id=${id}`;
      }
    }
    
    document.addEventListener('DOMContentLoaded', function() {
      const alerts = document.querySelectorAll('.alert');
      alerts.forEach(alert => {
        setTimeout(() => {
          alert.style.opacity = '0';
          setTimeout(() => {
            alert.remove();
          }, 300);
        }, 5000);
      });
    });
  </script>
  
  <?php include 'template/script.php'; ?>
</body>