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
                                                <i class="mdi mdi-account-multiple me-2"></i>
                                                Laporan Data Member
                                            </h4>
                                            <div>
                                                <a href="?controller=laporan" class="btn btn-light me-2">
                                                    <i class="mdi mdi-arrow-left"></i> Kembali
                                                </a>
                                                <a href="?controller=laporan&action=member&format=pdf" class="btn btn-danger btn-sm" target="_blank">
                                                    <i class="mdi mdi-file-pdf"></i> Cetak PDF
                                                </a>
                                            </div>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nama Member</th>
                                                        <th>No Telepon</th>
                                                        <th>Email</th>
                                                        <th>Membership</th>
                                                        <th>Total Poin</th>
                                                        <th>Tanggal Daftar</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (empty($data['member'])): ?>
                                                        <tr>
                                                            <td colspan="7" class="text-center">Tidak ada data member</td>
                                                        </tr>
                                                    <?php else: ?>
                                                        <?php
                                                        $no = 1;
                                                        $total_poin_keseluruhan = 0;
                                                        ?>
                                                        <?php foreach ($data['member'] as $member): ?>
                                                            <tr>
                                                                <td><?= $no++ ?></td>
                                                                <td><?= htmlspecialchars($member['nama_customer']) ?></td>
                                                                <td><?= htmlspecialchars($member['no_telepon']) ?></td>
                                                                <td><?= htmlspecialchars($member['email']) ?></td>
                                                                <td>
                                                                    <label class="badge 
                <?= $member['nama_membership'] == 'Platinum' ? 'badge-dark' : ($member['nama_membership'] == 'Gold' ? 'badge-warning' : ($member['nama_membership'] == 'Silver' ? 'badge-secondary' : 'badge-primary')) ?>">
                                                                        <?= htmlspecialchars($member['nama_membership']) ?>
                                                                    </label>
                                                                </td>
                                                                <td><strong><?= number_format($member['total_poin']) ?></strong></td>
                                                                <td><?= date('d/m/Y', strtotime($member['tanggal_daftar'])) ?></td>
                                                            </tr>
                                                            <?php
                                                            $total_poin_keseluruhan += $member['total_poin'];
                                                            ?>
                                                        <?php endforeach; ?>
                                                        <tr class="table-active">
                                                            <td colspan="5" class="text-end"><strong>Total Keseluruhan:</strong></td>
                                                            <td><strong><?= number_format($total_poin_keseluruhan) ?></strong></td>
                                                            <td></td>
                                                        </tr>
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