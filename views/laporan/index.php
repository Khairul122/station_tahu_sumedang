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
                    <h4 class="card-title">
                      <i class="mdi mdi-file-document me-2"></i>
                      Laporan
                    </h4>
                    <p class="card-description">Pilih jenis laporan yang ingin digenerate</p>

                    <?php if (!empty($data['success'])): ?>
                      <div class="alert alert-success alert-dismissible fade show">
                        <i class="mdi mdi-check-circle me-2"></i>
                        <?= htmlspecialchars($data['success']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                      </div>
                    <?php endif; ?>

                    <?php if (!empty($data['error'])): ?>
                      <div class="alert alert-danger alert-dismissible fade show">
                        <i class="mdi mdi-alert-circle me-2"></i>
                        <?= htmlspecialchars($data['error']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                      </div>
                    <?php endif; ?>

                    <div class="row">
                      <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card">
                          <div class="card-body text-center">
                            <i class="mdi mdi-package-variant display-4 text-primary"></i>
                            <h5 class="card-title">Laporan Produk</h5>
                            <p class="card-text">Data produk dan penjualan</p>
                            <a href="?controller=laporan&action=produk" class="btn btn-primary btn-sm">
                              <i class="mdi mdi-eye"></i> Lihat
                            </a>
                          </div>
                        </div>
                    </div>

                      <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card">
                          <div class="card-body text-center">
                            <i class="mdi mdi-calendar-today display-4 text-warning"></i>
                            <h5 class="card-title">Penjualan Harian</h5>
                            <p class="card-text">Data penjualan per hari</p>
                            <a href="?controller=laporan&action=penjualanHarian" class="btn btn-warning btn-sm">
                              <i class="mdi mdi-eye"></i> Lihat
                            </a>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card">
                          <div class="card-body text-center">
                            <i class="mdi mdi-calendar-month display-4 text-info"></i>
                            <h5 class="card-title">Penjualan Bulanan</h5>
                            <p class="card-text">Data penjualan per bulan</p>
                            <a href="?controller=laporan&action=penjualanBulanan" class="btn btn-info btn-sm">
                              <i class="mdi mdi-eye"></i> Lihat
                            </a>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card">
                          <div class="card-body text-center">
                            <i class="mdi mdi-calendar display-4 text-danger"></i>
                            <h5 class="card-title">Penjualan Tahunan</h5>
                            <p class="card-text">Data penjualan per tahun</p>
                            <a href="?controller=laporan&action=penjualanTahunan" class="btn btn-danger btn-sm">
                              <i class="mdi mdi-eye"></i> Lihat
                            </a>
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
  <?php include 'template/script.php'; ?>
</body>

</html>