<?php
class AdminRewardModel {
    private $conn;
    
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }
    
    public function getAllRewards() {
        $query = "SELECT * FROM rewards ORDER BY poin_required ASC";
        $result = $this->conn->query($query);
        
        $rewards = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $rewards[] = $row;
            }
        }
        
        return $rewards;
    }
    
    public function getRewardById($rewardId) {
        $stmt = $this->conn->prepare("SELECT * FROM rewards WHERE reward_id = ?");
        $stmt->bind_param("i", $rewardId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
    
    public function createReward($namaReward, $poinRequired, $stock, $status) {
        $stmt = $this->conn->prepare("INSERT INTO rewards (nama_reward, poin_required, stock, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("siis", $namaReward, $poinRequired, $stock, $status);
        
        if ($stmt->execute()) {
            return [
                'success' => true,
                'message' => 'Reward berhasil ditambahkan',
                'reward_id' => $this->conn->insert_id
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Gagal menambahkan reward: ' . $stmt->error
            ];
        }
    }
    
    public function updateReward($rewardId, $namaReward, $poinRequired, $stock, $status) {
        $stmt = $this->conn->prepare("UPDATE rewards SET nama_reward = ?, poin_required = ?, stock = ?, status = ? WHERE reward_id = ?");
        $stmt->bind_param("siisi", $namaReward, $poinRequired, $stock, $status, $rewardId);
        
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                return [
                    'success' => true,
                    'message' => 'Reward berhasil diupdate'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Tidak ada perubahan data atau reward tidak ditemukan'
                ];
            }
        } else {
            return [
                'success' => false,
                'message' => 'Gagal mengupdate reward: ' . $stmt->error
            ];
        }
    }
    
    public function deleteReward($rewardId) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM tukar_poin WHERE reward_id = ?");
        $stmt->bind_param("i", $rewardId);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        
        if ($data['count'] > 0) {
            return [
                'success' => false,
                'message' => 'Tidak dapat menghapus reward karena sudah ada riwayat penukaran'
            ];
        }
        
        $stmt = $this->conn->prepare("DELETE FROM rewards WHERE reward_id = ?");
        $stmt->bind_param("i", $rewardId);
        
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                return [
                    'success' => true,
                    'message' => 'Reward berhasil dihapus'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Reward tidak ditemukan'
                ];
            }
        } else {
            return [
                'success' => false,
                'message' => 'Gagal menghapus reward: ' . $stmt->error
            ];
        }
    }
    
    public function getAllTukarPoin($limit = 20, $offset = 0, $status = '') {
        $whereClause = "";
        $params = [];
        $types = "";
        
        if (!empty($status)) {
            $whereClause = "WHERE tp.status = ?";
            $params[] = $status;
            $types .= "s";
        }
        
        $query = "
            SELECT 
                tp.tukar_id,
                tp.customer_id,
                tp.reward_id,
                tp.poin_digunakan,
                tp.reward,
                tp.status,
                tp.tanggal_tukar,
                c.nama_customer,
                c.no_telepon,
                r.nama_reward
            FROM tukar_poin tp
            JOIN customers c ON tp.customer_id = c.customer_id
            LEFT JOIN rewards r ON tp.reward_id = r.reward_id
            $whereClause
            ORDER BY tp.tanggal_tukar DESC
            LIMIT ? OFFSET ?
        ";
        
        $params[] = $limit;
        $params[] = $offset;
        $types .= "ii";
        
        $stmt = $this->conn->prepare($query);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        
        $tukarPoin = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $tukarPoin[] = $row;
            }
        }
        
        return $tukarPoin;
    }
    
    public function getTukarPoinById($tukarId) {
        $stmt = $this->conn->prepare("
            SELECT 
                tp.*,
                c.nama_customer,
                c.no_telepon,
                c.email,
                r.nama_reward,
                r.poin_required
            FROM tukar_poin tp
            JOIN customers c ON tp.customer_id = c.customer_id
            LEFT JOIN rewards r ON tp.reward_id = r.reward_id
            WHERE tp.tukar_id = ?
        ");
        $stmt->bind_param("i", $tukarId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
    
    public function updateStatusTukarPoin($tukarId, $status) {
        $stmt = $this->conn->prepare("UPDATE tukar_poin SET status = ? WHERE tukar_id = ?");
        $stmt->bind_param("si", $status, $tukarId);
        
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                return [
                    'success' => true,
                    'message' => 'Status penukaran poin berhasil diupdate'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Data penukaran poin tidak ditemukan'
                ];
            }
        } else {
            return [
                'success' => false,
                'message' => 'Gagal mengupdate status: ' . $stmt->error
            ];
        }
    }
    
    public function getCountTukarPoin($status = '') {
        $whereClause = "";
        $params = [];
        $types = "";
        
        if (!empty($status)) {
            $whereClause = "WHERE status = ?";
            $params[] = $status;
            $types .= "s";
        }
        
        $query = "SELECT COUNT(*) as total FROM tukar_poin $whereClause";
        
        if (!empty($params)) {
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $result = $this->conn->query($query);
        }
        
        if ($result) {
            $data = $result->fetch_assoc();
            return $data['total'];
        }
        
        return 0;
    }
    
    public function getRewardStatistics() {
        $query = "
            SELECT 
                r.reward_id,
                r.nama_reward,
                r.poin_required,
                r.stock,
                r.status,
                COUNT(tp.tukar_id) as total_ditukar,
                SUM(CASE WHEN tp.status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN tp.status = 'selesai' THEN 1 ELSE 0 END) as selesai
            FROM rewards r
            LEFT JOIN tukar_poin tp ON r.reward_id = tp.reward_id
            GROUP BY r.reward_id
            ORDER BY r.poin_required ASC
        ";
        
        $result = $this->conn->query($query);
        $statistics = [];
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $statistics[] = $row;
            }
        }
        
        return $statistics;
    }
    
    public function getDashboardStats() {
        $stats = [];
        
        $result = $this->conn->query("SELECT COUNT(*) as total FROM rewards WHERE status = 'aktif'");
        $stats['total_rewards'] = $result->fetch_assoc()['total'];
        
        $result = $this->conn->query("SELECT COUNT(*) as total FROM tukar_poin WHERE status = 'pending'");
        $stats['pending_tukar'] = $result->fetch_assoc()['total'];
        
        $result = $this->conn->query("SELECT COUNT(*) as total FROM tukar_poin WHERE status = 'selesai'");
        $stats['selesai_tukar'] = $result->fetch_assoc()['total'];
        
        $result = $this->conn->query("SELECT SUM(poin_digunakan) as total FROM tukar_poin");
        $stats['total_poin_ditukar'] = $result->fetch_assoc()['total'] ?? 0;
        
        return $stats;
    }
}
?>