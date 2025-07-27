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
                  <h3 class="font-weight-bold mb-1">Kelola Produk</h3>
                  <p class="text-muted mb-0">
                    <?= ($user_role ?? 'admin') == 'manajer' 
                        ? 'Manajemen produk untuk store Anda' 
                        : 'Manajemen produk tahu sumedang semua store' 
                    ?>
                  </p>
                </div>
                <a href="index.php?controller=produk&action=add" class="btn btn-primary">
                  <i class="mdi mdi-plus"></i> Tambah Produk
                </a>
              </div>
            </div>
          </div>
          
          <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <i class="mdi mdi-check-circle me-2"></i>
              <?= htmlspecialchars($_GET['success']) ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          <?php endif; ?>
          
          <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <i class="mdi mdi-alert-circle me-2"></i>
              <?= htmlspecialchars($_GET['error']) ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          <?php endif; ?>
          
          <div class="row">
            <div class="col-12 grid-margin stretch-card">
              <div class="card dynamic-card">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title">
                      <i class="mdi mdi-package-variant text-primary me-2"></i>
                      Daftar Produk
                      <?php if (($user_role ?? 'admin') == 'manajer'): ?>
                        <span class="store-badge ms-2">
                          <i class="mdi mdi-store"></i> 
                          Store Saya
                        </span>
                      <?php endif; ?>
                    </h4>
                    <div class="product-stats">
                      <span class="stat-item">
                        <i class="mdi mdi-cube-outline"></i>
                        Total: <?= count($produk) ?> produk
                      </span>
                    </div>
                  </div>
                  
                  <div class="search-section mb-4">
                    <div class="row align-items-center">
                      <div class="col-md-8">
                        <form method="GET" action="index.php" class="search-form">
                          <input type="hidden" name="controller" value="produk">
                          <div class="search-wrapper">
                            <i class="mdi mdi-magnify search-icon"></i>
                            <input type="text" class="form-control search-input" name="search" 
                                   placeholder="Cari nama produk, kategori<?= ($user_role ?? 'admin') == 'admin' ? ', atau store' : '' ?>..." 
                                   value="<?= htmlspecialchars($search) ?>">
                            <button class="btn btn-primary search-btn" type="submit">
                              Cari
                            </button>
                          </div>
                        </form>
                      </div>
                      <div class="col-md-4">
                        <?php if (!empty($search)): ?>
                          <a href="index.php?controller=produk" class="btn btn-outline-secondary clear-btn">
                            <i class="mdi mdi-close"></i> Clear Search
                          </a>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                  
                  <div class="table-responsive">
                    <table class="table table-hover modern-table">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Foto</th>
                          <th>Produk</th>
                          <?php if (($user_role ?? 'admin') == 'admin'): ?>
                          <th>Store</th>
                          <?php endif; ?>
                          <th>Kategori</th>
                          <th>Harga</th>
                          <th>Stok</th>
                          <th>Poin</th>
                          <th>Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if (!empty($produk)): ?>
                          <?php $no = 1; ?>
                          <?php foreach ($produk as $item): ?>
                          <tr class="product-row">
                            <td><?= $no++ ?></td>
                            <td>
                              <div class="product-image">
                                <?php if (!empty($item['foto_produk'])): ?>
                                  <img src="foto_produk/<?= htmlspecialchars($item['foto_produk']) ?>" 
                                       alt="<?= htmlspecialchars($item['nama_produk']) ?>" 
                                       class="product-thumb"
                                       onclick="showImageModal('<?= htmlspecialchars($item['foto_produk']) ?>', '<?= htmlspecialchars($item['nama_produk']) ?>')">
                                <?php else: ?>
                                  <div class="no-image">
                                    <i class="mdi mdi-image-off"></i>
                                  </div>
                                <?php endif; ?>
                              </div>
                            </td>
                            <td>
                              <div class="product-info">
                                <strong class="product-name"><?= htmlspecialchars($item['nama_produk']) ?></strong>
                                <small class="product-id">ID: #<?= $item['produk_id'] ?></small>
                              </div>
                            </td>
                            <?php if (($user_role ?? 'admin') == 'admin'): ?>
                            <td>
                              <span class="store-badge">
                                <i class="mdi mdi-store"></i>
                                <?= htmlspecialchars($item['nama_store'] ?? 'Store #' . $item['store_id']) ?>
                              </span>
                            </td>
                            <?php endif; ?>
                            <td>
                              <span class="category-badge category-<?= strtolower($item['kategori']) ?>">
                                <?= htmlspecialchars($item['kategori']) ?>
                              </span>
                            </td>
                            <td>
                              <span class="price-tag">
                                Rp <?= number_format($item['harga'], 0, ',', '.') ?>
                              </span>
                            </td>
                            <td>
                              <?php if ($item['stok'] == 0): ?>
                                <span class="stock-badge stock-empty">
                                  <i class="mdi mdi-alert-circle"></i> Habis
                                </span>
                              <?php elseif ($item['stok'] < 20): ?>
                                <span class="stock-badge stock-low">
                                  <i class="mdi mdi-alert"></i> <?= $item['stok'] ?>
                                </span>
                              <?php else: ?>
                                <span class="stock-badge stock-good">
                                  <i class="mdi mdi-check-circle"></i> <?= $item['stok'] ?>
                                </span>
                              <?php endif; ?>
                            </td>
                            <td>
                              <span class="point-badge">
                                <i class="mdi mdi-star"></i> <?= $item['poin_reward'] ?>
                              </span>
                            </td>
                            <td>
                              <div class="action-buttons">
                                <a href="index.php?controller=produk&action=view&id=<?= $item['produk_id'] ?>" 
                                   class="btn btn-sm btn-info" title="Detail">
                                  <i class="mdi mdi-eye"></i>
                                </a>
                                <a href="index.php?controller=produk&action=edit&id=<?= $item['produk_id'] ?>" 
                                   class="btn btn-sm btn-warning" title="Edit">
                                  <i class="mdi mdi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" 
                                        onclick="confirmDelete(<?= $item['produk_id'] ?>, '<?= htmlspecialchars($item['nama_produk']) ?>')"
                                        title="Hapus">
                                  <i class="mdi mdi-delete"></i>
                                </button>
                              </div>
                            </td>
                          </tr>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <tr>
                            <td colspan="<?= ($user_role ?? 'admin') == 'admin' ? '9' : '8' ?>" class="text-center">
                              <div class="empty-state">
                                <div class="empty-icon">
                                  <i class="mdi mdi-package-variant-closed"></i>
                                </div>
                                <h5 class="empty-title">
                                  <?= !empty($search) ? 'Produk Tidak Ditemukan' : 'Belum Ada Produk' ?>
                                </h5>
                                <p class="empty-message">
                                  <?php if (!empty($search)): ?>
                                    Tidak ada produk yang cocok dengan pencarian "<?= htmlspecialchars($search) ?>"
                                    <?= ($user_role ?? 'admin') == 'manajer' ? ' di store Anda' : '' ?>
                                  <?php else: ?>
                                    <?= ($user_role ?? 'admin') == 'manajer' 
                                        ? 'Mulai dengan menambahkan produk pertama ke store Anda' 
                                        : 'Mulai dengan menambahkan produk pertama ke store' 
                                    ?>
                                  <?php endif; ?>
                                </p>
                                <?php if (empty($search)): ?>
                                  <a href="index.php?controller=produk&action=add" class="btn btn-primary">
                                    <i class="mdi mdi-plus"></i> 
                                    <?= ($user_role ?? 'admin') == 'manajer' 
                                        ? 'Tambah Produk untuk Store Saya' 
                                        : 'Tambah Produk Pertama' 
                                    ?>
                                  </a>
                                <?php else: ?>
                                  <a href="index.php?controller=produk" class="btn btn-outline-primary">
                                    <i class="mdi mdi-arrow-left"></i> Lihat Semua Produk
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
                  <div class="table-footer">
                    <div class="row align-items-center">
                      <div class="col-md-6">
                        <small class="text-muted">
                          Menampilkan <?= count($produk) ?> produk
                          <?= !empty($search) ? 'dari pencarian "' . htmlspecialchars($search) . '"' : '' ?>
                          <?= ($user_role ?? 'admin') == 'manajer' ? ' di store Anda' : '' ?>
                        </small>
                      </div>
                      <div class="col-md-6 text-end">
                        <div class="summary-stats">
                          <?php
                          $totalStok = array_sum(array_column($produk, 'stok'));
                          $stokRendah = count(array_filter($produk, function($p) { return $p['stok'] < 20 && $p['stok'] > 0; }));
                          $stokHabis = count(array_filter($produk, function($p) { return $p['stok'] == 0; }));
                          ?>
                          <span class="summary-item">
                            <i class="mdi mdi-cube-outline text-primary"></i>
                            Total Stok: <?= number_format($totalStok) ?>
                          </span>
                          <?php if ($stokRendah > 0): ?>
                          <span class="summary-item text-warning">
                            <i class="mdi mdi-alert"></i>
                            Stok Rendah: <?= $stokRendah ?>
                          </span>
                          <?php endif; ?>
                          <?php if ($stokHabis > 0): ?>
                          <span class="summary-item text-danger">
                            <i class="mdi mdi-alert-circle"></i>
                            Stok Habis: <?= $stokHabis ?>
                          </span>
                          <?php endif; ?>
                        </div>
                      </div>
                    </div>
                  </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>

          <?php if (($user_role ?? 'admin') == 'manajer' && !empty($produk)): ?>
          <div class="row">
            <div class="col-12">
              <div class="card manager-summary">
                <div class="card-body">
                  <h4 class="card-title">
                    <i class="mdi mdi-chart-line text-success me-2"></i>
                    Ringkasan Store Anda
                  </h4>
                  <div class="summary-grid">
                    <div class="summary-item">
                      <div class="summary-icon bg-primary">
                        <i class="mdi mdi-package-variant"></i>
                      </div>
                      <div class="summary-content">
                        <h5><?= count($produk) ?></h5>
                        <p>Total Produk</p>
                      </div>
                    </div>
                    <div class="summary-item">
                      <div class="summary-icon bg-success">
                        <i class="mdi mdi-cube-outline"></i>
                      </div>
                      <div class="summary-content">
                        <h5><?= number_format($totalStok) ?></h5>
                        <p>Total Stok</p>
                      </div>
                    </div>
                    <div class="summary-item">
                      <div class="summary-icon bg-warning">
                        <i class="mdi mdi-alert"></i>
                      </div>
                      <div class="summary-content">
                        <h5><?= $stokRendah ?></h5>
                        <p>Stok Rendah</p>
                      </div>
                    </div>
                    <div class="summary-item">
                      <div class="summary-icon bg-danger">
                        <i class="mdi mdi-alert-circle"></i>
                      </div>
                      <div class="summary-content">
                        <h5><?= $stokHabis ?></h5>
                        <p>Stok Habis</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Image Modal -->
  <div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="imageModalTitle">Foto Produk</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center">
          <img id="modalImage" src="" alt="" class="img-fluid rounded">
        </div>
      </div>
    </div>
  </div>
  
  <style>
    .dynamic-card, .manager-summary {
      border-radius: 15px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      border: none;
    }
    
    .product-stats {
      display: flex;
      align-items: center;
      gap: 15px;
    }
    
    .stat-item {
      background: linear-gradient(135deg, #e3f2fd, #bbdefb);
      color: #1976d2;
      padding: 8px 15px;
      border-radius: 20px;
      font-size: 0.9em;
      font-weight: 500;
    }
    
    .store-badge {
      background: linear-gradient(45deg, #f8f9fa, #e9ecef);
      color: #495057;
      padding: 6px 12px;
      border-radius: 15px;
      font-size: 0.85em;
      font-weight: 500;
      border: 1px solid #dee2e6;
    }
    
    .search-section {
      background: #f8f9fa;
      padding: 20px;
      border-radius: 10px;
      border: 1px solid #e9ecef;
    }
    
    .search-wrapper {
      position: relative;
      display: flex;
      align-items: center;
    }
    
    .search-icon {
      position: absolute;
      left: 15px;
      color: #6c757d;
      z-index: 2;
    }
    
    .search-input {
      padding-left: 45px;
      border-radius: 25px 0 0 25px;
      border-right: none;
      height: 45px;
    }
    
    .search-btn {
      border-radius: 0 25px 25px 0;
      height: 45px;
      padding: 0 25px;
    }
    
    .clear-btn {
      border-radius: 25px;
      height: 45px;
    }
    
    .modern-table {
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .modern-table th {
      background: linear-gradient(135deg, #667eea, #764ba2);
      color: white;
      font-weight: 600;
      text-align: center;
      padding: 15px 8px;
      border: none;
    }
    
    .modern-table td {
      padding: 12px 8px;
      vertical-align: middle;
      border-bottom: 1px solid #f1f3f4;
    }
    
    .product-row:hover {
      background: linear-gradient(135deg, #f8f9ff, #e3f2fd);
    }
    
    .product-thumb {
      width: 60px;
      height: 60px;
      object-fit: cover;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.15);
      cursor: pointer;
      transition: transform 0.3s ease;
    }
    
    .product-thumb:hover {
      transform: scale(1.1);
    }
    
    .no-image {
      width: 60px;
      height: 60px;
      background: #f8f9fa;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #6c757d;
      font-size: 24px;
      border: 2px dashed #dee2e6;
    }
    
    .product-info {
      text-align: left;
    }
    
    .product-name {
      display: block;
      color: #2c3e50;
      font-size: 0.95em;
      margin-bottom: 3px;
    }
    
    .product-id {
      color: #6c757d;
      font-size: 0.8em;
    }
    
    .category-badge {
      padding: 6px 12px;
      border-radius: 15px;
      font-size: 0.85em;
      font-weight: 500;
      color: white;
    }
    
    .category-original { background: linear-gradient(45deg, #17a2b8, #007bff); }
    .category-pedas { background: linear-gradient(45deg, #dc3545, #fd7e14); }
    .category-isi { background: linear-gradient(45deg, #28a745, #20c997); }
    .category-mini { background: linear-gradient(45deg, #ffc107, #fd7e14); }
    .category-lainnya { background: linear-gradient(45deg, #6f42c1, #e83e8c); }
    
    .price-tag {
      font-weight: 600;
      color: #2e7d32;
      font-size: 0.9em;
    }
    
    .stock-badge {
      padding: 6px 12px;
      border-radius: 15px;
      font-size: 0.85em;
      font-weight: 500;
    }
    
    .stock-empty {
      background: #ffebee;
      color: #c62828;
      border: 1px solid #ffcdd2;
    }
    
    .stock-low {
      background: #fff3e0;
      color: #ef6c00;
      border: 1px solid #ffcc02;
    }
    
    .stock-good {
      background: #e8f5e8;
      color: #2e7d32;
      border: 1px solid #c8e6c9;
    }
    
    .point-badge {
      background: linear-gradient(45deg, #ff9800, #ff5722);
      color: white;
      padding: 6px 12px;
      border-radius: 15px;
      font-size: 0.85em;
      font-weight: 500;
    }
    
    .action-buttons {
      display: flex;
      gap: 5px;
      justify-content: center;
    }
    
    .action-buttons .btn {
      border-radius: 8px;
      transition: all 0.3s ease;
    }
    
    .action-buttons .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .empty-state {
      padding: 60px 20px;
    }
    
    .empty-icon {
      font-size: 72px;
      color: #dee2e6;
      margin-bottom: 20px;
    }
    
    .empty-title {
      color: #6c757d;
      margin-bottom: 10px;
    }
    
    .empty-message {
      color: #6c757d;
      margin-bottom: 30px;
    }
    
    .table-footer {
      background: #f8f9fa;
      padding: 15px 20px;
      border-radius: 0 0 10px 10px;
      margin: 0 -20px -20px -20px;
    }
    
    .summary-stats {
      display: flex;
      gap: 15px;
      justify-content: flex-end;
      flex-wrap: wrap;
    }
    
    .summary-item {
      font-size: 0.85em;
      font-weight: 500;
    }
    
    .summary-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      margin-top: 20px;
    }
    
    .summary-grid .summary-item {
      display: flex;
      align-items: center;
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .summary-icon {
      width: 50px;
      height: 50px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 1.5rem;
      margin-right: 15px;
    }
    
    .summary-content h5 {
      margin: 0;
      font-size: 1.5rem;
      font-weight: 700;
      color: #2c3e50;
    }
    
    .summary-content p {
      margin: 0;
      font-size: 0.9rem;
      color: #6c757d;
    }
    
    .alert {
      border-radius: 10px;
      border: none;
    }
    
    @media (max-width: 768px) {
      .d-flex.justify-content-between {
        flex-direction: column;
        align-items: flex-start;
      }
      
      .product-stats {
        margin-top: 15px;
        width: 100%;
      }
      
      .search-wrapper {
        flex-direction: column;
        gap: 10px;
      }
      
      .search-input, .search-btn {
        border-radius: 25px;
        width: 100%;
      }
      
      .action-buttons {
        flex-direction: column;
      }
      
      .summary-stats {
        justify-content: flex-start;
        margin-top: 10px;
      }
      
      .modern-table {
        font-size: 0.85em;
      }
      
      .product-thumb, .no-image {
        width: 45px;
        height: 45px;
      }
      
      .summary-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }
    
    @media (max-width: 576px) {
      .summary-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
  
  <script>
    function showImageModal(imagePath, productName) {
      document.getElementById('imageModalTitle').textContent = productName;
      document.getElementById('modalImage').src = 'foto_produk/' + imagePath;
      document.getElementById('modalImage').alt = productName;
      
      const modal = new bootstrap.Modal(document.getElementById('imageModal'));
      modal.show();
    }
    
    function confirmDelete(id, nama) {
      if (confirm(`Apakah Anda yakin ingin menghapus produk "${nama}"?\n\nTindakan ini tidak dapat dibatalkan.`)) {
        window.location.href = `index.php?controller=produk&action=delete&id=${id}`;
      }
    }
    
    document.addEventListener('DOMContentLoaded', function() {
      const alerts = document.querySelectorAll('.alert');
      alerts.forEach(alert => {
        setTimeout(() => {
          alert.style.opacity = '0';
          setTimeout(() => alert.remove(), 300);
        }, 5000);
      });
      
      const productRows = document.querySelectorAll('.product-row');
      productRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
          this.style.transform = 'translateX(5px)';
        });
        
        row.addEventListener('mouseleave', function() {
          this.style.transform = 'translateX(0)';
        });
      });
    });
  </script>
  
  <?php include 'template/script.php'; ?>
</body>

</html>