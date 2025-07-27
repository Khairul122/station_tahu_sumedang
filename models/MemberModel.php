<?php
class MemberModel {
    private $conn;
    
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }
    
    public function getMemberProfile($userId) {
        $stmt = $this->conn->prepare("
            SELECT 
                u.user_id,
                u.username,
                u.nama_lengkap,
                u.email,
                u.tanggal_dibuat,
                u.terakhir_login,
                c.customer_id,
                c.nama_customer,
                c.no_telepon,
                c.alamat,
                c.membership_id,
                c.total_pembelian,
                c.total_poin,
                c.tanggal_daftar,
                c.status_aktif,
                m.nama_membership,
                m.minimal_pembelian,
                m.diskon_persen,
                m.poin_per_pembelian,
                m.benefit
            FROM users u
            JOIN customers c ON u.user_id = c.user_id
            JOIN membership m ON c.membership_id = m.membership_id
            WHERE u.user_id = ? AND c.status_aktif = 'aktif'
        ");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
    
    public function getAllTransaksi($customerId, $limit = 20, $offset = 0, $startDate = null, $endDate = null) {
        $whereClause = "WHERE t.customer_id = ?";
        $params = [$customerId];
        $types = "i";
        
        if ($startDate && $endDate) {
            $whereClause .= " AND DATE(t.tanggal_transaksi) BETWEEN ? AND ?";
            $params[] = $startDate;
            $params[] = $endDate;
            $types .= "ss";
        }
        
        $stmt = $this->conn->prepare("
            SELECT 
                t.transaksi_id,
                t.tanggal_transaksi,
                t.total_sebelum_diskon,
                t.diskon_membership,
                t.total_bayar,
                t.poin_didapat,
                t.metode_pembayaran,
                COUNT(dt.detail_id) as total_item,
                SUM(dt.jumlah) as total_quantity
            FROM transaksi t
            LEFT JOIN detail_transaksi dt ON t.transaksi_id = dt.transaksi_id
            $whereClause
            GROUP BY t.transaksi_id
            ORDER BY t.tanggal_transaksi DESC
            LIMIT ? OFFSET ?
        ");
        
        $params[] = $limit;
        $params[] = $offset;
        $types .= "ii";
        
        $stmt->bind_param($types, ...$params);
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
    
    public function getTransaksiCount($customerId, $startDate = null, $endDate = null) {
        $whereClause = "WHERE customer_id = ?";
        $params = [$customerId];
        $types = "i";
        
        if ($startDate && $endDate) {
            $whereClause .= " AND DATE(tanggal_transaksi) BETWEEN ? AND ?";
            $params[] = $startDate;
            $params[] = $endDate;
            $types .= "ss";
        }
        
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM transaksi $whereClause");
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc()['total'];
        }
        return 0;
    }
    
    public function getTransaksiDetail($transaksiId, $customerId) {
        $stmt = $this->conn->prepare("
            SELECT 
                t.*,
                c.nama_customer,
                c.no_telepon,
                m.nama_membership
            FROM transaksi t
            JOIN customers c ON t.customer_id = c.customer_id
            JOIN membership m ON c.membership_id = m.membership_id
            WHERE t.transaksi_id = ? AND t.customer_id = ?
        ");
        $stmt->bind_param("ii", $transaksiId, $customerId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            $transaksi = $result->fetch_assoc();
            
            $detailStmt = $this->conn->prepare("
                SELECT 
                    dt.*,
                    p.nama_produk,
                    p.kategori
                FROM detail_transaksi dt
                JOIN produk p ON dt.produk_id = p.produk_id
                WHERE dt.transaksi_id = ?
                ORDER BY dt.detail_id
            ");
            $detailStmt->bind_param("i", $transaksiId);
            $detailStmt->execute();
            $detailResult = $detailStmt->get_result();
            
            $details = [];
            if ($detailResult) {
                while ($row = $detailResult->fetch_assoc()) {
                    $details[] = $row;
                }
            }
            
            $transaksi['details'] = $details;
            return $transaksi;
        }
        
        return null;
    }
    
    public function getPoinHistory($customerId, $limit = 20, $offset = 0, $startDate = null, $endDate = null) {
        $whereClause = "WHERE c.customer_id = ?";
        $params = [$customerId];
        $types = "i";
        
        if ($startDate && $endDate) {
            $whereClause .= " AND DATE(t.tanggal_transaksi) BETWEEN ? AND ?";
            $params[] = $startDate;
            $params[] = $endDate;
            $types .= "ss";
        }
        
        $stmt = $this->conn->prepare("
            SELECT 
                t.transaksi_id,
                t.tanggal_transaksi,
                t.total_bayar,
                t.poin_didapat,
                'earned' as type,
                'Pembelian produk' as keterangan
            FROM transaksi t
            JOIN customers c ON t.customer_id = c.customer_id
            $whereClause AND t.poin_didapat > 0
            ORDER BY t.tanggal_transaksi DESC
            LIMIT ? OFFSET ?
        ");
        
        $params[] = $limit;
        $params[] = $offset;
        $types .= "ii";
        
        $stmt->bind_param($types, ...$params);
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
    
    public function getPoinStats($customerId) {
        $stmt = $this->conn->prepare("
            SELECT 
                c.total_poin,
                COALESCE(SUM(t.poin_didapat), 0) as total_poin_earned,
                COUNT(t.transaksi_id) as total_transaksi_with_poin,
                COALESCE(AVG(t.poin_didapat), 0) as rata_poin_per_transaksi
            FROM customers c
            LEFT JOIN transaksi t ON c.customer_id = t.customer_id AND t.poin_didapat > 0
            WHERE c.customer_id = ?
            GROUP BY c.customer_id
        ");
        $stmt->bind_param("i", $customerId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
    
    public function getMonthlyTransaksiStats($customerId, $months = 12) {
        $stmt = $this->conn->prepare("
            SELECT 
                YEAR(t.tanggal_transaksi) as tahun,
                MONTH(t.tanggal_transaksi) as bulan,
                COUNT(t.transaksi_id) as total_transaksi,
                SUM(t.total_bayar) as total_spending,
                SUM(t.poin_didapat) as total_poin
            FROM transaksi t
            WHERE t.customer_id = ? 
            AND t.tanggal_transaksi >= DATE_SUB(CURDATE(), INTERVAL ? MONTH)
            GROUP BY YEAR(t.tanggal_transaksi), MONTH(t.tanggal_transaksi)
            ORDER BY tahun DESC, bulan DESC
        ");
        $stmt->bind_param("ii", $customerId, $months);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $monthlyStats = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $monthlyStats[] = [
                    'month' => date('M Y', mktime(0, 0, 0, $row['bulan'], 1, $row['tahun'])),
                    'year' => $row['tahun'],
                    'month_num' => $row['bulan'],
                    'total_transaksi' => $row['total_transaksi'],
                    'total_spending' => $row['total_spending'],
                    'total_poin' => $row['total_poin']
                ];
            }
        }
        
        return $monthlyStats;
    }
    
    public function getFavoriteProducts($customerId, $limit = 10) {
        $stmt = $this->conn->prepare("
            SELECT 
                p.produk_id,
                p.nama_produk,
                p.harga,
                p.kategori,
                SUM(dt.jumlah) as total_dibeli,
                COUNT(dt.detail_id) as frekuensi_beli,
                SUM(dt.subtotal) as total_spending,
                SUM(dt.total_poin_item) as total_poin_from_product
            FROM detail_transaksi dt
            JOIN produk p ON dt.produk_id = p.produk_id
            JOIN transaksi t ON dt.transaksi_id = t.transaksi_id
            WHERE t.customer_id = ?
            GROUP BY dt.produk_id
            ORDER BY total_dibeli DESC, frekuensi_beli DESC
            LIMIT ?
        ");
        $stmt->bind_param("ii", $customerId, $limit);
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
    
    public function getNextMembershipProgress($customerId) {
        $stmt = $this->conn->prepare("
            SELECT 
                c.total_pembelian,
                c.membership_id as current_membership_id,
                m1.nama_membership as current_membership,
                m1.minimal_pembelian as current_minimal,
                m1.diskon_persen as current_diskon,
                m1.poin_per_pembelian as current_multiplier,
                m2.membership_id as next_membership_id,
                m2.nama_membership as next_membership,
                m2.minimal_pembelian as next_minimal,
                m2.diskon_persen as next_diskon,
                m2.poin_per_pembelian as next_multiplier,
                GREATEST(0, m2.minimal_pembelian - c.total_pembelian) as sisa_pembelian
            FROM customers c
            JOIN membership m1 ON c.membership_id = m1.membership_id
            LEFT JOIN membership m2 ON m2.membership_id = c.membership_id + 1
            WHERE c.customer_id = ?
        ");
        $stmt->bind_param("i", $customerId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    public function getMembershipHistory($customerId) {
        $stmt = $this->conn->prepare("
            SELECT 
                ac.aktivitas_id,
                ac.tanggal_aktivitas,
                ac.catatan
            FROM aktivitas_customer ac
            WHERE ac.customer_id = ? 
            AND ac.jenis_aktivitas = 'follow_up'
            AND ac.catatan LIKE '%tier%'
            ORDER BY ac.tanggal_aktivitas DESC
        ");
        $stmt->bind_param("i", $customerId);
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
    
    public function updateProfile($userId, $profileData) {
        $this->conn->begin_transaction();
        
        try {
            $userStmt = $this->conn->prepare("
                UPDATE users 
                SET nama_lengkap = ?, email = ? 
                WHERE user_id = ?
            ");
            $userStmt->bind_param("ssi", 
                $profileData['nama_lengkap'], 
                $profileData['email'], 
                $userId
            );
            $userStmt->execute();
            
            $customerStmt = $this->conn->prepare("
                UPDATE customers 
                SET nama_customer = ?, email = ?, no_telepon = ?, alamat = ? 
                WHERE user_id = ?
            ");
            $customerStmt->bind_param("ssssi", 
                $profileData['nama_lengkap'], 
                $profileData['email'], 
                $profileData['no_telepon'], 
                $profileData['alamat'], 
                $userId
            );
            $customerStmt->execute();
            
            $this->conn->commit();
            
            return [
                'success' => true,
                'message' => 'Profil berhasil diperbarui'
            ];
            
        } catch (Exception $e) {
            $this->conn->rollback();
            return [
                'success' => false,
                'message' => 'Gagal memperbarui profil: ' . $e->getMessage()
            ];
        }
    }
    
    public function changePassword($userId, $oldPassword, $newPassword) {
        $stmt = $this->conn->prepare("SELECT password FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            if ($oldPassword === $user['password']) {
                $updateStmt = $this->conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
                $updateStmt->bind_param("si", $newPassword, $userId);
                $updateStmt->execute();
                
                return [
                    'success' => true,
                    'message' => 'Password berhasil diubah'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Password lama salah'
                ];
            }
        }
        
        return [
            'success' => false,
            'message' => 'User tidak ditemukan'
        ];
    }
    
    public function getTransaksiSummary($customerId) {
        $stmt = $this->conn->prepare("
            SELECT 
                COUNT(t.transaksi_id) as total_transaksi,
                COALESCE(SUM(t.total_bayar), 0) as total_spending,
                COALESCE(SUM(t.poin_didapat), 0) as total_poin_earned,
                COALESCE(SUM(t.diskon_membership), 0) as total_hemat,
                COALESCE(AVG(t.total_bayar), 0) as rata_spending_per_transaksi,
                MAX(t.tanggal_transaksi) as transaksi_terakhir
            FROM transaksi t
            WHERE t.customer_id = ?
        ");
        $stmt->bind_param("i", $customerId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
    
    public function searchTransaksi($customerId, $keyword, $limit = 20, $offset = 0) {
        $searchTerm = "%$keyword%";
        $stmt = $this->conn->prepare("
            SELECT DISTINCT
                t.transaksi_id,
                t.tanggal_transaksi,
                t.total_sebelum_diskon,
                t.diskon_membership,
                t.total_bayar,
                t.poin_didapat,
                t.metode_pembayaran,
                COUNT(dt.detail_id) as total_item
            FROM transaksi t
            LEFT JOIN detail_transaksi dt ON t.transaksi_id = dt.transaksi_id
            LEFT JOIN produk p ON dt.produk_id = p.produk_id
            WHERE t.customer_id = ? 
            AND (
                t.transaksi_id LIKE ? OR 
                t.metode_pembayaran LIKE ? OR
                p.nama_produk LIKE ? OR
                p.kategori LIKE ?
            )
            GROUP BY t.transaksi_id
            ORDER BY t.tanggal_transaksi DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->bind_param("issssii", $customerId, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $limit, $offset);
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
}
?>