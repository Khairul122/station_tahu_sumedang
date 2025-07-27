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
            <div class="col-12 grid-margin">
              <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                  <h3 class="font-weight-bold mb-1"><?= $title ?></h3>
                  <p class="text-muted mb-0">
                    <?= $action == 'add' ? 'Tambah produk baru' : 'Edit informasi produk' ?>
                    <?php if (($user_role ?? '') == 'manajer'): ?>
                      untuk store Anda
                    <?php else: ?>
                      ke store
                    <?php endif; ?>
                  </p>
                </div>
                <a href="index.php?controller=produk" class="btn btn-secondary">
                  <i class="mdi mdi-arrow-left"></i> Kembali
                </a>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-xl-8 grid-margin stretch-card">
              <div class="card dynamic-card">
                <div class="card-body">
                  <h4 class="card-title mb-4">
                    <i class="mdi mdi-package-variant text-primary me-2"></i>
                    Informasi Produk
                  </h4>
                  
                  <?php if (!empty($error)): ?>
                    <div class="alert alert-danger alert-dismissible">
                      <i class="mdi mdi-alert-circle me-2"></i>
                      <?= $error ?>
                      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                  <?php endif; ?>
                  
                  <?php if (!empty($success)): ?>
                    <div class="alert alert-success alert-dismissible">
                      <i class="mdi mdi-check-circle me-2"></i>
                      <?= $success ?>
                      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                  <?php endif; ?>
                  
                  <form method="POST" enctype="multipart/form-data" class="dynamic-form">
                    <div class="form-floating mb-3">
                      <input type="text" class="form-control" id="nama_produk" name="nama_produk" 
                             value="<?= htmlspecialchars($produk['nama_produk'] ?? '') ?>" 
                             placeholder="Nama Produk" required>
                      <label for="nama_produk">Nama Produk <span class="text-danger">*</span></label>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <div class="form-floating">
                          <select class="form-select" id="kategori" name="kategori" required>
                            <option value="">Pilih Kategori</option>
                            <option value="Original" <?= ($produk['kategori'] ?? '') == 'Original' ? 'selected' : '' ?>>Original</option>
                            <option value="Pedas" <?= ($produk['kategori'] ?? '') == 'Pedas' ? 'selected' : '' ?>>Pedas</option>
                            <option value="Isi" <?= ($produk['kategori'] ?? '') == 'Isi' ? 'selected' : '' ?>>Isi</option>
                            <option value="Mini" <?= ($produk['kategori'] ?? '') == 'Mini' ? 'selected' : '' ?>>Mini</option>
                            <option value="Lainnya" <?= ($produk['kategori'] ?? '') == 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                          </select>
                          <label for="kategori">Kategori <span class="text-danger">*</span></label>
                        </div>
                      </div>
                      
                      <div class="col-md-6 mb-3">
                        <div class="form-floating">
                          <input type="number" class="form-control" id="harga" name="harga" 
                                 value="<?= $produk['harga'] ?? '' ?>" 
                                 placeholder="0" min="0" step="100" required>
                          <label for="harga">Harga (Rp) <span class="text-danger">*</span></label>
                        </div>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <div class="form-floating">
                          <input type="number" class="form-control" id="stok" name="stok" 
                                 value="<?= $produk['stok'] ?? '0' ?>" 
                                 placeholder="0" min="0">
                          <label for="stok">Stok</label>
                        </div>
                        <div id="stok-info" class="form-text"></div>
                      </div>
                      
                      <div class="col-md-6 mb-3">
                        <div class="form-floating">
                          <input type="number" class="form-control" id="poin_reward" name="poin_reward" 
                                 value="<?= $produk['poin_reward'] ?? '0' ?>" 
                                 placeholder="0" min="0">
                          <label for="poin_reward">Poin Reward</label>
                        </div>
                        <div class="form-text">Poin yang didapat member</div>
                      </div>
                    </div>
                    
                    <div class="photo-upload-section mb-4">
                      <label class="form-label fw-bold">
                        <i class="mdi mdi-camera me-2"></i>Foto Produk
                      </label>
                      
                      <div class="upload-area" id="uploadArea">
                        <div class="upload-content">
                          <i class="mdi mdi-cloud-upload upload-icon"></i>
                          <h6>Klik atau drag foto ke sini</h6>
                          <p class="text-muted">JPG, PNG, maksimal 2MB</p>
                          <input type="file" id="foto_produk" name="foto_produk" accept="image/*" hidden>
                        </div>
                      </div>
                      
                      <div class="photo-preview" id="photoPreview" style="display: none;">
                        <img id="previewImage" src="" alt="Preview">
                        <div class="preview-overlay">
                          <button type="button" class="btn btn-sm btn-danger" id="removePhoto">
                            <i class="mdi mdi-delete"></i>
                          </button>
                        </div>
                      </div>
                      
                      <?php if (!empty($produk['foto_produk'])): ?>
                        <div class="current-photo">
                          <label class="form-label">Foto Saat Ini:</label>
                          <img src="foto_produk/<?= htmlspecialchars($produk['foto_produk']) ?>" alt="Current Photo" class="current-image">
                        </div>
                      <?php endif; ?>
                    </div>
                    
                    <div class="store-selection mb-4" <?= ($user_role ?? '') == 'manajer' ? 'style="display: none;"' : '' ?>>
                      <label class="form-label fw-bold">
                        <i class="mdi mdi-store me-2"></i>Pilih Store <span class="text-danger">*</span>
                      </label>
                      
                      <div class="store-card">
                        <?php if (($user_role ?? '') == 'admin'): ?>
                        <div class="store-header">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="select_all_stores">
                            <label class="form-check-label fw-bold" for="select_all_stores">
                              <i class="mdi mdi-select-all me-1"></i>Pilih Semua Store
                            </label>
                          </div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="store-grid">
                          <?php if (!empty($stores)): ?>
                            <?php foreach ($stores as $store): ?>
                              <div class="store-item">
                                <input class="form-check-input store-checkbox" type="checkbox" 
                                       name="store_ids[]" value="<?= $store['id_store'] ?>" 
                                       id="store_<?= $store['id_store'] ?>"
                                       <?php 
                                       if ($action == 'edit') {
                                           echo (isset($produk_stores) && in_array($store['id_store'], $produk_stores)) ? 'checked' : '';
                                       } elseif ($action == 'add') {
                                           if (($user_role ?? '') == 'manajer') {
                                               echo 'checked style="display: none;"';
                                           } else {
                                               echo ($store['id_store'] == 1) ? 'checked' : '';
                                           }
                                       }
                                       ?>>
                                <label class="store-label" for="store_<?= $store['id_store'] ?>">
                                  <div class="store-info">
                                    <h6><?= htmlspecialchars($store['nama_store']) ?></h6>
                                    <p><?= htmlspecialchars($store['alamat_store']) ?></p>
                                    <?php if (($user_role ?? '') == 'manajer'): ?>
                                      <small class="text-primary"><i class="mdi mdi-account-circle me-1"></i>Store Anda</small>
                                    <?php endif; ?>
                                  </div>
                                  <div class="store-icon">
                                    <i class="mdi mdi-store"></i>
                                  </div>
                                </label>
                              </div>
                            <?php endforeach; ?>
                          <?php else: ?>
                            <div class="alert alert-warning">
                              <i class="mdi mdi-alert-circle me-2"></i>
                              Tidak ada store yang tersedia
                            </div>
                          <?php endif; ?>
                        </div>
                      </div>
                    </div>

                    <?php if (($user_role ?? '') == 'manajer'): ?>
                    <div class="manager-store-info mb-4">
                      <div class="alert alert-info">
                        <i class="mdi mdi-information me-2"></i>
                        Produk akan ditambahkan ke store yang Anda kelola
                        <?php if (!empty($stores) && count($stores) > 0): ?>
                          <strong>(<?= htmlspecialchars($stores[0]['nama_store']) ?>)</strong>
                        <?php endif; ?>
                      </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="form-check mb-4">
                      <input class="form-check-input" type="checkbox" id="confirm" required>
                      <label class="form-check-label" for="confirm">
                        <i class="mdi mdi-check-circle-outline me-1"></i>
                        Saya yakin data yang dimasukkan sudah benar
                      </label>
                    </div>
                    
                    <div class="action-buttons">
                      <button type="button" class="btn btn-secondary" onclick="window.location.href='index.php?controller=produk'">
                        <i class="mdi mdi-close"></i> Batal
                      </button>
                      <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-content-save"></i> 
                        <?= $action == 'add' ? 'Simpan Produk' : 'Update Produk' ?>
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            
            <div class="col-xl-4 grid-margin">
              <div class="card tips-card">
                <div class="card-body">
                  <h4 class="card-title">
                    <i class="mdi mdi-lightbulb text-warning me-2"></i>
                    Tips & Panduan
                  </h4>
                  
                  <div class="tip-item">
                    <div class="tip-icon">
                      <i class="mdi mdi-tag"></i>
                    </div>
                    <div class="tip-content">
                      <h6>Nama Produk</h6>
                      <p>Gunakan nama yang jelas dan mudah diingat customer</p>
                    </div>
                  </div>
                  
                  <div class="tip-item">
                    <div class="tip-icon">
                      <i class="mdi mdi-camera"></i>
                    </div>
                    <div class="tip-content">
                      <h6>Foto Produk</h6>
                      <p>Upload foto berkualitas baik untuk menarik customer</p>
                    </div>
                  </div>
                  
                  <div class="tip-item">
                    <div class="tip-icon">
                      <i class="mdi mdi-cash"></i>
                    </div>
                    <div class="tip-content">
                      <h6>Harga</h6>
                      <p>Tentukan harga yang kompetitif sesuai pasar</p>
                    </div>
                  </div>
                  
                  <div class="tip-item">
                    <div class="tip-icon">
                      <i class="mdi mdi-store"></i>
                    </div>
                    <div class="tip-content">
                      <h6><?= ($user_role ?? '') == 'manajer' ? 'Store Management' : 'Multi Store' ?></h6>
                      <p>
                        <?= ($user_role ?? '') == 'manajer' 
                            ? 'Kelola produk untuk store Anda dengan optimal' 
                            : 'Pilih store yang strategis untuk penjualan optimal' 
                        ?>
                      </p>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="card price-calculator mt-3">
                <div class="card-body">
                  <h5 class="card-title">
                    <i class="mdi mdi-calculator text-info me-2"></i>
                    Kalkulator Poin
                  </h5>
                  <div class="calculator-content">
                    <p class="text-muted">Saran poin berdasarkan harga:</p>
                    <div id="pointSuggestion" class="point-display">
                      <span class="point-value">0</span>
                      <span class="point-label">poin</span>
                    </div>
                    <small class="text-muted">1 poin = Rp 3.000</small>
                  </div>
                </div>
              </div>

              <?php if (($user_role ?? '') == 'manajer'): ?>
              <div class="card manager-info mt-3">
                <div class="card-body">
                  <h5 class="card-title">
                    <i class="mdi mdi-account-star text-success me-2"></i>
                    Info Manajer
                  </h5>
                  <div class="manager-content">
                    <div class="info-item">
                      <i class="mdi mdi-account-circle"></i>
                      <span>Role: Manager Store</span>
                    </div>
                    <?php if (!empty($stores) && count($stores) > 0): ?>
                    <div class="info-item">
                      <i class="mdi mdi-store"></i>
                      <span>Store: <?= htmlspecialchars($stores[0]['nama_store']) ?></span>
                    </div>
                    <div class="info-item">
                      <i class="mdi mdi-map-marker"></i>
                      <span><?= htmlspecialchars($stores[0]['alamat_store']) ?></span>
                    </div>
                    <?php endif; ?>
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
    .dynamic-card {
      border-radius: 15px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      border: none;
      overflow: hidden;
    }
    
    .tips-card, .manager-info {
      border-radius: 15px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      border: none;
    }
    
    .form-floating {
      position: relative;
    }
    
    .form-floating > .form-control,
    .form-floating > .form-select {
      border-radius: 10px;
      border: 2px solid #e9ecef;
      transition: all 0.3s ease;
    }
    
    .form-floating > .form-control:focus,
    .form-floating > .form-select:focus {
      border-color: #007bff;
      box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.15);
    }
    
    .upload-area {
      border: 2px dashed #007bff;
      border-radius: 15px;
      padding: 40px 20px;
      text-align: center;
      background: linear-gradient(135deg, #f8f9ff, #e3f2fd);
      cursor: pointer;
      transition: all 0.3s ease;
    }
    
    .upload-area:hover {
      border-color: #0056b3;
      background: linear-gradient(135deg, #e3f2fd, #bbdefb);
    }
    
    .upload-icon {
      font-size: 48px;
      color: #007bff;
      margin-bottom: 15px;
    }
    
    .photo-preview {
      position: relative;
      display: inline-block;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    
    .photo-preview img {
      width: 200px;
      height: 150px;
      object-fit: cover;
    }
    
    .preview-overlay {
      position: absolute;
      top: 5px;
      right: 5px;
    }
    
    .current-photo {
      margin-top: 15px;
    }
    
    .current-image {
      width: 150px;
      height: 100px;
      object-fit: cover;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .store-card {
      border: 1px solid #e9ecef;
      border-radius: 15px;
      overflow: hidden;
    }
    
    .store-header {
      background: linear-gradient(135deg, #f8f9fa, #e9ecef);
      padding: 15px 20px;
      border-bottom: 1px solid #dee2e6;
    }
    
    .store-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 15px;
      padding: 20px;
    }
    
    .store-item {
      position: relative;
    }
    
    .store-label {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 15px;
      border: 2px solid #e9ecef;
      border-radius: 10px;
      cursor: pointer;
      transition: all 0.3s ease;
      background: white;
    }
    
    .store-label:hover {
      border-color: #007bff;
      background: #f8f9ff;
    }
    
    .store-checkbox:checked + .store-label {
      border-color: #007bff;
      background: linear-gradient(135deg, #e3f2fd, #bbdefb);
    }
    
    .store-info h6 {
      margin: 0 0 5px 0;
      color: #2c3e50;
      font-weight: 600;
    }
    
    .store-info p {
      margin: 0 0 5px 0;
      color: #6c757d;
      font-size: 0.85em;
    }
    
    .store-info small {
      font-weight: 500;
    }
    
    .store-icon {
      width: 40px;
      height: 40px;
      background: #007bff;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 18px;
    }
    
    .manager-store-info .alert {
      border-radius: 10px;
      background: linear-gradient(135deg, #e3f2fd, #bbdefb);
      border: 1px solid #007bff;
    }
    
    .tip-item {
      display: flex;
      align-items: flex-start;
      margin-bottom: 20px;
    }
    
    .tip-icon {
      width: 40px;
      height: 40px;
      background: linear-gradient(45deg, #007bff, #0056b3);
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      margin-right: 15px;
      flex-shrink: 0;
    }
    
    .tip-content h6 {
      margin: 0 0 5px 0;
      color: #2c3e50;
      font-weight: 600;
    }
    
    .tip-content p {
      margin: 0;
      color: #6c757d;
      font-size: 0.9em;
    }
    
    .calculator-content {
      text-align: center;
    }
    
    .point-display {
      background: linear-gradient(135deg, #17a2b8, #007bff);
      color: white;
      padding: 20px;
      border-radius: 15px;
      margin: 15px 0;
    }
    
    .point-value {
      font-size: 2em;
      font-weight: bold;
      display: block;
    }
    
    .point-label {
      font-size: 0.9em;
      opacity: 0.9;
    }
    
    .manager-content {
      space-y: 10px;
    }
    
    .info-item {
      display: flex;
      align-items: center;
      margin-bottom: 10px;
      color: #495057;
    }
    
    .info-item i {
      margin-right: 10px;
      color: #28a745;
    }
    
    .action-buttons {
      display: flex;
      gap: 15px;
      justify-content: flex-end;
    }
    
    .btn {
      border-radius: 10px;
      padding: 12px 25px;
      font-weight: 500;
      transition: all 0.3s ease;
    }
    
    .btn-primary {
      background: linear-gradient(45deg, #007bff, #0056b3);
      border: none;
    }
    
    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(0,123,255,0.3);
    }
    
    .alert {
      border-radius: 10px;
      border: none;
    }
    
    #stok-info {
      margin-top: 5px;
      font-size: 0.85em;
    }
    
    .low-stock {
      color: #dc3545;
      font-weight: 500;
    }
    
    .good-stock {
      color: #28a745;
    }
    
    @media (max-width: 768px) {
      .store-grid {
        grid-template-columns: 1fr;
      }
      
      .action-buttons {
        flex-direction: column;
      }
      
      .upload-area {
        padding: 30px 15px;
      }
      
      .d-flex.justify-content-between {
        flex-direction: column;
        align-items: flex-start;
      }
      
      .btn {
        width: 100%;
        margin-top: 10px;
      }
    }
    
    @media (max-width: 576px) {
      .card-body {
        padding: 15px;
      }
      
      .upload-icon {
        font-size: 36px;
      }
      
      .photo-preview img {
        width: 150px;
        height: 100px;
      }
    }
  </style>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const form = document.querySelector('.dynamic-form');
      const hargaInput = document.getElementById('harga');
      const stokInput = document.getElementById('stok');
      const poinInput = document.getElementById('poin_reward');
      const selectAllCheckbox = document.getElementById('select_all_stores');
      const storeCheckboxes = document.querySelectorAll('.store-checkbox');
      const uploadArea = document.getElementById('uploadArea');
      const fileInput = document.getElementById('foto_produk');
      const photoPreview = document.getElementById('photoPreview');
      const previewImage = document.getElementById('previewImage');
      const removePhoto = document.getElementById('removePhoto');
      const pointSuggestion = document.getElementById('pointSuggestion');
      const stokInfo = document.getElementById('stok-info');
      
      function updateSelectAllState() {
        if (selectAllCheckbox) {
          const checkedStores = Array.from(storeCheckboxes).filter(cb => cb.checked);
          const allChecked = checkedStores.length === storeCheckboxes.length;
          const noneChecked = checkedStores.length === 0;
          
          if (allChecked) {
            selectAllCheckbox.checked = true;
            selectAllCheckbox.indeterminate = false;
          } else if (noneChecked) {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = false;
          } else {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = true;
          }
        }
      }
      
      function updatePointSuggestion() {
        const harga = parseFloat(hargaInput.value) || 0;
        const suggestedPoin = Math.max(1, Math.floor(harga / 3000));
        pointSuggestion.querySelector('.point-value').textContent = suggestedPoin;
      }
      
      function updateStokInfo() {
        const stok = parseInt(stokInput.value) || 0;
        if (stok === 0) {
          stokInfo.innerHTML = '<span class="text-danger"><i class="mdi mdi-alert-circle me-1"></i>Stok habis</span>';
        } else if (stok < 20) {
          stokInfo.innerHTML = `<span class="low-stock"><i class="mdi mdi-alert me-1"></i>Stok rendah (${stok} item)</span>`;
        } else {
          stokInfo.innerHTML = `<span class="good-stock"><i class="mdi mdi-check me-1"></i>Stok tersedia (${stok} item)</span>`;
        }
      }
      
      function handleFileUpload(file) {
        if (file && file.type.startsWith('image/')) {
          if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file terlalu besar. Maksimal 2MB.');
            return;
          }
          
          const reader = new FileReader();
          reader.onload = function(e) {
            previewImage.src = e.target.result;
            photoPreview.style.display = 'block';
            uploadArea.style.display = 'none';
          };
          reader.readAsDataURL(file);
        }
      }
      
      updateSelectAllState();
      updatePointSuggestion();
      updateStokInfo();
      
      if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
          storeCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
          });
        });
      }
      
      storeCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectAllState);
      });
      
      hargaInput.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
        updatePointSuggestion();
        
        const currentPoin = parseInt(poinInput.value) || 0;
        if (currentPoin === 0) {
          const suggestedPoin = Math.max(1, Math.floor(parseFloat(this.value) / 3000));
          poinInput.value = suggestedPoin;
        }
      });
      
      stokInput.addEventListener('input', function() {
        if (this.value < 0) this.value = 0;
        updateStokInfo();
      });
      
      poinInput.addEventListener('input', function() {
        if (this.value < 0) this.value = 0;
      });
      
      uploadArea.addEventListener('click', () => fileInput.click());
      
      uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.style.borderColor = '#0056b3';
      });
      
      uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.style.borderColor = '#007bff';
      });
      
      uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        this.style.borderColor = '#007bff';
        const files = e.dataTransfer.files;
        if (files.length > 0) {
          fileInput.files = files;
          handleFileUpload(files[0]);
        }
      });
      
      fileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
          handleFileUpload(this.files[0]);
        }
      });
      
      removePhoto.addEventListener('click', function() {
        fileInput.value = '';
        photoPreview.style.display = 'none';
        uploadArea.style.display = 'block';
      });
      
      form.addEventListener('submit', function(e) {
        const nama = document.getElementById('nama_produk').value.trim();
        const kategori = document.getElementById('kategori').value;
        const harga = parseFloat(hargaInput.value);
        const selectedStores = Array.from(storeCheckboxes).filter(cb => cb.checked);
        const confirm = document.getElementById('confirm').checked;
        
        if (!nama) {
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
        
        if (selectedStores.length === 0) {
          e.preventDefault();
          alert('Pilih minimal satu store');
          return;
        }
        
        if (!confirm) {
          e.preventDefault();
          alert('Harap centang konfirmasi terlebih dahulu');
          return;
        }
      });
      
      const inputs = document.querySelectorAll('.form-control, .form-select');
      inputs.forEach(input => {
        input.addEventListener('focus', function() {
          this.parentElement.style.transform = 'scale(1.02)';
        });
        
        input.addEventListener('blur', function() {
          this.parentElement.style.transform = 'scale(1)';
        });
      });
    });
  </script>
  
  <?php include 'template/script.php'; ?>
</body>

</html>