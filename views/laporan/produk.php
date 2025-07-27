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
                        <a href="?controller=laporan&action=produk&format=pdf<?= isset($_GET['store_id']) ? '&store_id=' . $_GET['store_id'] : '' ?>" class="btn btn-danger btn-sm" target="_blank">
                          <i class="mdi mdi-file-pdf"></i> Cetak PDF
                        </a>
                      </div>
                    </div>

                    <div class="row mb-4">
                      <div class="col-md-4">
                        <form method="GET" action="">
                          <input type="hidden" name="controller" value="laporan">
                          <input type="hidden" name="action" value="produk">
                          <div class="form-group">
                            <label for="store_id">Filter Store:</label>
                            <select name="store_id" id="store_id" class="form-control" onchange="this.form.submit()">
                              <option value="">Semua Store</option>
                              <?php foreach ($data['stores'] as $store): ?>
                                <option value="<?= $store['id_store'] ?>" <?= (isset($data['selected_store']) && $data['selected_store'] == $store['id_store']) ? 'selected' : '' ?>>
                                  <?= htmlspecialchars($store['nama_store']) ?>
                                </option>
                              <?php endforeach; ?>
                            </select>
                          </div>
                        </form>
                      </div>
                      <?php if (isset($data['selected_store']) && $data['selected_store']): ?>
                        <div class="col-md-8">
                          <div class="alert alert-info">
                            <i class="mdi mdi-information"></i>
                            Menampilkan data untuk: <strong>
                              <?php 
                                foreach ($data['stores'] as $store) {
                                  if ($store['id_store'] == $data['selected_store']) {
                                    echo htmlspecialchars($store['nama_store']);
                                    break;
                                  }
                                }
                              ?>
                            </strong>
                          </div>
                        </div>
                      <?php endif; ?>
                    </div>

                    <div class="table-responsive">
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>No</th>
                            <th>Nama Produk</th>
                            <th>Store</th>
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
                              <td colspan="8" class="text-center">Tidak ada data produk</td>
                            </tr>
                          <?php else: ?>
                            <?php 
                              $no = 1; 
                              $total_pendapatan_keseluruhan = 0;
                              $total_terjual_keseluruhan = 0;
                              foreach ($data['produk'] as $row): 
                                $total_pendapatan_keseluruhan += $row['total_pendapatan'];
                                $total_terjual_keseluruhan += $row['total_terjual'];
                            ?>
                              <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row['nama_produk']) ?></td>
                                <td>
                                  <small class="text-muted">
                                    <?= htmlspecialchars($row['nama_store'] ?? 'Tidak ada') ?>
                                  </small>
                                </td>
                                <td>
                                  <label class="badge badge-info">
                                    <?= htmlspecialchars($row['kategori']) ?>
                                  </label>
                                </td>
                                <td>Rp <?= number_format($row['harga']) ?></td>
                                <td>
                                  <span class="badge badge-<?= $row['stok'] > 10 ? 'success' : ($row['stok'] > 0 ? 'warning' : 'danger') ?>">
                                    <?= number_format($row['stok']) ?>
                                  </span>
                                </td>
                                <td><strong><?= number_format($row['total_terjual']) ?></strong></td>
                                <td><strong>Rp <?= number_format($row['total_pendapatan']) ?></strong></td>
                              </tr>
                            <?php endforeach; ?>
                            <tr class="table-info">
                              <td colspan="6" class="text-end"><strong>TOTAL KESELURUHAN:</strong></td>
                              <td><strong><?= number_format($total_terjual_keseluruhan) ?></strong></td>
                              <td><strong>Rp <?= number_format($total_pendapatan_keseluruhan) ?></strong></td>
                            </tr>
                          <?php endif; ?>
                        </tbody>
                      </table>
                    </div>

                    <?php if (!empty($data['produk'])): ?>
                      <div class="row mt-4">
                        <div class="col-md-6">
                          <div class="card border-primary">
                            <div class="card-body text-center">
                              <h5 class="card-title text-primary">
                                <i class="mdi mdi-chart-line"></i> Total Produk Terjual
                              </h5>
                              <h3 class="text-primary"><?= number_format($total_terjual_keseluruhan) ?></h3>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="card border-success">
                            <div class="card-body text-center">
                              <h5 class="card-title text-success">
                                <i class="mdi mdi-cash-multiple"></i> Total Pendapatan
                              </h5>
                              <h3 class="text-success">Rp <?= number_format($total_pendapatan_keseluruhan) ?></h3>
                            </div>
                          </div>
                        </div>
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
  <?php include 'template/script.php'; ?>
</body>

</html>