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
                    <?= $action == 'add' ? 'Tambah produk baru' : 'Edit informasi produk' ?>
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
                    <a href="index.php?controller=produk" class="btn btn-secondary">
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
                      <label for="nama_produk" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" id="nama_produk" name="nama_produk" 
                             value="<?= htmlspecialchars($produk['nama_produk'] ?? '') ?>" 
                             placeholder="Masukkan nama produk" required>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group mb-3">
                          <label for="kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
                          <select class="form-select" id="kategori" name="kategori" required>
                            <option value="">Pilih Kategori</option>
                            <option value="Original" <?= ($produk['kategori'] ?? '') == 'Original' ? 'selected' : '' ?>>Original</option>
                            <option value="Pedas" <?= ($produk['kategori'] ?? '') == 'Pedas' ? 'selected' : '' ?>>Pedas</option>
                            <option value="Isi" <?= ($produk['kategori'] ?? '') == 'Isi' ? 'selected' : '' ?>>Isi</option>
                            <option value="Mini" <?= ($produk['kategori'] ?? '') == 'Mini' ? 'selected' : '' ?>>Mini</option>
                            <option value="Lainnya" <?= ($produk['kategori'] ?? '') == 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                          </select>
                        </div>
                      </div>
                      
                      <div class="col-md-6">
                        <div class="form-group mb-3">
                          <label for="harga" class="form-label">Harga <span class="text-danger">*</span></label>
                          <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control" id="harga" name="harga" 
                                   value="<?= $produk['harga'] ?? '' ?>" 
                                   placeholder="0" min="0" step="100" required>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group mb-3">
                          <label for="stok" class="form-label">Stok</label>
                          <input type="number" class="form-control" id="stok" name="stok" 
                                 value="<?= $produk['stok'] ?? '0' ?>" 
                                 placeholder="0" min="0">
                          <small class="form-text text-muted">Jumlah stok yang tersedia</small>
                        </div>
                      </div>
                      
                      <div class="col-md-6">
                        <div class="form-group mb-3">
                          <label for="poin_reward" class="form-label">Poin Reward</label>
                          <input type="number" class="form-control" id="poin_reward" name="poin_reward" 
                                 value="<?= $produk['poin_reward'] ?? '0' ?>" 
                                 placeholder="0" min="0">
                          <small class="form-text text-muted">Poin yang didapat member saat membeli</small>
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
                      <a href="index.php?controller=produk" class="btn btn-secondary me-md-2">
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
                      <h6 class="mb-1">Nama Produk</h6>
                      <p class="mb-1 text-muted">Gunakan nama yang jelas dan mudah diingat customer</p>
                    </div>
                    <div class="list-group-item">
                      <h6 class="mb-1">Kategori</h6>
                      <p class="mb-1 text-muted">Pilih kategori yang sesuai untuk memudahkan pencarian</p>
                    </div>
                    <div class="list-group-item">
                      <h6 class="mb-1">Harga</h6>
                      <p class="mb-1 text-muted">Masukkan harga dalam rupiah tanpa titik atau koma</p>
                    </div>
                    <div class="list-group-item">
                      <h6 class="mb-1">Stok</h6>
                      <p class="mb-1 text-muted">Stok di bawah 20 akan muncul peringatan</p>
                    </div>
                    <div class="list-group-item">
                      <h6 class="mb-1">Poin Reward</h6>
                      <p class="mb-1 text-muted">Poin yang didapat member setiap pembelian produk ini</p>
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
    
    .input-group-text {
      background-color: #e9ecef;
      border: 1px solid #ced4da;
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
    
    .required::after {
      content: " *";
      color: #dc3545;
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
      const hargaInput = document.getElementById('harga');
      const stokInput = document.getElementById('stok');
      const poinInput = document.getElementById('poin_reward');
      
      // Format harga input
      hargaInput.addEventListener('input', function() {
        let value = this.value.replace(/\D/g, '');
        if (value) {
          this.value = value;
        }
      });
      
      // Validasi stok
      stokInput.addEventListener('input', function() {
        if (this.value < 0) {
          this.value = 0;
        }
      });
      
      // Validasi poin reward
      poinInput.addEventListener('input', function() {
        if (this.value < 0) {
          this.value = 0;
        }
      });
      
      // Form validation
      form.addEventListener('submit', function(e) {
        const namaProduk = document.getElementById('nama_produk').value.trim();
        const kategori = document.getElementById('kategori').value;
        const harga = parseFloat(hargaInput.value);
        const confirm = document.getElementById('confirm').checked;
        
        if (!namaProduk) {
          e.preventDefault();
          alert('Nama produk harus diisi');
          return;
        }
        
        if (!kategori) {
          e.preventDefault();
          alert('Kategori harus dipilih');
          return;
        }
        
        if (!harga || harga <= 0) {
          e.preventDefault();
          alert('Harga harus lebih dari 0');
          return;
        }
        
        if (!confirm) {
          e.preventDefault();
          alert('Harap centang konfirmasi terlebih dahulu');
          return;
        }
      });
      
      // Auto-calculate suggested poin reward based on harga
      hargaInput.addEventListener('blur', function() {
        const harga = parseFloat(this.value);
        const currentPoin = parseInt(poinInput.value) || 0;
        
        if (harga > 0 && currentPoin === 0) {
          // Suggest 1 poin for every 3000 rupiah
          const suggestedPoin = Math.max(1, Math.floor(harga / 3000));
          poinInput.value = suggestedPoin;
        }
      });
      
      // Show warning for low stock
      stokInput.addEventListener('blur', function() {
        const stok = parseInt(this.value) || 0;
        const warning = document.getElementById('stok-warning');
        
        if (warning) {
          warning.remove();
        }
        
        if (stok < 20 && stok > 0) {
          const warningDiv = document.createElement('div');
          warningDiv.id = 'stok-warning';
          warningDiv.className = 'alert alert-warning mt-2';
          warningDiv.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i>Stok rendah! Pertimbangkan untuk menambah stok.';
          this.parentNode.appendChild(warningDiv);
        }
      });
    });
  </script>
  
  <?php include 'template/script.php'; ?>
</body>

</html>