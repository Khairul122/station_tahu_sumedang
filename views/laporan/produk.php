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
                        <i class="mdi mdi-package-variant me-2"></i>
                        Laporan Data Produk
                      </h4>
                      <div>
                        <a href="?controller=laporan" class="btn btn-light me-2">
                          <i class="mdi mdi-arrow-left"></i> Kembali
                        </a>
                        <a href="?controller=laporan&action=produk&format=pdf" class="btn btn-danger btn-sm" target="_blank">
                          <i class="mdi mdi-file-pdf"></i> Cetak PDF
                        </a>
                      </div>
                    </div>

                    <div class="table-responsive">
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>No</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Total Terjual</th>
                            <th>Total Pendapatan</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if (empty($data['produk'])): ?>
                            <tr>
                              <td colspan="7" class="text-center">Tidak ada data produk</td>
                            </tr>
                          <?php else: ?>
                            <?php $no = 1; foreach ($data['produk'] as $row): ?>
                              <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row['nama_produk']) ?></td>
                                <td>
                                  <label class="badge badge-info">
                                    <?= htmlspecialchars($row['kategori']) ?>
                                  </label>
                                </td>
                                <td>Rp <?= number_format($row['harga']) ?></td>
                                <td><?= number_format($row['stok']) ?></td>
                                <td><strong><?= number_format($row['total_terjual']) ?></strong></td>
                                <td><strong>Rp <?= number_format($row['total_pendapatan']) ?></strong></td>
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