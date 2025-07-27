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
                  <h6 class="font-weight-normal mb-0">Kelola semua store Station Tahu Sumedang</h6>
                </div>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title">Daftar Store</h4>
                    <a href="index.php?controller=store&action=add" class="btn btn-primary">
                      <i class="mdi mdi-plus"></i> Tambah Store
                    </a>
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
                        <input type="hidden" name="controller" value="store">
                        <input type="text" class="form-control me-2" name="search" 
                               value="<?= htmlspecialchars($search) ?>" 
                               placeholder="Cari store...">
                        <button type="submit" class="btn btn-outline-primary">
                          <i class="mdi mdi-magnify"></i>
                        </button>
                        <?php if (!empty($search)): ?>
                          <a href="index.php?controller=store" class="btn btn-outline-secondary ms-2">
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
                          <th>Nama Store</th>
                          <th>Alamat</th>
                          <th>Manajer</th>
                          <th>Status</th>
                          <th>Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if (!empty($stores)): ?>
                          <?php $no = 1; ?>
                          <?php foreach ($stores as $store): ?>
                            <tr>
                              <td><?= $no++ ?></td>
                              <td>
                                <strong><?= htmlspecialchars($store['nama_store']) ?></strong>
                                <small class="text-muted d-block">ID: <?= $store['id_store'] ?></small>
                              </td>
                              <td><?= htmlspecialchars($store['alamat_store']) ?></td>
                              <td><?= htmlspecialchars($store['manajer_store'] ?: '-') ?></td>
                              <td>
                                <?php if ($store['status_store'] == 'aktif'): ?>
                                  <span class="badge bg-success">Aktif</span>
                                <?php else: ?>
                                  <span class="badge bg-danger">Tidak Aktif</span>
                                <?php endif; ?>
                              </td>
                              <td>
                                <div class="btn-group" role="group">
                                  <a href="index.php?controller=store&action=view&id=<?= $store['id_store'] ?>" 
                                     class="btn btn-sm btn-info" title="Lihat Detail">
                                    <i class="mdi mdi-eye"></i>
                                  </a>
                                  <a href="index.php?controller=store&action=edit&id=<?= $store['id_store'] ?>" 
                                     class="btn btn-sm btn-warning" title="Edit">
                                    <i class="mdi mdi-pencil"></i>
                                  </a>
                                  <button type="button" class="btn btn-sm btn-danger" 
                                          onclick="confirmDelete(<?= $store['id_store'] ?>, '<?= htmlspecialchars($store['nama_store']) ?>')"
                                          title="Hapus">
                                    <i class="mdi mdi-delete"></i>
                                  </button>
                                </div>
                              </td>
                            </tr>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <tr>
                            <td colspan="6" class="text-center">
                              <?php if (!empty($search)): ?>
                                Tidak ada store yang ditemukan dengan kata kunci "<?= htmlspecialchars($search) ?>"
                              <?php else: ?>
                                Belum ada data store
                              <?php endif; ?>
                            </td>
                          </tr>
                        <?php endif; ?>
                      </tbody>
                    </table>
                  </div>
                  
                  <?php if (!empty($stores)): ?>
                    <div class="mt-3">
                      <small class="text-muted">
                        Menampilkan <?= count($stores) ?> store
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
    }
    
    .bg-danger {
      background-color: #dc3545 !important;
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
    
    @media (max-width: 768px) {
      .btn-group {
        flex-direction: column;
      }
      
      .btn-group .btn {
        margin-bottom: 0.25rem;
        border-radius: 0.375rem !important;
      }
    }
  </style>
  
  <script>
    function confirmDelete(id, nama) {
      if (confirm(`Apakah Anda yakin ingin menghapus store "${nama}"?\n\nPerhatian: Store yang memiliki produk tidak dapat dihapus.`)) {
        window.location.href = `index.php?controller=store&action=delete&id=${id}`;
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

</html>