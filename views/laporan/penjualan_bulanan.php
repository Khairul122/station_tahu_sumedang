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
                        <div class="col-md-2">
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
                        <div class="col-md-2">
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
                        <div class="col-md-2">
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
                              <a href="?controller=laporan&action=penjualanBulanan&format=pdf&bulan=<?= $data['bulan'] ?>&tahun=<?= $data['tahun'] ?><?= isset($data['selected_store']) && $data['selected_store'] ? '&store_id=' . $data['selected_store'] : '' ?>" 
                                 class="btn btn-danger" target="_blank">
                                <i class="mdi mdi-file-pdf"></i> Cetak PDF
                              </a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </form>

                    <?php 
                      $namaBulan = [
                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                      ];
                    ?>

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
                        </strong> pada bulan <strong><?= $namaBulan[$data['bulan']] ?> <?= $data['tahun'] ?></strong>
                      </div>
                    <?php endif; ?>

                    <div class="table-responsive">
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Hari</th>
                            <th>Total Transaksi</th>
                            <th>Total Pendapatan</th>
                            <th>Total Customer</th>
                            <th>Rata-rata per Transaksi</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if (empty($data['penjualan'])): ?>
                            <tr>
                              <td colspan="7" class="text-center">Tidak ada data penjualan pada <?= $namaBulan[$data['bulan']] ?> <?= $data['tahun'] ?></td>
                            </tr>
                          <?php else: ?>
                            <?php 
                              $no = 1; 
                              $total_transaksi_bulanan = 0;
                              $total_pendapatan_bulanan = 0;
                              $total_customer_bulanan = 0;
                              $hari_indonesia = [
                                'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
                                'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
                              ];
                              foreach ($data['penjualan'] as $row): 
                                $total_transaksi_bulanan += $row['total_transaksi'];
                                $total_pendapatan_bulanan += $row['total_pendapatan'];
                                $total_customer_bulanan += $row['total_customer'];
                                $rata_per_transaksi = $row['total_transaksi'] > 0 ? $row['total_pendapatan'] / $row['total_transaksi'] : 0;
                                $hari_eng = date('l', strtotime($row['tanggal']));
                                $hari_indo = $hari_indonesia[$hari_eng];
                            ?>
                              <tr>
                                <td><?= $no++ ?></td>
                                <td><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                                <td>
                                  <span class="badge badge-<?= in_array($hari_eng, ['Saturday', 'Sunday']) ? 'warning' : 'info' ?>">
                                    <?= $hari_indo ?>
                                  </span>
                                </td>
                                <td>
                                  <span class="badge badge-primary">
                                    <?= number_format($row['total_transaksi']) ?>
                                  </span>
                                </td>
                                <td><strong class="text-success">Rp <?= number_format($row['total_pendapatan']) ?></strong></td>
                                <td><?= number_format($row['total_customer']) ?></td>
                                <td class="text-muted">Rp <?= number_format($rata_per_transaksi) ?></td>
                              </tr>
                            <?php endforeach; ?>
                            <tr class="table-success">
                              <td colspan="3" class="text-end"><strong>TOTAL BULANAN:</strong></td>
                              <td><strong class="text-primary"><?= number_format($total_transaksi_bulanan) ?></strong></td>
                              <td><strong class="text-success">Rp <?= number_format($total_pendapatan_bulanan) ?></strong></td>
                              <td><strong><?= number_format($total_customer_bulanan) ?></strong></td>
                              <td><strong class="text-warning">Rp <?= number_format($total_transaksi_bulanan > 0 ? $total_pendapatan_bulanan / $total_transaksi_bulanan : 0) ?></strong></td>
                            </tr>
                          <?php endif; ?>
                        </tbody>
                      </table>
                    </div>

                    <?php if (!empty($data['penjualan'])): ?>
                      <div class="row mt-4">
                        <div class="col-md-3">
                          <div class="card border-primary">
                            <div class="card-body text-center">
                              <h5 class="card-title text-primary">
                                <i class="mdi mdi-cart"></i> Total Transaksi
                              </h5>
                              <h3 class="text-primary"><?= number_format($total_transaksi_bulanan) ?></h3>
                              <p class="text-muted mb-0">Transaksi bulan ini</p>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="card border-success">
                            <div class="card-body text-center">
                              <h5 class="card-title text-success">
                                <i class="mdi mdi-cash-multiple"></i> Total Pendapatan
                              </h5>
                              <h3 class="text-success">Rp <?= number_format($total_pendapatan_bulanan) ?></h3>
                              <p class="text-muted mb-0">Pendapatan bulan ini</p>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="card border-info">
                            <div class="card-body text-center">
                              <h5 class="card-title text-info">
                                <i class="mdi mdi-account-multiple"></i> Total Customer
                              </h5>
                              <h3 class="text-info"><?= number_format($total_customer_bulanan) ?></h3>
                              <p class="text-muted mb-0">Customer bulan ini</p>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="card border-warning">
                            <div class="card-body text-center">
                              <h5 class="card-title text-warning">
                                <i class="mdi mdi-calculator"></i> Rata-rata Harian
                              </h5>
                              <h3 class="text-warning">Rp <?= number_format($total_pendapatan_bulanan / count($data['penjualan'])) ?></h3>
                              <p class="text-muted mb-0">Per hari aktif</p>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="row mt-3">
                        <div class="col-md-6">
                          <div class="card">
                            <div class="card-body">
                              <h5 class="card-title">
                                <i class="mdi mdi-chart-bar"></i> Performa Hari
                              </h5>
                              <?php
                                $hari_terbaik = array_reduce($data['penjualan'], function($max, $item) {
                                  return ($max === null || $item['total_pendapatan'] > $max['total_pendapatan']) ? $item : $max;
                                });
                                $hari_terburuk = array_reduce($data['penjualan'], function($min, $item) {
                                  return ($min === null || $item['total_pendapatan'] < $min['total_pendapatan']) ? $item : $min;
                                });
                              ?>
                              <p><strong>Hari Terbaik:</strong> <?= date('d/m/Y', strtotime($hari_terbaik['tanggal'])) ?> 
                                 <span class="text-success">(Rp <?= number_format($hari_terbaik['total_pendapatan']) ?>)</span></p>
                              <p><strong>Hari Terburuk:</strong> <?= date('d/m/Y', strtotime($hari_terburuk['tanggal'])) ?> 
                                 <span class="text-danger">(Rp <?= number_format($hari_terburuk['total_pendapatan']) ?>)</span></p>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="card">
                            <div class="card-body">
                              <h5 class="card-title">
                                <i class="mdi mdi-information"></i> Informasi Periode
                              </h5>
                              <p><strong>Periode:</strong> <?= $namaBulan[$data['bulan']] ?> <?= $data['tahun'] ?></p>
                              <p><strong>Hari Aktif:</strong> <?= count($data['penjualan']) ?> hari</p>
                              <p><strong>Growth Potential:</strong> 
                                <span class="text-<?= $total_pendapatan_bulanan > 1000000 ? 'success' : ($total_pendapatan_bulanan > 500000 ? 'warning' : 'danger') ?>">
                                  <?= $total_pendapatan_bulanan > 1000000 ? 'Excellent' : ($total_pendapatan_bulanan > 500000 ? 'Good' : 'Need Improvement') ?>
                                </span>
                              </p>
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