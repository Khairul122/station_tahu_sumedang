<?php
class RewardModel {
    private $conn;
    
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }
    
    public function getAllRewards() {
        $query = "SELECT * FROM rewards WHERE status = 'aktif' AND stock > 0 ORDER BY poin_required ASC";
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
        $stmt = $this->conn->prepare("SELECT * FROM rewards WHERE reward_id = ? AND status = 'aktif'");
        $stmt->bind_param("i", $rewardId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
    
    public function getMemberPoin($customerId) {
        $stmt = $this->conn->prepare("SELECT total_poin FROM customers WHERE customer_id = ?");
        $stmt->bind_param("i", $customerId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc()['total_poin'];
        }
        return 0;
    }
    
    public function tukarReward($customerId, $rewardId) {
        $this->conn->begin_transaction();
        
        try {
            $reward = $this->getRewardById($rewardId);
            if (!$reward) {
                throw new Exception("Reward tidak ditemukan");
            }
            
            $memberPoin = $this->getMemberPoin($customerId);
            if ($memberPoin < $reward['poin_required']) {
                throw new Exception("Poin tidak mencukupi");
            }
            
            if ($reward['stock'] <= 0) {
                throw new Exception("Stock reward habis");
            }
            
            $stmt = $this->conn->prepare("
                INSERT INTO tukar_poin (customer_id, reward_id, poin_digunakan, reward, status) 
                VALUES (?, ?, ?, ?, 'pending')
            ");
            $stmt->bind_param("iiss", $customerId, $rewardId, $reward['poin_required'], $reward['nama_reward']);
            $stmt->execute();
            
            $tukarId = $this->conn->insert_id;
            
            $updatePoinStmt = $this->conn->prepare("UPDATE customers SET total_poin = total_poin - ? WHERE customer_id = ?");
            $updatePoinStmt->bind_param("ii", $reward['poin_required'], $customerId);
            $updatePoinStmt->execute();
            
            $updateStockStmt = $this->conn->prepare("UPDATE rewards SET stock = stock - 1 WHERE reward_id = ?");
            $updateStockStmt->bind_param("i", $rewardId);
            $updateStockStmt->execute();
            
            $this->conn->commit();
            
            return [
                'success' => true,
                'message' => 'Reward berhasil ditukar',
                'tukar_id' => $tukarId
            ];
            
        } catch (Exception $e) {
            $this->conn->rollback();
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    public function getMemberRewardHistory($customerId) {
        $stmt = $this->conn->prepare("
            SELECT 
                tp.*,
                r.nama_reward
            FROM tukar_poin tp
            LEFT JOIN rewards r ON tp.reward_id = r.reward_id
            WHERE tp.customer_id = ?
            ORDER BY tp.tanggal_tukar DESC
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
}
?>