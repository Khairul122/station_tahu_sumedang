<?php
class DashboardModel {
    private $conn;
    
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }
    
    public function getAdminStats() {
        $stats = [];
        
        $query = "SELECT COUNT(*) as total FROM customers WHERE status_aktif = 'aktif'";
        $result = $this->conn->query($query);
        $stats['total_customers'] = $result ? $result->fetch_assoc()['total'] : 0;
        
        $query = "SELECT COUNT(*) as total FROM transaksi";
        $result = $this->conn->query($query);
        $stats['total_transaksi'] = $result ? $result->fetch_assoc()['total'] : 0;
        
        $query = "SELECT COUNT(*) as total FROM produk";
        $result = $this->conn->query($query);
        $stats['total_produk'] = $result ? $result->fetch_assoc()['total'] : 0;
        
        $query = "SELECT SUM(total_bayar) as total FROM transaksi";
        $result = $this->conn->query($query);
        $row = $result ? $result->fetch_assoc() : null;
        $stats['total_pendapatan'] = $row ? ($row['total'] ?? 0) : 0;
        
        return $stats;
    }
    
    public function getPimpinanStats() {
        $stats = [];
        
        $query = "SELECT SUM(total_bayar) as total FROM transaksi WHERE DATE(tanggal_transaksi) = CURDATE()";
        $result = $this->conn->query($query);
        $row = $result ? $result->fetch_assoc() : null;
        $stats['pendapatan_hari_ini'] = $row ? ($row['total'] ?? 0) : 0;
        
        $query = "SELECT SUM(total_bayar) as total FROM transaksi WHERE MONTH(tanggal_transaksi) = MONTH(CURDATE()) AND YEAR(tanggal_transaksi) = YEAR(CURDATE())";
        $result = $this->conn->query($query);
        $row = $result ? $result->fetch_assoc() : null;
        $stats['pendapatan_bulan_ini'] = $row ? ($row['total'] ?? 0) : 0;
        
        $query = "SELECT COUNT(*) as total FROM customers WHERE status_aktif = 'aktif'";
        $result = $this->conn->query($query);
        $stats['customer_aktif'] = $result ? $result->fetch_assoc()['total'] : 0;
        
        $query = "SELECT COUNT(*) as total FROM customers c JOIN membership m ON c.membership_id = m.membership_id WHERE m.nama_membership IN ('Gold', 'Platinum')";
        $result = $this->conn->query($query);
        $stats['member_premium'] = $result ? $result->fetch_assoc()['total'] : 0;
        
        return $stats;
    }
    
    public function getMemberStats($userId) {
        $stats = [];
        
        $query = "SELECT 
                    c.nama_customer,
                    c.total_poin,
                    c.total_pembelian,
                    m.nama_membership,
                    m.diskon_persen,
                    m.poin_per_pembelian
                  FROM customers c
                  JOIN membership m ON c.membership_id = m.membership_id
                  WHERE c.user_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            $stats = $result->fetch_assoc();
        }
        
        return $stats;
    }
    
    public function getMemberTransaksiHistory($userId, $limit = 10) {
        $query = "SELECT 
                    t.transaksi_id,
                    t.tanggal_transaksi,
                    t.total_bayar,
                    t.poin_didapat,
                    t.metode_pembayaran,
                    COUNT(dt.detail_id) as total_item
                  FROM transaksi t
                  JOIN customers c ON t.customer_id = c.customer_id
                  LEFT JOIN detail_transaksi dt ON t.transaksi_id = dt.transaksi_id
                  WHERE c.user_id = ?
                  GROUP BY t.transaksi_id
                  ORDER BY t.tanggal_transaksi DESC
                  LIMIT ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $userId, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $history = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $history[] = $row;
            }
        }
        
        return $history;
    }
    
    public function getMemberFavoriteProducts($userId, $limit = 5) {
        $query = "SELECT 
                    p.nama_produk,
                    p.harga,
                    p.kategori,
                    SUM(dt.jumlah) as total_dibeli,
                    COUNT(dt.detail_id) as frekuensi_beli
                  FROM detail_transaksi dt
                  JOIN produk p ON dt.produk_id = p.produk_id
                  JOIN transaksi t ON dt.transaksi_id = t.transaksi_id
                  JOIN customers c ON t.customer_id = c.customer_id
                  WHERE c.user_id = ?
                  GROUP BY dt.produk_id
                  ORDER BY total_dibeli DESC, frekuensi_beli DESC
                  LIMIT ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $userId, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $products = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
        }
        
        return $products;
    }
    
    public function getMemberPoinHistory($userId, $limit = 10) {
        $query = "SELECT 
                    t.transaksi_id,
                    t.tanggal_transaksi,
                    t.total_bayar,
                    t.poin_didapat,
                    'pembelian' as type
                  FROM transaksi t
                  JOIN customers c ON t.customer_id = c.customer_id
                  WHERE c.user_id = ? AND t.poin_didapat > 0
                  ORDER BY t.tanggal_transaksi DESC
                  LIMIT ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $userId, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $poinHistory = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $poinHistory[] = $row;
            }
        }
        
        return $poinHistory;
    }
    
    public function getMemberNextMembership($userId) {
        $query = "SELECT 
                    c.total_pembelian,
                    c.membership_id as current_membership_id,
                    m1.nama_membership as current_membership,
                    m2.nama_membership as next_membership,
                    m2.minimal_pembelian as next_minimal,
                    (m2.minimal_pembelian - c.total_pembelian) as sisa_pembelian
                  FROM customers c
                  JOIN membership m1 ON c.membership_id = m1.membership_id
                  LEFT JOIN membership m2 ON m2.membership_id = c.membership_id + 1
                  WHERE c.user_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    public function getMemberMonthlySpending($userId) {
        $query = "SELECT 
                    MONTH(t.tanggal_transaksi) as bulan,
                    YEAR(t.tanggal_transaksi) as tahun,
                    SUM(t.total_bayar) as total_spending,
                    COUNT(t.transaksi_id) as total_transaksi
                  FROM transaksi t
                  JOIN customers c ON t.customer_id = c.customer_id
                  WHERE c.user_id = ? AND t.tanggal_transaksi >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
                  GROUP BY YEAR(t.tanggal_transaksi), MONTH(t.tanggal_transaksi)
                  ORDER BY tahun DESC, bulan DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $monthlyData = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $monthlyData[] = [
                    'month' => date('M Y', mktime(0, 0, 0, $row['bulan'], 1, $row['tahun'])),
                    'spending' => $row['total_spending'],
                    'transactions' => $row['total_transaksi']
                ];
            }
        }
        
        return $monthlyData;
    }
    
    public function getMemberRecommendations($userId, $limit = 5) {
        $query = "SELECT 
                    p.produk_id,
                    p.nama_produk,
                    p.harga,
                    p.kategori,
                    COUNT(dt.detail_id) as popularity
                  FROM produk p
                  JOIN detail_transaksi dt ON p.produk_id = dt.produk_id
                  JOIN transaksi t ON dt.transaksi_id = t.transaksi_id
                  JOIN customers c ON t.customer_id = c.customer_id
                  WHERE c.membership_id = (
                      SELECT membership_id 
                      FROM customers 
                      WHERE user_id = ?
                  )
                  AND p.produk_id NOT IN (
                      SELECT DISTINCT dt2.produk_id
                      FROM detail_transaksi dt2
                      JOIN transaksi t2 ON dt2.transaksi_id = t2.transaksi_id
                      JOIN customers c2 ON t2.customer_id = c2.customer_id
                      WHERE c2.user_id = ?
                  )
                  GROUP BY p.produk_id
                  ORDER BY popularity DESC
                  LIMIT ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iii", $userId, $userId, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $recommendations = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $recommendations[] = $row;
            }
        }
        
        return $recommendations;
    }
    
    public function getMemberTotalSavings($userId) {
        $query = "SELECT 
                    SUM(t.diskon_membership) as total_hemat,
                    SUM(t.poin_didapat) as total_poin_earned
                  FROM transaksi t
                  JOIN customers c ON t.customer_id = c.customer_id
                  WHERE c.user_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return ['total_hemat' => 0, 'total_poin_earned' => 0];
    }
    
    public function getRecentActivities() {
        $activities = [];
        
        $query = "SELECT 
                    c.nama_customer,
                    t.tanggal_transaksi,
                    t.total_bayar,
                    'transaksi' as type
                  FROM transaksi t
                  JOIN customers c ON t.customer_id = c.customer_id
                  ORDER BY t.tanggal_transaksi DESC
                  LIMIT 5";
        
        $result = $this->conn->query($query);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $activities[] = [
                    'type' => 'transaksi',
                    'customer' => $row['nama_customer'],
                    'amount' => $row['total_bayar'],
                    'time' => $row['tanggal_transaksi']
                ];
            }
        }
        
        $query = "SELECT 
                    c.nama_customer,
                    c.tanggal_daftar,
                    'customer_baru' as type
                  FROM customers c
                  ORDER BY c.tanggal_daftar DESC
                  LIMIT 3";
        
        $result = $this->conn->query($query);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $activities[] = [
                    'type' => 'customer_baru',
                    'customer' => $row['nama_customer'],
                    'time' => $row['tanggal_daftar']
                ];
            }
        }
        
        if (!empty($activities)) {
            usort($activities, function($a, $b) {
                return strtotime($b['time']) - strtotime($a['time']);
            });
        }
        
        return array_slice($activities, 0, 5);
    }
    
    public function getSalesChart() {
        $chartData = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $dayName = date('D', strtotime("-$i days"));
            
            $query = "SELECT SUM(total_bayar) as total FROM transaksi WHERE DATE(tanggal_transaksi) = '$date'";
            $result = $this->conn->query($query);
            $row = $result ? $result->fetch_assoc() : null;
            $total = $row ? ($row['total'] ?? 0) : 0;
            
            $chartData[] = [
                'day' => $dayName,
                'total' => $total
            ];
        }
        
        return $chartData;
    }
    
    public function getTopProducts() {
        $query = "SELECT 
                    p.nama_produk,
                    SUM(dt.jumlah) as total_terjual
                  FROM detail_transaksi dt
                  JOIN produk p ON dt.produk_id = p.produk_id
                  GROUP BY dt.produk_id
                  ORDER BY total_terjual DESC
                  LIMIT 5";
        
        $result = $this->conn->query($query);
        $products = [];
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
        }
        
        return $products;
    }
    
    public function getMembershipStats() {
        $query = "SELECT 
                    m.nama_membership,
                    COUNT(c.customer_id) as total_member
                  FROM membership m
                  LEFT JOIN customers c ON m.membership_id = c.membership_id
                  GROUP BY m.membership_id
                  ORDER BY m.membership_id";
        
        $result = $this->conn->query($query);
        $membershipStats = [];
        $totalMembers = 0;
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $membershipStats[] = $row;
                $totalMembers += $row['total_member'];
            }
        }
        
        foreach ($membershipStats as &$stat) {
            $stat['percentage'] = $totalMembers > 0 ? round(($stat['total_member'] / $totalMembers) * 100, 1) : 0;
        }
        
        return $membershipStats;
    }
    
    public function getTransaksiToday() {
        $query = "SELECT COUNT(*) as total FROM transaksi WHERE DATE(tanggal_transaksi) = CURDATE()";
        $result = $this->conn->query($query);
        return $result ? $result->fetch_assoc()['total'] : 0;
    }
    
    public function getNewCustomersToday() {
        $query = "SELECT COUNT(*) as total FROM customers WHERE DATE(tanggal_daftar) = CURDATE()";
        $result = $this->conn->query($query);
        return $result ? $result->fetch_assoc()['total'] : 0;
    }
    
    public function getStokRendah() {
        $query = "SELECT nama_produk, stok FROM produk WHERE stok < 20 ORDER BY stok ASC";
        $result = $this->conn->query($query);
        $products = [];
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
        }
        
        return $products;
    }
    
    public function getCustomerBirthday() {
        $query = "SELECT nama_customer FROM customers WHERE DATE_FORMAT(tanggal_daftar, '%m-%d') = DATE_FORMAT(CURDATE(), '%m-%d')";
        $result = $this->conn->query($query);
        $customers = [];
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $customers[] = $row;
            }
        }
        
        return $customers;
    }
}
?>