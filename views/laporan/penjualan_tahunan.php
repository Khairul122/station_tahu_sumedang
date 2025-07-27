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
                        <i class="mdi mdi-calendar me-2"></i>
                        Laporan Data Penjualan Tahunan
                      </h4>
                      <a href="?controller=laporan" class="btn btn-light">
                        <i class="mdi mdi-arrow-left"></i> Kembali
                      </a>
                    </div>

                    <form method="GET" action="index.php" class="mb-4">
                      <input type="hidden" name="controller" value="laporan">
                      <input type="hidden" name="action" value="penjualanTahunan">
                      
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Tahun</label>
                            <select class="form-select" name="tahun">
                              <?php for($i = date('Y'); $i >= 2020; $i--): ?>
                                <option value="<?= $i ?>" <?= $data['tahun'] == $i ? 'selected' : '' ?>><?= $i ?></option>
                              <?php endfor; ?>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>&nbsp;</label>
                            <div class="d-grid gap-2">
                              <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-filter"></i> Filter
                              </button>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>&nbsp;</label>
                            <div class="d-grid gap-2">
                              <a href="?controller=laporan&action=penjualanTahunan&format=pdf&tahun=<?= $data['tahun'] ?>" 
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
                            <th>Bulan</th>
                            <th>Total Transaksi</th>
                            <th>Total Pendapatan</th>
                            <th>Total Customer</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if (empty($data['penjualan'])): ?>
                            <tr>
                              <td colspan="5" class="text-center">Tidak ada data penjualan tahun <?= $data['tahun'] ?></td>
                            </tr>
                          <?php else: ?>
                            <?php 
                            $namaBulan = [
                              1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                              5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                              9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                            ];
                            $no = 1; 
                            foreach ($data['penjualan'] as $row): 
                            ?>
                              <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $namaBulan[$row['bulan']] ?></td>
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