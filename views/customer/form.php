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
                    <?= $action == 'add' ? 'Tambah customer baru' : 'Edit informasi customer' ?>
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
                    <a href="index.php?controller=customer" class="btn btn-secondary">
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
                      <label for="nama_customer" class="form-label">Nama Customer <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" id="nama_customer" name="nama_customer" 
                             value="<?= htmlspecialchars($customer['nama_customer'] ?? '') ?>" 
                             placeholder="Masukkan nama lengkap customer" required>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group mb-3">
                          <label for="no_telepon" class="form-label">No Telepon <span class="text-danger">*</span></label>
                          <input type="text" class="form-control" id="no_telepon" name="no_telepon" 
                                 value="<?= htmlspecialchars($customer['no_telepon'] ?? '') ?>" 
                                 placeholder="08xxxxxxxxxx" required>
                        </div>
                      </div>
                      
                      <div class="col-md-6">
                        <div class="form-group mb-3">
                          <label for="email" class="form-label">Email</label>
                          <input type="email" class="form-control" id="email" name="email" 
                                 value="<?= htmlspecialchars($customer['email'] ?? '') ?>" 
                                 placeholder="customer@email.com">
                        </div>
                      </div>
                    </div>
                    
                    <div class="form-group mb-3">
                      <label for="alamat" class="form-label">Alamat</label>
                      <textarea class="form-control" id="alamat" name="alamat" rows="3" 
                                placeholder="Masukkan alamat lengkap customer"><?= htmlspecialchars($customer['alamat'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="form-group mb-3">
                      <label for="membership_id" class="form-label">Membership <span class="text-danger">*</span></label>
                      <select class="form-select" id="membership_id" name="membership_id" required>
                        <option value="">Pilih Membership</option>
                        <?php foreach ($memberships as $membership): ?>
                          <option value="<?= $membership['membership_id'] ?>" 
                                  <?= ($customer['membership_id'] ?? 1) == $membership['membership_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($membership['nama_membership']) ?> 
                            (Min. Rp <?= number_format($membership['minimal_pembelian']) ?>)
                          </option>
                        <?php endforeach; ?>
                      </select>
                      <small class="form-text text-muted">Membership akan otomatis upgrade berdasarkan total pembelian</small>
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
                      <a href="index.php?controller=customer" class="btn btn-secondary me-md-2">
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
                  <h4 class="card-title">Informasi Membership</h4>
                  <div class="membership-info">
                    <?php foreach ($memberships as $membership): ?>
                    <div class="membership-tier mb-3">
                      <div class="tier-header">
                        <h6 class="tier-name"><?= htmlspecialchars($membership['nama_membership']) ?></h6>
                        <span class="tier-discount"><?= $membership['diskon_persen'] ?>% diskon</span>
                      </div>
                      <div class="tier-details">
                        <p class="tier-requirement">Min. pembelian: Rp <?= number_format($membership['minimal_pembelian']) ?></p>
                        <p class="tier-benefit">Poin: <?= $membership['poin_per_pembelian'] ?>x per pembelian</p>
                        <p class="tier-benefit"><?= htmlspecialchars($membership['benefit']) ?></p>
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
    
    .membership-info {
      max-height: 400px;
      overflow-y: auto;
    }
    
    .membership-tier {
      border: 1px solid #dee2e6;
      border-radius: 0.375rem;
      padding: 1rem;
      background: #f8f9fa;
    }
    
    .tier-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 0.5rem;
    }
    
    .tier-name {
      font-weight: 600;
      color: #495057;
      margin-bottom: 0;
    }
    
    .tier-discount {
      background: #28a745;
      color: white;
      padding: 0.25rem 0.5rem;
      border-radius: 0.25rem;
      font-size: 0.75rem;
      font-weight: 600;
    }
    
    .tier-details p {
      margin-bottom: 0.25rem;
      font-size: 0.875rem;
    }
    
    .tier-requirement {
      color: #6c757d;
      font-weight: 500;
    }
    
    .tier-benefit {
      color: #495057;
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
    
    .is-invalid {
      border-color: #dc3545;
      box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
    }
    
    .invalid-feedback {
      display: block;
      width: 100%;
      margin-top: 0.25rem;
      font-size: 0.875rem;
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
      
      .membership-info {
        max-height: none;
      }
    }
  </style>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const form = document.querySelector('form');
      const namaInput = document.getElementById('nama_customer');
      const teleponInput = document.getElementById('no_telepon');
      const emailInput = document.getElementById('email');
      
      // Format no telepon
      teleponInput.addEventListener('input', function() {
        let value = this.value.replace(/\D/g, '');
        if (value.startsWith('62')) {
          value = '+' + value;
        } else if (value.startsWith('8')) {
          value = '0' + value;
        }
        this.value = value;
      });
      
      // Validasi nama customer
      namaInput.addEventListener('input', function() {
        this.value = this.value.replace(/[^a-zA-Z\s]/g, '');
      });
      
      // Form validation
      form.addEventListener('submit', function(e) {
        const nama = namaInput.value.trim();
        const telepon = teleponInput.value.trim();
        const email = emailInput.value.trim();
        const membership = document.getElementById('membership_id').value;
        const confirm = document.getElementById('confirm').checked;
        
        if (!nama) {
          e.preventDefault();
          alert('Nama customer harus diisi');
          namaInput.focus();
          return;
        }
        
        if (!telepon) {
          e.preventDefault();
          alert('No telepon harus diisi');
          teleponInput.focus();
          return;
        }
        
        if (telepon.length < 10) {
          e.preventDefault();
          alert('No telepon tidak valid');
          teleponInput.focus();
          return;
        }
        
        if (email && !isValidEmail(email)) {
          e.preventDefault();
          alert('Format email tidak valid');
          emailInput.focus();
          return;
        }
        
        if (!membership) {
          e.preventDefault();
          alert('Membership harus dipilih');
          document.getElementById('membership_id').focus();
          return;
        }
        
        if (!confirm) {
          e.preventDefault();
          alert('Harap centang konfirmasi terlebih dahulu');
          return;
        }
      });
      
      function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
      }
      
      // Real-time validation feedback
      namaInput.addEventListener('blur', function() {
        if (this.value.trim().length < 3) {
          this.classList.add('is-invalid');
          showFeedback(this, 'Nama minimal 3 karakter');
        } else {
          this.classList.remove('is-invalid');
          hideFeedback(this);
        }
      });
      
      teleponInput.addEventListener('blur', function() {
        if (this.value.trim().length < 10) {
          this.classList.add('is-invalid');
          showFeedback(this, 'No telepon minimal 10 digit');
        } else {
          this.classList.remove('is-invalid');
          hideFeedback(this);
        }
      });
      
      emailInput.addEventListener('blur', function() {
        if (this.value.trim() && !isValidEmail(this.value.trim())) {
          this.classList.add('is-invalid');
          showFeedback(this, 'Format email tidak valid');
        } else {
          this.classList.remove('is-invalid');
          hideFeedback(this);
        }
      });
      
      function showFeedback(element, message) {
        hideFeedback(element);
        const feedback = document.createElement('div');
        feedback.className = 'invalid-feedback';
        feedback.textContent = message;
        element.parentNode.appendChild(feedback);
      }
      
      function hideFeedback(element) {
        const feedback = element.parentNode.querySelector('.invalid-feedback');
        if (feedback) {
          feedback.remove();
        }
      }
    });
  </script>
  
  <?php include 'template/script.php'; ?>
</body>

</html>