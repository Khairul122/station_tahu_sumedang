<?php include('template/header.php'); ?>

<body class="with-welcome-text">
  <div class="container-scroller">
    <?php include 'template/navbar.php'; ?>
    <div class="container-fluid page-body-wrapper">
      <?php include 'template/setting_panel.php'; ?>
      <?php include 'template/sidebar.php'; ?>
      <div class="main-panel">
        <div class="content-wrapper">
          
          <!-- Header Section -->
          <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
              <h3 class="font-weight-bold">Detail Transaksi</h3>
              <h6 class="font-weight-normal mb-0">
                Informasi lengkap transaksi penjualan
                <?php if (isset($store_info)): ?>
                  - <?= htmlspecialchars($store_info['nama_store']) ?>
                <?php endif; ?>
              </h6>
            </div>
            <div class="btn-group-responsive">
              <a href="index.php?controller=transaksi_admin" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
              </a>
              <a href="index.php?controller=transaksi_admin&action=edit&id=<?= $transaksi['transaksi_id'] ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-edit"></i> Edit
              </a>
            </div>
          </div>
          
          <!-- Transaction Header Card -->
          <div class="card mb-4">
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <h4 class="mb-2">Transaksi #<?= $transaksi['transaksi_id'] ?></h4>
                  <p class="text-muted mb-0">
                    <i class="fas fa-calendar me-2"></i>
                    <?= date('d F Y, H:i', strtotime($transaksi['tanggal_transaksi'])) ?>
                  </p>
                </div>
                <div class="col-md-3">
                  <?php if (isset($transaksi['nama_store'])): ?>
                  <div class="store-info">
                    <small class="text-muted">Store</small>
                    <h6 class="mb-0"><?= htmlspecialchars($transaksi['nama_store']) ?></h6>
                    <small class="text-muted"><?= htmlspecialchars($transaksi['alamat_store']) ?></small>
                  </div>
                  <?php endif; ?>
                </div>
                <div class="col-md-3 text-md-end mt-3 mt-md-0">
                  <span class="badge badge-<?= $transaksi['metode_pembayaran'] == 'tunai' ? 'success' : 
                    ($transaksi['metode_pembayaran'] == 'transfer' ? 'info' : 'warning') ?> fs-6 px-3 py-2">
                    <?= ucfirst($transaksi['metode_pembayaran']) ?>
                  </span>
                  <?php if (!empty($transaksi['bukti_pembayaran'])): ?>
                    <br>
                    <small class="badge badge-info mt-1">Ada Bukti Pembayaran</small>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Main Content Row -->
          <div class="row">
            <!-- Left Column -->
            <div class="col-lg-8">
              
              <!-- Customer Information -->
              <div class="card mb-4">
                <div class="card-header">
                  <h5 class="mb-0">
                    <i class="fas fa-user text-primary me-2"></i>
                    Informasi Customer
                  </h5>
                </div>
                <div class="card-body">
                  <?php if ($transaksi['customer_id']): ?>
                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <div class="info-item">
                          <small class="text-muted">Nama Customer</small>
                          <p class="fw-bold mb-0"><?= htmlspecialchars($transaksi['nama_customer']) ?></p>
                        </div>
                      </div>
                      <div class="col-md-6 mb-3">
                        <div class="info-item">
                          <small class="text-muted">No Telepon</small>
                          <p class="mb-0"><?= htmlspecialchars($transaksi['no_telepon']) ?></p>
                        </div>
                      </div>
                      <div class="col-md-6 mb-3">
                        <div class="info-item">
                          <small class="text-muted">Email</small>
                          <p class="mb-0"><?= htmlspecialchars($transaksi['email'] ?: '-') ?></p>
                        </div>
                      </div>
                      <div class="col-md-6 mb-3">
                        <div class="info-item">
                          <small class="text-muted">Membership</small>
                          <p class="mb-0">
                            <span class="badge badge-<?= $transaksi['nama_membership'] == 'Bronze' ? 'secondary' : 
                              ($transaksi['nama_membership'] == 'Silver' ? 'info' : 
                              ($transaksi['nama_membership'] == 'Gold' ? 'warning' : 'primary')) ?>">
                              <?= htmlspecialchars($transaksi['nama_membership']) ?>
                            </span>
                          </p>
                        </div>
                      </div>
                    </div>
                  <?php else: ?>
                    <div class="text-center py-4">
                      <span class="badge badge-secondary badge-lg">Customer Umum</span>
                    </div>
                  <?php endif; ?>
                </div>
              </div>

              <!-- Payment Proof Section -->
              <?php if ($transaksi['metode_pembayaran'] === 'transfer' && !empty($transaksi['bukti_pembayaran'])): ?>
              <div class="card mb-4">
                <div class="card-header">
                  <h5 class="mb-0">
                    <i class="fas fa-receipt text-primary me-2"></i>
                    Bukti Pembayaran
                  </h5>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-4">
                      <div class="payment-proof-container">
                        <img src="bukti_pembayaran/<?= htmlspecialchars($transaksi['bukti_pembayaran']) ?>" 
                             alt="Bukti Pembayaran" 
                             class="proof-image"
                             onclick="showImageModal(this.src)">
                        <div class="proof-overlay">
                          <i class="fas fa-search-plus"></i>
                          <span>Klik untuk memperbesar</span>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-8">
                      <div class="proof-info">
                        <div class="info-item">
                          <small class="text-muted">Nama File</small>
                          <p class="mb-2"><?= htmlspecialchars($transaksi['bukti_pembayaran']) ?></p>
                        </div>
                        <div class="info-item">
                          <small class="text-muted">Status</small>
                          <p class="mb-2">
                            <span class="badge badge-success">Terverifikasi</span>
                          </p>
                        </div>
                        <a href="bukti_pembayaran/<?= htmlspecialchars($transaksi['bukti_pembayaran']) ?>" 
                           target="_blank" class="btn btn-sm btn-outline-primary">
                          <i class="fas fa-external-link-alt"></i> Buka di Tab Baru
                        </a>
                        <a href="bukti_pembayaran/<?= htmlspecialchars($transaksi['bukti_pembayaran']) ?>" 
                           download class="btn btn-sm btn-outline-success">
                          <i class="fas fa-download"></i> Download
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php endif; ?>
              
              <!-- Products Table -->
              <div class="card">
                <div class="card-header">
                  <h5 class="mb-0">
                    <i class="fas fa-shopping-cart text-primary me-2"></i>
                    Detail Produk
                  </h5>
                </div>
                <div class="card-body p-0">
                  <div class="table-responsive">
                    <table class="table table-hover mb-0">
                      <thead class="table-light">
                        <tr>
                          <th>Produk</th>
                          <th class="text-center d-none d-md-table-cell">Foto</th>
                          <th class="text-end d-none d-md-table-cell">Harga</th>
                          <th class="text-center">Qty</th>
                          <th class="text-center d-none d-sm-table-cell">Poin</th>
                          <th class="text-end">Subtotal</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if (!empty($detail)): ?>
                          <?php foreach ($detail as $item): ?>
                          <tr>
                            <td>
                              <div>
                                <h6 class="mb-1"><?= htmlspecialchars($item['nama_produk']) ?></h6>
                                <small class="text-muted"><?= htmlspecialchars($item['kategori']) ?></small>
                                <div class="d-md-none">
                                  <small class="text-muted">Rp <?= number_format($item['harga_satuan'], 0, ',', '.') ?></small>
                                </div>
                              </div>
                            </td>
                            <td class="text-center d-none d-md-table-cell">
                              <div class="product-image-small">
                                <?php if (!empty($item['foto_produk'])): ?>
                                  <img src="foto_produk/<?= htmlspecialchars($item['foto_produk']) ?>" 
                                       alt="<?= htmlspecialchars($item['nama_produk']) ?>"
                                       onclick="showImageModal(this.src)">
                                <?php else: ?>
                                  <div class="no-image">
                                    <i class="fas fa-image"></i>
                                  </div>
                                <?php endif; ?>
                              </div>
                            </td>
                            <td class="text-end d-none d-md-table-cell">
                              Rp <?= number_format($item['harga_satuan'], 0, ',', '.') ?>
                            </td>
                            <td class="text-center">
                              <span class="badge bg-light text-dark"><?= $item['jumlah'] ?></span>
                            </td>
                            <td class="text-center d-none d-sm-table-cell">
                              <?php if ($item['total_poin_item'] > 0): ?>
                                <span class="badge badge-primary">+<?= number_format($item['total_poin_item']) ?></span>
                              <?php else: ?>
                                <span class="text-muted">-</span>
                              <?php endif; ?>
                            </td>
                            <td class="text-end fw-bold">
                              Rp <?= number_format($item['subtotal'], 0, ',', '.') ?>
                            </td>
                          </tr>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                              Tidak ada detail produk
                            </td>
                          </tr>
                        <?php endif; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Right Column -->
            <div class="col-lg-4 mt-4 mt-lg-0">
              
              <!-- Transaction Summary -->
              <div class="card mb-4">
                <div class="card-header">
                  <h5 class="mb-0">
                    <i class="fas fa-calculator text-primary me-2"></i>
                    Ringkasan Transaksi
                  </h5>
                </div>
                <div class="card-body">
                  <div class="summary-row">
                    <span>Total Sebelum Diskon</span>
                    <span class="fw-bold">Rp <?= number_format($transaksi['total_sebelum_diskon'], 0, ',', '.') ?></span>
                  </div>
                  
                  <?php if ($transaksi['diskon_membership'] > 0): ?>
                  <div class="summary-row text-success">
                    <span>Diskon Membership</span>
                    <span class="fw-bold">- Rp <?= number_format($transaksi['diskon_membership'], 0, ',', '.') ?></span>
                  </div>
                  <?php endif; ?>
                  
                  <hr>
                  
                  <div class="summary-row total-row">
                    <span>Total Bayar</span>
                    <span class="fw-bold">Rp <?= number_format($transaksi['total_bayar'], 0, ',', '.') ?></span>
                  </div>
                  
                  <?php if ($transaksi['poin_didapat'] > 0): ?>
                  <div class="summary-row text-primary mt-3">
                    <span>Poin Didapat</span>
                    <span class="fw-bold">+<?= number_format($transaksi['poin_didapat']) ?> poin</span>
                  </div>
                  <?php endif; ?>
                </div>
              </div>

              <!-- Store Information -->
              <?php if (isset($transaksi['nama_store'])): ?>
              <div class="card mb-4">
                <div class="card-header">
                  <h5 class="mb-0">
                    <i class="fas fa-store text-primary me-2"></i>
                    Informasi Store
                  </h5>
                </div>
                <div class="card-body">
                  <div class="info-item mb-2">
                    <small class="text-muted">Nama Store</small>
                    <p class="fw-bold mb-0"><?= htmlspecialchars($transaksi['nama_store']) ?></p>
                  </div>
                  <div class="info-item mb-2">
                    <small class="text-muted">Alamat</small>
                    <p class="mb-0"><?= htmlspecialchars($transaksi['alamat_store']) ?></p>
                  </div>
                  <div class="info-item">
                    <small class="text-muted">Store ID</small>
                    <p class="mb-0">
                      <span class="badge badge-info">#<?= $transaksi['store_id'] ?></span>
                    </p>
                  </div>
                </div>
              </div>
              <?php endif; ?>
              
              <!-- Transaction Stats -->
              <div class="card mb-4">
                <div class="card-header">
                  <h5 class="mb-0">
                    <i class="fas fa-chart-bar text-primary me-2"></i>
                    Statistik
                  </h5>
                </div>
                <div class="card-body">
                  <div class="stat-item">
                    <div class="stat-icon">
                      <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="stat-content">
                      <h6><?= count($detail) ?></h6>
                      <span>Jenis Produk</span>
                    </div>
                  </div>
                  <div class="stat-item">
                    <div class="stat-icon">
                      <i class="fas fa-boxes"></i>
                    </div>
                    <div class="stat-content">
                      <h6><?= array_sum(array_column($detail, 'jumlah')) ?></h6>
                      <span>Total Item</span>
                    </div>
                  </div>
                  <div class="stat-item">
                    <div class="stat-icon">
                      <i class="fas fa-percentage"></i>
                    </div>
                    <div class="stat-content">
                      <h6><?= $transaksi['total_sebelum_diskon'] > 0 ? number_format(($transaksi['diskon_membership'] / $transaksi['total_sebelum_diskon']) * 100, 1) : 0 ?>%</h6>
                      <span>Diskon</span>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Actions -->
              <div class="card">
                <div class="card-header">
                  <h5 class="mb-0">
                    <i class="fas fa-cogs text-primary me-2"></i>
                    Aksi
                  </h5>
                </div>
                <div class="card-body">
                  <div class="d-grid gap-2">
                    <a href="index.php?controller=transaksi_admin&action=edit&id=<?= $transaksi['transaksi_id'] ?>" 
                       class="btn btn-warning">
                      <i class="fas fa-edit me-1"></i> Edit Transaksi
                    </a>
                    <a href="index.php?controller=transaksi_admin&action=delete&id=<?= $transaksi['transaksi_id'] ?>" 
                       class="btn btn-danger"
                       onclick="return confirm('Yakin ingin menghapus transaksi ini? Stok produk akan dikembalikan dan data customer akan diupdate.')">
                      <i class="fas fa-trash me-1"></i> Hapus Transaksi
                    </a>
                    <button class="btn btn-info" onclick="printTransaction()">
                      <i class="fas fa-print me-1"></i> Print Transaksi
                    </button>
                    <button class="btn btn-success" onclick="downloadReceipt()">
                      <i class="fas fa-download me-1"></i> Download Receipt
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Image Modal -->
  <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="imageModalLabel">Preview Gambar</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body text-center">
          <img id="modalImage" src="" alt="Preview" class="img-fluid">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
          <a id="downloadImage" href="" download class="btn btn-primary">Download</a>
        </div>
      </div>
    </div>
  </div>
  
  <style>
    /* Enhanced Responsive Styles */
    .btn-group-responsive {
      display: flex;
      gap: 0.5rem;
    }
    
    .card {
      border: none;
      box-shadow: 0 0.125rem 0.5rem rgba(0, 0, 0, 0.1);
      border-radius: 0.75rem;
      border-left: 4px solid #007bff;
    }
    
    .card-header {
      background: linear-gradient(45deg, #f8f9fa, #e9ecef);
      border-bottom: 1px solid #dee2e6;
      padding: 1rem 1.25rem;
      border-radius: 0.75rem 0.75rem 0 0;
    }
    
    .info-item {
      padding: 0.5rem 0;
    }
    
    .info-item small {
      display: block;
      margin-bottom: 0.25rem;
      font-weight: 500;
    }
    
    .summary-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0.5rem 0;
      border-bottom: 1px solid #f1f3f4;
    }
    
    .summary-row:last-child {
      border-bottom: none;
    }
    
    .total-row {
      font-size: 1.1rem;
      font-weight: 700;
      color: #2c3e50;
      background: linear-gradient(45deg, #e8f5e8, #f0f8f0);
      padding: 1rem;
      border-radius: 0.5rem;
      border: none;
    }
    
    .table th {
      font-weight: 600;
      background: linear-gradient(45deg, #f8f9fa, #e9ecef);
      border-bottom: 2px solid #dee2e6;
      color: #495057;
    }
    
    .table td {
      vertical-align: middle;
      border-bottom: 1px solid #f1f3f4;
    }
    
    .store-info {
      background: linear-gradient(45deg, #e3f2fd, #f3e5f5);
      padding: 1rem;
      border-radius: 0.5rem;
      border-left: 3px solid #2196f3;
    }
    
    .payment-proof-container {
      position: relative;
      border-radius: 0.5rem;
      overflow: hidden;
      cursor: pointer;
      transition: transform 0.3s ease;
    }
    
    .payment-proof-container:hover {
      transform: scale(1.05);
    }
    
    .proof-image {
      width: 100%;
      height: 200px;
      object-fit: cover;
      border-radius: 0.5rem;
    }
    
    .proof-overlay {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0,0,0,0.7);
      color: white;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      opacity: 0;
      transition: opacity 0.3s ease;
    }
    
    .payment-proof-container:hover .proof-overlay {
      opacity: 1;
    }
    
    .product-image-small {
      width: 60px;
      height: 60px;
      border-radius: 0.5rem;
      overflow: hidden;
      cursor: pointer;
      transition: transform 0.3s ease;
      margin: 0 auto;
    }
    
    .product-image-small:hover {
      transform: scale(1.1);
    }
    
    .product-image-small img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    
    .no-image {
      width: 100%;
      height: 100%;
      background: #f8f9fa;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #6c757d;
      font-size: 1.5rem;
      border: 2px dashed #dee2e6;
    }
    
    .stat-item {
      display: flex;
      align-items: center;
      gap: 1rem;
      padding: 0.75rem;
      background: linear-gradient(45deg, #f8f9fa, #ffffff);
      border-radius: 0.5rem;
      margin-bottom: 1rem;
      border-left: 3px solid #007bff;
    }
    
    .stat-item:last-child {
      margin-bottom: 0;
    }
    
    .stat-icon {
      width: 45px;
      height: 45px;
      border-radius: 50%;
      background: linear-gradient(45deg, #007bff, #0056b3);
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.1rem;
    }
    
    .stat-content h6 {
      font-size: 1.3rem;
      font-weight: 700;
      color: #2c3e50;
      margin: 0;
    }
    
    .stat-content span {
      color: #6c757d;
      font-size: 0.85rem;
      font-weight: 500;
    }
    
    .badge-lg {
      font-size: 1rem;
      padding: 0.5rem 1rem;
    }
    
    .fs-6 {
      font-size: 1rem;
    }
    
    .me-1 {
      margin-right: 0.25rem;
    }
    
    .me-2 {
      margin-right: 0.5rem;
    }
    
    .fw-bold {
      font-weight: 700;
    }
    
    .d-grid {
      display: grid;
    }
    
    .gap-2 {
      gap: 0.5rem;
    }
    
    .bg-light {
      background-color: #f8f9fa !important;
    }
    
    .btn {
      border-radius: 0.5rem;
      font-weight: 500;
      transition: all 0.3s ease;
    }
    
    .btn:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    
    .modal-content {
      border-radius: 0.75rem;
      border: none;
      box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    
    .modal-header {
      background: linear-gradient(45deg, #667eea, #764ba2);
      color: white;
      border-radius: 0.75rem 0.75rem 0 0;
    }
    
    #modalImage {
      max-height: 70vh;
      border-radius: 0.5rem;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    /* Mobile Responsive */
    @media (max-width: 768px) {
      .btn-group-responsive {
        flex-direction: column;
        width: 100%;
      }
      
      .btn-group-responsive .btn {
        width: 100%;
        margin-bottom: 0.5rem;
      }
      
      .d-flex.justify-content-between {
        flex-direction: column;
        gap: 1rem;
      }
      
      .text-md-end {
        text-align: left !important;
      }
      
      .col-lg-4 {
        margin-top: 1rem;
      }
      
      .summary-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
      }
      
      .table {
        font-size: 0.875rem;
      }
      
      .store-info {
        margin-top: 1rem;
      }
      
      .stat-item {
        padding: 0.5rem;
      }
      
      .stat-icon {
        width: 40px;
        height: 40px;
        font-size: 1rem;
      }
      
      .stat-content h6 {
        font-size: 1.1rem;
      }
    }
    
    @media (max-width: 576px) {
      .btn-group-responsive {
        position: sticky;
        top: 0;
        z-index: 10;
        background: white;
        padding: 0.5rem 0;
        margin-bottom: 1rem;
        border-bottom: 1px solid #dee2e6;
      }
      
      .content-wrapper {
        padding: 1rem;
      }
      
      .card-body {
        padding: 1rem;
      }
      
      .table th,
      .table td {
        padding: 0.5rem;
      }
      
      .payment-proof-container {
        margin-bottom: 1rem;
      }
      
      .proof-info {
        margin-top: 1rem;
      }
    }
    
    /* Print Styles */
    @media print {
      .btn,
      .btn-group-responsive,
      .modal {
        display: none !important;
      }
      
      .card {
        box-shadow: none;
        border: 1px solid #ddd;
        page-break-inside: avoid;
      }
      
      .d-grid,
      .proof-overlay {
        display: none !important;
      }
      
      .payment-proof-container {
        page-break-inside: avoid;
      }
    }
  </style>
  
  <script>
    function showImageModal(imageSrc) {
      const modal = $('#imageModal');
      const modalImage = $('#modalImage');
      const downloadLink = $('#downloadImage');
      
      modalImage.attr('src', imageSrc);
      downloadLink.attr('href', imageSrc);
      modal.modal('show');
    }

    function printTransaction() {
      const printContent = `
        <html>
          <head>
            <title>Transaksi #<?= $transaksi['transaksi_id'] ?></title>
            <style>
              body { font-family: Arial, sans-serif; margin: 20px; color: #333; }
              .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
              .store-info { text-align: center; margin-bottom: 15px; }
              .transaction-info { margin-bottom: 20px; }
              .customer-info { margin-bottom: 20px; padding: 10px; border: 1px solid #ccc; }
              .products-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
              .products-table th, .products-table td { border: 1px solid #ccc; padding: 8px; text-align: left; }
              .products-table th { background: #f5f5f5; }
              .summary { border-top: 2px solid #000; padding-top: 15px; margin-top: 20px; }
              .summary-row { display: flex; justify-content: space-between; margin-bottom: 5px; }
              .total-row { font-weight: bold; font-size: 1.2em; border-top: 1px solid #000; padding-top: 10px; }
              .text-center { text-align: center; }
              .text-right { text-align: right; }
              @media print { body { margin: 0; } }
            </style>
          </head>
          <body>
            <div class="header">
              <h1>STATION TAHU SUMEDANG</h1>
              <p>Struk Transaksi</p>
            </div>
            
            <div class="store-info">
              <h3><?= htmlspecialchars($transaksi['nama_store'] ?? 'Store') ?></h3>
              <p><?= htmlspecialchars($transaksi['alamat_store'] ?? '') ?></p>
            </div>
            
            <div class="transaction-info">
              <table style="width: 100%;">
                <tr>
                  <td><strong>No. Transaksi:</strong></td>
                  <td>#<?= $transaksi['transaksi_id'] ?></td>
                </tr>
                <tr>
                  <td><strong>Tanggal:</strong></td>
                  <td><?= date('d F Y, H:i', strtotime($transaksi['tanggal_transaksi'])) ?></td>
                </tr>
                <tr>
                  <td><strong>Metode Bayar:</strong></td>
                  <td><?= ucfirst($transaksi['metode_pembayaran']) ?></td>
                </tr>
              </table>
            </div>
            
            <?php if ($transaksi['customer_id']): ?>
            <div class="customer-info">
              <h4>Informasi Customer</h4>
              <p><strong>Nama:</strong> <?= htmlspecialchars($transaksi['nama_customer']) ?></p>
              <p><strong>Telepon:</strong> <?= htmlspecialchars($transaksi['no_telepon']) ?></p>
              <p><strong>Membership:</strong> <?= htmlspecialchars($transaksi['nama_membership']) ?></p>
            </div>
            <?php endif; ?>
            
            <table class="products-table">
              <thead>
                <tr>
                  <th>Produk</th>
                  <th>Harga</th>
                  <th>Qty</th>
                  <th>Subtotal</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($detail as $item): ?>
                <tr>
                  <td><?= htmlspecialchars($item['nama_produk']) ?></td>
                  <td>Rp <?= number_format($item['harga_satuan'], 0, ',', '.') ?></td>
                  <td class="text-center"><?= $item['jumlah'] ?></td>
                  <td class="text-right">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
            
            <div class="summary">
              <div class="summary-row">
                <span>Subtotal:</span>
                <span>Rp <?= number_format($transaksi['total_sebelum_diskon'], 0, ',', '.') ?></span>
              </div>
              <?php if ($transaksi['diskon_membership'] > 0): ?>
              <div class="summary-row">
                <span>Diskon:</span>
                <span>-Rp <?= number_format($transaksi['diskon_membership'], 0, ',', '.') ?></span>
              </div>
              <?php endif; ?>
              <div class="summary-row total-row">
                <span>TOTAL:</span>
                <span>Rp <?= number_format($transaksi['total_bayar'], 0, ',', '.') ?></span>
              </div>
              <?php if ($transaksi['poin_didapat'] > 0): ?>
              <div class="summary-row">
                <span>Poin Didapat:</span>
                <span>+<?= number_format($transaksi['poin_didapat']) ?> poin</span>
              </div>
              <?php endif; ?>
            </div>
            
            <div class="text-center" style="margin-top: 30px;">
              <p>Terima kasih atas kunjungan Anda!</p>
              <p style="font-size: 0.9em;">Simpan struk ini sebagai bukti pembelian</p>
            </div>
          </body>
        </html>
      `;
      
      const printWindow = window.open('', '_blank');
      printWindow.document.write(printContent);
      printWindow.document.close();
      printWindow.focus();
      setTimeout(() => {
        printWindow.print();
        printWindow.close();
      }, 500);
    }

    function downloadReceipt() {
      // Simple receipt download functionality
      const content = `
STATION TAHU SUMEDANG
<?= htmlspecialchars($transaksi['nama_store'] ?? 'Store') ?>
<?= htmlspecialchars($transaksi['alamat_store'] ?? '') ?>
==========================================
No. Transaksi: #<?= $transaksi['transaksi_id'] ?>
Tanggal: <?= date('d/m/Y H:i', strtotime($transaksi['tanggal_transaksi'])) ?>
Metode Bayar: <?= ucfirst($transaksi['metode_pembayaran']) ?>
==========================================

<?php if ($transaksi['customer_id']): ?>
CUSTOMER: <?= htmlspecialchars($transaksi['nama_customer']) ?>
TELEPON: <?= htmlspecialchars($transaksi['no_telepon']) ?>
MEMBERSHIP: <?= htmlspecialchars($transaksi['nama_membership']) ?>
==========================================

<?php endif; ?>
DETAIL PEMBELIAN:
<?php foreach ($detail as $item): ?>
<?= htmlspecialchars($item['nama_produk']) ?>
  <?= $item['jumlah'] ?> x Rp <?= number_format($item['harga_satuan'], 0, ',', '.') ?> = Rp <?= number_format($item['subtotal'], 0, ',', '.') ?>
<?php endforeach; ?>

==========================================
Subtotal: Rp <?= number_format($transaksi['total_sebelum_diskon'], 0, ',', '.') ?>
<?php if ($transaksi['diskon_membership'] > 0): ?>
Diskon: -Rp <?= number_format($transaksi['diskon_membership'], 0, ',', '.') ?>
<?php endif; ?>
TOTAL: Rp <?= number_format($transaksi['total_bayar'], 0, ',', '.') ?>
<?php if ($transaksi['poin_didapat'] > 0): ?>
Poin Didapat: +<?= number_format($transaksi['poin_didapat']) ?> poin
<?php endif; ?>

==========================================
Terima kasih atas kunjungan Anda!
      `;
      
      const blob = new Blob([content], { type: 'text/plain' });
      const url = window.URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url;
      a.download = `receipt_${<?= $transaksi['transaksi_id'] ?>}_${new Date().getTime()}.txt`;
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
      window.URL.revokeObjectURL(url);
    }

    $(document).ready(function() {
      // Animate stats cards
      $('.stat-item').each(function(index) {
        $(this).css({
          'opacity': '0',
          'transform': 'translateY(20px)'
        });
        
        setTimeout(() => {
          $(this).css({
            'transition': 'all 0.5s ease',
            'opacity': '1',
            'transform': 'translateY(0)'
          });
        }, index * 100);
      });

      // Animate cards
      $('.card').each(function(index) {
        $(this).css({
          'opacity': '0',
          'transform': 'translateY(30px)'
        });
        
        setTimeout(() => {
          $(this).css({
            'transition': 'all 0.6s ease',
            'opacity': '1',
            'transform': 'translateY(0)'
          });
        }, index * 150);
      });

      // Image error handling
      $('img').on('error', function() {
        $(this).parent().html('<div class="no-image"><i class="fas fa-image"></i></div>');
      });

      // Tooltip initialization
      $('[data-toggle="tooltip"]').tooltip();

      // Enhanced modal functionality
      $('#imageModal').on('shown.bs.modal', function() {
        const modalImage = $('#modalImage');
        modalImage.css({
          'max-width': '100%',
          'max-height': '70vh',
          'object-fit': 'contain'
        });
      });

      // Success message from URL parameter
      if (window.location.search.includes('success=')) {
        const successMessage = new URLSearchParams(window.location.search).get('success');
        if (successMessage) {
          const alert = $(`
            <div class="alert alert-success alert-dismissible fade show" style="position: fixed; top: 20px; right: 20px; z-index: 9999; max-width: 400px;">
              <i class="fas fa-check-circle me-2"></i>
              ${successMessage}
              <button type="button" class="close" onclick="this.parentElement.remove()">
                <span>&times;</span>
              </button>
            </div>
          `);
          $('body').append(alert);
          
          setTimeout(() => {
            alert.fadeOut(() => alert.remove());
          }, 5000);
        }
      }

      // Smooth scrolling for anchor links
      $('a[href^="#"]').on('click', function(e) {
        e.preventDefault();
        const target = $($(this).attr('href'));
        if (target.length) {
          $('html, body').animate({
            scrollTop: target.offset().top - 100
          }, 500);
        }
      });

      // Auto-refresh notification for real-time updates
      let lastUpdate = new Date().getTime();
      setInterval(() => {
        // Check for updates every 30 seconds
        // This could be enhanced with AJAX calls to check for changes
      }, 30000);
    });

    // Keyboard shortcuts
    $(document).keydown(function(e) {
      // Ctrl/Cmd + P for print
      if ((e.ctrlKey || e.metaKey) && e.which === 80) {
        e.preventDefault();
        printTransaction();
      }
      
      // Escape to go back
      if (e.which === 27) {
        window.location.href = 'index.php?controller=transaksi_admin';
      }
    });
  </script>
  
  <?php include 'template/script.php'; ?>
</body>