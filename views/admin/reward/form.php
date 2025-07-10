<?php
$isEdit = isset($data['reward']);
$pageTitle = $isEdit ? 'Edit Reward' : 'Tambah Reward';
$reward = $data['reward'] ?? null;
?>
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
                        <i class="mdi mdi-<?= $isEdit ? 'pencil' : 'plus' ?> me-2"></i>
                        <?= $pageTitle ?>
                      </h4>
                      <a href="index.php?controller=adminreward" class="btn btn-light">
                        <i class="mdi mdi-arrow-left"></i> Kembali
                      </a>
                    </div>

                    <?php if (!empty($data['error'])): ?>
                      <div class="alert alert-danger alert-dismissible fade show">
                        <i class="mdi mdi-alert-circle me-2"></i>
                        <?= htmlspecialchars($data['error']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                      </div>
                    <?php endif; ?>

                    <form class="forms-sample" method="POST" action="index.php?controller=adminreward&action=<?= $isEdit ? 'edit&id=' . $reward['reward_id'] : 'create' ?>">
                      <div class="form-group">
                        <label for="nama_reward">Nama Reward <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_reward" name="nama_reward" 
                               value="<?= htmlspecialchars($reward['nama_reward'] ?? $_POST['nama_reward'] ?? '') ?>" 
                               placeholder="Masukkan nama reward" required>
                      </div>

                      <div class="form-group">
                        <label for="poin_required">Poin Yang Dibutuhkan <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="poin_required" name="poin_required" 
                               value="<?= htmlspecialchars($reward['poin_required'] ?? $_POST['poin_required'] ?? '') ?>" 
                               placeholder="Masukkan jumlah poin" min="1" required>
                      </div>

                      <div class="form-group">
                        <label for="stock">Stock <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="stock" name="stock" 
                               value="<?= htmlspecialchars($reward['stock'] ?? $_POST['stock'] ?? '10') ?>" 
                               placeholder="Masukkan jumlah stock" min="0" required>
                      </div>

                      <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-select" id="status" name="status">
                          <?php 
                          $currentStatus = $reward['status'] ?? $_POST['status'] ?? 'aktif';
                          ?>
                          <option value="aktif" <?= $currentStatus === 'aktif' ? 'selected' : '' ?>>Aktif</option>
                          <option value="tidak_aktif" <?= $currentStatus === 'tidak_aktif' ? 'selected' : '' ?>>Tidak Aktif</option>
                        </select>
                      </div>

                      <div class="d-flex justify-content-end">
                        <a href="index.php?controller=adminreward" class="btn btn-light me-2">
                          <i class="mdi mdi-close"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-<?= $isEdit ? 'warning' : 'success' ?>">
                          <i class="mdi mdi-content-save"></i> <?= $isEdit ? 'Update' : 'Simpan' ?> Reward
                        </button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php include 'template/script.php'; ?>
</body>

</html>