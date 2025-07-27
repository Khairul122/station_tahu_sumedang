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
                                    <h3 class="font-weight-bold">Kelola Transaksi</h3>
                                    <h6 class="font-weight-normal mb-0">Manajemen transaksi penjualan</h6>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
                            <div class="stats-card primary">
                                <div class="stats-icon">
                                    <i class="mdi mdi-receipt"></i>
                                </div>
                                <div class="stats-content">
                                    <h3 class="stats-number"><?= number_format($stats['total_transaksi']) ?></h3>
                                    <p class="stats-label">Total Transaksi</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
                            <div class="stats-card success">
                                <div class="stats-icon">
                                    <i class="mdi mdi-cash-multiple"></i>
                                </div>
                                <div class="stats-content">
                                    <h3 class="stats-number">Rp <?= number_format($stats['total_revenue']) ?></h3>
                                    <p class="stats-label">Total Revenue</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
                            <div class="stats-card warning">
                                <div class="stats-icon">
                                   <i class="mdi mdi-calendar-today"></i>
                                </div>
                                <div class="stats-content">
                                    <h3 class="stats-number"><?= number_format($stats['transaksi_hari_ini']) ?></h3>
                                    <p class="stats-label">Transaksi Hari Ini</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
                            <div class="stats-card info">
                                <div class="stats-icon">
                                   <i class="mdi mdi-calculator"></i>
                                </div>
                                <div class="stats-content">
                                    <h3 class="stats-number">Rp <?= number_format($stats['avg_transaksi']) ?></h3>
                                    <p class="stats-label">Rata-rata Transaksi</p>
                                </div>
                            </div>
                        </div>
                    </div>

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
                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 class="card-title">Daftar Transaksi</h4>
                                        <a href="index.php?controller=transaksi_admin&action=add" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Tambah Transaksi
                                        </a>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <form method="GET" action="index.php">
                                                <input type="hidden" name="controller" value="transaksi_admin">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="search"
                                                        placeholder="Cari transaksi..." value="<?= htmlspecialchars($search) ?>">
                                                    <button class="btn btn-outline-secondary" type="submit">
                                                        <i class="fas fa-search"></i>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-md-6">
                                            <form method="GET" action="index.php">
                                                <input type="hidden" name="controller" value="transaksi_admin">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <input type="date" class="form-control" name="start_date"
                                                            value="<?= htmlspecialchars($start_date) ?>" placeholder="Tanggal Mulai">
                                                    </div>
                                                    <div class="col-md-5">
                                                        <input type="date" class="form-control" name="end_date"
                                                            value="<?= htmlspecialchars($end_date) ?>" placeholder="Tanggal Selesai">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button class="btn btn-outline-secondary" type="submit">
                                                            <i class="fas fa-filter"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-md-2">
                                            <?php if (!empty($search) || !empty($start_date) || !empty($end_date)): ?>
                                                <a href="index.php?controller=transaksi_admin" class="btn btn-outline-secondary">
                                                    <i class="fas fa-times"></i> Clear
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Tanggal</th>
                                                    <th>Customer</th>
                                                    <th>Total Bayar</th>
                                                    <th>Diskon</th>
                                                    <th>Poin</th>
                                                    <th>Metode Bayar</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($transaksi)): ?>
                                                    <?php foreach ($transaksi as $item): ?>
                                                        <tr>
                                                            <td>#<?= $item['transaksi_id'] ?></td>
                                                            <td><?= date('d/m/Y H:i', strtotime($item['tanggal_transaksi'])) ?></td>
                                                            <td>
                                                                <?php if ($item['customer_id']): ?>
                                                                    <div>
                                                                        <strong><?= htmlspecialchars($item['nama_customer']) ?></strong>
                                                                        <br>
                                                                        <small class="text-muted"><?= htmlspecialchars($item['no_telepon']) ?></small>
                                                                        <br>
                                                                        <span class="badge badge-<?= $item['nama_membership'] == 'Bronze' ? 'secondary' : ($item['nama_membership'] == 'Silver' ? 'info' : ($item['nama_membership'] == 'Gold' ? 'warning' : 'danger')) ?>">
                                                                            <?= htmlspecialchars($item['nama_membership']) ?>
                                                                        </span>
                                                                    </div>
                                                                <?php else: ?>
                                                                    <span class="text-muted">Customer Umum</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <strong>Rp <?= number_format($item['total_bayar']) ?></strong>
                                                                <?php if ($item['total_sebelum_diskon'] > $item['total_bayar']): ?>
                                                                    <br>
                                                                    <small class="text-muted">
                                                                        <del>Rp <?= number_format($item['total_sebelum_diskon']) ?></del>
                                                                    </small>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <?php if ($item['diskon_membership'] > 0): ?>
                                                                    <span class="badge badge-success">Rp <?= number_format($item['diskon_membership']) ?></span>
                                                                <?php else: ?>
                                                                    <span class="text-muted">-</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <?php if ($item['poin_didapat'] > 0): ?>
                                                                    <span class="badge badge-primary">+<?= number_format($item['poin_didapat']) ?></span>
                                                                <?php else: ?>
                                                                    <span class="text-muted">-</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <span class="badge badge-<?= $item['metode_pembayaran'] == 'tunai' ? 'success' : ($item['metode_pembayaran'] == 'transfer' ? 'info' : 'warning') ?>">
                                                                    <?= ucfirst($item['metode_pembayaran']) ?>
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <div class="btn-group" role="group">
                                                                    <a href="index.php?controller=transaksiadmin&action=view&id=<?= $item['transaksi_id'] ?>"
                                                                        class="btn btn-sm btn-info" title="Detail">
                                                                        <i class="mdi mdi-eye"></i>
                                                                    </a>
                                                                    <!-- <a href="index.php?controller=transaksiadmin&action=edit&id=<?= $item['transaksi_id'] ?>"
                                                                        class="btn btn-sm btn-warning" title="Edit">
                                                                        <i class="fas fa-edit"></i>
                                                                    </a> -->
                                                                    <a href="index.php?controller=transaksiadmin&action=delete&id=<?= $item['transaksi_id'] ?>"
                                                                        class="btn btn-sm btn-danger" title="Hapus"
                                                                        onclick="return confirm('Yakin ingin menghapus transaksi ini? Stok produk akan dikembalikan.')">
                                                                       <i class="mdi mdi-delete-forever"></i>
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="8" class="text-center">
                                                            <div class="py-4">
                                                                <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                                                                <p class="text-muted">
                                                                    <?= !empty($search) ? 'Tidak ada transaksi yang ditemukan' : 'Belum ada transaksi' ?>
                                                                </p>
                                                                <?php if (empty($search)): ?>
                                                                    <a href="index.php?controller=transaksi_admin&action=add" class="btn btn-primary">
                                                                        <i class="fas fa-plus"></i> Tambah Transaksi Pertama
                                                                    </a>
                                                                <?php endif; ?>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <?php if (!empty($transaksi)): ?>
                                        <div class="mt-3">
                                            <small class="text-muted">
                                                Total: <?= count($transaksi) ?> transaksi
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

    <style>
        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .stats-card.primary {
            border-left: 4px solid #007bff;
        }

        .stats-card.success {
            border-left: 4px solid #28a745;
        }

        .stats-card.warning {
            border-left: 4px solid #ffc107;
        }

        .stats-card.info {
            border-left: 4px solid #17a2b8;
        }

        .stats-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: white;
            margin-right: 1rem;
            flex-shrink: 0;
        }

        .stats-card.primary .stats-icon {
            background: #007bff;
        }

        .stats-card.success .stats-icon {
            background: #28a745;
        }

        .stats-card.warning .stats-icon {
            background: #ffc107;
        }

        .stats-card.info .stats-icon {
            background: #17a2b8;
        }

        .stats-content {
            flex: 1;
        }

        .stats-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 0.25rem;
        }

        .stats-label {
            color: #6c757d;
            font-size: 0.875rem;
            margin-bottom: 0;
        }

        .table th {
            background-color: #f8f9fa;
            border-top: none;
            font-weight: 600;
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }

        .btn-group .btn {
            margin-right: 2px;
        }

        .btn-group .btn:last-child {
            margin-right: 0;
        }

        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.125);
        }

        .alert {
            border: none;
            border-radius: 0.5rem;
        }

        .input-group .form-control {
            border-right: none;
        }

        .input-group .btn {
            border-left: none;
        }

        .table-responsive {
            border-radius: 0.375rem;
        }

        .btn-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            line-height: 1;
            color: #000;
            opacity: 0.5;
        }

        .btn-close:hover {
            opacity: 0.75;
        }

        @media (max-width: 768px) {
            .stats-card {
                flex-direction: column;
                text-align: center;
            }

            .stats-icon {
                margin-right: 0;
                margin-bottom: 1rem;
            }

            .stats-number {
                font-size: 1.25rem;
            }

            .btn-group {
                flex-direction: column;
            }

            .btn-group .btn {
                margin-right: 0;
                margin-bottom: 2px;
            }
        }
    </style>

    <?php include 'template/script.php'; ?>
</body>

</html>