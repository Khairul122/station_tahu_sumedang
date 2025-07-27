<?php
class DataMemberModel {
    private $conn;
    
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }
    
    public function getAllMembers() {
        $sql = "SELECT 
                    c.customer_id,
                    c.user_id,
                    c.nama_customer,
                    c.no_telepon,
                    c.alamat,
                    c.email,
                    c.total_pembelian,
                    c.total_poin,
                    c.tanggal_daftar,
                    c.status_aktif,
                    m.nama_membership,
                    u.username
                FROM customers c
                LEFT JOIN membership m ON c.membership_id = m.membership_id
                LEFT JOIN users u ON c.user_id = u.user_id
                WHERE c.user_id IS NOT NULL
                ORDER BY c.customer_id DESC";
        
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getMemberById($customerId) {
        $sql = "SELECT 
                    c.*,
                    m.nama_membership,
                    u.username
                FROM customers c
                LEFT JOIN membership m ON c.membership_id = m.membership_id
                LEFT JOIN users u ON c.user_id = u.user_id
                WHERE c.customer_id = ? AND c.user_id IS NOT NULL";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $customerId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }
    
    public function deleteMember($customerId) {
        $this->conn->begin_transaction();
        
        try {
            $memberData = $this->getMemberById($customerId);
            if (!$memberData) {
                throw new Exception("Member tidak ditemukan");
            }
            
            $sql = "DELETE FROM aktivitas_customer WHERE customer_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $customerId);
            $stmt->execute();
            
            $sql = "DELETE FROM tukar_poin WHERE customer_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $customerId);
            $stmt->execute();
            
            $sql = "DELETE FROM detail_transaksi WHERE transaksi_id IN (SELECT transaksi_id FROM transaksi WHERE customer_id = ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $customerId);
            $stmt->execute();
            
            $sql = "DELETE FROM transaksi WHERE customer_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $customerId);
            $stmt->execute();
            
            $sql = "DELETE FROM customers WHERE customer_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $customerId);
            $stmt->execute();
            
            if ($memberData['user_id']) {
                $sql = "DELETE FROM users WHERE user_id = ?";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("i", $memberData['user_id']);
                $stmt->execute();
            }
            
            $this->conn->commit();
            
            return [
                'success' => true,
                'message' => 'Member berhasil dihapus'
            ];
            
        } catch (Exception $e) {
            $this->conn->rollback();
            return [
                'success' => false,
                'message' => 'Gagal menghapus member: ' . $e->getMessage()
            ];
        }
    }
}