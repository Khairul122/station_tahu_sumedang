<?php
class LaporanModel {
    private $conn;
    
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }
    
    public function getLaporanProduk($store_id = null) {
        $query = "
            SELECT 
                p.produk_id,
                p.nama_produk,
                p.kategori,
                p.harga,
                p.stok,
                s.nama_store,
                COALESCE(SUM(dt.jumlah), 0) as total_terjual,
                COALESCE(SUM(dt.subtotal), 0) as total_pendapatan
            FROM produk p
            LEFT JOIN store s ON p.store_id = s.id_store
            LEFT JOIN detail_transaksi dt ON p.produk_id = dt.produk_id
        ";
        
        if ($store_id) {
            $query .= " WHERE p.store_id = ?";
        }
        
        $query .= " GROUP BY p.produk_id ORDER BY p.nama_produk";
        
        if ($store_id) {
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $store_id);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $result = $this->conn->query($query);
        }
        
        $produk = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $produk[] = $row;
            }
        }
        
        return $produk;
    }
    
    public function getLaporanMembership($store_id = null) {
        if ($store_id) {
            $query = "
                SELECT 
                    m.membership_id,
                    m.nama_membership,
                    m.diskon_persen,
                    COUNT(DISTINCT c.customer_id) as total_member,
                    COALESCE(SUM(t.total_bayar), 0) as total_pembelian_member,
                    COALESCE(SUM(c.total_poin), 0) as total_poin_member
                FROM membership m
                LEFT JOIN customers c ON m.membership_id = c.membership_id AND c.status_aktif = 'aktif'
                LEFT JOIN transaksi t ON c.customer_id = t.customer_id AND t.store_id = ?
                GROUP BY m.membership_id
                ORDER BY m.membership_id
            ";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $store_id);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
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
        }
        
        $membership = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $membership[] = $row;
            }
        }
        
        return $membership;
    }
    
    public function getLaporanPenjualanHarian($tanggal, $store_id = null) {
        $query = "
            SELECT 
                t.transaksi_id,
                t.tanggal_transaksi,
                c.nama_customer,
                c.alamat,
                c.no_telepon,
                t.total_bayar,
                s.nama_store,
                u.nama_lengkap as owner
            FROM transaksi t
            JOIN customers c ON t.customer_id = c.customer_id
            JOIN store s ON t.store_id = s.id_store
            LEFT JOIN users u ON c.user_id = u.user_id
            WHERE DATE(t.tanggal_transaksi) = ?
        ";
        
        if ($store_id) {
            $query .= " AND t.store_id = ?";
        }
        
        $query .= " ORDER BY t.tanggal_transaksi DESC";
        
        $stmt = $this->conn->prepare($query);
        if ($store_id) {
            $stmt->bind_param("si", $tanggal, $store_id);
        } else {
            $stmt->bind_param("s", $tanggal);
        }
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
    
    public function getLaporanPenjualanBulanan($bulan, $tahun, $store_id = null) {
        $query = "
            SELECT 
                DATE(t.tanggal_transaksi) as tanggal,
                COUNT(t.transaksi_id) as total_transaksi,
                SUM(t.total_bayar) as total_pendapatan,
                COUNT(DISTINCT t.customer_id) as total_customer
            FROM transaksi t
            JOIN store s ON t.store_id = s.id_store
            WHERE MONTH(t.tanggal_transaksi) = ? AND YEAR(t.tanggal_transaksi) = ?
        ";
        
        if ($store_id) {
            $query .= " AND t.store_id = ?";
        }
        
        $query .= " GROUP BY DATE(t.tanggal_transaksi) ORDER BY tanggal DESC";
        
        $stmt = $this->conn->prepare($query);
        if ($store_id) {
            $stmt->bind_param("iii", $bulan, $tahun, $store_id);
        } else {
            $stmt->bind_param("ii", $bulan, $tahun);
        }
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
    
    public function getLaporanPenjualanTahunan($tahun, $store_id = null) {
        $query = "
            SELECT 
                MONTH(t.tanggal_transaksi) as bulan,
                COUNT(t.transaksi_id) as total_transaksi,
                SUM(t.total_bayar) as total_pendapatan,
                COUNT(DISTINCT t.customer_id) as total_customer
            FROM transaksi t
            JOIN store s ON t.store_id = s.id_store
            WHERE YEAR(t.tanggal_transaksi) = ?
        ";
        
        if ($store_id) {
            $query .= " AND t.store_id = ?";
        }
        
        $query .= " GROUP BY MONTH(t.tanggal_transaksi) ORDER BY bulan";
        
        $stmt = $this->conn->prepare($query);
        if ($store_id) {
            $stmt->bind_param("ii", $tahun, $store_id);
        } else {
            $stmt->bind_param("i", $tahun);
        }
        
        if ($stmt === false) {
            die('Prepare failed: ' . $this->conn->error);
        }
        
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
    
    public function getAllStores() {
        $query = "SELECT id_store, nama_store FROM store WHERE status_store = 'aktif' ORDER BY nama_store";
        $result = $this->conn->query($query);
        
        $stores = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $stores[] = $row;
            }
        }
        
        return $stores;
    }
    
    public function getRekapPenjualanPerStore($tahun = null) {
        $query = "
            SELECT 
                s.id_store,
                s.nama_store,
                s.alamat_store,
                s.manajer_store,
                COUNT(t.transaksi_id) as total_transaksi,
                COALESCE(SUM(t.total_bayar), 0) as total_pendapatan,
                COUNT(DISTINCT t.customer_id) as total_customer,
                COUNT(DISTINCT MONTH(t.tanggal_transaksi)) as bulan_aktif
            FROM store s
            LEFT JOIN transaksi t ON s.id_store = t.store_id
        ";
        
        if ($tahun) {
            $query .= " AND YEAR(t.tanggal_transaksi) = ?";
        }
        
        $query .= " WHERE s.status_store = 'aktif' GROUP BY s.id_store ORDER BY total_pendapatan DESC";
        
        if ($tahun) {
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $tahun);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $result = $this->conn->query($query);
        }
        
        $stores = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $stores[] = $row;
            }
        }
        
        return $stores;
    }
}
?>