\<?php include('template/header.php'); ?>

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
                        <i class="mdi mdi-calendar-month me-2"></i>
                        Laporan Data Penjualan Bulanan
                      </h4>
                      <a href="?controller=laporan" class="btn btn-light">
                        <i class="mdi mdi-arrow-left"></i> Kembali
                      </a>
                    </div>

                    <form method="GET" action="index.php" class="mb-4">
                      <input type="hidden" name="controller" value="laporan">
                      <input type="hidden" name="action" value="penjualanBulanan">
                      
                      <div class="row">
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>Bulan</label>
                            <select class="form-select" name="bulan">
                              <option value="1" <?= $data['bulan'] == 1 ? 'selected' : '' ?>>Januari</option>
                              <option value="2" <?= $data['bulan'] == 2 ? 'selected' : '' ?>>Februari</option>
                              <option value="3" <?= $data['bulan'] == 3 ? 'selected' : '' ?>>Maret</option>
                              <option value="4" <?= $data['bulan'] == 4 ? 'selected' : '' ?>>April</option>
                              <option value="5" <?= $data['bulan'] == 5 ? 'selected' : '' ?>>Mei</option>
                              <option value="6" <?= $data['bulan'] == 6 ? 'selected' : '' ?>>Juni</option>
                              <option value="7" <?= $data['bulan'] == 7 ? 'selected' : '' ?>>Juli</option>
                              <option value="8" <?= $data['bulan'] == 8 ? 'selected' : '' ?>>Agustus</option>
                              <option value="9" <?= $data['bulan'] == 9 ? 'selected' : '' ?>>September</option>
                              <option value="10" <?= $data['bulan'] == 10 ? 'selected' : '' ?>>Oktober</option>
                              <option value="11" <?= $data['bulan'] == 11 ? 'selected' : '' ?>>November</option>
                              <option value="12" <?= $data['bulan'] == 12 ? 'selected' : '' ?>>Desember</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>Tahun</label>
                            <select class="form-select" name="tahun">
                              <?php for($i = date('Y'); $i >= 2020; $i--): ?>
                                <option value="<?= $i ?>" <?= $data['tahun'] == $i ? 'selected' : '' ?>><?= $i ?></option>
                              <?php endfor; ?>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>&nbsp;</label>
                            <div class="d-grid gap-2">
                              <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-filter"></i> Filter
                              </button>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>&nbsp;</label>
                            <div class="d-grid gap-2">
                              <a href="?controller=laporan&action=penjualanBulanan&format=pdf&bulan=<?= $data['bulan'] ?>&tahun=<?= $data['tahun'] ?>" 
                                 class="btn btn-danger" target="_blank">
                                <i class="mdi mdi-file-pdf"></i> Cetak PDF
                              </a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </form>

                    <div class="table-responsive">
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Total Transaksi</th>
                            <th>Total Pendapatan</th>
                            <th>Total Customer</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if (empty($data['penjualan'])): ?>
                            <tr>
                              <td colspan="5" class="text-center">Tidak ada data penjualan</td>
                            </tr>
                          <?php else: ?>
                            <?php $no = 1; foreach ($data['penjualan'] as $row): ?>
                              <tr>
                                <td><?= $no++ ?></td>
                                <td><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                                <td><?= number_format($row['total_transaksi']) ?></td>
                                <td><strong>Rp <?= number_format($row['total_pendapatan']) ?></strong></td>
                                <td><?= number_format($row['total_customer']) ?></td>
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
        </div>
      </div>
    </div>
  </div>
  <?php include 'template/script.php'; ?>
</body>

</html>