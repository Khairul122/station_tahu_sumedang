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
                  <h3 class="font-weight-bold">Registrasi Manajer Store</h3>
                  <h6 class="font-weight-normal mb-0">Daftarkan manajer baru untuk store</h6>
                </div>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-lg-8 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Form Registrasi Manajer</h4>
                  
                  <?php if (isset($error) && !empty($error)): ?>
                    <div class="alert alert-danger">
                      <i class="mdi mdi-alert-circle me-2"></i>
                      <?= $error ?>
                    </div>
                  <?php endif; ?>
                  
                  <?php if (isset($success) && !empty($success)): ?>
                    <div class="alert alert-success">
                      <i class="mdi mdi-check-circle me-2"></i>
                      <?= $success ?>
                    </div>
                  <?php endif; ?>
                  
                  <form method="POST" class="forms-sample">
                    <div class="form-group">
                      <label for="store_id">Pilih Store</label>
                      <select class="form-control" id="store_id" name="store_id" required>
                        <option value="">-- Pilih Store --</option>
                        <?php foreach ($stores as $store): ?>
                          <option value="<?= $store['id_store'] ?>" 
                                  <?= (isset($_POST['store_id']) && $_POST['store_id'] == $store['id_store']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($store['nama_store']) ?> - <?= htmlspecialchars($store['alamat_store']) ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                    
                    <div class="form-group">
                      <label for="username">Username</label>
                      <input type="text" class="form-control" id="username" name="username" 
                             value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" 
                             placeholder="Masukkan username" required>
                    </div>
                    
                    <div class="form-group">
                      <label for="password">Password</label>
                      <input type="password" class="form-control" id="password" name="password" 
                             placeholder="Masukkan password" required>
                    </div>
                    
                    <div class="form-group">
                      <label for="confirm_password">Konfirmasi Password</label>
                      <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                             placeholder="Konfirmasi password" required>
                    </div>
                    
                    <div class="form-group">
                      <label for="nama_lengkap">Nama Lengkap</label>
                      <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" 
                             value="<?= htmlspecialchars($_POST['nama_lengkap'] ?? '') ?>" 
                             placeholder="Masukkan nama lengkap" required>
                    </div>
                    
                    <div class="form-group">
                      <label for="email">Email</label>
                      <input type="email" class="form-control" id="email" name="email" 
                             value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" 
                             placeholder="Masukkan email (opsional)">
                    </div>
                    
                    <div class="form-group">
                      <label for="role">Role</label>
                      <input type="text" class="form-control" value="Manajer Store" readonly style="background-color: #e9ecef; color: #495057;">
                      <small class="form-text text-muted">
                        <i class="mdi mdi-information"></i> Role otomatis diset sebagai Manajer Store
                      </small>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                      <a href="index.php?controller=store" class="btn btn-light">
                        <i class="mdi mdi-arrow-left"></i> Kembali
                      </a>
                      <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-account-plus"></i> Daftar Manajer
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            
            <div class="col-lg-4 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Informasi</h4>
                  <div class="alert alert-info">
                    <h6><i class="mdi mdi-information"></i> Petunjuk:</h6>
                    <ul class="mb-0">
                      <li>Pilih store yang akan dikelola</li>
                      <li>Username harus unik</li>
                      <li>Password minimal 6 karakter</li>
                      <li>Email harus valid (opsional)</li>
                      <li>Role otomatis: Manajer Store</li>
                    </ul>
                  </div>
                  
                  <div class="mt-3">
                    <h6>Store Tersedia:</h6>
                    <div class="list-group">
                      <?php foreach ($stores as $store): ?>
                        <div class="list-group-item">
                          <strong><?= htmlspecialchars($store['nama_store']) ?></strong>
                          <small class="text-muted d-block"><?= htmlspecialchars($store['alamat_store']) ?></small>
                          <div class="d-flex justify-content-between align-items-center mt-2">
                            <span class="badge bg-<?= $store['status_store'] == 'aktif' ? 'success' : 'danger' ?>">
                              <?= ucfirst($store['status_store']) ?>
                            </span>
                            <?php if ($store['manajer_store']): ?>
                              <small class="text-muted">
                                <i class="mdi mdi-account-check text-success"></i>
                                Sudah ada manajer
                              </small>
                            <?php else: ?>
                              <small class="text-warning">
                                <i class="mdi mdi-account-alert"></i>
                                Butuh manajer
                              </small>
                            <?php endif; ?>
                          </div>
                        </div>
                      <?php endforeach; ?>
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
      border-radius: 0.5rem;
    }
    
    .form-group {
      margin-bottom: 1.5rem;
    }
    
    .form-control {
      border-radius: 0.375rem;
      border: 1px solid #ced4da;
      padding: 0.5rem 0.75rem;
    }
    
    .form-control:focus {
      border-color: #86b7fe;
      box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    .form-control[readonly] {
      background-color: #e9ecef !important;
      cursor: not-allowed;
    }
    
    .btn {
      border-radius: 0.375rem;
      padding: 0.5rem 1rem;
    }
    
    .btn-primary {
      background-color: #0d6efd;
      border-color: #0d6efd;
    }
    
    .btn-light {
      background-color: #f8f9fa;
      border-color: #f8f9fa;
      color: #212529;
    }
    
    .alert {
      border: none;
      border-radius: 0.5rem;
      padding: 1rem;
    }
    
    .alert-info {
      background-color: #d1ecf1;
      color: #0c5460;
    }
    
    .list-group-item {
      border: 1px solid rgba(0, 0, 0, 0.125);
      border-radius: 0.375rem;
      margin-bottom: 0.5rem;
      padding: 0.75rem;
    }
    
    .badge {
      font-size: 0.75em;
      padding: 0.25rem 0.5rem;
      border-radius: 0.25rem;
    }
    
    .bg-success {
      background-color: #198754 !important;
      color: white;
    }
    
    .bg-danger {
      background-color: #dc3545 !important;
      color: white;
    }
    
    .text-success {
      color: #198754 !important;
    }
    
    .text-warning {
      color: #ffc107 !important;
    }
    
    select.form-control {
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m1 6 7 7 7-7'/%3e%3c/svg%3e");
      background-repeat: no-repeat;
      background-position: right 0.75rem center;
      background-size: 16px 12px;
      padding-right: 2.25rem;
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
    
    @media (max-width: 768px) {
      .d-flex.justify-content-between:last-child {
        flex-direction: column;
      }
      
      .d-flex.justify-content-between:last-child .btn {
        margin-bottom: 0.5rem;
        width: 100%;
      }
    }
  </style>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const alerts = document.querySelectorAll('.alert:not(.alert-info)');
      alerts.forEach(alert => {
        setTimeout(() => {
          alert.style.opacity = '0';
          setTimeout(() => {
            alert.remove();
          }, 300);
        }, 5000);
      });
      
      const form = document.querySelector('form');
      form.addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        
        if (password !== confirmPassword) {
          e.preventDefault();
          alert('Password dan konfirmasi password tidak sama!');
          return false;
        }
        
        if (password.length < 6) {
          e.preventDefault();
          alert('Password minimal 6 karakter!');
          return false;
        }
      });
    });
  </script>
  
  <?php include 'template/script.php'; ?>
</body>

</html>