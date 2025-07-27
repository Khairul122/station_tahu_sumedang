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
                  <h2 class="page-title">Riwayat Transaksi</h2>
                  <p class="page-subtitle">Kelola dan lihat semua transaksi Anda</p>
                </div>
                <div class="header-actions">
                  <a href="index.php?controller=member&action=exportTransaksi<?= !empty($startDate) && !empty($endDate) ? '&start_date=' . $startDate . '&end_date=' . $endDate : '' ?>" 
                     class="btn btn-success">
                    <i class="fas fa-download"></i> Export CSV
                  </a>
                </div>
              </div>
            </div>
          </div>

          <div class="row mb-4">
            <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
              <div class="stats-card total-transaksi">
                <div class="stats-icon">
                  <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stats-content">
                  <h4><?= number_format($transaksiSummary['total_transaksi']) ?></h4>
                  <span>Total Transaksi</span>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
              <div class="stats-card total-spending">
                <div class="stats-icon">
                  <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="stats-content">
                  <h4>Rp <?= number_format($transaksiSummary['total_spending']) ?></h4>
                  <span>Total Pengeluaran</span>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
            <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
              <div class="stats-card avg-spending">
                <div class="stats-icon">
                  <i class="fas fa-chart-bar"></i>
                </div>
                <div class="stats-content">
                  <h4>Rp <?= number_format($transaksiSummary['rata_spending_per_transaksi']) ?></h4>
                  <span>Rata-rata per Transaksi</span>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
              <div class="stats-card total-hemat">
                <div class="stats-icon">
                  <i class="fas fa-piggy-bank"></i>
                </div>
                <div class="stats-content">
                  <h4>Rp <?= number_format($transaksiSummary['total_hemat']) ?></h4>
                  <span>Total Penghematan</span>
                </div>
              </div>
            </div>
          </div>

          <div class="row mb-4">
            <div class="col-lg-8 col-md-12">
              <div class="filter-card">
                <div class="filter-header">
                  <h4>Filter & Pencarian</h4>
                </div>
                <div class="filter-body">
                  <form method="GET" action="index.php">
                    <input type="hidden" name="controller" value="member">
                    <input type="hidden" name="action" value="transaksi">
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="search">Cari Transaksi</label>
                          <input type="text" class="form-control" id="search" name="search" 
                                 value="<?= htmlspecialchars($search) ?>" placeholder="ID, produk, metode...">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="start_date">Tanggal Mulai</label>
                          <input type="date" class="form-control" id="start_date" name="start_date" 
                                 value="<?= htmlspecialchars($startDate) ?>">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="end_date">Tanggal Akhir</label>
                          <input type="date" class="form-control" id="end_date" name="end_date" 
                                 value="<?= htmlspecialchars($endDate) ?>">
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label>&nbsp;</label>
                          <div class="btn-group-vertical w-100">
                            <button type="submit" class="btn btn-primary btn-sm">
                              <i class="fas fa-search"></i> Cari
                            </button>
                            <a href="index.php?controller=member&action=transaksi" class="btn btn-outline-secondary btn-sm">
                              <i class="fas fa-refresh"></i> Reset
                            </a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>

            <div class="col-lg-4 col-md-12">
              <div class="chart-card">
                <div class="chart-header">
                  <h4>Tren Pengeluaran (6 Bulan)</h4>
                </div>
                <div class="chart-body">
                  <canvas id="monthlyChart" height="150"></canvas>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-12">
              <div class="transaksi-card">
                <div class="transaksi-header">
                  <h4>Daftar Transaksi</h4>
                  <div class="transaksi-info">
                    <?php if (!empty($search) || !empty($startDate) || !empty($endDate)): ?>
                      <span class="result-info">Menampilkan <?= count($transaksiList) ?> dari <?= $totalTransaksi ?> transaksi</span>
                    <?php else: ?>
                      <span class="result-info">Total <?= $totalTransaksi ?> transaksi</span>
                    <?php endif; ?>
                  </div>
                </div>
                <div class="transaksi-body">
                  <?php if (!empty($transaksiList)): ?>
                    <div class="table-responsive">
                      <table class="table transaksi-table">
                        <thead>
                          <tr>
                            <th>ID</th>
                            <th>Tanggal</th>
                            <th>Item</th>
                            <th>Subtotal</th>
                            <th>Diskon</th>
                            <th>Total</th>
                            <th>Poin</th>
                            <th>Pembayaran</th>
                            <th>Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach ($transaksiList as $transaksi): ?>
                          <tr>
                            <td>
                              <span class="transaksi-id">#<?= $transaksi['transaksi_id'] ?></span>
                            </td>
                            <td>
                              <span class="transaksi-date"><?= date('d/m/Y', strtotime($transaksi['tanggal_transaksi'])) ?></span>
                              <small class="transaksi-time"><?= date('H:i', strtotime($transaksi['tanggal_transaksi'])) ?></small>
                            </td>
                            <td>
                              <span class="item-count"><?= $transaksi['total_item'] ?> jenis</span>
                              <small class="item-quantity"><?= $transaksi['total_quantity'] ?> item</small>
                            </td>
                            <td>
                              <span class="amount">Rp <?= number_format($transaksi['total_sebelum_diskon']) ?></span>
                            </td>
                            <td>
                              <span class="discount">-Rp <?= number_format($transaksi['diskon_membership']) ?></span>
                            </td>
                            <td>
                              <span class="total-amount">Rp <?= number_format($transaksi['total_bayar']) ?></span>
                            </td>
                            <td>
                              <span class="points-earned">+<?= $transaksi['poin_didapat'] ?></span>
                            </td>
                            <td>
                              <span class="payment-method"><?= ucfirst($transaksi['metode_pembayaran']) ?></span>
                            </td>
                            <td>
                              <div class="action-buttons">
                                <a href="index.php?controller=member&action=transaksiDetail&id=<?= $transaksi['transaksi_id'] ?>" 
                                   class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                  <i class="fas fa-eye"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-success reorder-btn" 
                                        data-transaksi-id="<?= $transaksi['transaksi_id'] ?>" title="Beli Lagi">
                                  <i class="fas fa-redo"></i>
                                </button>
                              </div>
                            </td>
                          </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>

                    <?php if ($totalPages > 1): ?>
                    <div class="pagination-wrapper">
                      <nav aria-label="Pagination">
                        <ul class="pagination">
                          <?php if ($currentPage > 1): ?>
                            <li class="page-item">
                              <a class="page-link" href="?controller=member&action=transaksi&page=<?= $currentPage - 1 ?><?= $search ? '&search=' . urlencode($search) : '' ?><?= $startDate ? '&start_date=' . $startDate : '' ?><?= $endDate ? '&end_date=' . $endDate : '' ?>">
                                <i class="fas fa-chevron-left"></i>
                              </a>
                            </li>
                          <?php endif; ?>

                          <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                            <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                              <a class="page-link" href="?controller=member&action=transaksi&page=<?= $i ?><?= $search ? '&search=' . urlencode($search) : '' ?><?= $startDate ? '&start_date=' . $startDate : '' ?><?= $endDate ? '&end_date=' . $endDate : '' ?>">
                                <?= $i ?>
                              </a>
                            </li>
                          <?php endfor; ?>

                          <?php if ($currentPage < $totalPages): ?>
                            <li class="page-item">
                              <a class="page-link" href="?controller=member&action=transaksi&page=<?= $currentPage + 1 ?><?= $search ? '&search=' . urlencode($search) : '' ?><?= $startDate ? '&start_date=' . $startDate : '' ?><?= $endDate ? '&end_date=' . $endDate : '' ?>">
                                <i class="fas fa-chevron-right"></i>
                              </a>
                            </li>
                          <?php endif; ?>
                        </ul>
                      </nav>
                      <div class="pagination-info">
                        Halaman <?= $currentPage ?> dari <?= $totalPages ?> (<?= $totalTransaksi ?> total transaksi)
                      </div>
                    </div>
                    <?php endif; ?>

                  <?php else: ?>
                    <div class="empty-state">
                      <i class="fas fa-receipt"></i>
                      <h5>Tidak Ada Transaksi</h5>
                      <?php if (!empty($search) || !empty($startDate) || !empty($endDate)): ?>
                        <p>Tidak ditemukan transaksi sesuai filter yang diterapkan</p>
                        <a href="index.php?controller=member&action=transaksi" class="btn btn-primary">Reset Filter</a>
                      <?php else: ?>
                        <p>Anda belum melakukan transaksi apapun</p>
                        <a href="index.php?controller=pembelian" class="btn btn-primary">Mulai Berbelanja</a>
                      <?php endif; ?>
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

  <style>
    :root {
      --primary-color: #667eea;
      --success-color: #28a745;
      --warning-color: #ffc107;
      --info-color: #17a2b8;
      --danger-color: #dc3545;
      --light-color: #f8f9fa;
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
      display: flex;
      justify-content: space-between;
      align-items: center;
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

    .header-actions .btn {
      background: rgba(255,255,255,0.2);
      border: 1px solid rgba(255,255,255,0.3);
      color: white;
    }

    .header-actions .btn:hover {
      background: rgba(255,255,255,0.3);
      color: white;
    }

    .stats-card {
      background: white;
      border-radius: 12px;
      padding: 1.5rem;
      box-shadow: var(--shadow);
      border: 1px solid var(--border-color);
      display: flex;
      align-items: center;
      gap: 1rem;
      transition: transform 0.3s ease;
    }

    .stats-card:hover {
      transform: translateY(-3px);
    }

    .stats-icon {
      width: 60px;
      height: 60px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      color: white;
    }

    .total-transaksi .stats-icon { background: var(--primary-color); }
    .total-spending .stats-icon { background: var(--success-color); }
    .avg-spending .stats-icon { background: var(--info-color); }
    .total-hemat .stats-icon { background: var(--warning-color); }

    .stats-content h4 {
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--text-primary);
      margin-bottom: 0.3rem;
    }

    .stats-content span {
      color: var(--text-secondary);
      font-size: 0.9rem;
    }

    .filter-card, .chart-card, .transaksi-card {
      background: white;
      border-radius: 12px;
      box-shadow: var(--shadow);
      border: 1px solid var(--border-color);
      margin-bottom: 2rem;
    }

    .filter-header, .chart-header, .transaksi-header {
      padding: 1.5rem;
      border-bottom: 1px solid var(--border-color);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .filter-header h4, .chart-header h4, .transaksi-header h4 {
      margin: 0;
      font-weight: 600;
      color: var(--text-primary);
    }

    .filter-body, .chart-body, .transaksi-body {
      padding: 1.5rem;
    }

    .form-group {
      margin-bottom: 1rem;
    }

    .form-group label {
      font-weight: 500;
      color: var(--text-primary);
      margin-bottom: 0.5rem;
    }

    .form-control {
      border-radius: 6px;
      border: 1px solid var(--border-color);
      padding: 0.6rem;
    }

    .form-control:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .btn {
      border-radius: 6px;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .btn:hover {
      transform: translateY(-1px);
    }

    .result-info {
      color: var(--text-secondary);
      font-size: 0.9rem;
    }

    .transaksi-table {
      margin-bottom: 0;
    }

    .transaksi-table th {
      background: var(--light-color);
      border: none;
      font-weight: 600;
      color: var(--text-primary);
      padding: 1rem 0.8rem;
    }

    .transaksi-table td {
      padding: 1rem 0.8rem;
      border: none;
      border-bottom: 1px solid var(--border-color);
      vertical-align: middle;
    }

    .transaksi-table tr:hover {
      background: var(--light-color);
    }

    .transaksi-id {
      font-weight: 600;
      color: var(--primary-color);
    }

    .transaksi-date {
      font-weight: 500;
      color: var(--text-primary);
      display: block;
    }

    .transaksi-time {
      color: var(--text-secondary);
      font-size: 0.8rem;
    }

    .item-count {
      font-weight: 500;
      color: var(--text-primary);
      display: block;
    }

    .item-quantity {
      color: var(--text-secondary);
      font-size: 0.8rem;
    }

    .amount {
      color: var(--text-primary);
      font-weight: 500;
    }

    .discount {
      color: var(--success-color);
      font-weight: 500;
    }

    .total-amount {
      color: var(--primary-color);
      font-weight: 600;
      font-size: 1.05rem;
    }

    .points-earned {
      color: var(--warning-color);
      font-weight: 600;
    }

    .payment-method {
      background: var(--info-color);
      color: white;
      padding: 0.3rem 0.6rem;
      border-radius: 12px;
      font-size: 0.8rem;
      font-weight: 500;
    }

    .action-buttons {
      display: flex;
      gap: 0.5rem;
    }

    .action-buttons .btn {
      padding: 0.4rem 0.6rem;
    }

    .pagination-wrapper {
      display: flex;
      justify-content: between;
      align-items: center;
      padding-top: 1.5rem;
      border-top: 1px solid var(--border-color);
    }

    .pagination {
      margin: 0;
    }

    .pagination .page-link {
      color: var(--primary-color);
      border: 1px solid var(--border-color);
      border-radius: 6px;
      margin: 0 2px;
    }

    .pagination .page-item.active .page-link {
      background: var(--primary-color);
      border-color: var(--primary-color);
    }

    .pagination-info {
      color: var(--text-secondary);
      font-size: 0.9rem;
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
        flex-direction: column;
        gap: 1rem;
        text-align: center;
      }

      .page-title {
        font-size: 1.5rem;
      }

      .stats-card {
        flex-direction: column;
        text-align: center;
      }

      .stats-icon {
        margin-bottom: 1rem;
      }

      .table-responsive {
        font-size: 0.9rem;
      }

      .pagination-wrapper {
        flex-direction: column;
        gap: 1rem;
      }
    }
  </style>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const ctx = document.getElementById('monthlyChart').getContext('2d');
      const monthlyData = <?= json_encode($monthlyStats) ?>;
      
      const labels = monthlyData.map(item => item.month);
      const data = monthlyData.map(item => item.total_spending);
      
      new Chart(ctx, {
        type: 'line',
        data: {
          labels: labels,
          datasets: [{
            label: 'Pengeluaran',
            data: data,
            borderColor: '#667eea',
            backgroundColor: 'rgba(102, 126, 234, 0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                callback: function(value) {
                  return 'Rp ' + value.toLocaleString();
                }
              }
            }
          }
        }
      });

      document.querySelectorAll('.reorder-btn').forEach(btn => {
        btn.addEventListener('click', function() {
          const transaksiId = this.dataset.transaksiId;
          if (confirm('Beli lagi produk dari transaksi ini?')) {
            window.location.href = `index.php?controller=pembelian&reorder=${transaksiId}`;
          }
        });
      });
    });
  </script>

  <?php include 'template/script.php'; ?>
</body>
</html>