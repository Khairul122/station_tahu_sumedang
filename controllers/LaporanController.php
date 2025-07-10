<?php
require_once 'TCPDF/tcpdf.php';

class LaporanController {
    private $model;
    private $authModel;
    
    public function __construct() {
        $this->model = new LaporanModel();
        $this->authModel = new AuthModel();
    }
    
    public function index() {
        $this->authModel->requireAdminOrPimpinanRole();
        
        $data = [
            'success' => $_GET['success'] ?? '',
            'error' => $_GET['error'] ?? ''
        ];
        
        include 'views/laporan/index.php';
    }
    
    public function produk() {
        $this->authModel->requireAdminOrPimpinanRole();
        
        $format = $_GET['format'] ?? 'view';
        $produk = $this->model->getLaporanProduk();
        
        if ($format === 'pdf') {
            $this->generatePDFProduk($produk);
        } else {
            $data = ['produk' => $produk];
            include 'views/laporan/produk.php';
        }
    }
    
    public function membership() {
        $this->authModel->requireAdminOrPimpinanRole();
        
        $format = $_GET['format'] ?? 'view';
        $membership = $this->model->getLaporanMembership();
        
        if ($format === 'pdf') {
            $this->generatePDFMembership($membership);
        } else {
            $data = ['membership' => $membership];
            include 'views/laporan/membership.php';
        }
    }
    
    public function penjualanHarian() {
        $this->authModel->requireAdminOrPimpinanRole();
        
        $tanggal = $_GET['tanggal'] ?? date('Y-m-d');
        $format = $_GET['format'] ?? 'view';
        
        $transaksi = $this->model->getLaporanPenjualanHarian($tanggal);
        
        if ($format === 'pdf') {
            $this->generatePDFPenjualanHarian($transaksi, $tanggal);
        } else {
            $data = [
                'transaksi' => $transaksi,
                'tanggal' => $tanggal
            ];
            include 'views/laporan/penjualan_harian.php';
        }
    }
    
    public function penjualanBulanan() {
        $this->authModel->requireAdminOrPimpinanRole();
        
        $bulan = $_GET['bulan'] ?? date('n');
        $tahun = $_GET['tahun'] ?? date('Y');
        $format = $_GET['format'] ?? 'view';
        
        $penjualan = $this->model->getLaporanPenjualanBulanan($bulan, $tahun);
        
        if ($format === 'pdf') {
            $this->generatePDFPenjualanBulanan($penjualan, $bulan, $tahun);
        } else {
            $data = [
                'penjualan' => $penjualan,
                'bulan' => $bulan,
                'tahun' => $tahun
            ];
            include 'views/laporan/penjualan_bulanan.php';
        }
    }
    
    public function penjualanTahunan() {
        $this->authModel->requireAdminOrPimpinanRole();
        
        $tahun = $_GET['tahun'] ?? date('Y');
        $format = $_GET['format'] ?? 'view';
        
        $penjualan = $this->model->getLaporanPenjualanTahunan($tahun);
        
        if ($format === 'pdf') {
            $this->generatePDFPenjualanTahunan($penjualan, $tahun);
        } else {
            $data = [
                'penjualan' => $penjualan,
                'tahun' => $tahun
            ];
            include 'views/laporan/penjualan_tahunan.php';
        }
    }
    
