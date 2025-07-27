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
            <div class="col-12">
              <div class="success-header">
                <div class="success-icon">
                  <i class="fas fa-check-circle"></i>
                </div>
                <h2 class="success-title">Transaksi Berhasil!</h2>
                <p class="success-subtitle">Terima kasih atas pembelian Anda</p>
              </div>
            </div>
          </div>

          <?php if (!empty($success)): ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($success) ?>
            <button type="button" class="close" data-dismiss="alert">
              <span>&times;</span>
            </button>
          </div>
          <?php endif; ?>

          <div class="row">
            <div class="col-lg-8 col-md-12">
              <div class="transaction-card">
                <div class="transaction-header">
                  <h4>Detail Transaksi</h4>
                  <span class="transaction-id">#<?= $transaksi['transaksi_id'] ?></span>
                </div>
                <div class="transaction-body">
                  <div class="transaction-info">
                    <div class="info-row">
                      <span class="info-label">Tanggal:</span>
                      <span class="info-value"><?= date('d M Y, H:i', strtotime($transaksi['tanggal_transaksi'])) ?></span>
                    </div>
                    <div class="info-row">
                      <span class="info-label">Metode Pembayaran:</span>
                      <span class="info-value"><?= ucfirst($transaksi['metode_pembayaran']) ?></span>
                    </div>
                    <div class="info-row">
                      <span class="info-label">Status:</span>
                      <span class="status-badge success">Berhasil</span>
                    </div>
                  </div>

                  <div class="items-section">
                    <h5>Produk yang Dibeli</h5>
                    <div class="items-list">
                      <?php foreach ($transaksi['details'] as $detail): ?>
                      <div class="item-row">
                        <div class="item-info">
                          <h6 class="item-name"><?= htmlspecialchars($detail['nama_produk']) ?></h6>
                          <span class="item-category"><?= htmlspecialchars($detail['kategori']) ?></span>
                        </div>
                        <div class="item-quantity">
                          <span><?= $detail['jumlah'] ?>x</span>
                        </div>
                        <div class="item-price">
                          <span class="unit-price">Rp <?= number_format($detail['harga_satuan']) ?></span>
                          <span class="total-price">Rp <?= number_format($detail['subtotal']) ?></span>
                        </div>
                        <div class="item-points">
                          <span class="points-earned">+<?= $detail['total_poin_item'] ?> poin</span>
                        </div>
                      </div>
                      <?php endforeach; ?>
                    </div>
                  </div>

                  <div class="transaction-summary">
                    <div class="summary-row">
                      <span>Subtotal:</span>
                      <span>Rp <?= number_format($transaksi['total_sebelum_diskon']) ?></span>
                    </div>
                    <div class="summary-row">
                      <span>Diskon Membership:</span>
                      <span class="discount">-Rp <?= number_format($transaksi['diskon_membership']) ?></span>
                    </div>
                    <div class="summary-row total">
                      <span>Total Bayar:</span>
                      <span>Rp <?= number_format($transaksi['total_bayar']) ?></span>
                    </div>
                    <div class="summary-row">
                      <span>Poin Didapat:</span>
                      <span class="points">+<?= number_format($transaksi['poin_didapat']) ?> poin</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-4 col-md-12">
              <div class="member-update-card">
                <div class="member-update-header">
                  <h4>Update Member</h4>
                </div>
                <div class="member-update-body">
                  <div class="current-status">
                    <h6>Status Terkini</h6>
                    <div class="status-item">
                      <span>Membership:</span>
                      <span class="membership-badge <?= strtolower($memberData['nama_membership']) ?>">
                        <?= htmlspecialchars($memberData['nama_membership']) ?>
                      </span>
                    </div>
                    <div class="status-item">
                      <span>Total Poin:</span>
                      <span class="points-value"><?= number_format($memberData['total_poin']) ?></span>
                    </div>
                    <div class="status-item">
                      <span>Total Pembelian:</span>
                      <span>Rp <?= number_format($memberData['total_pembelian']) ?></span>
                    </div>
                  </div>

                  <div class="benefits-info">
                    <h6>Keuntungan Membership</h6>
                    <ul>
                      <li>Diskon <?= $memberData['diskon_persen'] ?>% setiap pembelian</li>
                      <li>Poin <?= $memberData['poin_per_pembelian'] ?>x lipat</li>
                      <li>Akses ke produk eksklusif</li>
                    </ul>
                  </div>
                </div>
              </div>

              <div class="action-buttons">
                <a href="index.php?controller=pembelian" class="btn btn-primary btn-block">
                  <i class="fas fa-shopping-bag"></i> Belanja Lagi
                </a>
                <a href="index.php?controller=pembelian&action=history" class="btn btn-outline-primary btn-block">
                  <i class="fas fa-history"></i> Riwayat Transaksi
                </a>
                <a href="index.php?controller=dashboard" class="btn btn-outline-secondary btn-block">
                  <i class="fas fa-home"></i> Kembali ke Dashboard
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <style>
    :root {
      --success-color: #28a745;
      --primary-color: #667eea;
      --light-color: #f8f9fa;
      --border-color: #e9ecef;
      --text-primary: #2c3e50;
      --text-secondary: #6c757d;
      --shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .success-header {
      background: linear-gradient(135deg, var(--success-color), #20c997);
      border-radius: 12px;
      padding: 3rem 2rem;
      text-align: center;
      color: white;
      margin-bottom: 2rem;
    }

    .success-icon {
      font-size: 4rem;
      margin-bottom: 1rem;
      animation: successBounce 0.6s ease-out;
    }

    @keyframes successBounce {
      0% { transform: scale(0); }
      50% { transform: scale(1.2); }
      100% { transform: scale(1); }
    }

    .success-title {
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 0.5rem;
    }

    .success-subtitle {
      font-size: 1.2rem;
      opacity: 0.9;
      margin-bottom: 0;
    }

    .transaction-card, .member-update-card {
      background: white;
      border-radius: 12px;
      box-shadow: var(--shadow);
      border: 1px solid var(--border-color);
      margin-bottom: 2rem;
    }

    .transaction-header, .member-update-header {
      padding: 1.5rem;
      border-bottom: 1px solid var(--border-color);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .transaction-header h4, .member-update-header h4 {
      margin: 0;
      font-weight: 600;
    }

    .transaction-id {
      background: var(--primary-color);
      color: white;
      padding: 0.5rem 1rem;
      border-radius: 20px;
      font-weight: 600;
    }

    .transaction-body, .member-update-body {
      padding: 1.5rem;
    }

    .transaction-info {
      margin-bottom: 2rem;
    }

    .info-row, .status-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 0.8rem;
      padding-bottom: 0.8rem;
      border-bottom: 1px solid var(--border-color);
    }

    .info-row:last-child, .status-item:last-child {
      border-bottom: none;
      margin-bottom: 0;
      padding-bottom: 0;
    }

    .info-label {
      font-weight: 500;
      color: var(--text-secondary);
    }

    .info-value {
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

    .items-section {
      margin-bottom: 2rem;
    }

    .items-section h5 {
      color: var(--text-primary);
      font-weight: 600;
      margin-bottom: 1rem;
      padding-bottom: 0.5rem;
      border-bottom: 2px solid var(--border-color);
    }

    .item-row {
      display: grid;
      grid-template-columns: 2fr 1fr 1.5fr 1fr;
      gap: 1rem;
      align-items: center;
      padding: 1rem;
      background: var(--light-color);
      border-radius: 8px;
      margin-bottom: 1rem;
    }

    .item-row:last-child {
      margin-bottom: 0;
    }

    .item-name {
      font-weight: 600;
      color: var(--text-primary);
      margin-bottom: 0.2rem;
    }

    .item-category {
      font-size: 0.9rem;
      color: var(--text-secondary);
    }

    .item-quantity {
      text-align: center;
      font-weight: 600;
      color: var(--primary-color);
    }

    .item-price {
      text-align: right;
    }

    .unit-price {
      display: block;
      font-size: 0.9rem;
      color: var(--text-secondary);
    }

    .total-price {
      font-weight: 600;
      color: var(--text-primary);
    }

    .item-points {
      text-align: center;
    }

    .points-earned {
      background: var(--success-color);
      color: white;
      padding: 0.2rem 0.5rem;
      border-radius: 12px;
      font-size: 0.8rem;
      font-weight: 600;
    }

    .transaction-summary {
      border-top: 2px solid var(--border-color);
      padding-top: 1.5rem;
    }

    .summary-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 0.8rem;
      font-size: 1rem;
    }

    .summary-row.total {
      font-size: 1.3rem;
      font-weight: 700;
      color: var(--success-color);
      border-top: 1px solid var(--border-color);
      padding-top: 0.8rem;
      margin-top: 0.8rem;
    }

    .discount {
      color: var(--success-color);
      font-weight: 600;
    }

    .points {
      color: var(--primary-color);
      font-weight: 600;
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

    .points-value {
      font-weight: 700;
      color: var(--primary-color);
      font-size: 1.1rem;
    }

    .current-status {
      margin-bottom: 2rem;
    }

    .current-status h6 {
      color: var(--text-primary);
      font-weight: 600;
      margin-bottom: 1rem;
      padding-bottom: 0.5rem;
      border-bottom: 2px solid var(--border-color);
    }

    .benefits-info h6 {
      color: var(--text-primary);
      font-weight: 600;
      margin-bottom: 1rem;
    }

    .benefits-info ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .benefits-info li {
      padding: 0.5rem 0;
      border-bottom: 1px solid var(--border-color);
      position: relative;
      padding-left: 1.5rem;
    }

    .benefits-info li:before {
      content: '✓';
      position: absolute;
      left: 0;
      color: var(--success-color);
      font-weight: bold;
    }

    .benefits-info li:last-child {
      border-bottom: none;
    }

    .action-buttons {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    .action-buttons .btn {
      padding: 0.8rem 1.5rem;
      font-weight: 600;
      border-radius: 8px;
      transition: all 0.3s ease;
    }

    .action-buttons .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    @media (max-width: 768px) {
      .success-header {
        padding: 2rem 1rem;
      }

      .success-title {
        font-size: 2rem;
      }

      .success-icon {
        font-size: 3rem;
      }

      .item-row {
        grid-template-columns: 1fr;
        gap: 0.5rem;
        text-align: left;
      }

      .item-quantity, .item-points {
        text-align: left;
      }

      .item-price {
        text-align: left;
      }

      .transaction-header {
        flex-direction: column;
        gap: 1rem;
      }
    }

    @media (max-width: 576px) {
      .success-header {
        padding: 1.5rem 1rem;
      }

      .success-title {
        font-size: 1.5rem;
      }

      .summary-row.total {
        font-size: 1.1rem;
      }

      .action-buttons .btn {
        padding: 0.6rem 1rem;
      }
    }
  </style>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const alerts = document.querySelectorAll('.alert');
      alerts.forEach(alert => {
        setTimeout(() => {
          if (alert && alert.parentNode) {
            alert.classList.remove('show');
            setTimeout(() => {
              alert.remove();
            }, 150);
          }
        }, 5000);
      });

      setTimeout(() => {
        createConfetti();
      }, 500);
    });

    function createConfetti() {
      const colors = ['#667eea', '#28a745', '#f39c12', '#e74c3c', '#9b59b6'];
      const confettiCount = 50;

      for (let i = 0; i < confettiCount; i++) {
        setTimeout(() => {
          const confetti = document.createElement('div');
          confetti.style.position = 'fixed';
          confetti.style.left = Math.random() * 100 + 'vw';
          confetti.style.top = '-10px';
          confetti.style.width = '10px';
          confetti.style.height = '10px';
          confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
          confetti.style.pointerEvents = 'none';
          confetti.style.zIndex = '9999';
          confetti.style.borderRadius = '50%';
          confetti.style.opacity = '0.8';

          document.body.appendChild(confetti);

          const fallDuration = Math.random() * 2000 + 1000;
          const horizontalMovement = (Math.random() - 0.5) * 200;

          confetti.animate([
            { transform: 'translateY(0px) translateX(0px) rotate(0deg)', opacity: 0.8 },
            { transform: `translateY(100vh) translateX(${horizontalMovement}px) rotate(360deg)`, opacity: 0 }
          ], {
            duration: fallDuration,
            easing: 'cubic-bezier(0.25, 0.46, 0.45, 0.94)'
          }).addEventListener('finish', () => {
            confetti.remove();
          });
        }, i * 100);
      }
    }

    function printReceipt() {
      const printContent = document.querySelector('.transaction-card').innerHTML;
      const printWindow = window.open('', '_blank');
      
      printWindow.document.write(`
        <html>
          <head>
            <title>Struk Transaksi #<?= $transaksi['transaksi_id'] ?></title>
            <style>
              body { font-family: Arial, sans-serif; margin: 20px; }
              .transaction-header { border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
              .item-row { display: flex; justify-content: space-between; margin-bottom: 10px; padding: 5px 0; border-bottom: 1px solid #ccc; }
              .transaction-summary { border-top: 2px solid #000; padding-top: 10px; margin-top: 20px; }
              .summary-row { display: flex; justify-content: space-between; margin-bottom: 5px; }
              .total { font-weight: bold; font-size: 1.2em; }
              @media print { body { margin: 0; } }
            </style>
          </head>
          <body>
            <h2>Station Tahu Sumedang</h2>
            <p>Struk Transaksi</p>
            ${printContent}
            <script>window.print(); window.close();<\/script>
          </body>
        </html>
      `);
    }

    const printBtn = document.createElement('button');
    printBtn.className = 'btn btn-outline-info btn-block';
    printBtn.innerHTML = '<i class="fas fa-print"></i> Cetak Struk';
    printBtn.onclick = printReceipt;
    
    document.addEventListener('DOMContentLoaded', function() {
      document.querySelector('.action-buttons').appendChild(printBtn);
    });
  </script>

  <?php include 'template/script.php'; ?>
</body>
</html>