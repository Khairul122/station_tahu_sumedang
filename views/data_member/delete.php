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
            <div class="col-md-12 grid-margin">
              <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                  <h3 class="font-weight-bold">Hapus Member</h3>
                  <h6 class="font-weight-normal mb-0">Konfirmasi penghapusan data member</h6>
                </div>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-lg-8 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title text-danger">
                    <i class="fas fa-exclamation-triangle"></i> Konfirmasi Hapus Member
                  </h4>
                  
                  <div class="alert alert-warning">
                    <strong>Peringatan!</strong> Tindakan ini akan menghapus member beserta semua data terkait:
                    <ul class="mb-0 mt-2">
                      <li>Data akun user</li>
                      <li>Data customer</li>
                      <li>Riwayat transaksi</li>
                      <li>Aktivitas customer</li>
                      <li>Riwayat tukar poin</li>
                    </ul>
                  </div>
                  
                  <div class="row">
                    <div class="col-md-6">
                      <h6 class="font-weight-bold">Detail Member:</h6>
                      <table class="table table-borderless">
                        <tr>
                          <td width="40%">Nama</td>
                          <td>: <?= htmlspecialchars($member['nama_customer']) ?></td>
                        </tr>
                        <tr>
                          <td>Username</td>
                          <td>: <?= htmlspecialchars($member['username']) ?></td>
                        </tr>
                        <tr>
                          <td>Email</td>
                          <td>: <?= htmlspecialchars($member['email']) ?></td>
                        </tr>
                        <tr>
                          <td>No Telepon</td>
                          <td>: <?= htmlspecialchars($member['no_telepon']) ?></td>
                        </tr>
                        <tr>
                          <td>Membership</td>
                          <td>: <?= htmlspecialchars($member['nama_membership']) ?></td>
                        </tr>
                        <tr>
                          <td>Total Pembelian</td>
                          <td>: Rp <?= number_format($member['total_pembelian']) ?></td>
                        </tr>
                        <tr>
                          <td>Total Poin</td>
                          <td>: <?= number_format($member['total_poin']) ?> poin</td>
                        </tr>
                        <tr>
                          <td>Tanggal Daftar</td>
                          <td>: <?= date('d/m/Y', strtotime($member['tanggal_daftar'])) ?></td>
                        </tr>
                      </table>
                    </div>
                  </div>
                  
                  <div class="mt-4">
                    <form method="POST" class="d-inline">
                      <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Ya, Hapus Member
                      </button>
                    </form>
                    <a href="index.php?controller=datamember" class="btn btn-secondary ms-2">
                      <i class="fas fa-arrow-left"></i> Batal
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
  
  <?php include 'template/script.php'; ?>
</body>
</html>