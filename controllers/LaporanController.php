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
            'error' => $_GET['error'] ?? ''
        ];

        include 'views/laporan/index.php';
    }

    public function produk()
    {
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

    public function membership()
    {
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

    public function penjualanHarian()
    {
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

    public function penjualanBulanan()
    {
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

    public function penjualanTahunan()
    {
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

    private function createPDF($title, $subtitle)
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

        // Garis di bawah informasi perusahaan
        $pdf->Line(15, $pdf->GetY() + 3, $pdf->getPageWidth() - 15, $pdf->GetY() + 3);

        $pdf->Ln(10);

        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 8, $title, 0, 1, 'C');

        $pdf->SetFont('helvetica', '', 11);
        $pdf->Cell(0, 6, $subtitle, 0, 1, 'C');

        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 6, 'Periode: ' . date('d') . ' July ' . date('Y') . ' s.d ' . date('d') . ' July ' . date('Y'), 0, 1, 'C');

        $pdf->Ln(8);

        return $pdf;
    }

    private function generatePDFProduk($produk)
    {
        $pdf = $this->createPDF('LAPORAN DATA PRODUK', 'Daftar Produk dan Informasi Penjualan');

        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Ln(2);

        if (empty($produk)) {
            $pdf->SetFont('helvetica', '', 12);
            $pdf->Cell(0, 40, 'Tidak ada data produk dalam periode yang dipilih', 0, 1, 'C');
        } else {
            $this->checkPageBreak($pdf, 50);
            
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->SetFillColor(66, 139, 202);
            $pdf->SetTextColor(255, 255, 255);

            $pdf->Cell(15, 8, 'No', 1, 0, 'C', 1);
            $pdf->Cell(70, 8, 'Nama', 1, 0, 'C', 1);
            $pdf->Cell(35, 8, 'Kategori', 1, 0, 'C', 1);
            $pdf->Cell(35, 8, 'Harga', 1, 0, 'C', 1);
            $pdf->Cell(20, 8, 'Stok', 1, 0, 'C', 1);
            $pdf->Cell(25, 8, 'Terjual', 1, 0, 'C', 1);
            $pdf->Cell(52, 8, 'Pendapatan', 1, 1, 'C', 1);

            $pdf->SetFont('helvetica', '', 8);
            $pdf->SetTextColor(0, 0, 0);
            $no = 1;
            $total_pendapatan = 0;

            foreach ($produk as $row) {
                $this->checkPageBreak($pdf, 15);
                
                $fill = ($no % 2 == 0) ? 1 : 0;
                $pdf->SetFillColor(240, 240, 240);

                $pdf->Cell(15, 6, $no++, 1, 0, 'C', $fill);
                $pdf->Cell(70, 6, substr($row['nama_produk'], 0, 35), 1, 0, 'L', $fill);
                $pdf->Cell(35, 6, $row['kategori'], 1, 0, 'C', $fill);
                $pdf->Cell(35, 6, number_format($row['harga']), 1, 0, 'R', $fill);
                $pdf->Cell(20, 6, $row['stok'], 1, 0, 'C', $fill);
                $pdf->Cell(25, 6, $row['total_terjual'], 1, 0, 'C', $fill);
                $pdf->Cell(52, 6, number_format($row['total_pendapatan']), 1, 1, 'R', $fill);

                $total_pendapatan += $row['total_pendapatan'];
            }

            $this->checkPageBreak($pdf, 15);
            
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->SetFillColor(66, 139, 202);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->Cell(200, 8, 'TOTAL PENDAPATAN', 1, 0, 'R', 1);
            $pdf->Cell(52, 8, number_format($total_pendapatan), 1, 1, 'R', 1);
        }

        $this->addComplexSignature($pdf);
        $pdf->Output('laporan_produk_' . date('Y-m-d') . '.pdf', 'I');
    }

    private function generatePDFMembership($membership)
    {
        $pdf = $this->createPDF('LAPORAN DATA MEMBERSHIP', 'Daftar Membership dan Informasi Member');

        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(0, 8, 'Daftar Data Membership per ' . date('d F Y'), 0, 1, 'L');
        $pdf->Ln(2);

        if (empty($membership)) {
            $pdf->SetFont('helvetica', '', 12);
            $pdf->Cell(0, 40, 'Tidak ada data membership dalam periode yang dipilih', 0, 1, 'C');
        } else {
            $this->checkPageBreak($pdf, 50);
            
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->SetFillColor(66, 139, 202);
            $pdf->SetTextColor(255, 255, 255);

            $pdf->Cell(15, 8, 'No', 1, 0, 'C', 1);
            $pdf->Cell(65, 8, 'Nama Membership', 1, 0, 'C', 1);
            $pdf->Cell(30, 8, 'Diskon %', 1, 0, 'C', 1);
            $pdf->Cell(35, 8, 'Total Member', 1, 0, 'C', 1);
            $pdf->Cell(52, 8, 'Total Pembelian', 1, 0, 'C', 1);
            $pdf->Cell(35, 8, 'Total Poin', 1, 1, 'C', 1);

            $pdf->SetFont('helvetica', '', 8);
            $pdf->SetTextColor(0, 0, 0);
            $no = 1;
            $total_pembelian = 0;

            foreach ($membership as $row) {
                $this->checkPageBreak($pdf, 15);
                
                $fill = ($no % 2 == 0) ? 1 : 0;
                $pdf->SetFillColor(240, 240, 240);

                $pdf->Cell(15, 6, $no++, 1, 0, 'C', $fill);
                $pdf->Cell(65, 6, substr($row['nama_membership'], 0, 30), 1, 0, 'L', $fill);
                $pdf->Cell(30, 6, $row['diskon_persen'] . '%', 1, 0, 'C', $fill);
                $pdf->Cell(35, 6, $row['total_member'], 1, 0, 'C', $fill);
                $pdf->Cell(52, 6, number_format($row['total_pembelian_member']), 1, 0, 'R', $fill);
                $pdf->Cell(35, 6, number_format($row['total_poin_member']), 1, 1, 'C', $fill);

                $total_pembelian += $row['total_pembelian_member'];
            }

            $this->checkPageBreak($pdf, 15);
            
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->SetFillColor(66, 139, 202);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->Cell(145, 8, 'TOTAL KESELURUHAN', 1, 0, 'R', 1);
            $pdf->Cell(52, 8, number_format($total_pembelian), 1, 0, 'R', 1);
            $pdf->Cell(35, 8, '', 1, 1, 'C', 1);
        }

        $this->addComplexSignature($pdf);
        $pdf->Output('laporan_membership_' . date('Y-m-d') . '.pdf', 'I');
    }

    private function generatePDFPenjualanHarian($transaksi, $tanggal)
    {
        $pdf = $this->createPDF('LAPORAN PENJUALAN HARIAN', 'Daftar Transaksi Penjualan per Hari');

        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Ln(2);

        if (empty($transaksi)) {
            $pdf->SetFont('helvetica', '', 12);
            $pdf->Cell(0, 40, 'Tidak ada data transaksi dalam periode yang dipilih', 0, 1, 'C');
        } else {
            $this->checkPageBreak($pdf, 50);
            
            $startX = ($pdf->GetPageWidth() - 210) / 2;

            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->SetFillColor(66, 139, 202);
            $pdf->SetTextColor(255, 255, 255);

            $pdf->SetX($startX);
            $pdf->Cell(15, 8, 'No', 1, 0, 'C', 1);
            $pdf->Cell(55, 8, 'Nama', 1, 0, 'C', 1);
            $pdf->Cell(40, 8, 'Total', 1, 0, 'C', 1);
            $pdf->Cell(60, 8, 'Alamat', 1, 0, 'C', 1);
            $pdf->Cell(40, 8, 'Mobile Phone', 1, 1, 'C', 1);

            $pdf->SetFont('helvetica', '', 8);
            $pdf->SetTextColor(0, 0, 0);
            $no = 1;
            $total_penjualan = 0;

            foreach ($transaksi as $row) {
                $this->checkPageBreak($pdf, 15);
                
                $fill = ($no % 2 == 0) ? 1 : 0;
                $pdf->SetFillColor(240, 240, 240);
                $pdf->SetX($startX);
                $pdf->Cell(15, 6, $no++, 1, 0, 'C', $fill);
                $pdf->Cell(55, 6, substr($row['nama_customer'], 0, 25), 1, 0, 'L', $fill);
                $pdf->Cell(40, 6, number_format($row['total_bayar']), 1, 0, 'R', $fill);
                $pdf->Cell(60, 6, substr($row['alamat'], 0, 28), 1, 0, 'L', $fill);
                $pdf->Cell(40, 6, $row['no_telepon'], 1, 1, 'C', $fill);

                $total_penjualan += $row['total_bayar'];
            }

            $this->checkPageBreak($pdf, 15);
            
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->SetFillColor(66, 139, 202);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->SetX($startX);
            $pdf->Cell(170, 8, 'TOTAL PENJUALAN HARIAN', 1, 0, 'R', 1);
            $pdf->Cell(40, 8, number_format($total_penjualan), 1, 1, 'R', 1);
        }

        $this->addComplexSignature($pdf);
        $pdf->Output('laporan_penjualan_harian_' . $tanggal . '.pdf', 'I');
    }

    private function generatePDFPenjualanBulanan($penjualan, $bulan, $tahun)
    {
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $pdf = $this->createPDF('LAPORAN PENJUALAN BULANAN', 'Rekap Penjualan per Bulan');

        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Ln(2);

        if (empty($penjualan)) {
            $pdf->SetFont('helvetica', '', 12);
            $pdf->Cell(0, 40, 'Tidak ada data penjualan dalam periode yang dipilih', 0, 1, 'C');
        } else {
            $this->checkPageBreak($pdf, 50);
            
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->SetFillColor(66, 139, 202);
            $pdf->SetTextColor(255, 255, 255);

            $pdf->Cell(15, 8, 'No', 1, 0, 'C', 1);
            $pdf->Cell(45, 8, 'Tanggal', 1, 0, 'C', 1);
            $pdf->Cell(45, 8, 'Total Transaksi', 1, 0, 'C', 1);
            $pdf->Cell(60, 8, 'Total Pendapatan', 1, 0, 'C', 1);
            $pdf->Cell(40, 8, 'Total Customer', 1, 1, 'C', 1);

            $pdf->SetFont('helvetica', '', 8);
            $pdf->SetTextColor(0, 0, 0);
            $no = 1;
            $total_transaksi = 0;
            $total_pendapatan = 0;
            $total_customer = 0;

            foreach ($penjualan as $row) {
                $this->checkPageBreak($pdf, 15);
                
                $fill = ($no % 2 == 0) ? 1 : 0;
                $pdf->SetFillColor(240, 240, 240);

                $pdf->Cell(15, 6, $no++, 1, 0, 'C', $fill);
                $pdf->Cell(45, 6, date('d-m-Y', strtotime($row['tanggal'])), 1, 0, 'C', $fill);
                $pdf->Cell(45, 6, $row['total_transaksi'], 1, 0, 'C', $fill);
                $pdf->Cell(60, 6, number_format($row['total_pendapatan']), 1, 0, 'R', $fill);
                $pdf->Cell(40, 6, $row['total_customer'], 1, 1, 'C', $fill);

                $total_transaksi += $row['total_transaksi'];
                $total_pendapatan += $row['total_pendapatan'];
                $total_customer += $row['total_customer'];
            }

            $this->checkPageBreak($pdf, 15);
            
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->SetFillColor(66, 139, 202);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->Cell(60, 8, 'TOTAL BULANAN', 1, 0, 'R', 1);
            $pdf->Cell(45, 8, $total_transaksi, 1, 0, 'C', 1);
            $pdf->Cell(60, 8, number_format($total_pendapatan), 1, 0, 'R', 1);
            $pdf->Cell(40, 8, $total_customer, 1, 1, 'C', 1);
        }

        $this->addComplexSignature($pdf);
        $pdf->Output('laporan_penjualan_bulanan_' . $bulan . '_' . $tahun . '.pdf', 'I');
    }

    private function generatePDFPenjualanTahunan($penjualan, $tahun)
    {
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $pdf = $this->createPDF('LAPORAN PENJUALAN TAHUNAN', 'Rekap Penjualan per Tahun');

        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Ln(2);

        if (empty($penjualan)) {
            $pdf->SetFont('helvetica', '', 12);
            $pdf->Cell(0, 40, 'Tidak ada data penjualan dalam periode yang dipilih', 0, 1, 'C');
        } else {
            $this->checkPageBreak($pdf, 50);
            
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->SetFillColor(66, 139, 202);
            $pdf->SetTextColor(255, 255, 255);

            $pdf->Cell(15, 8, 'No', 1, 0, 'C', 1);
            $pdf->Cell(50, 8, 'Bulan', 1, 0, 'C', 1);
            $pdf->Cell(45, 8, 'Total Transaksi', 1, 0, 'C', 1);
            $pdf->Cell(60, 8, 'Total Pendapatan', 1, 0, 'C', 1);
            $pdf->Cell(35, 8, 'Total Customer', 1, 1, 'C', 1);

            $pdf->SetFont('helvetica', '', 8);
            $pdf->SetTextColor(0, 0, 0);
            $no = 1;
            $total_transaksi = 0;
            $total_pendapatan = 0;
            $total_customer = 0;

            foreach ($penjualan as $row) {
                $this->checkPageBreak($pdf, 15);
                
                $fill = ($no % 2 == 0) ? 1 : 0;
                $pdf->SetFillColor(240, 240, 240);

                $pdf->Cell(15, 6, $no++, 1, 0, 'C', $fill);
                $pdf->Cell(50, 6, $namaBulan[$row['bulan']], 1, 0, 'L', $fill);
                $pdf->Cell(45, 6, $row['total_transaksi'], 1, 0, 'C', $fill);
                $pdf->Cell(60, 6, number_format($row['total_pendapatan']), 1, 0, 'R', $fill);
                $pdf->Cell(35, 6, $row['total_customer'], 1, 1, 'C', $fill);

                $total_transaksi += $row['total_transaksi'];
                $total_pendapatan += $row['total_pendapatan'];
                $total_customer += $row['total_customer'];
            }

            $this->checkPageBreak($pdf, 15);
            
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->SetFillColor(66, 139, 202);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->Cell(65, 8, 'TOTAL TAHUNAN', 1, 0, 'R', 1);
            $pdf->Cell(45, 8, $total_transaksi, 1, 0, 'C', 1);
            $pdf->Cell(60, 8, number_format($total_pendapatan), 1, 0, 'R', 1);
            $pdf->Cell(35, 8, $total_customer, 1, 1, 'C', 1);
        }

        $this->addComplexSignature($pdf);
        $pdf->Output('laporan_penjualan_tahunan_' . $tahun . '.pdf', 'I');
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
            
            // Garis di bawah informasi perusahaan untuk halaman baru
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
        $pdf->Cell(0, 6, "(_____________________)", 0, 1, 'L');
        
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(180, 6, '', 0, 0);
        $pdf->Cell(0, 6, "Nama & Tanda Tangan", 0, 1, 'L');
        
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', 'I', 8);
        $pdf->Cell(0, 4, 'Dokumen ini telah ditandatangani secara elektronik pada: ' . date('d/m/Y H:i:s'), 0, 1, 'C');
    }
}
?>