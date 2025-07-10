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
            <div class="col-sm-12">
              <div class="col-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                      <h4 class="card-title">
                        <i class="mdi mdi-swap-horizontal me-2"></i>
                        Riwayat Tukar Poin
                      </h4>
                      <a href="index.php?controller=adminreward" class="btn btn-light">
                        <i class="mdi mdi-arrow-left"></i> Kembali ke Reward
                      </a>
                    </div>

                    <?php if (!empty($data['success'])): ?>
                      <div class="alert alert-success alert-dismissible fade show">
                        <i class="mdi mdi-check-circle me-2"></i>
                        <?= htmlspecialchars($data['success']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                      </div>
                    <?php endif; ?>

                    <?php if (!empty($data['error'])): ?>
                      <div class="alert alert-danger alert-dismissible fade show">
                        <i class="mdi mdi-alert-circle me-2"></i>
                        <?= htmlspecialchars($data['error']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                      </div>
                    <?php endif; ?>

                    <div class="row mb-3">
                      <div class="col-md-6">
                        <form method="GET" action="index.php" class="d-flex">
                          <input type="hidden" name="controller" value="adminreward">
                          <input type="hidden" name="action" value="tukarPoin">
                          <select name="status" class="form-select me-2" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="pending" <?= $data['selectedStatus'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="selesai" <?= $data['selectedStatus'] === 'selesai' ? 'selected' : '' ?>>Selesai</option>
                          </select>
                        </form>
                      </div>
                      <div class="col-md-6 text-end">
                        <small class="text-muted">
                          Total: <?= number_format($data['totalCount']) ?> penukaran
                          <?php if (!empty($data['selectedStatus'])): ?>
                            (Status: <?= ucfirst($data['selectedStatus']) ?>)
                          <?php endif; ?>
                        </small>
                      </div>
                    </div>

                    <div class="table-responsive">
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Reward</th>
                            <th>Poin Digunakan</th>
                            <th>Tanggal Tukar</th>
                            <th>Status</th>
                            <th>Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if (empty($data['tukarPoinList'])): ?>
                            <tr>
                              <td colspan="7" class="text-center">Belum ada data penukaran poin</td>
                            </tr>
                          <?php else: ?>
                            <?php foreach ($data['tukarPoinList'] as $tukar): ?>
                              <tr>
                                <td><?= $tukar['tukar_id'] ?></td>
                                <td>
                                  <div>
                                    <strong><?= htmlspecialchars($tukar['nama_customer']) ?></strong><br>
                                    <small class="text-muted"><?= htmlspecialchars($tukar['no_telepon']) ?></small>
                                  </div>
                                </td>
                                <td><?= htmlspecialchars($tukar['nama_reward'] ?? $tukar['reward']) ?></td>
                                <td><?= number_format($tukar['poin_digunakan']) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($tukar['tanggal_tukar'])) ?></td>
                                <td>
                                  <label class="badge badge-<?= $tukar['status'] === 'selesai' ? 'success' : 'warning' ?>">
                                    <?= ucfirst($tukar['status']) ?>
                                  </label>
                                </td>
                                <td>
                                  <a href="index.php?controller=adminreward&action=detailTukarPoin&id=<?= $tukar['tukar_id'] ?>" 
                                     class="btn btn-info btn-sm">
                                    <i class="mdi mdi-eye"></i>
                                  </a>
                                  <?php if ($tukar['status'] === 'pending'): ?>
                                    <button type="button" class="btn btn-success btn-sm" 
                                            onclick="updateStatus(<?= $tukar['tukar_id'] ?>, 'selesai')">
                                      <i class="mdi mdi-check"></i>
                                    </button>
                                  <?php endif; ?>
                                </td>
                              </tr>
                            <?php endforeach; ?>
                          <?php endif; ?>
                        </tbody>
                      </table>
                    </div>

                    <?php if ($data['totalPages'] > 1): ?>
                      <nav aria-label="Pagination" class="mt-4">
                        <ul class="pagination justify-content-center">
                          <?php for ($i = 1; $i <= $data['totalPages']; $i++): ?>
                            <li class="page-item <?= $i === $data['currentPage'] ? 'active' : '' ?>">
                              <a class="page-link" href="index.php?controller=adminreward&action=tukarPoin&page=<?= $i ?><?= !empty($data['selectedStatus']) ? '&status=' . $data['selectedStatus'] : '' ?>">
                                <?= $i ?>
                              </a>
                            </li>
                          <?php endfor; ?>
                        </ul>
                      </nav>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <form id="statusForm" method="POST" action="index.php?controller=adminreward&action=updateStatusTukarPoin" style="display: none;">
    <input type="hidden" name="tukar_id" id="statusTukarId">
    <input type="hidden" name="status" id="statusValue">
  </form>

  <?php include 'template/script.php'; ?>
  <script>
    function updateStatus(tukarId, status) {
      if (confirm('Apakah Anda yakin ingin mengubah status menjadi "' + status + '"?')) {
        document.getElementById('statusTukarId').value = tukarId;
        document.getElementById('statusValue').value = status;
        document.getElementById('statusForm').submit();
      }
    }
  </script>
</body>

</html>