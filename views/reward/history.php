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
                <h2>Riwayat Penukaran Reward</h2>
                <div class="poin-info">
                  <i class="fas fa-coins"></i>
                  <span>Poin Anda: <strong><?= number_format($memberPoin) ?></strong></span>
                </div>
              </div>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-12">
              <a href="index.php?controller=reward" class="btn btn-primary">
                <i class="fas fa-gift"></i> Tukar Reward
              </a>
            </div>
          </div>

          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h4>Riwayat Penukaran</h4>
                </div>
                <div class="card-body">
                  <?php if (!empty($history)): ?>
                    <div class="table-responsive">
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>ID</th>
                            <th>Reward</th>
                            <th>Poin Digunakan</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach ($history as $item): ?>
                          <tr>
                            <td>#<?= $item['tukar_id'] ?></td>
                            <td><?= htmlspecialchars($item['nama_reward'] ?: $item['reward']) ?></td>
                            <td>
                              <span class="badge badge-warning">
                                <?= number_format($item['poin_digunakan']) ?> poin
                              </span>
                            </td>
                            <td><?= date('d M Y, H:i', strtotime($item['tanggal_tukar'])) ?></td>
                            <td>
                              <?php if ($item['status'] === 'pending'): ?>
                                <span class="badge badge-warning">Pending</span>
                              <?php else: ?>
                                <span class="badge badge-success">Selesai</span>
                              <?php endif; ?>
                            </td>
                          </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>
                  <?php else: ?>
                    <div class="text-center py-5">
                      <i class="fas fa-history fa-3x text-muted mb-3"></i>
                      <h5>Belum Ada Riwayat</h5>
                      <p class="text-muted">Anda belum pernah menukar reward</p>
                      <a href="index.php?controller=reward" class="btn btn-primary">
                        <i class="fas fa-gift"></i> Tukar Reward Sekarang
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

  <style>
    .page-header {
      background: linear-gradient(135deg, #667eea, #764ba2);
      color: white;
      padding: 2rem;
      border-radius: 10px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
    }

    .page-header h2 {
      margin: 0;
      font-weight: 700;
    }

    .poin-info {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-size: 1.2rem;
    }

    .poin-info i {
      font-size: 1.5rem;
    }

    .card {
      border-radius: 10px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .card-header {
      background: #f8f9fa;
      border-bottom: 1px solid #dee2e6;
      font-weight: 600;
    }

    .badge-warning {
      background: #ffc107;
      color: #212529;
    }

    .badge-success {
      background: #28a745;
      color: white;
    }

    .btn {
      border-radius: 6px;
    }

    @media (max-width: 768px) {
      .page-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
      }

      .poin-info {
        justify-content: center;
      }
    }
  </style>

  <?php include 'template/script.php'; ?>
</body>
</html>