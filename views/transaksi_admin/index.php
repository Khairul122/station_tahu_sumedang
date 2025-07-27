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
                                    <h6 class="font-weight-normal mb-0">
                                        Manajemen transaksi penjualan
                                        <?php if (isset($store_info)): ?>
                                            - <?= htmlspecialchars($store_info['nama_store']) ?>
                                        <?php endif; ?>
                                    </h6>
                                </div>
                                <div class="col-12 col-xl-4">
                                    <div class="justify-content-end d-flex">
                                        <div class="dropdown flex-md-grow-1 flex-xl-grow-0">
                                            <button class="btn btn-sm btn-light bg-white dropdown-toggle" type="button" data-toggle="dropdown">
                                                <i class="mdi mdi-calendar"></i> Today
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="index.php?controller=transaksi_admin&start_date=<?= date('Y-m-d') ?>&end_date=<?= date('Y-m-d') ?>">
                                                    <i class="mdi mdi-calendar-today"></i> Hari Ini
                                                </a>
                                                <a class="dropdown-item" href="index.php?controller=transaksi_admin&start_date=<?= date('Y-m-01') ?>&end_date=<?= date('Y-m-d') ?>">
                                                    <i class="mdi mdi-calendar-month"></i> Bulan Ini
                                                </a>
                                                <a class="dropdown-item" href="index.php?controller=transaksi_admin&action=exportTransaksi&start_date=<?= $start_date ?: date('Y-m-01') ?>&end_date=<?= $end_date ?: date('Y-m-d') ?>">
                                                    <i class="mdi mdi-download"></i> Export CSV
                                                </a>
                                            </div>
                                        </div>
                                    </div>
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
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <?= htmlspecialchars($_GET['error']) ?>
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 class="card-title">Daftar Transaksi</h4>
                                        <div class="card-tools">
                                            <a href="index.php?controller=transaksi_admin&action=add" class="btn btn-primary btn-sm">
                                                <i class="mdi mdi-plus"></i> Tambah Transaksi
                                            </a>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <form method="GET" action="index.php">
                                                <input type="hidden" name="controller" value="transaksi_admin">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="search"
                                                        placeholder="Cari transaksi..." value="<?= htmlspecialchars($search) ?>">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-outline-secondary" type="submit">
                                                            <i class="fas fa-search"></i>
                                                        </button>
                                                    </div>
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
                                                    <?php if ($user_role === 'admin'): ?>
                                                    <th>Store</th>
                                                    <?php endif; ?>
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
                                                            <td>
                                                                <strong>#<?= $item['transaksi_id'] ?></strong>
                                                                <?php if (!empty($item['bukti_pembayaran'])): ?>
                                                                    <br><small class="badge badge-info">Ada Bukti</small>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td><?= date('d/m/Y H:i', strtotime($item['tanggal_transaksi'])) ?></td>
                                                            <td>
                                                                <?php if ($item['customer_id']): ?>
                                                                    <div>
                                                                        <strong><?= htmlspecialchars($item['nama_customer']) ?></strong>
                                                                        <br>
                                                                        <small class="text-muted"><?= htmlspecialchars($item['no_telepon']) ?></small>
                                                                        <br>
                                                                        <span class="badge badge-<?= $item['nama_membership'] == 'Bronze' ? 'secondary' : ($item['nama_membership'] == 'Silver' ? 'info' : ($item['nama_membership'] == 'Gold' ? 'warning' : 'primary')) ?>">
                                                                            <?= htmlspecialchars($item['nama_membership']) ?>
                                                                        </span>
                                                                    </div>
                                                                <?php else: ?>
                                                                    <span class="text-muted">Customer Umum</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <?php if ($user_role === 'admin'): ?>
                                                            <td>
                                                                <div class="store-info">
                                                                    <strong><?= htmlspecialchars($item['nama_store'] ?? 'N/A') ?></strong>
                                                                    <br>
                                                                    <small class="text-muted">ID: <?= $item['store_id'] ?? '-' ?></small>
                                                                </div>
                                                            </td>
                                                            <?php endif; ?>
                                                            <td>
                                                                <strong class="text-success">Rp <?= number_format($item['total_bayar']) ?></strong>
                                                                <?php if ($item['total_sebelum_diskon'] > $item['total_bayar']): ?>
                                                                    <br>
                                                                    <small class="text-muted">
                                                                        <del>Rp <?= number_format($item['total_sebelum_diskon']) ?></del>
                                                                    </small>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <?php if ($item['diskon_membership'] > 0): ?>
                                                                    <span class="badge badge-success">-Rp <?= number_format($item['diskon_membership']) ?></span>
                                                                <?php else: ?>
                                                                    <span class="text-muted">-</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <?php if ($item['poin_didapat'] > 0): ?>
                                                                    <span class="badge badge-warning">+<?= number_format($item['poin_didapat']) ?></span>
                                                                <?php else: ?>
                                                                    <span class="text-muted">-</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <span class="badge badge-<?= $item['metode_pembayaran'] == 'tunai' ? 'success' : ($item['metode_pembayaran'] == 'transfer' ? 'info' : 'primary') ?>">
                                                                    <?= ucfirst($item['metode_pembayaran']) ?>
                                                                </span>
                                                                <?php if ($item['metode_pembayaran'] === 'transfer' && !empty($item['bukti_pembayaran'])): ?>
                                                                    <br>
                                                                    <button type="button" class="btn btn-xs btn-outline-info mt-1" onclick="showBuktiModal('bukti_pembayaran/<?= htmlspecialchars($item['bukti_pembayaran']) ?>')">
                                                                        <i class="mdi mdi-image"></i> Bukti
                                                                    </button>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <div class="btn-group" role="group">
                                                                    <a href="index.php?controller=transaksiadmin&action=view&id=<?= $item['transaksi_id'] ?>"
                                                                        class="btn btn-sm btn-info" title="Detail">
                                                                        <i class="mdi mdi-eye"></i>
                                                                    </a>
                                                                   
                                                                    <a href="index.php?controller=transaksiadmin&action=delete&id=<?= $item['transaksi_id'] ?>"
                                                                        class="btn btn-sm btn-danger" title="Hapus"
                                                                        onclick="return confirm('Yakin ingin menghapus transaksi ini? Stok produk akan dikembalikan dan data customer akan diupdate.')">
                                                                       <i class="mdi mdi-delete-forever"></i>
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="<?= $user_role === 'admin' ? '9' : '8' ?>" class="text-center">
                                                            <div class="py-4">
                                                                <i class="mdi mdi-receipt fa-3x text-muted mb-3"></i>
                                                                <p class="text-muted">
                                                                    <?= !empty($search) ? 'Tidak ada transaksi yang ditemukan' : 'Belum ada transaksi' ?>
                                                                </p>
                                                                <?php if (empty($search)): ?>
                                                                    <a href="index.php?controller=transaksi_admin&action=add" class="btn btn-primary btn-sm">
                                                                        <i class="mdi mdi-plus"></i> Buat Transaksi Pertama
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
                                        <div class="mt-3 d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                Total: <?= count($transaksi) ?> transaksi
                                                <?php if (isset($store_info)): ?>
                                                    dari <?= htmlspecialchars($store_info['nama_store']) ?>
                                                <?php endif; ?>
                                            </small>
                                            <div class="export-options">
                                                <a href="index.php?controller=transaksi_admin&action=exportTransaksi&start_date=<?= $start_date ?: date('Y-m-01') ?>&end_date=<?= $end_date ?: date('Y-m-d') ?>" 
                                                   class="btn btn-sm btn-outline-success">
                                                    <i class="mdi mdi-download"></i> Export CSV
                                                </a>
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

    <div class="modal fade" id="buktiModal" tabindex="-1" role="dialog" aria-labelledby="buktiModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="buktiModalLabel">Bukti Pembayaran</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img id="buktiImage" src="" alt="Bukti Pembayaran" class="img-fluid">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <a id="downloadBukti" href="" download class="btn btn-primary">Download</a>
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
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(45deg, #007bff, #6c5ce7);
        }

        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .stats-card.primary::before {
            background: linear-gradient(45deg, #007bff, #0056b3);
        }

        .stats-card.success::before {
            background: linear-gradient(45deg, #28a745, #1e7e34);
        }

        .stats-card.warning::before {
            background: linear-gradient(45deg, #ffc107, #e0a800);
        }

        .stats-card.info::before {
            background: linear-gradient(45deg, #17a2b8, #138496);
        }

        .stats-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: white;
            margin-right: 1rem;
            flex-shrink: 0;
            background: linear-gradient(45deg, rgba(255,255,255,0.2), rgba(255,255,255,0.1));
            backdrop-filter: blur(10px);
        }

        .stats-card.primary .stats-icon {
            background: linear-gradient(45deg, #007bff, #0056b3);
        }

        .stats-card.success .stats-icon {
            background: linear-gradient(45deg, #28a745, #1e7e34);
        }

        .stats-card.warning .stats-icon {
            background: linear-gradient(45deg, #ffc107, #e0a800);
        }

        .stats-card.info .stats-icon {
            background: linear-gradient(45deg, #17a2b8, #138496);
        }

        .stats-content {
            flex: 1;
        }

        .stats-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 0.25rem;
            background: linear-gradient(45deg, #2c3e50, #34495e);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stats-label {
            color: #6c757d;
            font-size: 0.875rem;
            margin-bottom: 0;
            font-weight: 500;
        }

        .table th {
            background: linear-gradient(45deg, #f8f9fa, #e9ecef);
            border-top: none;
            font-weight: 600;
            color: #495057;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .table-responsive {
            border-radius: 0.75rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.35rem 0.6rem;
            border-radius: 0.5rem;
            font-weight: 500;
        }

        .btn-group .btn {
            margin-right: 2px;
            border-radius: 0.375rem !important;
        }

        .btn-group .btn:last-child {
            margin-right: 0;
        }

        .card {
            box-shadow: 0 0.125rem 0.75rem rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0, 0, 0, 0.08);
            border-radius: 0.75rem;
        }

        .card-title {
            color: #2c3e50;
            font-weight: 600;
        }

        .alert {
            border: none;
            border-radius: 0.75rem;
            border-left: 4px solid;
        }

        .alert-success {
            border-left-color: #28a745;
            background: linear-gradient(45deg, #d4edda, #c3e6cb);
        }

        .alert-danger {
            border-left-color: #dc3545;
            background: linear-gradient(45deg, #f8d7da, #f5c6cb);
        }

        .input-group .form-control {
            border-right: none;
            border-radius: 0.5rem 0 0 0.5rem;
        }

        .input-group .btn {
            border-left: none;
            border-radius: 0 0.5rem 0.5rem 0;
        }

        .store-info {
            padding: 0.5rem;
            background: linear-gradient(45deg, #e3f2fd, #f3e5f5);
            border-radius: 0.5rem;
            border-left: 3px solid #2196f3;
        }

        .btn {
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        .btn-xs {
            padding: 0.125rem 0.25rem;
            font-size: 0.75rem;
            border-radius: 0.25rem;
        }

        .export-options {
            display: flex;
            gap: 0.5rem;
        }

        .modal-content {
            border-radius: 0.75rem;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .modal-header {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            border-radius: 0.75rem 0.75rem 0 0;
        }

        #buktiImage {
            max-height: 70vh;
            border-radius: 0.5rem;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
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

            .table-responsive {
                border-radius: 0.5rem;
            }

            .store-info {
                padding: 0.25rem;
                font-size: 0.8rem;
            }
        }

        @media (max-width: 576px) {
            .stats-card {
                padding: 1rem;
            }

            .stats-number {
                font-size: 1.1rem;
            }

            .stats-label {
                font-size: 0.8rem;
            }

            .card-body {
                padding: 1rem;
            }
        }
    </style>

    <script>
        function showBuktiModal(imageSrc) {
            const modal = $('#buktiModal');
            const image = $('#buktiImage');
            const downloadLink = $('#downloadBukti');
            
            image.attr('src', imageSrc);
            downloadLink.attr('href', imageSrc);
            modal.modal('show');
        }

        $(document).ready(function() {
            $('.stats-card').each(function(index) {
                $(this).css({
                    'opacity': '0',
                    'transform': 'translateY(20px)'
                });
                
                setTimeout(() => {
                    $(this).css({
                        'transition': 'all 0.5s ease',
                        'opacity': '1',
                        'transform': 'translateY(0)'
                    });
                }, index * 100);
            });

            $('[data-toggle="tooltip"]').tooltip();

            $('.alert').each(function() {
                const alert = this;
                setTimeout(() => {
                    $(alert).fadeOut(500);
                }, 5000);
            });

            $('.table').on('click', 'tr', function(e) {
                if (!$(e.target).closest('.btn-group').length && !$(e.target).closest('button').length) {
                    const transaksiId = $(this).find('td:first strong').text().replace('#', '');
                    if (transaksiId && transaksiId !== 'ID') {
                        window.location.href = `index.php?controller=transaksi_admin&action=view&id=${transaksiId}`;
                    }
                }
            });

            $('.table tbody tr').hover(
                function() {
                    $(this).css('background-color', '#f8f9fa');
                    $(this).css('cursor', 'pointer');
                },
                function() {
                    $(this).css('background-color', '');
                }
            );
        });
    </script>

    <?php include 'template/script.php'; ?>
</body>

</html>