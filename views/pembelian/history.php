<?php
// Inisialisasi variabel penting agar tidak undefined
$currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$hasMore = isset($hasMore) ? $hasMore : false;

// Simulasi data default jika belum ada (untuk mencegah error saat pengembangan)
$memberData = isset($memberData) ? $memberData : [
  'nama_customer' => 'Nama Pengguna',
  'nama_membership' => 'Silver',
  'total_poin' => 0,
  'total_pembelian' => 0
];

$history = isset($history) ? $history : [];

include('template/header.php');
?>

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
                <h2 class="page-title">Riwayat Transaksi</h2>
                <p class="page-subtitle">Lihat semua transaksi pembelian Anda</p>
              </div>
            </div>
          </div>

          <?php if (isset($_GET['error'])): ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_GET['error']) ?>
            <button type="button" class="close" data-dismiss="alert">
              <span>&times;</span>
            </button>
          </div>
          <?php endif; ?>

          <div class="row">
            <div class="col-lg-3 col-md-12 mb-4">
              <div class="member-summary-card">
                <div class="member-summary-header">
                  <h4>Ringkasan Member</h4>
                </div>
                <div class="member-summary-body">
                  <div class="summary-item">
                    <span class="summary-label">Nama:</span>
                    <span class="summary-value"><?= htmlspecialchars($memberData['nama_customer']) ?></span>
                  </div>
                  <div class="summary-item">
                    <span class="summary-label">Membership:</span>
                    <span class="membership-badge <?= strtolower($memberData['nama_membership']) ?>">
                      <?= htmlspecialchars($memberData['nama_membership']) ?>
                    </span>
                  </div>
                  <div class="summary-item">
                    <span class="summary-label">Total Poin:</span>
                    <span class="summary-value points"><?= number_format($memberData['total_poin']) ?></span>
                  </div>
                  <div class="summary-item">
                    <span class="summary-label">Total Pembelian:</span>
                    <span class="summary-value">Rp <?= number_format($memberData['total_pembelian']) ?></span>
                  </div>
                </div>
              </div>

              <div class="quick-actions">
                <a href="index.php?controller=pembelian" class="btn btn-primary btn-block">
                  <i class="fas fa-shopping-bag"></i> Belanja Sekarang
                </a>
                <a href="index.php?controller=dashboard" class="btn btn-outline-primary btn-block">
                  <i class="fas fa-home"></i> Dashboard
                </a>
              </div>
            </div>

            <div class="col-lg-9 col-md-12">
              <div class="history-card">
                <div class="history-header">
                  <h4>Riwayat Transaksi</h4>
                  <div class="header-actions">
                    <select id="filterPeriod" class="form-control form-control-sm">
                      <option value="all">Semua Periode</option>
                      <option value="week">7 Hari Terakhir</option>
                      <option value="month">Bulan Ini</option>
                      <option value="quarter">3 Bulan Terakhir</option>
                    </select>
                  </div>
                </div>
                <div class="history-body">
                  <?php if (!empty($history)): ?>
                    <div class="transactions-list">
                      <?php foreach ($history as $transaksi): ?>
                      <div class="transaction-item">
                        <div class="transaction-header-item">
                          <div class="transaction-id">
                            <h6>#<?= $transaksi['transaksi_id'] ?></h6>
                            <span class="transaction-date"><?= date('d M Y, H:i', strtotime($transaksi['tanggal_transaksi'])) ?></span>
                          </div>
                          <div class="transaction-status">
                            <span class="status-badge success">Berhasil</span>
                          </div>
                        </div>
                        
                        <div class="transaction-details">
                          <div class="detail-grid">
                            <div class="detail-item">
                              <span class="detail-label">Total Item:</span>
                              <span class="detail-value"><?= $transaksi['total_item'] ?> item</span>
                            </div>
                            <div class="detail-item">
                              <span class="detail-label">Subtotal:</span>
                              <span class="detail-value">Rp <?= number_format($transaksi['total_sebelum_diskon']) ?></span>
                            </div>
                            <div class="detail-item">
                              <span class="detail-label">Diskon:</span>
                              <span class="detail-value discount">-Rp <?= number_format($transaksi['diskon_membership']) ?></span>
                            </div>
                            <div class="detail-item">
                              <span class="detail-label">Total Bayar:</span>
                              <span class="detail-value total">Rp <?= number_format($transaksi['total_bayar']) ?></span>
                            </div>
                            <div class="detail-item">
                              <span class="detail-label">Poin Didapat:</span>
                              <span class="detail-value points">+<?= number_format($transaksi['poin_didapat']) ?></span>
                            </div>
                            <div class="detail-item">
                              <span class="detail-label">Pembayaran:</span>
                              <span class="detail-value"><?= ucfirst($transaksi['metode_pembayaran']) ?></span>
                            </div>
                          </div>
                        </div>

                        <div class="transaction-actions">
                          <a href="index.php?controller=pembelian&action=detail&id=<?= $transaksi['transaksi_id'] ?>" 
                             class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye"></i> Detail
                          </a>
                          <button type="button" class="btn btn-sm btn-outline-info" 
                                  onclick="downloadReceipt(<?= $transaksi['transaksi_id'] ?>)">
                            <i class="fas fa-download"></i> Struk
                          </button>
                        </div>
                      </div>
                      <?php endforeach; ?>
                    </div>

                    <div class="pagination-wrapper">
                      <?php if ($currentPage > 1): ?>
                      <a href="index.php?controller=pembelian&action=history&page=<?= $currentPage - 1 ?>" 
                         class="btn btn-outline-primary">
                        <i class="fas fa-chevron-left"></i> Sebelumnya
                      </a>
                      <?php endif; ?>

                      <span class="pagination-info">Halaman <?= $currentPage ?></span>

                      <?php if ($hasMore): ?>
                      <a href="index.php?controller=pembelian&action=history&page=<?= $currentPage + 1 ?>" 
                         class="btn btn-outline-primary">
                        Selanjutnya <i class="fas fa-chevron-right"></i>
                      </a>
                      <?php endif; ?>
                    </div>

                  <?php else: ?>
                    <div class="empty-state">
                      <i class="fas fa-receipt"></i>
                      <h5>Belum Ada Transaksi</h5>
                      <p>Anda belum melakukan transaksi apapun</p>
                      <a href="index.php?controller=pembelian" class="btn btn-primary">
                        <i class="fas fa-shopping-bag"></i> Mulai Belanja
                      </a>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- CSS dan JavaScript tetap sama, tidak diubah -->

  <?php include 'template/script.php'; ?>
