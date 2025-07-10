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
                    <?= $action == 'add' ? 'Tambah membership level baru' : 'Edit informasi membership' ?>
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
                    <a href="index.php?controller=membership" class="btn btn-secondary">
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
                      <label for="nama_membership" class="form-label">Nama Membership <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" id="nama_membership" name="nama_membership" 
                             value="<?= htmlspecialchars($membership['nama_membership'] ?? '') ?>" 
                             placeholder="Contoh: Bronze, Silver, Gold, Platinum" required>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group mb-3">
                          <label for="minimal_pembelian" class="form-label">Minimal Pembelian <span class="text-danger">*</span></label>
                          <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control" id="minimal_pembelian" name="minimal_pembelian" 
                                   value="<?= $membership['minimal_pembelian'] ?? '0' ?>" 
                                   placeholder="0" min="0" step="1000" required>
                          </div>
                          <small class="form-text text-muted">Minimal pembelian untuk mencapai level ini</small>
                        </div>
                      </div>
                      
                      <div class="col-md-6">
                        <div class="form-group mb-3">
                          <label for="diskon_persen" class="form-label">Diskon Persen <span class="text-danger">*</span></label>
                          <div class="input-group">
                            <input type="number" class="form-control" id="diskon_persen" name="diskon_persen" 
                                   value="<?= $membership['diskon_persen'] ?? '0' ?>" 
                                   placeholder="0" min="0" max="100" required>
                            <span class="input-group-text">%</span>
                          </div>
                          <small class="form-text text-muted">Diskon yang didapat member level ini</small>
                        </div>
                      </div>
                    </div>
                    
                    <div class="form-group mb-3">
                      <label for="poin_per_pembelian" class="form-label">Poin per Pembelian <span class="text-danger">*</span></label>
                      <div class="input-group">
                        <input type="number" class="form-control" id="poin_per_pembelian" name="poin_per_pembelian" 
                               value="<?= $membership['poin_per_pembelian'] ?? '1' ?>" 
                               placeholder="1" min="1" max="10" required>
                        <span class="input-group-text">x</span>
                      </div>
                      <small class="form-text text-muted">Multiplier poin yang didapat (1x = normal, 2x = double, dst)</small>
                    </div>
                    
                    <div class="form-group mb-3">
                      <label for="benefit" class="form-label">Benefit <span class="text-danger">*</span></label>
                      <textarea class="form-control" id="benefit" name="benefit" rows="3" 
                                placeholder="Contoh: Diskon 10% + 3x poin + prioritas layanan" required><?= htmlspecialchars($membership['benefit'] ?? '') ?></textarea>
                      <small class="form-text text-muted">Deskripsi benefit yang didapat member level ini</small>
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
                      <a href="index.php?controller=membership" class="btn btn-secondary me-md-2">
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
                  <h4 class="card-title">Contoh Membership</h4>
                  <div class="membership-examples">
                    <div class="example-item">
                      <h6 class="example-title">Bronze</h6>
                      <p class="example-detail">Min: Rp 0 | Diskon: 0% | Poin: 1x</p>
                      <p class="example-benefit">Benefit: Poin setiap pembelian</p>
                    </div>
                    
                    <div class="example-item">
                      <h6 class="example-title">Silver</h6>
                      <p class="example-detail">Min: Rp 100.000 | Diskon: 5% | Poin: 2x</p>
                      <p class="example-benefit">Benefit: Diskon 5% + 2x poin</p>
                    </div>
                    
                    <div class="example-item">
                      <h6 class="example-title">Gold</h6>
                      <p class="example-detail">Min: Rp 300.000 | Diskon: 10% | Poin: 3x</p>
                      <p class="example-benefit">Benefit: Diskon 10% + 3x poin + prioritas</p>
                    </div>
                    
                    <div class="example-item">
                      <h6 class="example-title">Platinum</h6>
                      <p class="example-detail">Min: Rp 500.000 | Diskon: 15% | Poin: 5x</p>
                      <p class="example-benefit">Benefit: Diskon 15% + 5x poin + free delivery</p>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Tips</h4>
                  <div class="tips-list">
                    <div class="tip-item">
                      <i class="fas fa-lightbulb text-warning"></i>
                      <p>Buat nama membership yang mudah diingat dan menarik</p>
                    </div>
                    <div class="tip-item">
                      <i class="fas fa-chart-line text-success"></i>
                      <p>Atur minimal pembelian secara bertahap untuk mendorong loyalty</p>
                    </div>
                    <div class="tip-item">
                      <i class="fas fa-percentage text-info"></i>
                      <p>Diskon yang terlalu tinggi dapat mengurangi profit</p>
                    </div>
                    <div class="tip-item">
                      <i class="fas fa-star text-primary"></i>
                      <p>Poin multiplier yang tinggi meningkatkan engagement</p>
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
    
    .membership-examples {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }
    
    .example-item {
      padding: 1rem;
      background: #f8f9fa;
      border-radius: 0.375rem;
      border-left: 4px solid #0d6efd;
    }
    
    .example-title {
      font-weight: 600;
      color: #495057;
      margin-bottom: 0.5rem;
    }
    
    .example-detail {
      font-size: 0.875rem;
      color: #6c757d;
      margin-bottom: 0.5rem;
    }
    
    .example-benefit {
      font-size: 0.875rem;
      color: #495057;
      margin-bottom: 0;
    }
    
    .tips-list {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }
    
    .tip-item {
      display: flex;
      align-items: flex-start;
      padding: 0.75rem;
      background: #f8f9fa;
      border-radius: 0.375rem;
    }
    
    .tip-item i {
      margin-right: 0.75rem;
      margin-top: 0.25rem;
      flex-shrink: 0;
    }
    
    .tip-item p {
      margin-bottom: 0;
      font-size: 0.875rem;
      color: #495057;
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
      
      .membership-examples {
        margin-top: 2rem;
      }
    }
  </style>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const form = document.querySelector('form');
      const namaInput = document.getElementById('nama_membership');
      const minimalInput = document.getElementById('minimal_pembelian');
      const diskonInput = document.getElementById('diskon_persen');
      const poinInput = document.getElementById('poin_per_pembelian');
      const benefitInput = document.getElementById('benefit');
      
      // Format minimal pembelian
      minimalInput.addEventListener('input', function() {
        let value = this.value.replace(/\D/g, '');
        this.value = value;
      });
      
      // Validasi diskon persen
      diskonInput.addEventListener('input', function() {
        if (this.value > 100) {
          this.value = 100;
        } else if (this.value < 0) {
          this.value = 0;
        }
      });
      
      // Validasi poin per pembelian
      poinInput.addEventListener('input', function() {
        if (this.value > 10) {
          this.value = 10;
        } else if (this.value < 1) {
          this.value = 1;
        }
      });
      
      // Form validation
      form.addEventListener('submit', function(e) {
        const nama = namaInput.value.trim();
        const minimal = parseFloat(minimalInput.value);
        const diskon = parseInt(diskonInput.value);
        const poin = parseInt(poinInput.value);
        const benefit = benefitInput.value.trim();
        const confirm = document.getElementById('confirm').checked;
        
        if (!nama) {
          e.preventDefault();
          alert('Nama membership harus diisi');
          namaInput.focus();
          return;
        }
        
        if (isNaN(minimal) || minimal < 0) {
          e.preventDefault();
          alert('Minimal pembelian harus valid');
          minimalInput.focus();
          return;
        }
        
        if (isNaN(diskon) || diskon < 0 || diskon > 100) {
          e.preventDefault();
          alert('Diskon persen harus antara 0-100');
          diskonInput.focus();
          return;
        }
        
        if (isNaN(poin) || poin < 1 || poin > 10) {
          e.preventDefault();
          alert('Poin per pembelian harus antara 1-10');
          poinInput.focus();
          return;
        }
        
        if (!benefit) {
          e.preventDefault();
          alert('Benefit harus diisi');
          benefitInput.focus();
          return;
        }
        
        if (!confirm) {
          e.preventDefault();
          alert('Harap centang konfirmasi terlebih dahulu');
          return;
        }
      });
      
      // Real-time validation feedback
      namaInput.addEventListener('blur', function() {
        if (this.value.trim().length < 3) {
          this.classList.add('is-invalid');
          showFeedback(this, 'Nama membership minimal 3 karakter');
        } else {
          this.classList.remove('is-invalid');
          hideFeedback(this);
        }
      });
      
      minimalInput.addEventListener('blur', function() {
        if (this.value < 0) {
          this.classList.add('is-invalid');
          showFeedback(this, 'Minimal pembelian tidak boleh negatif');
        } else {
          this.classList.remove('is-invalid');
          hideFeedback(this);
        }
      });
      
      diskonInput.addEventListener('blur', function() {
        if (this.value < 0 || this.value > 100) {
          this.classList.add('is-invalid');
          showFeedback(this, 'Diskon harus antara 0-100%');
        } else {
          this.classList.remove('is-invalid');
          hideFeedback(this);
        }
      });
      
      poinInput.addEventListener('blur', function() {
        if (this.value < 1 || this.value > 10) {
          this.classList.add('is-invalid');
          showFeedback(this, 'Poin harus antara 1-10x');
        } else {
          this.classList.remove('is-invalid');
          hideFeedback(this);
        }
      });
      
      benefitInput.addEventListener('blur', function() {
        if (this.value.trim().length < 10) {
          this.classList.add('is-invalid');
          showFeedback(this, 'Benefit minimal 10 karakter');
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
      
      // Auto-generate benefit based on values
      function updateBenefitSuggestion() {
        const diskon = parseInt(diskonInput.value) || 0;
        const poin = parseInt(poinInput.value) || 1;
        
        if (benefitInput.value.trim() === '') {
          let suggestion = '';
          if (diskon > 0) {
            suggestion += `Diskon ${diskon}%`;
          }
          if (poin > 1) {
            suggestion += suggestion ? ` + ${poin}x poin` : `${poin}x poin`;
          }
          if (suggestion) {
            benefitInput.value = suggestion;
          }
        }
      }
      
      diskonInput.addEventListener('blur', updateBenefitSuggestion);
      poinInput.addEventListener('blur', updateBenefitSuggestion);
    });
  </script>
  
  <?php include 'template/script.php'; ?>
</body>

</html>