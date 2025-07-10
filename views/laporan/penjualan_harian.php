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
                        <i class="mdi mdi-calendar-today me-2"></i>
                        Laporan Data Penjualan Harian
                      </h4>
                      <a href="?controller=laporan" class="btn btn-light">
                        <i class="mdi mdi-arrow-left"></i> Kembali
                      </a>
                    </div>

                    <form method="GET" action="index.php" class="mb-4">
                      <input type="hidden" name="controller" value="laporan">
                      <input type="hidden" name="action" value="penjualanHarian">
                      
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Pilih Tanggal</label>
                            <input type="date" class="form-control" name="tanggal" 
                                   value="<?= htmlspecialchars($data['tanggal']) ?>">
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
                              <a href="?controller=laporan&action=penjualanHarian&format=pdf&tanggal=<?= urlencode($data['tanggal']) ?>" 
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
                            <th>Nama</th>
                            <th>Total</th>
                            <th>Alamat</th>
                            <th>Mobile Phone</th>
                            <th>Owner</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if (empty($data['transaksi'])): ?>
                            <tr>
                              <td colspan="6" class="text-center">Tidak ada data transaksi pada tanggal <?= date('d/m/Y', strtotime($data['tanggal'])) ?></td>
                            </tr>
                          <?php else: ?>
                            <?php $no = 1; foreach ($data['transaksi'] as $row): ?>
                              <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row['nama_customer']) ?></td>
                                <td><strong>Rp <?= number_format($row['total_bayar']) ?></strong></td>
                                <td><?= htmlspecialchars($row['alamat']) ?></td>
                                <td><?= htmlspecialchars($row['no_telepon']) ?></td>
                                <td><?= htmlspecialchars($row['owner'] ?? '-') ?></td>
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