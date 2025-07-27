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
                              <a href="?controller=laporan&action=penjualanTahunan&format=pdf&tahun=<?= $data['tahun'] ?><?= isset($data['selected_store']) && $data['selected_store'] ? '&store_id=' . $data['selected_store'] : '' ?>" 
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
                        </strong> pada tahun <strong><?= $data['tahun'] ?></strong>
                      </div>
                    <?php endif; ?>

                    <div class="table-responsive">
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>No</th>
                            <th>Bulan</th>
                            <th>Total Transaksi</th>
                            <th>Total Pendapatan</th>
                            <th>Total Customer</th>
                            <th>Rata-rata per Transaksi</th>
                            <th>Growth</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if (empty($data['penjualan'])): ?>
                            <tr>
                              <td colspan="7" class="text-center">Tidak ada data penjualan tahun <?= $data['tahun'] ?></td>
                            </tr>
                          <?php else: ?>
                            <?php 
                            $namaBulan = [
                              1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                              5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                              9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                            ];
                            $no = 1; 
                            $total_transaksi_tahunan = 0;
                            $total_pendapatan_tahunan = 0;
                            $total_customer_tahunan = 0;
                            $pendapatan_sebelumnya = 0;
                            
                            foreach ($data['penjualan'] as $row): 
                              $total_transaksi_tahunan += $row['total_transaksi'];
                              $total_pendapatan_tahunan += $row['total_pendapatan'];
                              $total_customer_tahunan += $row['total_customer'];
                              $rata_per_transaksi = $row['total_transaksi'] > 0 ? $row['total_pendapatan'] / $row['total_transaksi'] : 0;
                              
                              $growth = 0;
                              $growth_class = 'muted';
                              $growth_icon = '';
                              if ($pendapatan_sebelumnya > 0) {
                                $growth = (($row['total_pendapatan'] - $pendapatan_sebelumnya) / $pendapatan_sebelumnya) * 100;
                                if ($growth > 0) {
                                  $growth_class = 'success';
                                  $growth_icon = 'mdi-trending-up';
                                } elseif ($growth < 0) {
                                  $growth_class = 'danger';
                                  $growth_icon = 'mdi-trending-down';
                                } else {
                                  $growth_class = 'warning';
                                  $growth_icon = 'mdi-trending-neutral';
                                }
                              }
                            ?>
                              <tr>
                                <td><?= $no++ ?></td>
                                <td>
                                  <span class="badge badge-<?= $row['bulan'] == date('n') ? 'success' : 'info' ?>">
                                    <?= $namaBulan[$row['bulan']] ?>
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
                                <td>
                                  <?php if ($pendapatan_sebelumnya > 0): ?>
                                    <span class="text-<?= $growth_class ?>">
                                      <i class="mdi <?= $growth_icon ?>"></i>
                                      <?= number_format(abs($growth), 1) ?>%
                                    </span>
                                  <?php else: ?>
                                    <span class="text-muted">-</span>
                                  <?php endif; ?>
                                </td>
                              </tr>
                            <?php 
                              $pendapatan_sebelumnya = $row['total_pendapatan'];
                              endforeach; 
                            ?>
                            <tr class="table-success">
                              <td colspan="2" class="text-end"><strong>TOTAL TAHUNAN:</strong></td>
                              <td><strong class="text-primary"><?= number_format($total_transaksi_tahunan) ?></strong></td>
                              <td><strong class="text-success">Rp <?= number_format($total_pendapatan_tahunan) ?></strong></td>
                              <td><strong><?= number_format($total_customer_tahunan) ?></strong></td>
                              <td><strong class="text-warning">Rp <?= number_format($total_transaksi_tahunan > 0 ? $total_pendapatan_tahunan / $total_transaksi_tahunan : 0) ?></strong></td>
                              <td><strong class="text-info">100%</strong></td>
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
                              <h3 class="text-primary"><?= number_format($total_transaksi_tahunan) ?></h3>
                              <p class="text-muted mb-0">Transaksi tahun <?= $data['tahun'] ?></p>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="card border-success">
                            <div class="card-body text-center">
                              <h5 class="card-title text-success">
                                <i class="mdi mdi-cash-multiple"></i> Total Pendapatan
                              </h5>
                              <h3 class="text-success">Rp <?= number_format($total_pendapatan_tahunan) ?></h3>
                              <p class="text-muted mb-0">Pendapatan tahun <?= $data['tahun'] ?></p>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="card border-info">
                            <div class="card-body text-center">
                              <h5 class="card-title text-info">
                                <i class="mdi mdi-account-multiple"></i> Total Customer
                              </h5>
                              <h3 class="text-info"><?= number_format($total_customer_tahunan) ?></h3>
                              <p class="text-muted mb-0">Customer tahun <?= $data['tahun'] ?></p>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="card border-warning">
                            <div class="card-body text-center">
                              <h5 class="card-title text-warning">
                                <i class="mdi mdi-calculator"></i> Rata-rata Bulanan
                              </h5>
                              <h3 class="text-warning">Rp <?= number_format($total_pendapatan_tahunan / count($data['penjualan'])) ?></h3>
                              <p class="text-muted mb-0">Per bulan aktif</p>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="row mt-3">
                        <div class="col-md-6">
                          <div class="card">
                            <div class="card-body">
                              <h5 class="card-title">
                                <i class="mdi mdi-chart-line"></i> Performa Bulanan
                              </h5>
                              <?php
                                $bulan_terbaik = array_reduce($data['penjualan'], function($max, $item) use ($namaBulan) {
                                  return ($max === null || $item['total_pendapatan'] > $max['total_pendapatan']) ? $item : $max;
                                });
                                $bulan_terburuk = array_reduce($data['penjualan'], function($min, $item) use ($namaBulan) {
                                  return ($min === null || $item['total_pendapatan'] < $min['total_pendapatan']) ? $item : $min;
                                });
                              ?>
                              <p><strong>Bulan Terbaik:</strong> <?= $namaBulan[$bulan_terbaik['bulan']] ?> 
                                 <span class="text-success">(Rp <?= number_format($bulan_terbaik['total_pendapatan']) ?>)</span></p>
                              <p><strong>Bulan Terburuk:</strong> <?= $namaBulan[$bulan_terburuk['bulan']] ?> 
                                 <span class="text-danger">(Rp <?= number_format($bulan_terburuk['total_pendapatan']) ?>)</span></p>
                              <p><strong>Selisih:</strong> 
                                 <span class="text-warning">Rp <?= number_format($bulan_terbaik['total_pendapatan'] - $bulan_terburuk['total_pendapatan']) ?></span></p>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="card">
                            <div class="card-body">
                              <h5 class="card-title">
                                <i class="mdi mdi-information"></i> Analisis Tahunan
                              </h5>
                              <p><strong>Tahun:</strong> <?= $data['tahun'] ?></p>
                              <p><strong>Bulan Aktif:</strong> <?= count($data['penjualan']) ?> bulan</p>
                              <p><strong>Target Achievement:</strong> 
                                <span class="text-<?= $total_pendapatan_tahunan > 10000000 ? 'success' : ($total_pendapatan_tahunan > 5000000 ? 'warning' : 'danger') ?>">
                                  <?= $total_pendapatan_tahunan > 10000000 ? 'Excellent (>10M)' : ($total_pendapatan_tahunan > 5000000 ? 'Good (>5M)' : 'Need Improvement (<5M)') ?>
                                </span>
                              </p>
                              <p><strong>Consistency Score:</strong> 
                                <?php 
                                  $consistency = 100 - (($bulan_terbaik['total_pendapatan'] - $bulan_terburuk['total_pendapatan']) / $total_pendapatan_tahunan * 100);
                                  $consistency_class = $consistency > 80 ? 'success' : ($consistency > 60 ? 'warning' : 'danger');
                                ?>
                                <span class="text-<?= $consistency_class ?>"><?= number_format($consistency, 1) ?>%</span>
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