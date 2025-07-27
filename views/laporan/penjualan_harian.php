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
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>Pilih Tanggal</label>
                            <input type="date" class="form-control" name="tanggal" 
                                   value="<?= htmlspecialchars($data['tanggal']) ?>">
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>Filter Store</label>
                            <select name="store_id" class="form-control">
                              <option value="">Semua Store</option>
                              <?php foreach ($data['stores'] as $store): ?>
                                <option value="<?= $store['id_store'] ?>" <?= (isset($data['selected_store']) && $data['selected_store'] == $store['id_store']) ? 'selected' : '' ?>>
                                  <?= htmlspecialchars($store['nama_store']) ?>
                                </option>
                              <?php endforeach; ?>
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
                              <a href="?controller=laporan&action=penjualanHarian&format=pdf&tanggal=<?= urlencode($data['tanggal']) ?><?= isset($data['selected_store']) && $data['selected_store'] ? '&store_id=' . $data['selected_store'] : '' ?>" 
                                 class="btn btn-danger" target="_blank">
                                <i class="mdi mdi-file-pdf"></i> Cetak PDF
                              </a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </form>

                    <?php if (isset($data['selected_store']) && $data['selected_store']): ?>
                      <div class="alert alert-info mb-3">
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
                        </strong> pada tanggal <strong><?= date('d/m/Y', strtotime($data['tanggal'])) ?></strong>
                      </div>
                    <?php endif; ?>

                    <div class="table-responsive">
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>No</th>
                            <th>Nama Customer</th>
                            <th>Store</th>
                            <th>Total Bayar</th>
                            <th>Alamat</th>
                            <th>Mobile Phone</th>
                            <th>Owner</th>
                            <th>Waktu</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if (empty($data['transaksi'])): ?>
                            <tr>
                              <td colspan="8" class="text-center">Tidak ada data transaksi pada tanggal <?= date('d/m/Y', strtotime($data['tanggal'])) ?></td>
                            </tr>
                          <?php else: ?>
                            <?php 
                              $no = 1; 
                              $total_penjualan = 0;
                              $total_transaksi = count($data['transaksi']);
                              foreach ($data['transaksi'] as $row): 
                                $total_penjualan += $row['total_bayar'];
                            ?>
                              <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row['nama_customer']) ?></td>
                                <td>
                                  <small class="text-muted">
                                    <?= htmlspecialchars($row['nama_store'] ?? 'Tidak ada') ?>
                                  </small>
                                </td>
                                <td><strong class="text-success">Rp <?= number_format($row['total_bayar']) ?></strong></td>
                                <td><?= htmlspecialchars($row['alamat']) ?></td>
                                <td><?= htmlspecialchars($row['no_telepon']) ?></td>
                                <td>
                                  <span class="badge badge-primary">
                                    <?= htmlspecialchars($row['owner'] ?? 'Guest') ?>
                                  </span>
                                </td>
                                <td>
                                  <small class="text-muted">
                                    <?= date('H:i', strtotime($row['tanggal_transaksi'])) ?>
                                  </small>
                                </td>
                              </tr>
                            <?php endforeach; ?>
                            <tr class="table-success">
                              <td colspan="3" class="text-end"><strong>TOTAL PENJUALAN HARIAN:</strong></td>
                              <td><strong class="text-success">Rp <?= number_format($total_penjualan) ?></strong></td>
                              <td colspan="4" class="text-start"><strong><?= $total_transaksi ?> Transaksi</strong></td>
                            </tr>
                          <?php endif; ?>
                        </tbody>
                      </table>
                    </div>

                    <?php if (!empty($data['transaksi'])): ?>
                      <div class="row mt-4">
                        <div class="col-md-4">
                          <div class="card border-primary">
                            <div class="card-body text-center">
                              <h5 class="card-title text-primary">
                                <i class="mdi mdi-cart"></i> Total Transaksi
                              </h5>
                              <h3 class="text-primary"><?= number_format($total_transaksi) ?></h3>
                              <p class="text-muted mb-0">Transaksi hari ini</p>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="card border-success">
                            <div class="card-body text-center">
                              <h5 class="card-title text-success">
                                <i class="mdi mdi-cash-multiple"></i> Total Penjualan
                              </h5>
                              <h3 class="text-success">Rp <?= number_format($total_penjualan) ?></h3>
                              <p class="text-muted mb-0">Pendapatan hari ini</p>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="card border-warning">
                            <div class="card-body text-center">
                              <h5 class="card-title text-warning">
                                <i class="mdi mdi-account-multiple"></i> Rata-rata per Transaksi
                              </h5>
                              <h3 class="text-warning">Rp <?= number_format($total_penjualan / $total_transaksi) ?></h3>
                              <p class="text-muted mb-0">Nilai rata-rata</p>
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