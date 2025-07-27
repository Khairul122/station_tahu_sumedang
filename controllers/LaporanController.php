<?php
require_once 'TCPDF/tcpdf.php';

class LaporanController
{
    private $model;
    private $authModel;

    private $company_name = "Station Tahu Sumedang";
    private $company_address = "Jl. Raya Padang - Bukittinggi, Sungai Buluh, Kec. Batang Anai, Kabupaten Padang Pariaman, Sumatera Barat";
    private $company_phone = "Telp: 0812-7588-0001";

    public function __construct()
    {
        $this->model = new LaporanModel();
        $this->authModel = new AuthModel();
    }

    public function index()
    {
        $this->authModel->requireAdminOrPimpinanRole();

        $data = [
            'success' => $_GET['success'] ?? '',
            'error' => $_GET['error'] ?? '',
            'stores' => $this->model->getAllStores()
        ];

        include 'views/laporan/index.php';
    }

    public function produk()
    {
        $this->authModel->requireAdminOrPimpinanRole();

        $format = $_GET['format'] ?? 'view';
        $store_id = $_GET['store_id'] ?? null;
        $produk = $this->model->getLaporanProduk($store_id);

        if ($format === 'pdf') {
            $this->generatePDFProduk($produk, $store_id);
        } else {
            $data = [
                'produk' => $produk,
                'stores' => $this->model->getAllStores(),
                'selected_store' => $store_id
            ];
            include 'views/laporan/produk.php';
        }
    }

    public function membership()
    {
        $this->authModel->requireAdminOrPimpinanRole();

        $format = $_GET['format'] ?? 'view';
        $store_id = $_GET['store_id'] ?? null;
        $membership = $this->model->getLaporanMembership($store_id);

        if ($format === 'pdf') {
            $this->generatePDFMembership($membership, $store_id);
        } else {
            $data = [
                'membership' => $membership,
                'stores' => $this->model->getAllStores(),
                'selected_store' => $store_id
            ];
            include 'views/laporan/membership.php';
        }
    }

    public function penjualanHarian()
    {
        $this->authModel->requireAdminOrPimpinanRole();

        $tanggal = $_GET['tanggal'] ?? date('Y-m-d');
        $format = $_GET['format'] ?? 'view';
        $store_id = $_GET['store_id'] ?? null;

        $transaksi = $this->model->getLaporanPenjualanHarian($tanggal, $store_id);

        if ($format === 'pdf') {
            $this->generatePDFPenjualanHarian($transaksi, $tanggal, $store_id);
        } else {
            $data = [
                'transaksi' => $transaksi,
                'tanggal' => $tanggal,
                'stores' => $this->model->getAllStores(),
                'selected_store' => $store_id
            ];
            include 'views/laporan/penjualan_harian.php';
        }
    }

    public function penjualanBulanan()
    {
        $this->authModel->requireAdminOrPimpinanRole();

        $bulan = $_GET['bulan'] ?? date('n');
        $tahun = $_GET['tahun'] ?? date('Y');
        $format = $_GET['format'] ?? 'view';
        $store_id = $_GET['store_id'] ?? null;

        $penjualan = $this->model->getLaporanPenjualanBulanan($bulan, $tahun, $store_id);

        if ($format === 'pdf') {
            $this->generatePDFPenjualanBulanan($penjualan, $bulan, $tahun, $store_id);
        } else {
            $data = [
                'penjualan' => $penjualan,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'stores' => $this->model->getAllStores(),
                'selected_store' => $store_id
            ];
            include 'views/laporan/penjualan_bulanan.php';
        }
    }

    public function penjualanTahunan()
    {
        $this->authModel->requireAdminOrPimpinanRole();

        $tahun = $_GET['tahun'] ?? date('Y');
        $format = $_GET['format'] ?? 'view';
        $store_id = $_GET['store_id'] ?? null;

        $penjualan = $this->model->getLaporanPenjualanTahunan($tahun, $store_id);

        if ($format === 'pdf') {
            $this->generatePDFPenjualanTahunan($penjualan, $tahun, $store_id);
        } else {
            $data = [
                'penjualan' => $penjualan,
                'tahun' => $tahun,
                'stores' => $this->model->getAllStores(),
                'selected_store' => $store_id
            ];
            include 'views/laporan/penjualan_tahunan.php';
        }
    }