    private function generatePDFProduk($produk) {
        $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        $pdf->SetCreator('Station Tahu Sumedang');
        $pdf->SetTitle('Laporan Data Produk');
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(TRUE, 15);
        $pdf->AddPage();
        
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 10, 'Laporan Data Produk', 1, 1, 'C');
        
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 8, 'Periode: ' . date('d-m-Y'), 0, 1, 'R');
        $pdf->Ln(5);
        
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(15, 8, 'No', 1, 0, 'C');
        $pdf->Cell(80, 8, 'Nama', 1, 0, 'C');
        $pdf->Cell(30, 8, 'Kategori', 1, 0, 'C');
        $pdf->Cell(25, 8, 'Harga', 1, 0, 'C');
        $pdf->Cell(20, 8, 'Stok', 1, 0, 'C');
        $pdf->Cell(25, 8, 'Terjual', 1, 0, 'C');
        $pdf->Cell(35, 8, 'Pendapatan', 1, 1, 'C');
        
        $pdf->SetFont('helvetica', '', 8);
        $no = 1;
        foreach ($produk as $row) {
            $pdf->Cell(15, 6, $no++, 1, 0, 'C');
            $pdf->Cell(80, 6, substr($row['nama_produk'], 0, 40), 1, 0, 'L');
            $pdf->Cell(30, 6, $row['kategori'], 1, 0, 'C');
            $pdf->Cell(25, 6, number_format($row['harga']), 1, 0, 'R');
            $pdf->Cell(20, 6, $row['stok'], 1, 0, 'C');
            $pdf->Cell(25, 6, $row['total_terjual'], 1, 0, 'C');
            $pdf->Cell(35, 6, number_format($row['total_pendapatan']), 1, 1, 'R');
        }
        
        $pdf->Output('laporan_produk_' . date('Y-m-d') . '.pdf', 'I');
    }
    
    private function generatePDFMembership($membership) {
        $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        $pdf->SetCreator('Station Tahu Sumedang');
        $pdf->SetTitle('Laporan Data Membership');
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(TRUE, 15);
        $pdf->AddPage();
        
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 10, 'Laporan Data Membership', 1, 1, 'C');
        
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 8, 'Periode: ' . date('d-m-Y'), 0, 1, 'R');
        $pdf->Ln(5);
        
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(15, 8, 'No', 1, 0, 'C');
        $pdf->Cell(50, 8, 'Nama Membership', 1, 0, 'C');
        $pdf->Cell(25, 8, 'Diskon %', 1, 0, 'C');
        $pdf->Cell(30, 8, 'Total Member', 1, 0, 'C');
        $pdf->Cell(50, 8, 'Total Pembelian', 1, 0, 'C');
        $pdf->Cell(30, 8, 'Total Poin', 1, 1, 'C');
        
        $pdf->SetFont('helvetica', '', 9);
        $no = 1;
        foreach ($membership as $row) {
            $pdf->Cell(15, 6, $no++, 1, 0, 'C');
            $pdf->Cell(50, 6, $row['nama_membership'], 1, 0, 'L');
            $pdf->Cell(25, 6, $row['diskon_persen'] . '%', 1, 0, 'C');
            $pdf->Cell(30, 6, $row['total_member'], 1, 0, 'C');
            $pdf->Cell(50, 6, number_format($row['total_pembelian_member']), 1, 0, 'R');
            $pdf->Cell(30, 6, number_format($row['total_poin_member']), 1, 1, 'C');
        }
        
        $pdf->Output('laporan_membership_' . date('Y-m-d') . '.pdf', 'I');
    }
    
    private function generatePDFPenjualanHarian($transaksi, $tanggal) {
        $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        $pdf->SetCreator('Station Tahu Sumedang');
        $pdf->SetTitle('Laporan Data Penjualan Harian');
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(TRUE, 15);
        $pdf->AddPage();
        
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 10, 'Laporan Data Penjualan Harian', 1, 1, 'C');
        
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 8, 'Periode: ' . date('d-m-Y', strtotime($tanggal)), 0, 1, 'R');
        $pdf->Ln(5);
        
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(15, 8, 'No', 1, 0, 'C');
        $pdf->Cell(60, 8, 'Nama', 1, 0, 'C');
        $pdf->Cell(40, 8, 'Total', 1, 0, 'C');
        $pdf->Cell(70, 8, 'Alamat', 1, 0, 'C');
        $pdf->Cell(35, 8, 'Mobile Phone', 1, 0, 'C');
        $pdf->Cell(30, 8, 'Owner', 1, 1, 'C');
        
        $pdf->SetFont('helvetica', '', 8);
        $no = 1;
        foreach ($transaksi as $row) {
            $pdf->Cell(15, 6, $no++, 1, 0, 'C');
            $pdf->Cell(60, 6, substr($row['nama_customer'], 0, 30), 1, 0, 'L');
            $pdf->Cell(40, 6, number_format($row['total_bayar']), 1, 0, 'R');
            $pdf->Cell(70, 6, substr($row['alamat'], 0, 35), 1, 0, 'L');
            $pdf->Cell(35, 6, $row['no_telepon'], 1, 0, 'C');
            $pdf->Cell(30, 6, substr($row['owner'] ?? '-', 0, 15), 1, 1, 'L');
        }
        
        $pdf->Output('laporan_penjualan_harian_' . $tanggal . '.pdf', 'I');
    }
    
    private function generatePDFPenjualanBulanan($penjualan, $bulan, $tahun) {
        $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        $pdf->SetCreator('Station Tahu Sumedang');
        $pdf->SetTitle('Laporan Data Penjualan Bulanan');
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(TRUE, 15);
        $pdf->AddPage();
        
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 10, 'Laporan Data Penjualan Bulanan', 1, 1, 'C');
        
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 8, 'Periode: ' . $namaBulan[$bulan] . ' ' . $tahun, 0, 1, 'R');
        $pdf->Ln(5);
        
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(15, 8, 'No', 1, 0, 'C');
        $pdf->Cell(40, 8, 'Tanggal', 1, 0, 'C');
        $pdf->Cell(40, 8, 'Total Transaksi', 1, 0, 'C');
        $pdf->Cell(50, 8, 'Total Pendapatan', 1, 0, 'C');
        $pdf->Cell(40, 8, 'Total Customer', 1, 1, 'C');
        
        $pdf->SetFont('helvetica', '', 9);
        $no = 1;
        foreach ($penjualan as $row) {
            $pdf->Cell(15, 6, $no++, 1, 0, 'C');
            $pdf->Cell(40, 6, date('d-m-Y', strtotime($row['tanggal'])), 1, 0, 'C');
            $pdf->Cell(40, 6, $row['total_transaksi'], 1, 0, 'C');
            $pdf->Cell(50, 6, number_format($row['total_pendapatan']), 1, 0, 'R');
            $pdf->Cell(40, 6, $row['total_customer'], 1, 1, 'C');
        }
        
        $pdf->Output('laporan_penjualan_bulanan_' . $bulan . '_' . $tahun . '.pdf', 'I');
    }
    
    private function generatePDFPenjualanTahunan($penjualan, $tahun) {
        $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        $pdf->SetCreator('Station Tahu Sumedang');
        $pdf->SetTitle('Laporan Data Penjualan Tahunan');
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(TRUE, 15);
        $pdf->AddPage();
        
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 10, 'Laporan Data Penjualan Tahunan', 1, 1, 'C');
        
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 8, 'Periode: ' . $tahun, 0, 1, 'R');
        $pdf->Ln(5);
        
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(15, 8, 'No', 1, 0, 'C');
        $pdf->Cell(40, 8, 'Bulan', 1, 0, 'C');
        $pdf->Cell(40, 8, 'Total Transaksi', 1, 0, 'C');
        $pdf->Cell(50, 8, 'Total Pendapatan', 1, 0, 'C');
        $pdf->Cell(40, 8, 'Total Customer', 1, 1, 'C');
        
        $pdf->SetFont('helvetica', '', 9);
        $no = 1;
        foreach ($penjualan as $row) {
            $pdf->Cell(15, 6, $no++, 1, 0, 'C');
            $pdf->Cell(40, 6, $namaBulan[$row['bulan']], 1, 0, 'L');
            $pdf->Cell(40, 6, $row['total_transaksi'], 1, 0, 'C');
            $pdf->Cell(50, 6, number_format($row['total_pendapatan']), 1, 0, 'R');
            $pdf->Cell(40, 6, $row['total_customer'], 1, 1, 'C');
        }
        
        $pdf->Output('laporan_penjualan_tahunan_' . $tahun . '.pdf', 'I');
    }
}
?>