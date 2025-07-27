<?php include('template/header.php'); ?>

<body class="with-welcome-text">
  <div class="container-scroller">
    <?php include 'template/navbar.php'; ?>
    <div class="container-fluid page-body-wrapper">
      <?php include 'template/setting_panel.php'; ?>
      <?php include 'template/sidebar.php'; ?>
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row mb-4">
            <div class="col-12">
              <div class="page-header">
                <div class="header-content">
                  <h2 class="page-title">Detail Transaksi #<?= $transaksi['transaksi_id'] ?></h2>
                  <p class="page-subtitle"><?= date('d M Y, H:i', strtotime($transaksi['tanggal_transaksi'])) ?></p>
                </div>
                <div class="header-actions">
                  <a href="index.php?controller=pembelian&action=history" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                  </a>
                  <button type="button" class="btn btn-primary" onclick="printReceipt()">
                    <i class="fas fa-print"></i> Cetak
                  </button>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-8 col-md-12">
              <div class="transaction-detail-card">
                <div class="transaction-detail-header">
                  <div class="header-left">
                    <h4>Informasi Transaksi</h4>
                    <span class="status-badge success">Berhasil</span>
                  </div>
                  <div class="header-right">
                    <div class="transaction-meta">
                      <span class="meta-label">ID Transaksi:</span>
                      <span class="meta-value">#<?= $transaksi['transaksi_id'] ?></span>
                    </div>
                  </div>
                </div>

                <div class="transaction-detail-body">
                  <div class="info-section">
                    <h5>Detail Pembayaran</h5>
                    <div class="info-grid">
                      <div class="info-item">
                        <span class="info-label">Tanggal Transaksi:</span>
                        <span class="info-value"><?= date('d M Y, H:i:s', strtotime($transaksi['tanggal_transaksi'])) ?></span>
                      </div>
                      <div class="info-item">
                        <span class="info-label">Metode Pembayaran:</span>
                        <span class="info-value payment-method"><?= ucfirst($transaksi['metode_pembayaran']) ?></span>
                      </div>
                      <div class="info-item">
                        <span class="info-label">Customer:</span>
                        <span class="info-value"><?= htmlspecialchars($transaksi['nama_customer']) ?></span>
                      </div>
                      <div class="info-item">
                        <span class="info-label">Membership:</span>
                        <span class="membership-badge <?= strtolower($transaksi['nama_membership']) ?>">
                          <?= htmlspecialchars($transaksi['nama_membership']) ?>
                        </span>
                      </div>
                    </div>
                  </div>

                  <div class="products-section">
                    <h5>Produk yang Dibeli</h5>
                    <div class="products-table-responsive">
                      <table class="products-table">
                        <thead>
                          <tr>
                            <th>Produk</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                            <th>Poin</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach ($transaksi['details'] as $detail): ?>
                          <tr>
                            <td>
                              <div class="product-info">
                                <h6 class="product-name"><?= htmlspecialchars($detail['nama_produk']) ?></h6>
                              </div>
                            </td>
                            <td>
                              <span class="category-badge"><?= htmlspecialchars($detail['kategori']) ?></span>
                            </td>
                            <td>
                              <span class="price">Rp <?= number_format($detail['harga_satuan']) ?></span>
                            </td>
                            <td>
                              <span class="quantity"><?= $detail['jumlah'] ?></span>
                            </td>
                            <td>
                              <span class="subtotal">Rp <?= number_format($detail['subtotal']) ?></span>
                            </td>
                            <td>
                              <span class="points-badge">+<?= $detail['total_poin_item'] ?></span>
                            </td>
                          </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>
                  </div>

                  <div class="summary-section">
                    <div class="summary-card">
                      <h5>Ringkasan Pembayaran</h5>
                      <div class="summary-details">
                        <div class="summary-row">
                          <span class="summary-label">Subtotal:</span>
                          <span class="summary-value">Rp <?= number_format($transaksi['total_sebelum_diskon']) ?></span>
                        </div>
                        <div class="summary-row">
                          <span class="summary-label">Diskon Membership:</span>
                          <span class="summary-value discount">-Rp <?= number_format($transaksi['diskon_membership']) ?></span>
                        </div>
                        <div class="summary-row total">
                          <span class="summary-label">Total Bayar:</span>
                          <span class="summary-value">Rp <?= number_format($transaksi['total_bayar']) ?></span>
                        </div>
                        <div class="summary-row">
                          <span class="summary-label">Poin Didapat:</span>
                          <span class="summary-value points">+<?= number_format($transaksi['poin_didapat']) ?> poin</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-4 col-md-12">
              <div class="customer-info-card">
                <div class="customer-info-header">
                  <h4>Informasi Customer</h4>
                </div>
                <div class="customer-info-body">
                  <div class="customer-detail">
                    <div class="detail-item">
                      <span class="detail-label">Nama:</span>
                      <span class="detail-value"><?= htmlspecialchars($memberData['nama_customer']) ?></span>
                    </div>
                    <div class="detail-item">
                      <span class="detail-label">Email:</span>
                      <span class="detail-value"><?= htmlspecialchars($memberData['email']) ?></span>
                    </div>
                    <div class="detail-item">
                      <span class="detail-label">No. Telepon:</span>
                      <span class="detail-value"><?= htmlspecialchars($memberData['no_telepon']) ?></span>
                    </div>
                    <div class="detail-item">
                      <span class="detail-label">Membership:</span>
                      <span class="membership-badge <?= strtolower($memberData['nama_membership']) ?>">
                        <?= htmlspecialchars($memberData['nama_membership']) ?>
                      </span>
                    </div>
                    <div class="detail-item">
                      <span class="detail-label">Total Poin Saat Ini:</span>
                      <span class="detail-value points-current"><?= number_format($memberData['total_poin']) ?></span>
                    </div>
                  </div>
                </div>
              </div>

              <div class="quick-actions-card">
                <div class="quick-actions-header">
                  <h4>Aksi Cepat</h4>
                </div>
                <div class="quick-actions-body">
                  <a href="index.php?controller=pembelian" class="action-btn primary">
                    <i class="fas fa-shopping-bag"></i>
                    <span>Belanja Lagi</span>
                  </a>
                  <a href="index.php?controller=pembelian&action=history" class="action-btn secondary">
                    <i class="fas fa-history"></i>
                    <span>Riwayat Transaksi</span>
                  </a>
                  <button type="button" class="action-btn info" onclick="shareTransaction()">
                    <i class="fas fa-share-alt"></i>
                    <span>Bagikan</span>
                  </button>
                  <button type="button" class="action-btn success" onclick="downloadPDF()">
                    <i class="fas fa-download"></i>
                    <span>Download PDF</span>
                  </button>
                </div>
              </div>

              <div class="transaction-stats-card">
                <div class="transaction-stats-header">
                  <h4>Statistik Transaksi</h4>
                </div>
                <div class="transaction-stats-body">
                  <div class="stat-item">
                    <div class="stat-icon">
                      <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="stat-content">
                      <h6><?= count($transaksi['details']) ?></h6>
                      <span>Jenis Produk</span>
                    </div>
                  </div>
                  <div class="stat-item">
                    <div class="stat-icon">
                      <i class="fas fa-boxes"></i>
                    </div>
                    <div class="stat-content">
                      <h6><?= array_sum(array_column($transaksi['details'], 'jumlah')) ?></h6>
                      <span>Total Item</span>
                    </div>
                  </div>
                  <div class="stat-item">
                    <div class="stat-icon">
                      <i class="fas fa-coins"></i>
                    </div>
                    <div class="stat-content">
                      <h6><?= number_format($transaksi['poin_didapat']) ?></h6>
                      <span>Poin Earned</span>
                    </div>
                  </div>
                  <div class="stat-item">
                    <div class="stat-icon">
                      <i class="fas fa-percentage"></i>
                    </div>
                    <div class="stat-content">
                      <h6><?= number_format(($transaksi['diskon_membership'] / $transaksi['total_sebelum_diskon']) * 100, 1) ?>%</h6>
                      <span>Diskon</span>
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
    :root {
      --primary-color: #667eea;
      --success-color: #28a745;
      --info-color: #17a2b8;
      --warning-color: #ffc107;
      --danger-color: #dc3545;
      --light-color: #f8f9fa;
      --dark-color: #343a40;
      --border-color: #e9ecef;
      --text-primary: #2c3e50;
      --text-secondary: #6c757d;
      --shadow: 0 2px 4px rgba(0,0,0,0.1);
      --shadow-lg: 0 4px 6px rgba(0,0,0,0.1);
    }

    .page-header {
      background: linear-gradient(135deg, var(--primary-color) 0%, #764ba2 100%);
      border-radius: 12px;
      padding: 2rem;
      color: white;
      margin-bottom: 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .header-content .page-title {
      font-size: 2rem;
      font-weight: 700;
      margin-bottom: 0.5rem;
    }

    .header-content .page-subtitle {
      opacity: 0.9;
      margin-bottom: 0;
    }

    .header-actions {
      display: flex;
      gap: 1rem;
    }

    .transaction-detail-card, .customer-info-card, .quick-actions-card, .transaction-stats-card {
      background: white;
      border-radius: 12px;
      box-shadow: var(--shadow);
      border: 1px solid var(--border-color);
      margin-bottom: 2rem;
    }

    .transaction-detail-header, .customer-info-header, .quick-actions-header, .transaction-stats-header {
      padding: 1.5rem;
      border-bottom: 1px solid var(--border-color);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .header-left h4, .customer-info-header h4, .quick-actions-header h4, .transaction-stats-header h4 {
      margin: 0;
      font-weight: 600;
      color: var(--text-primary);
    }

    .status-badge {
      padding: 0.3rem 0.8rem;
      border-radius: 20px;
      font-size: 0.8rem;
      font-weight: 600;
    }

    .status-badge.success {
      background: var(--success-color);
      color: white;
    }

    .transaction-meta {
      text-align: right;
    }

    .meta-label {
      display: block;
      font-size: 0.9rem;
      color: var(--text-secondary);
    }

    .meta-value {
      font-weight: 700;
      color: var(--primary-color);
      font-size: 1.1rem;
    }

    .transaction-detail-body, .customer-info-body, .quick-actions-body, .transaction-stats-body {
      padding: 1.5rem;
    }

    .info-section, .products-section, .summary-section {
      margin-bottom: 2rem;
    }

    .info-section:last-child, .products-section:last-child, .summary-section:last-child {
      margin-bottom: 0;
    }

    .info-section h5, .products-section h5, .summary-section h5 {
      color: var(--text-primary);
      font-weight: 600;
      margin-bottom: 1rem;
      padding-bottom: 0.5rem;
      border-bottom: 2px solid var(--border-color);
    }

    .info-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1rem;
    }

    .info-item, .detail-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0.8rem;
      background: var(--light-color);
      border-radius: 6px;
    }

    .info-label, .detail-label {
      font-weight: 500;
      color: var(--text-secondary);
    }

    .info-value, .detail-value {
      font-weight: 600;
      color: var(--text-primary);
    }

    .payment-method {
      background: var(--info-color);
      color: white;
      padding: 0.2rem 0.6rem;
      border-radius: 12px;
      font-size: 0.8rem;
    }

    .membership-badge {
      padding: 0.3rem 0.8rem;
      border-radius: 20px;
      font-size: 0.8rem;
      font-weight: 600;
      color: white;
    }

    .membership-badge.bronze { background: #cd7f32; }
    .membership-badge.silver { background: #95a5a6; }
    .membership-badge.gold { background: #f39c12; }
    .membership-badge.platinum { background: #9b59b6; }

    .products-table-responsive {
      overflow-x: auto;
    }

    .products-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 1rem;
    }

    .products-table th,
    .products-table td {
      padding: 1rem;
      text-align: left;
      border-bottom: 1px solid var(--border-color);
    }

    .products-table th {
      background: var(--light-color);
      font-weight: 600;
      color: var(--text-primary);
    }

    .product-name {
      font-weight: 600;
      color: var(--text-primary);
      margin: 0;
    }

    .category-badge {
      background: var(--primary-color);
      color: white;
      padding: 0.2rem 0.6rem;
      border-radius: 12px;
      font-size: 0.8rem;
      font-weight: 500;
    }

    .price, .subtotal {
      font-weight: 600;
      color: var(--success-color);
    }

    .quantity {
      font-weight: 600;
      color: var(--text-primary);
    }

    .points-badge {
      background: var(--warning-color);
      color: white;
      padding: 0.2rem 0.6rem;
      border-radius: 12px;
      font-size: 0.8rem;
      font-weight: 600;
    }

    .summary-card {
      background: var(--light-color);
      border-radius: 8px;
      padding: 1.5rem;
    }

    .summary-card h5 {
      margin-bottom: 1rem;
      border-bottom: none;
      padding-bottom: 0;
    }

    .summary-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 0.8rem;
      padding-bottom: 0.8rem;
      border-bottom: 1px solid var(--border-color);
    }

    .summary-row:last-child {
      border-bottom: none;
      margin-bottom: 0;
      padding-bottom: 0;
    }

    .summary-row.total {
      font-size: 1.2rem;
      font-weight: 700;
      color: var(--success-color);
      border-top: 2px solid var(--border-color);
      padding-top: 0.8rem;
      margin-top: 0.8rem;
    }

    .summary-label {
      font-weight: 500;
      color: var(--text-secondary);
    }

    .summary-value {
      font-weight: 600;
      color: var(--text-primary);
    }

    .summary-value.discount {
      color: var(--success-color);
    }

    .summary-value.points {
      color: var(--warning-color);
    }

    .points-current {
      color: var(--primary-color);
      font-size: 1.1rem;
      font-weight: 700;
    }

    .action-btn {
      display: flex;
      align-items: center;
      gap: 0.8rem;
      padding: 1rem;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 500;
      margin-bottom: 1rem;
      transition: all 0.3s ease;
      border: none;
      width: 100%;
      cursor: pointer;
    }

    .action-btn:last-child {
      margin-bottom: 0;
    }

    .action-btn:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow-lg);
      text-decoration: none;
    }

    .action-btn.primary {
      background: var(--primary-color);
      color: white;
    }

    .action-btn.secondary {
      background: var(--text-secondary);
      color: white;
    }

    .action-btn.info {
      background: var(--info-color);
      color: white;
    }

    .action-btn.success {
      background: var(--success-color);
      color: white;
    }

    .stat-item {
      display: flex;
      align-items: center;
      gap: 1rem;
      padding: 1rem;
      background: var(--light-color);
      border-radius: 8px;
      margin-bottom: 1rem;
    }

    .stat-item:last-child {
      margin-bottom: 0;
    }

    .stat-icon {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background: var(--primary-color);
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2rem;
    }

    .stat-content h6 {
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--text-primary);
      margin: 0;
    }

    .stat-content span {
      color: var(--text-secondary);
      font-size: 0.9rem;
    }

    @media (max-width: 768px) {
      .page-header {
        flex-direction: column;
        gap: 1rem;
        padding: 1.5rem;
      }

      .header-content .page-title {
        font-size: 1.5rem;
      }

      .header-actions {
        width: 100%;
        justify-content: center;
      }

      .info-grid {
        grid-template-columns: 1fr;
      }

      .products-table {
        font-size: 0.9rem;
      }

      .products-table th,
      .products-table td {
        padding: 0.5rem;
      }

      .transaction-detail-header {
        flex-direction: column;
        gap: 1rem;
      }

      .header-left {
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
      }

      .header-right {
        width: 100%;
      }
    }

    @media (max-width: 576px) {
      .products-table {
        font-size: 0.8rem;
      }

      .stat-icon {
        width: 40px;
        height: 40px;
        font-size: 1rem;
      }

      .stat-content h6 {
        font-size: 1.2rem;
      }

      .action-btn {
        padding: 0.8rem;
      }
    }
  </style>

  <script>
    function printReceipt() {
      const printContent = document.querySelector('.transaction-detail-card').innerHTML;
      const printWindow = window.open('', '_blank');
      
      printWindow.document.write(`
        <html>
          <head>
            <title>Detail Transaksi #<?= $transaksi['transaksi_id'] ?></title>
            <style>
              body { font-family: Arial, sans-serif; margin: 20px; color: #333; }
              .transaction-detail-header { border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
              .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 20px; }
              .info-item { display: flex; justify-content: space-between; padding: 5px; border-bottom: 1px solid #ccc; }
              .products-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
              .products-table th, .products-table td { border: 1px solid #ccc; padding: 8px; text-align: left; }
              .products-table th { background: #f5f5f5; }
              .summary-section { border-top: 2px solid #000; padding-top: 15px; margin-top: 20px; }
              .summary-row { display: flex; justify-content: space-between; margin-bottom: 5px; }
              .summary-row.total { font-weight: bold; font-size: 1.2em; border-top: 1px solid #000; padding-top: 10px; }
              @media print { body { margin: 0; } }
            </style>
          </head>
          <body>
            <h1>Station Tahu Sumedang</h1>
            <h2>Detail Transaksi</h2>
            ${printContent}
            <script>window.print(); window.close();<\/script>
          </body>
        </html>
      `);
    }

    function shareTransaction() {
      if (navigator.share) {
        navigator.share({
          title: 'Transaksi #<?= $transaksi['transaksi_id'] ?>',
          text: 'Detail transaksi pembelian di Station Tahu Sumedang',
          url: window.location.href
        });
      } else {
        const url = window.location.href;
        navigator.clipboard.writeText(url).then(() => {
          alert('Link transaksi telah disalin ke clipboard');
        });
      }
    }

    function downloadPDF() {
      alert('Fitur download PDF sedang dalam pengembangan');
    }

    document.addEventListener('DOMContentLoaded', function() {
      const statItems = document.querySelectorAll('.stat-item');
      statItems.forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
          item.style.transition = 'all 0.5s ease';
          item.style.opacity = '1';
          item.style.transform = 'translateY(0)';
        }, index * 100);
      });
    });
  </script>

  <?php include 'template/script.php'; ?>
</body>
</html>