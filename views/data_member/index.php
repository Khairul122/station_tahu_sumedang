<?php include('template/header.php'); ?>

<body class="with-welcome-text">
  <div class="container-scroller">
    <?php include 'template/navbar.php'; ?>
    <div class="container-fluid page-body-wrapper">
      <?php include 'template/setting_panel.php'; ?>
      <?php include 'template/sidebar.php'; ?>
      <div class="main-panel">
        <div class="content-wrapper">
          <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <i class="fas fa-check-circle me-2"></i>
              <?= htmlspecialchars($_GET['success']) ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          <?php endif; ?>
          
          <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <i class="fas fa-exclamation-circle me-2"></i>
              <?= htmlspecialchars($_GET['error']) ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          <?php endif; ?>
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                  <h3 class="font-weight-bold">Data Member</h3>
                  <h6 class="font-weight-normal mb-0">Daftar semua member terdaftar</h6>
                </div>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Daftar Member</h4>
                  
                  <div class="table-responsive">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Nama Member</th>
                          <th>Username</th>
                          <th>Email</th>
                          <th>No Telepon</th>
                          <th>Membership</th>
                          <th>Total Pembelian</th>
                          <th>Total Poin</th>
                          <th>Tanggal Daftar</th>
                          <th>Status</th>
                          <th>Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if (!empty($members)): ?>
                          <?php $no = 1; foreach ($members as $member): ?>
                          <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($member['nama_customer']) ?></td>
                            <td><?= htmlspecialchars($member['username']) ?></td>
                            <td><?= htmlspecialchars($member['email']) ?></td>
                            <td><?= htmlspecialchars($member['no_telepon']) ?></td>
                            <td>
                              <span class="badge badge-primary">
                                <?= htmlspecialchars($member['nama_membership']) ?>
                              </span>
                            </td>
                            <td>Rp <?= number_format($member['total_pembelian']) ?></td>
                            <td><?= number_format($member['total_poin']) ?> poin</td>
                            <td><?= date('d/m/Y', strtotime($member['tanggal_daftar'])) ?></td>
                            <td>
                              <span class="badge badge-<?= $member['status_aktif'] == 'aktif' ? 'success' : 'secondary' ?>">
                                <?= ucfirst($member['status_aktif']) ?>
                              </span>
                            </td>
                            <td>
                              <a href="index.php?controller=datamember&action=delete&id=<?= $member['customer_id'] ?>" 
                                 class="btn btn-sm btn-danger" 
                                 onclick="return confirm('Yakin ingin menghapus member <?= htmlspecialchars($member['nama_customer']) ?>? Data transaksi member juga akan terhapus!')">
                                <i class="fas fa-trash"></i> Hapus
                              </a>
                            </td>
                          </tr>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <tr>
                            <td colspan="11" class="text-center">
                              <p class="text-muted">Belum ada member terdaftar</p>
                            </td>
                          </tr>
                        <?php endif; ?>
                      </tbody>
                    </table>
                  </div>
                  
                  <?php if (!empty($members)): ?>
                  <div class="mt-3">
                    <small class="text-muted">
                      Total: <?= count($members) ?> member
                    </small>
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
  
  <?php include 'template/script.php'; ?>
</body>
</html>