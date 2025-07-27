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
                <h2>Tukar Poin</h2>
                <div class="poin-info">
                  <i class="fas fa-coins"></i>
                  <span>Poin Anda: <strong><?= number_format($memberPoin) ?></strong></span>
                </div>
              </div>
            </div>
          </div>

          <?php if (!empty($success)): ?>
          <div class="alert alert-success">
            <?= htmlspecialchars($success) ?>
          </div>
          <?php endif; ?>

          <?php if (!empty($error)): ?>
          <div class="alert alert-danger">
            <?= htmlspecialchars($error) ?>
          </div>
          <?php endif; ?>

          <div class="row mb-3">
            <div class="col-12">
              <a href="index.php?controller=reward&action=history" class="btn btn-outline-primary">
                <i class="fas fa-history"></i> Riwayat Penukaran
              </a>
            </div>
          </div>

          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h4>Daftar Reward</h4>
                </div>
                <div class="card-body">
                  <?php if (!empty($rewards)): ?>
                    <div class="table-responsive">
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>Nama Reward</th>
                            <th>Poin Diperlukan</th>
                            <th>Stock</th>
                            <th>Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach ($rewards as $reward): ?>
                          <tr>
                            <td><?= htmlspecialchars($reward['nama_reward']) ?></td>
                            <td>
                              <span class="badge badge-warning">
                                <?= number_format($reward['poin_required']) ?> poin
                              </span>
                            </td>
                            <td><?= $reward['stock'] ?></td>
                            <td>
                              <?php if ($memberPoin >= $reward['poin_required']): ?>
                                <form method="POST" action="index.php?controller=reward&action=tukar" style="display: inline;">
                                  <input type="hidden" name="reward_id" value="<?= $reward['reward_id'] ?>">
                                  <button type="submit" class="btn btn-success btn-sm" 
                                          onclick="return confirm('Yakin ingin menukar <?= htmlspecialchars($reward['nama_reward']) ?>?')">
                                    <i class="fas fa-exchange-alt"></i> Tukar
                                  </button>
                                </form>
                              <?php else: ?>
                                <button class="btn btn-secondary btn-sm" disabled>
                                  Poin Kurang
                                </button>
                              <?php endif; ?>
                            </td>
                          </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>
                  <?php else: ?>
                    <div class="text-center py-5">
                      <i class="fas fa-gift fa-3x text-muted mb-3"></i>
                      <h5>Tidak Ada Reward</h5>
                      <p class="text-muted">Belum ada reward yang tersedia saat ini</p>
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
      background: linear-gradient(135deg, #ffc107, #ff8c00);
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

    .alert {
      border-radius: 8px;
      margin-bottom: 1rem;
    }

    .badge-warning {
      background: #ffc107;
      color: #212529;
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