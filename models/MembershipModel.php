<?php
class MembershipModel {
    private $conn;
    
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }
    
    public function getAllMemberships() {
        $query = "SELECT * FROM membership ORDER BY membership_id";
        $result = $this->conn->query($query);
        
        $memberships = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $memberships[] = $row;
            }
        }
        
        return $memberships;
    }
    
    public function getMembershipById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM membership WHERE membership_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result ? $result->fetch_assoc() : null;
    }
    
    public function createMembership($data) {
        $stmt = $this->conn->prepare("INSERT INTO membership (nama_membership, minimal_pembelian, diskon_persen, poin_per_pembelian, benefit) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sdiis", $data['nama_membership'], $data['minimal_pembelian'], $data['diskon_persen'], $data['poin_per_pembelian'], $data['benefit']);
        
        if ($stmt->execute()) {
            return [
                'success' => true,
                'message' => 'Membership berhasil ditambahkan',
                'id' => $this->conn->insert_id
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Gagal menambahkan membership: ' . $stmt->error
            ];
        }
    }
    
    public function updateMembership($id, $data) {
        $stmt = $this->conn->prepare("UPDATE membership SET nama_membership = ?, minimal_pembelian = ?, diskon_persen = ?, poin_per_pembelian = ?, benefit = ? WHERE membership_id = ?");
        $stmt->bind_param("sdiisi", $data['nama_membership'], $data['minimal_pembelian'], $data['diskon_persen'], $data['poin_per_pembelian'], $data['benefit'], $id);
        
        if ($stmt->execute()) {
            return [
                'success' => true,
                'message' => 'Membership berhasil diupdate'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Gagal mengupdate membership: ' . $stmt->error
            ];
        }
    }
    
    public function deleteMembership($id) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM customers WHERE membership_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $count = $result->fetch_assoc()['total'];
        
        if ($count > 0) {
            return [
                'success' => false,
                'message' => 'Tidak dapat menghapus membership karena masih ada customer yang menggunakan'
            ];
        }
        
        $stmt = $this->conn->prepare("DELETE FROM membership WHERE membership_id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            return [
                'success' => true,
                'message' => 'Membership berhasil dihapus'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Gagal menghapus membership: ' . $stmt->error
            ];
        }
    }
    
    public function getMembershipStats($id) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total_customers, SUM(total_pembelian) as total_revenue FROM customers WHERE membership_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result ? $result->fetch_assoc() : ['total_customers' => 0, 'total_revenue' => 0];
    }
    
    public function validateMembership($data) {
        $errors = [];
        
        if (empty($data['nama_membership'])) {
            $errors[] = "Nama membership tidak boleh kosong";
        }
        
        if (!isset($data['minimal_pembelian']) || $data['minimal_pembelian'] < 0) {
            $errors[] = "Minimal pembelian tidak boleh negatif";
        }
        
        if (!isset($data['diskon_persen']) || $data['diskon_persen'] < 0 || $data['diskon_persen'] > 100) {
            $errors[] = "Diskon persen harus antara 0-100";
        }
        
        if (!isset($data['poin_per_pembelian']) || $data['poin_per_pembelian'] < 1) {
            $errors[] = "Poin per pembelian minimal 1";
        }
        
        if (empty($data['benefit'])) {
            $errors[] = "Benefit tidak boleh kosong";
        }
        
        return $errors;
    }
    
    public function checkDuplicateName($name, $excludeId = null) {
        if ($excludeId) {
            $stmt = $this->conn->prepare("SELECT membership_id FROM membership WHERE nama_membership = ? AND membership_id != ?");
            $stmt->bind_param("si", $name, $excludeId);
        } else {
            $stmt = $this->conn->prepare("SELECT membership_id FROM membership WHERE nama_membership = ?");
            $stmt->bind_param("s", $name);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result && $result->num_rows > 0;
    }
    
    public function getTotalMemberships() {
        $query = "SELECT COUNT(*) as total FROM membership";
        $result = $this->conn->query($query);
        return $result ? $result->fetch_assoc()['total'] : 0;
    }
}
?>