    private function getStoreName($store_id)
    {
        if (!$store_id) return 'Semua Store';

        $stores = $this->model->getAllStores();
        foreach ($stores as $store) {
            if ($store['id_store'] == $store_id) {
                return $store['nama_store'];
            }
        }
        return 'Store Tidak Ditemukan';
    }

    private function createPDF($title, $subtitle, $store_id = null)
    {
        $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator('Station Tahu Sumedang');
        $pdf->SetTitle($title);
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(FALSE);

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->AddPage();

        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, $this->company_name, 0, 1, 'C');

        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 6, $this->company_address, 0, 1, 'C');
        $pdf->Cell(0, 6, $this->company_phone, 0, 1, 'C');

        $pdf->Line(15, $pdf->GetY() + 3, $pdf->getPageWidth() - 15, $pdf->GetY() + 3);

        $pdf->Ln(10);

        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 8, $title, 0, 1, 'C');

        $pdf->SetFont('helvetica', '', 11);
        $pdf->Cell(0, 6, $subtitle, 0, 1, 'C');

        if ($store_id) {
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Cell(0, 6, 'Store: ' . $this->getStoreName($store_id), 0, 1, 'C');
        }

        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 6, 'Periode: ' . date('d') . ' July ' . date('Y') . ' s.d ' . date('d') . ' July ' . date('Y'), 0, 1, 'C');

        $pdf->Ln(8);

        return $pdf;
    }

    private function generatePDFProduk($produk, $store_id = null)
    {
        $pdf = $this->createPDF('LAPORAN DATA PRODUK', 'Daftar Produk dan Informasi Penjualan', $store_id);
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Ln(2);

        if (empty($produk)) {
            $pdf->SetFont('helvetica', '', 12);
            $pdf->Cell(0, 20, 'Tidak ada data produk dalam periode yang dipilih', 0, 1, 'C');
        } else {
            $this->checkPageBreak($pdf, 30);
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->SetFillColor(66, 139, 202);
            $pdf->SetTextColor(255, 255, 255);

            $pdf->SetX(($pdf->GetPageWidth() - 242) / 2);
            $pdf->Cell(15, 7, 'No', 1, 0, 'C', 1);
            $pdf->Cell(50, 7, 'Nama', 1, 0, 'C', 1);
            $pdf->Cell(40, 7, 'Store', 1, 0, 'C', 1);
            $pdf->Cell(25, 7, 'Kategori', 1, 0, 'C', 1);
            $pdf->Cell(25, 7, 'Harga', 1, 0, 'C', 1);
            $pdf->Cell(20, 7, 'Stok', 1, 0, 'C', 1);
            $pdf->Cell(25, 7, 'Terjual', 1, 0, 'C', 1);
            $pdf->Cell(42, 7, 'Pendapatan', 1, 1, 'C', 1);

            $pdf->SetFont('helvetica', '', 8);
            $pdf->SetTextColor(0, 0, 0);
            $no = 1;
            $total_pendapatan = 0;

            foreach ($produk as $row) {
                $this->checkPageBreak($pdf, 12);
                $fill = ($no % 2 == 0) ? 1 : 0;
                $pdf->SetFillColor(240, 240, 240);
                $pdf->SetX(($pdf->GetPageWidth() - 242) / 2);
                $pdf->Cell(15, 6, $no++, 1, 0, 'C', $fill);
                $pdf->Cell(50, 6, substr($row['nama_produk'], 0, 25), 1, 0, 'L', $fill);
                $pdf->Cell(40, 6, substr($row['nama_store'], 0, 20), 1, 0, 'L', $fill);
                $pdf->Cell(25, 6, $row['kategori'], 1, 0, 'C', $fill);
                $pdf->Cell(25, 6, number_format($row['harga']), 1, 0, 'R', $fill);
                $pdf->Cell(20, 6, $row['stok'], 1, 0, 'C', $fill);
                $pdf->Cell(25, 6, $row['total_terjual'], 1, 0, 'C', $fill);
                $pdf->Cell(42, 6, number_format($row['total_pendapatan']), 1, 1, 'R', $fill);
                $total_pendapatan += $row['total_pendapatan'];
            }

            $this->checkPageBreak($pdf, 12);
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->SetFillColor(66, 139, 202);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->SetX(($pdf->GetPageWidth() - 242) / 2);
            $pdf->Cell(200, 7, 'TOTAL PENDAPATAN', 1, 0, 'R', 1);
            $pdf->Cell(42, 7, number_format($total_pendapatan), 1, 1, 'R', 1);
        }

        $this->addComplexSignature($pdf);
        $filename = 'laporan_produk_' . ($store_id ? 'store_' . $store_id . '_' : '') . date('Y-m-d') . '.pdf';
        $pdf->Output($filename, 'I');
    }


    private function generatePDFMembership($membership, $store_id = null)
    {
        $pdf = $this->createPDF('LAPORAN DATA MEMBERSHIP', 'Daftar Membership dan Informasi Member', $store_id);
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(0, 7, 'Daftar Data Membership per ' . date('d F Y'), 0, 1, 'L');
        $pdf->Ln(2);

        if (empty($membership)) {
            $pdf->SetFont('helvetica', '', 12);
            $pdf->Cell(0, 20, 'Tidak ada data membership dalam periode yang dipilih', 0, 1, 'C');
        } else {
            $this->checkPageBreak($pdf, 30);
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->SetFillColor(66, 139, 202);
            $pdf->SetTextColor(255, 255, 255);

            $tableWidth = 15 + 65 + 30 + 35 + 52 + 35;
            $pdf->SetX(($pdf->GetPageWidth() - $tableWidth) / 2);
            $pdf->Cell(15, 7, 'No', 1, 0, 'C', 1);
            $pdf->Cell(65, 7, 'Nama Membership', 1, 0, 'C', 1);
            $pdf->Cell(30, 7, 'Diskon %', 1, 0, 'C', 1);
            $pdf->Cell(35, 7, 'Total Member', 1, 0, 'C', 1);
            $pdf->Cell(52, 7, 'Total Pembelian', 1, 0, 'C', 1);
            $pdf->Cell(35, 7, 'Total Poin', 1, 1, 'C', 1);

            $pdf->SetFont('helvetica', '', 8);
            $pdf->SetTextColor(0, 0, 0);
            $no = 1;
            $total_pembelian = 0;

            foreach ($membership as $row) {
                $this->checkPageBreak($pdf, 12);
                $fill = ($no % 2 == 0) ? 1 : 0;
                $pdf->SetFillColor(240, 240, 240);

                $pdf->SetX(($pdf->GetPageWidth() - $tableWidth) / 2);
                $pdf->Cell(15, 6, $no++, 1, 0, 'C', $fill);
                $pdf->Cell(65, 6, substr($row['nama_membership'], 0, 30), 1, 0, 'L', $fill);
                $pdf->Cell(30, 6, $row['diskon_persen'] . '%', 1, 0, 'C', $fill);
                $pdf->Cell(35, 6, $row['total_member'], 1, 0, 'C', $fill);
                $pdf->Cell(52, 6, number_format($row['total_pembelian_member']), 1, 0, 'R', $fill);
                $pdf->Cell(35, 6, number_format($row['total_poin_member']), 1, 1, 'C', $fill);

                $total_pembelian += $row['total_pembelian_member'];
            }

            $this->checkPageBreak($pdf, 12);
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->SetFillColor(66, 139, 202);
            $pdf->SetTextColor(255, 255, 255);

            $pdf->SetX(($pdf->GetPageWidth() - $tableWidth) / 2);
            $pdf->Cell(145, 7, 'TOTAL KESELURUHAN', 1, 0, 'R', 1);
            $pdf->Cell(52, 7, number_format($total_pembelian), 1, 0, 'R', 1);
            $pdf->Cell(35, 7, '', 1, 1, 'C', 1);
        }

        $this->addComplexSignature($pdf);
        $filename = 'laporan_membership_' . ($store_id ? 'store_' . $store_id . '_' : '') . date('Y-m-d') . '.pdf';
        $pdf->Output($filename, 'I');
    }


    private function generatePDFPenjualanHarian($transaksi, $tanggal, $store_id = null)
    {
        $pdf = $this->createPDF('LAPORAN PENJUALAN HARIAN', 'Daftar Transaksi Penjualan per Hari', $store_id);
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Ln(2);

        if (empty($transaksi)) {
            $pdf->SetFont('helvetica', '', 12);
            $pdf->Cell(0, 20, 'Tidak ada data transaksi dalam periode yang dipilih', 0, 1, 'C');
        } else {
            $this->checkPageBreak($pdf, 30);

            $tableWidth = 15 + 50 + 40 + 35 + 55 + 35;
            $startX = ($pdf->GetPageWidth() - $tableWidth) / 2;

            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->SetFillColor(66, 139, 202);
            $pdf->SetTextColor(255, 255, 255);

            $pdf->SetX($startX);
            $pdf->Cell(15, 7, 'No', 1, 0, 'C', 1);
            $pdf->Cell(50, 7, 'Nama', 1, 0, 'C', 1);
            $pdf->Cell(40, 7, 'Store', 1, 0, 'C', 1);
            $pdf->Cell(35, 7, 'Total', 1, 0, 'C', 1);
            $pdf->Cell(55, 7, 'Alamat', 1, 0, 'C', 1);
            $pdf->Cell(35, 7, 'Mobile Phone', 1, 1, 'C', 1);

            $pdf->SetFont('helvetica', '', 8);
            $pdf->SetTextColor(0, 0, 0);
            $no = 1;
            $total_penjualan = 0;

            foreach ($transaksi as $row) {
                $this->checkPageBreak($pdf, 12);
                $fill = ($no % 2 == 0) ? 1 : 0;
                $pdf->SetFillColor(240, 240, 240);

                $pdf->SetX($startX);
                $pdf->Cell(15, 6, $no++, 1, 0, 'C', $fill);
                $pdf->Cell(50, 6, substr($row['nama_customer'], 0, 22), 1, 0, 'L', $fill);
                $pdf->Cell(40, 6, substr($row['nama_store'], 0, 18), 1, 0, 'L', $fill);
                $pdf->Cell(35, 6, number_format($row['total_bayar']), 1, 0, 'R', $fill);
                $pdf->Cell(55, 6, substr($row['alamat'], 0, 25), 1, 0, 'L', $fill);
                $pdf->Cell(35, 6, $row['no_telepon'], 1, 1, 'C', $fill);

                $total_penjualan += $row['total_bayar'];
            }

            $this->checkPageBreak($pdf, 12);
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->SetFillColor(66, 139, 202);
            $pdf->SetTextColor(255, 255, 255);

            $pdf->SetX($startX);
            $pdf->Cell(195, 7, 'TOTAL PENJUALAN HARIAN', 1, 0, 'R', 1);
            $pdf->Cell(35, 7, number_format($total_penjualan), 1, 1, 'R', 1);
        }

        $this->addComplexSignature($pdf);
        $filename = 'laporan_penjualan_harian_' . ($store_id ? 'store_' . $store_id . '_' : '') . $tanggal . '.pdf';
        $pdf->Output($filename, 'I');
    }

    private function generatePDFPenjualanBulanan($penjualan, $bulan, $tahun, $store_id = null)
    {
        $namaBulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        $pdf = $this->createPDF('LAPORAN PENJUALAN BULANAN', 'Rekap Penjualan per Bulan', $store_id);
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Ln(2);

        if (empty($penjualan)) {
            $pdf->SetFont('helvetica', '', 12);
            $pdf->Cell(0, 20, 'Tidak ada data penjualan dalam periode yang dipilih', 0, 1, 'C');
        } else {
            $this->checkPageBreak($pdf, 30);

            $tableWidth = 15 + 45 + 45 + 60 + 40;
            $startX = ($pdf->GetPageWidth() - $tableWidth) / 2;

            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->SetFillColor(66, 139, 202);
            $pdf->SetTextColor(255, 255, 255);

            $pdf->SetX($startX);
            $pdf->Cell(15, 7, 'No', 1, 0, 'C', 1);
            $pdf->Cell(45, 7, 'Tanggal', 1, 0, 'C', 1);
            $pdf->Cell(45, 7, 'Total Transaksi', 1, 0, 'C', 1);
            $pdf->Cell(60, 7, 'Total Pendapatan', 1, 0, 'C', 1);
            $pdf->Cell(40, 7, 'Total Customer', 1, 1, 'C', 1);

            $pdf->SetFont('helvetica', '', 8);
            $pdf->SetTextColor(0, 0, 0);
            $no = 1;
            $total_transaksi = 0;
            $total_pendapatan = 0;
            $total_customer = 0;

            foreach ($penjualan as $row) {
                $this->checkPageBreak($pdf, 12);
                $fill = ($no % 2 == 0) ? 1 : 0;
                $pdf->SetFillColor(240, 240, 240);

                $pdf->SetX($startX);
                $pdf->Cell(15, 6, $no++, 1, 0, 'C', $fill);
                $pdf->Cell(45, 6, date('d-m-Y', strtotime($row['tanggal'])), 1, 0, 'C', $fill);
                $pdf->Cell(45, 6, $row['total_transaksi'], 1, 0, 'C', $fill);
                $pdf->Cell(60, 6, number_format($row['total_pendapatan']), 1, 0, 'R', $fill);
                $pdf->Cell(40, 6, $row['total_customer'], 1, 1, 'C', $fill);

                $total_transaksi += $row['total_transaksi'];
                $total_pendapatan += $row['total_pendapatan'];
                $total_customer += $row['total_customer'];
            }

            $this->checkPageBreak($pdf, 12);
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->SetFillColor(66, 139, 202);
            $pdf->SetTextColor(255, 255, 255);

            $pdf->SetX($startX);
            $pdf->Cell(60, 7, 'TOTAL BULANAN', 1, 0, 'R', 1);
            $pdf->Cell(45, 7, $total_transaksi, 1, 0, 'C', 1);
            $pdf->Cell(60, 7, number_format($total_pendapatan), 1, 0, 'R', 1);
            $pdf->Cell(40, 7, $total_customer, 1, 1, 'C', 1);
        }

        $this->addComplexSignature($pdf);
        $filename = 'laporan_penjualan_bulanan_' . ($store_id ? 'store_' . $store_id . '_' : '') . $bulan . '_' . $tahun . '.pdf';
        $pdf->Output($filename, 'I');
    }


    private function generatePDFPenjualanTahunan($penjualan, $tahun, $store_id = null)
    {
        $namaBulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        $pdf = $this->createPDF('LAPORAN PENJUALAN TAHUNAN', 'Rekap Penjualan per Tahun', $store_id);
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Ln(2);

        if (empty($penjualan)) {
            $pdf->SetFont('helvetica', '', 12);
            $pdf->Cell(0, 20, 'Tidak ada data penjualan dalam periode yang dipilih', 0, 1, 'C');
        } else {
            $this->checkPageBreak($pdf, 30);

            $tableWidth = 15 + 50 + 45 + 60 + 35;
            $startX = ($pdf->GetPageWidth() - $tableWidth) / 2;

            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->SetFillColor(66, 139, 202);
            $pdf->SetTextColor(255, 255, 255);

            $pdf->SetX($startX);
            $pdf->Cell(15, 7, 'No', 1, 0, 'C', 1);
            $pdf->Cell(50, 7, 'Bulan', 1, 0, 'C', 1);
            $pdf->Cell(45, 7, 'Total Transaksi', 1, 0, 'C', 1);
            $pdf->Cell(60, 7, 'Total Pendapatan', 1, 0, 'C', 1);
            $pdf->Cell(35, 7, 'Total Customer', 1, 1, 'C', 1);

            $pdf->SetFont('helvetica', '', 8);
            $pdf->SetTextColor(0, 0, 0);
            $no = 1;
            $total_transaksi = 0;
            $total_pendapatan = 0;
            $total_customer = 0;

            foreach ($penjualan as $row) {
                $this->checkPageBreak($pdf, 12);
                $fill = ($no % 2 == 0) ? 1 : 0;
                $pdf->SetFillColor(240, 240, 240);

                $pdf->SetX($startX);
                $pdf->Cell(15, 6, $no++, 1, 0, 'C', $fill);
                $pdf->Cell(50, 6, $namaBulan[$row['bulan']], 1, 0, 'L', $fill);
                $pdf->Cell(45, 6, $row['total_transaksi'], 1, 0, 'C', $fill);
                $pdf->Cell(60, 6, number_format($row['total_pendapatan']), 1, 0, 'R', $fill);
                $pdf->Cell(35, 6, $row['total_customer'], 1, 1, 'C', $fill);

                $total_transaksi += $row['total_transaksi'];
                $total_pendapatan += $row['total_pendapatan'];
                $total_customer += $row['total_customer'];
            }

            $this->checkPageBreak($pdf, 12);
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->SetFillColor(66, 139, 202);
            $pdf->SetTextColor(255, 255, 255);

            $pdf->SetX($startX);
            $pdf->Cell(65, 7, 'TOTAL TAHUNAN', 1, 0, 'R', 1);
            $pdf->Cell(45, 7, $total_transaksi, 1, 0, 'C', 1);
            $pdf->Cell(60, 7, number_format($total_pendapatan), 1, 0, 'R', 1);
            $pdf->Cell(35, 7, $total_customer, 1, 1, 'C', 1);
        }

        $this->addComplexSignature($pdf);
        $filename = 'laporan_penjualan_tahunan_' . ($store_id ? 'store_' . $store_id . '_' : '') . $tahun . '.pdf';
        $pdf->Output($filename, 'I');
    }

    private function checkPageBreak($pdf, $height)
    {
        $currentY = $pdf->GetY();
        $pageHeight = $pdf->getPageHeight();
        $bottomMargin = 80;

        if (($currentY + $height) > ($pageHeight - $bottomMargin)) {
            $pdf->AddPage();

            $pdf->SetFont('helvetica', 'B', 16);
            $pdf->Cell(0, 10, $this->company_name, 0, 1, 'C');

            $pdf->SetFont('helvetica', '', 10);
            $pdf->Cell(0, 6, $this->company_address, 0, 1, 'C');
            $pdf->Cell(0, 6, $this->company_phone, 0, 1, 'C');

            $pdf->Line(15, $pdf->GetY() + 3, $pdf->getPageWidth() - 15, $pdf->GetY() + 3);

            $pdf->Ln(10);
        }
    }

    private function addComplexSignature($pdf)
    {
        $currentY = $pdf->GetY();
        $pageHeight = $pdf->getPageHeight();
        $signatureHeight = 60;
        $bottomMargin = 15;

        $requiredSpace = $signatureHeight + $bottomMargin;
        $availableSpace = $pageHeight - $currentY - $bottomMargin;

        if ($availableSpace < $signatureHeight) {
            $pdf->AddPage();
            $currentY = $pdf->GetY();
        }

        $signatureStartY = $pageHeight - $signatureHeight - $bottomMargin;

        if ($currentY < $signatureStartY) {
            $pdf->SetY($signatureStartY);
        } else {
            $pdf->Ln(10);
        }

        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('helvetica', '', 10);

        $pdf->Cell(180, 6, '', 0, 0);
        $pdf->Cell(0, 6, "Kasai, " . date('d F Y'), 0, 1, 'L');

        $pdf->Cell(180, 6, '', 0, 0);
        $pdf->Cell(0, 6, "Mengetahui,", 0, 1, 'L');

        $pdf->Cell(180, 6, '', 0, 0);
        $pdf->Cell(0, 6, "Pimpinan " . $this->company_name, 0, 1, 'L');

        $pdf->Ln(25);

        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(180, 6, '', 0, 0);
        $pdf->Cell(0, 6, "(Dindin Irawan)", 0, 1, 'L');

        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(180, 6, '', 0, 0);

        $pdf->Ln(5);
        $pdf->SetFont('helvetica', 'I', 8);
        $pdf->Cell(0, 4, 'Dokumen ini telah ditandatangani secara elektronik pada: ' . date('d/m/Y H:i:s'), 0, 1, 'C');
    }
}
