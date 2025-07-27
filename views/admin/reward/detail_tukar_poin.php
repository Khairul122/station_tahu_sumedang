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
                        <i class="mdi mdi-information me-2"></i>
                        Detail Penukaran Poin #<?= $data['tukarPoin']['tukar_id'] ?>
                      </h4>
                      <a href="index.php?controller=adminreward&action=tukarPoin" class="btn btn-light">
                        <i class="mdi mdi-arrow-left"></i> Kembali
                      </a>
                    </div>

                    <div class="row">
                      <div class="col-md-6">
                        <div class="card">
                          <div class="card-body">
                            <h5 class="card-title">
                              <i class="mdi mdi-account me-2"></i>
                              Informasi Customer
                            </h5>
                            <table class="table table-borderless">
                              <tbody>
                                <tr>
                                  <td width="40%"><strong>Nama:</strong></td>
                                  <td><?= htmlspecialchars($data['tukarPoin']['nama_customer']) ?></td>
                                </tr>
                                <tr>
                                  <td><strong>No. Telepon:</strong></td>
                                  <td><?= htmlspecialchars($data['tukarPoin']['no_telepon']) ?></td>
                                </tr>
                                <tr>
                                  <td><strong>Email:</strong></td>
                                  <td><?= htmlspecialchars($data['tukarPoin']['email']) ?></td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="card">
                          <div class="card-body">
                            <h5 class="card-title">
                              <i class="mdi mdi-gift me-2"></i>
                              Informasi Reward
                            </h5>
                            <table class="table table-borderless">
                              <tbody>
                                <tr>
                                  <td width="40%"><strong>Reward:</strong></td>
                                  <td><?= htmlspecialchars($data['tukarPoin']['nama_reward'] ?? $data['tukarPoin']['reward']) ?></td>
                                </tr>
                                <tr>
                                  <td><strong>Poin Digunakan:</strong></td>
                                  <td><?= number_format($data['tukarPoin']['poin_digunakan']) ?></td>
                                </tr>
                                <?php if (!empty($data['tukarPoin']['poin_required'])): ?>
                                <tr>
                                  <td><strong>Poin Required:</strong></td>
                                  <td><?= number_format($data['tukarPoin']['poin_required']) ?></td>
                                </tr>
                                <?php endif; ?>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="row mt-4">
                      <div class="col-md-6">
                        <div class="card">
                          <div class="card-body">
                            <h5 class="card-title">
                              <i class="mdi mdi-calendar me-2"></i>
                              Informasi Transaksi
                            </h5>
                            <table class="table table-borderless">
                              <tbody>
                                <tr>
                                  <td width="40%"><strong>Tanggal Tukar:</strong></td>
                                  <td><?= date('d/m/Y H:i:s', strtotime($data['tukarPoin']['tanggal_tukar'])) ?></td>
                                </tr>
                                <tr>
                                  <td><strong>Status:</strong></td>
                                  <td>
                                    <label class="badge badge-<?= $data['tukarPoin']['status'] === 'selesai' ? 'success' : 'warning' ?> badge-pill">
                                      <?= ucfirst($data['tukarPoin']['status']) ?>
                                    </label>
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="card">
                          <div class="card-body">
                            <h5 class="card-title">
                              <i class="mdi mdi-cog me-2"></i>
                              Aksi
                            </h5>
                            <?php if ($data['tukarPoin']['status'] === 'pending'): ?>
                              <div class="d-grid gap-2">
                                <button type="button" class="btn btn-success" 
                                        onclick="updateStatus(<?= $data['tukarPoin']['tukar_id'] ?>, 'selesai')">
                                  <i class="mdi mdi-check-circle me-2"></i>
                                  Selesaikan Penukaran
                                </button>
                              </div>
                            <?php else: ?>
                              <div class="alert alert-success">
                                <i class="mdi mdi-check-circle me-2"></i>
                                Penukaran sudah selesai
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