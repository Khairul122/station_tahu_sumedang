<?php
class LaporanModel {
    private $conn;
    
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }
    
    public function getLaporanProduk() {
        $query = "
            SELECT 
                p.produk_id,
                p.nama_produk,
                p.kategori,
                p.harga,
                p.stok,
                COALESCE(SUM(dt.jumlah), 0) as total_terjual,
                COALESCE(SUM(dt.subtotal), 0) as total_pendapatan
            FROM produk p
            LEFT JOIN detail_transaksi dt ON p.produk_id = dt.produk_id
            GROUP BY p.produk_id
            ORDER BY p.nama_produk
        ";
        
        $result = $this->conn->query($query);
        $produk = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $produk[] = $row;
            }
        }
        
        return $produk;
    }
    
    public function getLaporanMembership() {
        $query = "
            SELECT 
                m.membership_id,
                m.nama_membership,
                m.diskon_persen,
                COUNT(c.customer_id) as total_member,
                COALESCE(SUM(c.total_pembelian), 0) as total_pembelian_member,
                COALESCE(SUM(c.total_poin), 0) as total_poin_member
            FROM membership m
            LEFT JOIN customers c ON m.membership_id = c.membership_id AND c.status_aktif = 'aktif'
            GROUP BY m.membership_id
            ORDER BY m.membership_id
        ";
        
        $result = $this->conn->query($query);
        $membership = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $membership[] = $row;
            }
        }
        
        return $membership;
    }
    
    public function getLaporanPenjualanHarian($tanggal) {
        $query = "
            SELECT 
                t.transaksi_id,
                t.tanggal_transaksi,
                c.nama_customer,
                c.alamat,
                c.no_telepon,
                t.total_bayar,
                u.nama_lengkap as owner
            FROM transaksi t
            JOIN customers c ON t.customer_id = c.customer_id
            LEFT JOIN users u ON c.user_id = u.user_id
            WHERE DATE(t.tanggal_transaksi) = ?
            ORDER BY t.tanggal_transaksi DESC
        ";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $tanggal);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $transaksi = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $transaksi[] = $row;
            }
        }
        
        return $transaksi;
    }
    
    public function getLaporanPenjualanBulanan($bulan, $tahun) {
        $query = "
            SELECT 
                DATE(t.tanggal_transaksi) as tanggal,
                COUNT(t.transaksi_id) as total_transaksi,
                SUM(t.total_bayar) as total_pendapatan,
                COUNT(DISTINCT t.customer_id) as total_customer
            FROM transaksi t
            WHERE MONTH(t.tanggal_transaksi) = ? AND YEAR(t.tanggal_transaksi) = ?
            GROUP BY DATE(t.tanggal_transaksi)
            ORDER BY tanggal DESC
        ";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $bulan, $tahun);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $penjualan = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $penjualan[] = $row;
            }
        }
        
        return $penjualan;
    }
    
    public function getLaporanPenjualanTahunan($tahun) {
        $query = "
            SELECT 
                MONTH(t.tanggal_transaksi) as bulan,
                COUNT(t.transaksi_id) as total_transaksi,
                SUM(t.total_bayar) as total_pendapatan,
                COUNT(DISTINCT t.customer_id) as total_customer
            FROM transaksi t
            WHERE YEAR(t.tanggal_transaksi) = ?
            GROUP BY MONTH(t.tanggal_transaksi)
            ORDER BY bulan
        ";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $tahun);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $penjualan = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $penjualan[] = $row;
            }
        }
        
        return $penjualan;
    }
}
?>