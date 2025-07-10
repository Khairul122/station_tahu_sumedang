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
              <div class="home-tab">
                <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                  <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Dashboard</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#rewards" role="tab" aria-selected="false">Kelola Reward</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#statistics" role="tab" aria-selected="false">Statistik</a>
                    </li>
                  </ul>
                  <div>
                    <a href="index.php?controller=adminreward&action=tukarPoin" class="btn btn-outline-info btn-sm me-2">
                      <i class="icon-paper"></i> Riwayat Tukar Poin
                    </a>
                    <a href="index.php?controller=adminreward&action=create" class="btn btn-success btn-sm">
                      <i class="icon-plus"></i> Tambah Reward
                    </a>
                  </div>
                </div>

                <?php if (!empty($data['success'])): ?>
                  <div class="alert alert-success alert-dismissible fade show mt-3">
                    <i class="mdi mdi-check-circle me-2"></i>
                    <?= htmlspecialchars($data['success']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                  </div>
                <?php endif; ?>

                <?php if (!empty($data['error'])): ?>
                  <div class="alert alert-danger alert-dismissible fade show mt-3">
                    <i class="mdi mdi-alert-circle me-2"></i>
                    <?= htmlspecialchars($data['error']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                  </div>
                <?php endif; ?>

                <div class="tab-content tab-content-basic">
                  <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="home-tab">
                    <div class="row">
                      <div class="col-lg-3 col-sm-6">
                        <div class="card">
                          <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                              <div>
                                <h6 class="card-title mb-0">Total Reward</h6>
                                <h3 class="text-primary"><?= number_format($data['dashboardStats']['total_rewards']) ?></h3>
                              </div>
                              <div class="icon-lg bg-primary text-white rounded-circle d-flex align-items-center justify-content-center">
                                <i class="mdi mdi-gift"></i>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-3 col-sm-6">
                        <div class="card">
                          <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                              <div>
                                <h6 class="card-title mb-0">Pending</h6>
                                <h3 class="text-warning"><?= number_format($data['dashboardStats']['pending_tukar']) ?></h3>
                              </div>
                              <div class="icon-lg bg-warning text-white rounded-circle d-flex align-items-center justify-content-center">
                                <i class="mdi mdi-clock"></i>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-3 col-sm-6">
                        <div class="card">
                          <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                              <div>
                                <h6 class="card-title mb-0">Selesai</h6>
                                <h3 class="text-success"><?= number_format($data['dashboardStats']['selesai_tukar']) ?></h3>
                              </div>
                              <div class="icon-lg bg-success text-white rounded-circle d-flex align-items-center justify-content-center">
                                <i class="mdi mdi-check-circle"></i>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-3 col-sm-6">
                        <div class="card">
                          <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                              <div>
                                <h6 class="card-title mb-0">Total Poin Ditukar</h6>
                                <h3 class="text-info"><?= number_format($data['dashboardStats']['total_poin_ditukar']) ?></h3>
                              </div>
                              <div class="icon-lg bg-info text-white rounded-circle d-flex align-items-center justify-content-center">
                                <i class="mdi mdi-star"></i>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="tab-pane fade" id="rewards" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="row">
                      <div class="col-12">
                        <div class="card">
                          <div class="card-body">
                            <h4 class="card-title">Daftar Reward</h4>
                            <div class="table-responsive">
                              <table class="table table-striped">
                                <thead>
                                  <tr>
                                    <th>ID</th>
                                    <th>Nama Reward</th>
                                    <th>Poin Required</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php if (empty($data['rewards'])): ?>
                                    <tr>
                                      <td colspan="6" class="text-center">Belum ada data reward</td>
                                    </tr>
                                  <?php else: ?>
                                    <?php foreach ($data['rewards'] as $reward): ?>
                                      <tr>
                                        <td><?= $reward['reward_id'] ?></td>
                                        <td><?= htmlspecialchars($reward['nama_reward']) ?></td>
                                        <td><?= number_format($reward['poin_required']) ?></td>
                                        <td><?= number_format($reward['stock']) ?></td>
                                        <td>
                                          <label class="badge badge-<?= $reward['status'] === 'aktif' ? 'success' : 'secondary' ?>">
                                            <?= ucfirst($reward['status']) ?>
                                          </label>
                                        </td>
                                        <td>
                                          <a href="index.php?controller=adminreward&action=edit&id=<?= $reward['reward_id'] ?>" 
                                             class="btn btn-warning btn-sm">
                                            <i class="mdi mdi-pencil"></i>
                                          </a>
                                          <button type="button" class="btn btn-danger btn-sm" 
                                                  onclick="confirmDelete(<?= $reward['reward_id'] ?>, '<?= htmlspecialchars($reward['nama_reward']) ?>')">
                                            <i class="mdi mdi-delete"></i>
                                          </button>
                                        </td>
                                      </tr>
                                    <?php endforeach; ?>
                                  <?php endif; ?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="tab-pane fade" id="statistics" role="tabpanel" aria-labelledby="contact-tab">
                    <div class="row">
                      <div class="col-12">
                        <div class="card">
                          <div class="card-body">
                            <h4 class="card-title">Statistik Reward</h4>
                            <div class="table-responsive">
                              <table class="table">
                                <thead>
                                  <tr>
                                    <th>Nama Reward</th>
                                    <th>Poin Required</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th>Total Ditukar</th>
                                    <th>Pending</th>
                                    <th>Selesai</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php foreach ($data['statistics'] as $stat): ?>
                                    <tr>
                                      <td><?= htmlspecialchars($stat['nama_reward']) ?></td>
                                      <td><?= number_format($stat['poin_required']) ?></td>
                                      <td><?= number_format($stat['stock']) ?></td>
                                      <td>
                                        <label class="badge badge-<?= $stat['status'] === 'aktif' ? 'success' : 'secondary' ?>">
                                          <?= ucfirst($stat['status']) ?>
                                        </label>
                                      </td>
                                      <td><?= number_format($stat['total_ditukar']) ?></td>
                                      <td><?= number_format($stat['pending']) ?></td>
                                      <td><?= number_format($stat['selesai']) ?></td>
                                    </tr>
                                  <?php endforeach; ?>
                                </tbody>
                              </table>
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
  </div>

  <form id="deleteForm" method="POST" action="index.php?controller=adminreward&action=delete" style="display: none;">
    <input type="hidden" name="reward_id" id="deleteRewardId">
  </form>

  <?php include 'template/script.php'; ?>
  <script>
    function confirmDelete(rewardId, namaReward) {
      if (confirm('Apakah Anda yakin ingin menghapus reward "' + namaReward + '"?')) {
        document.getElementById('deleteRewardId').value = rewardId;
        document.getElementById('deleteForm').submit();
      }
    }
  </script>
</body>

</html>