</body>


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
    }

    .page-header {
      background: linear-gradient(135deg, var(--primary-color) 0%, #764ba2 100%);
      border-radius: 12px;
      padding: 2rem;
      color: white;
      margin-bottom: 2rem;
    }

    .page-title {
      font-size: 2rem;
      font-weight: 700;
      margin-bottom: 0.5rem;
    }

    .page-subtitle {
      opacity: 0.9;
      margin-bottom: 0;
    }

    .member-summary-card, .history-card {
      background: white;
      border-radius: 12px;
      box-shadow: var(--shadow);
      border: 1px solid var(--border-color);
      margin-bottom: 2rem;
    }

    .member-summary-header, .history-header {
      padding: 1.5rem;
      border-bottom: 1px solid var(--border-color);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .member-summary-header h4, .history-header h4 {
      margin: 0;
      font-weight: 600;
      color: var(--text-primary);
    }

    .header-actions {
      display: flex;
      gap: 1rem;
      align-items: center;
    }

    .member-summary-body {
      padding: 1.5rem;
    }

    .summary-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid var(--border-color);
    }

    .summary-item:last-child {
      border-bottom: none;
      margin-bottom: 0;
      padding-bottom: 0;
    }

    .summary-label {
      font-weight: 500;
      color: var(--text-secondary);
    }

    .summary-value {
      font-weight: 600;
      color: var(--text-primary);
    }

    .summary-value.points {
      color: var(--primary-color);
      font-size: 1.1rem;
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

    .quick-actions {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    .quick-actions .btn {
      padding: 0.8rem;
      font-weight: 600;
      border-radius: 8px;
    }

    .history-body {
      padding: 1.5rem;
    }

    .transactions-list {
      margin-bottom: 2rem;
    }

    .transaction-item {
      border: 1px solid var(--border-color);
      border-radius: 8px;
      margin-bottom: 1.5rem;
      background: white;
      transition: all 0.3s ease;
    }

    .transaction-item:hover {
      box-shadow: 0 4px 8px rgba(0,0,0,0.15);
      transform: translateY(-2px);
    }

    .transaction-item:last-child {
      margin-bottom: 0;
    }

    .transaction-header-item {
      padding: 1rem 1.5rem;
      border-bottom: 1px solid var(--border-color);
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: var(--light-color);
      border-radius: 8px 8px 0 0;
    }

    .transaction-id h6 {
      margin: 0;
      font-weight: 700;
      color: var(--primary-color);
    }

    .transaction-date {
      font-size: 0.9rem;
      color: var(--text-secondary);
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

    .transaction-details {
      padding: 1.5rem;
    }

    .detail-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1rem;
    }

    .detail-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .detail-label {
      font-weight: 500;
      color: var(--text-secondary);
    }

    .detail-value {
      font-weight: 600;
      color: var(--text-primary);
    }

    .detail-value.discount {
      color: var(--success-color);
    }

    .detail-value.total {
      color: var(--primary-color);
      font-size: 1.1rem;
    }

    .detail-value.points {
      color: var(--warning-color);
    }

    .transaction-actions {
      padding: 1rem 1.5rem;
      border-top: 1px solid var(--border-color);
      display: flex;
      gap: 1rem;
      background: var(--light-color);
      border-radius: 0 0 8px 8px;
    }

    .pagination-wrapper {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1rem 0;
      border-top: 1px solid var(--border-color);
    }

    .pagination-info {
      font-weight: 500;
      color: var(--text-secondary);
    }

    .empty-state {
      text-align: center;
      padding: 4rem 2rem;
      color: var(--text-secondary);
    }

    .empty-state i {
      font-size: 4rem;
      margin-bottom: 1rem;
      opacity: 0.5;
    }

    .empty-state h5 {
      color: var(--text-primary);
      margin-bottom: 1rem;
    }

    .empty-state p {
      margin-bottom: 2rem;
    }

    @media (max-width: 768px) {
      .page-header {
        padding: 1.5rem;
      }

      .page-title {
        font-size: 1.5rem;
      }

      .history-header {
        flex-direction: column;
        gap: 1rem;
      }

      .header-actions {
        width: 100%;
      }

      .detail-grid {
        grid-template-columns: 1fr;
      }

      .transaction-header-item {
        flex-direction: column;
        gap: 0.5rem;
        text-align: center;
      }

      .transaction-actions {
        flex-direction: column;
      }

      .pagination-wrapper {
        flex-direction: column;
        gap: 1rem;
      }

      .quick-actions .btn {
        padding: 0.6rem;
      }
    }

    @media (max-width: 576px) {
      .transaction-details {
        padding: 1rem;
      }

      .transaction-actions {
        padding: 1rem;
      }

      .empty-state {
        padding: 2rem 1rem;
      }

      .empty-state i {
        font-size: 3rem;
      }
    }
  </style>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      initializeFilters();
    });

    function initializeFilters() {
      const filterPeriod = document.getElementById('filterPeriod');
      
      if (filterPeriod) {
        filterPeriod.addEventListener('change', function() {
          filterTransactions(this.value);
        });
      }
    }

    function filterTransactions(period) {
      const transactions = document.querySelectorAll('.transaction-item');
      const now = new Date();
      
      transactions.forEach(transaction => {
        const dateElement = transaction.querySelector('.transaction-date');
        if (!dateElement) return;
        
        const transactionDate = new Date(dateElement.textContent.replace(/(\d{2}) (\w{3}) (\d{4}), (\d{2}:\d{2})/, '$3-$2-$1 $4'));
        let showTransaction = true;
        
        switch(period) {
          case 'week':
            const weekAgo = new Date(now.getTime() - 7 * 24 * 60 * 60 * 1000);
            showTransaction = transactionDate >= weekAgo;
            break;
          case 'month':
            showTransaction = transactionDate.getMonth() === now.getMonth() && 
                            transactionDate.getFullYear() === now.getFullYear();
            break;
          case 'quarter':
            const threeMonthsAgo = new Date(now.getTime() - 90 * 24 * 60 * 60 * 1000);
            showTransaction = transactionDate >= threeMonthsAgo;
            break;
          case 'all':
          default:
            showTransaction = true;
        }
        
        transaction.style.display = showTransaction ? 'block' : 'none';
      });
      
      const visibleTransactions = document.querySelectorAll('.transaction-item[style="display: block"], .transaction-item:not([style])');
      const emptyState = document.querySelector('.empty-state');
      
      if (period !== 'all' && visibleTransactions.length === 0) {
        if (!emptyState) {
          const transactionsList = document.querySelector('.transactions-list');
          const noResults = document.createElement('div');
          noResults.className = 'empty-state';
          noResults.innerHTML = `
            <i class="fas fa-search"></i>
            <h5>Tidak Ada Transaksi</h5>
            <p>Tidak ada transaksi pada periode yang dipilih</p>
          `;
          transactionsList.appendChild(noResults);
        }
      } else if (emptyState && period === 'all') {
        emptyState.remove();
      }
    }

    function downloadReceipt(transaksiId) {
      const url = `index.php?controller=pembelian&action=detail&id=${transaksiId}&download=1`;
      window.open(url, '_blank');
    }

    // Auto-hide alerts
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
  </script>

  <?php include 'template/script.php'; ?>