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
                  <h6 class="font-weight-normal mb-0">
                    <?= $action == 'add' ? 'Tambah store baru' : 'Edit informasi store' ?>
                  </h6>
                </div>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-lg-8 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title"><?= $title ?></h4>
                    <a href="index.php?controller=store" class="btn btn-secondary">
                      <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                  </div>
                  
                  <?php if (!empty($error)): ?>
                    <div class="alert alert-danger">
                      <i class="fas fa-exclamation-circle me-2"></i>
                      <?= $error ?>
                    </div>
                  <?php endif; ?>
                  
                  <?php if (!empty($success)): ?>
                    <div class="alert alert-success">
                      <i class="fas fa-check-circle me-2"></i>
                      <?= $success ?>
                    </div>
                  <?php endif; ?>
                  
                  <form method="POST" class="forms-sample">
                    <div class="form-group mb-3">
                      <label for="nama_store" class="form-label">Nama Store <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" id="nama_store" name="nama_store" 
                             value="<?= htmlspecialchars($store['nama_store'] ?? '') ?>" 
                             placeholder="Masukkan nama store" required>
                    </div>
                    
                    <div class="form-group mb-3">
                      <label for="alamat_store" class="form-label">Alamat Store <span class="text-danger">*</span></label>
                      <textarea class="form-control" id="alamat_store" name="alamat_store" 
                                rows="3" placeholder="Masukkan alamat lengkap store" required><?= htmlspecialchars($store['alamat_store'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group mb-3">
                          <label for="manajer_store" class="form-label">Manajer Store</label>
                          <input type="text" class="form-control" id="manajer_store" name="manajer_store" 
                                 value="<?= htmlspecialchars($store['manajer_store'] ?? '') ?>" 
                                 placeholder="Nama manajer store">
                          <small class="form-text text-muted">Opsional - bisa diisi nanti</small>
                        </div>
                      </div>
                      
                      <div class="col-md-6">
                        <div class="form-group mb-3">
                          <label for="status_store" class="form-label">Status Store <span class="text-danger">*</span></label>
                          <select class="form-select" id="status_store" name="status_store" required>
                            <option value="aktif" <?= ($store['status_store'] ?? 'aktif') == 'aktif' ? 'selected' : '' ?>>Aktif</option>
                            <option value="tidak_aktif" <?= ($store['status_store'] ?? '') == 'tidak_aktif' ? 'selected' : '' ?>>Tidak Aktif</option>
                          </select>
                        </div>
                      </div>
                    </div>
                    
                    <div class="form-group mb-4">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="confirm" required>
                        <label class="form-check-label" for="confirm">
                          Saya yakin data yang dimasukkan sudah benar
                        </label>
                      </div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                      <a href="index.php?controller=store" class="btn btn-secondary me-md-2">
                        <i class="fas fa-times"></i> Batal
                      </a>
                      <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> 
                        <?= $action == 'add' ? 'Simpan' : 'Update' ?>
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            
            <div class="col-lg-4 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Panduan</h4>
                  <div class="list-group list-group-flush">
                    <div class="list-group-item">
                      <h6 class="mb-1">Nama Store</h6>
                      <p class="mb-1 text-muted">Gunakan nama yang mudah dikenali dan unik</p>
                    </div>
                    <div class="list-group-item">
                      <h6 class="mb-1">Alamat Store</h6>
                      <p class="mb-1 text-muted">Tulis alamat lengkap termasuk kota dan kode pos</p>
                    </div>
                    <div class="list-group-item">
                      <h6 class="mb-1">Manajer Store</h6>
                      <p class="mb-1 text-muted">Nama penanggung jawab store (bisa diisi nanti)</p>
                    </div>
                    <div class="list-group-item">
                      <h6 class="mb-1">Status Store</h6>
                      <p class="mb-1 text-muted">Store tidak aktif tidak akan muncul di pilihan produk</p>
                    </div>
                  </div>
                </div>
              </div>
              
              <?php if ($action == 'add'): ?>
                <div class="card mt-3">
                  <div class="card-body">
                    <h4 class="card-title">Tips</h4>
                    <div class="alert alert-info">
                      <i class="fas fa-lightbulb me-2"></i>
                      Setelah store dibuat, Anda bisa menambahkan produk ke store ini melalui menu Produk.
                    </div>
                  </div>
                </div>
              <?php endif; ?>
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
    
    .form-label {
      font-weight: 500;
      margin-bottom: 0.5rem;
    }
    
    .form-control, .form-select {
      border-radius: 0.375rem;
      border: 1px solid #ced4da;
    }
    
    .form-control:focus, .form-select:focus {
      border-color: #86b7fe;
      box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    .alert {
      border: none;
      border-radius: 0.5rem;
    }
    
    .text-danger {
      color: #dc3545;
    }
    
    .text-muted {
      color: #6c757d;
    }
    
    .btn {
      border-radius: 0.375rem;
      padding: 0.375rem 0.75rem;
    }
    
    .btn-primary {
      background-color: #0d6efd;
      border-color: #0d6efd;
    }
    
    .btn-secondary {
      background-color: #6c757d;
      border-color: #6c757d;
    }
    
    .list-group-item {
      border: none;
      padding: 1rem 0;
    }
    
    .list-group-item:not(:last-child) {
      border-bottom: 1px solid #dee2e6;
    }
    
    .list-group-item h6 {
      color: #495057;
      font-weight: 600;
    }
    
    .form-check-input:checked {
      background-color: #0d6efd;
      border-color: #0d6efd;
    }
    
    .alert-info {
      color: #055160;
      background-color: #cff4fc;
      border-color: #b6effb;
    }
    
    @media (max-width: 768px) {
      .d-md-flex {
        flex-direction: column;
      }
      
      .me-md-2 {
        margin-right: 0 !important;
        margin-bottom: 0.5rem;
      }
    }
  </style>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const form = document.querySelector('form');
      const namaStoreInput = document.getElementById('nama_store');
      const alamatStoreInput = document.getElementById('alamat_store');
      const confirm = document.getElementById('confirm');
      
      form.addEventListener('submit', function(e) {
        const namaStore = namaStoreInput.value.trim();
        const alamatStore = alamatStoreInput.value.trim();
        
        if (!namaStore) {
          e.preventDefault();
          alert('Nama store harus diisi');
          namaStoreInput.focus();
          return;
        }
        
        if (!alamatStore) {
          e.preventDefault();
          alert('Alamat store harus diisi');
          alamatStoreInput.focus();
          return;
        }
        
        if (!confirm.checked) {
          e.preventDefault();
          alert('Harap centang konfirmasi terlebih dahulu');
          confirm.focus();
          return;
        }
      });
      
      namaStoreInput.addEventListener('input', function() {
        this.value = this.value.replace(/[^a-zA-Z0-9\s\-]/g, '');
      });
      
      alamatStoreInput.addEventListener('input', function() {
        if (this.value.length > 500) {
          this.value = this.value.substring(0, 500);
        }
      });
    });
  </script>
  
  <?php include 'template/script.php'; ?>
</body>

</html>