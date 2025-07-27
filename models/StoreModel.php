<?php
class StoreModel {
    private $conn;
    
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }
    
    public function getAllStores() {
        $query = "SELECT * FROM store ORDER BY id_store DESC";
        $result = $this->conn->query($query);
        
        $stores = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $stores[] = $row;
            }
        }
        
        return $stores;
    }
    
    public function getStoreById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM store WHERE id_store = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result ? $result->fetch_assoc() : null;
    }
    
    public function createStore($data) {
        $stmt = $this->conn->prepare("INSERT INTO store (nama_store, alamat_store, manajer_store, status_store) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $data['nama_store'], $data['alamat_store'], $data['manajer_store'], $data['status_store']);
        
        if ($stmt->execute()) {
            return [
                'success' => true,
                'message' => 'Store berhasil ditambahkan',
                'id' => $this->conn->insert_id
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Gagal menambahkan store: ' . $stmt->error
            ];
        }
    }
    
    public function updateStore($id, $data) {
        $stmt = $this->conn->prepare("UPDATE store SET nama_store = ?, alamat_store = ?, manajer_store = ?, status_store = ? WHERE id_store = ?");
        $stmt->bind_param("ssssi", $data['nama_store'], $data['alamat_store'], $data['manajer_store'], $data['status_store'], $id);
        
        if ($stmt->execute()) {
            return [
                'success' => true,
                'message' => 'Store berhasil diupdate'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Gagal mengupdate store: ' . $stmt->error
            ];
        }
    }
    
    public function deleteStore($id) {
        $check = $this->conn->prepare("SELECT COUNT(*) as count FROM produk WHERE store_id = ?");
        $check->bind_param("i", $id);
        $check->execute();
        $result = $check->get_result()->fetch_assoc();
        
        if ($result['count'] > 0) {
            return [
                'success' => false,
                'message' => 'Store tidak dapat dihapus karena masih memiliki produk'
            ];
        }
        
        $stmt = $this->conn->prepare("DELETE FROM store WHERE id_store = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            return [
                'success' => true,
                'message' => 'Store berhasil dihapus'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Gagal menghapus store: ' . $stmt->error
            ];
        }
    }
    
    public function validateStore($data) {
        $errors = [];
        
        if (empty($data['nama_store'])) {
            $errors[] = "Nama store tidak boleh kosong";
        }
        
        if (empty($data['alamat_store'])) {
            $errors[] = "Alamat store tidak boleh kosong";
        }
        
        if (!in_array($data['status_store'], ['aktif', 'tidak_aktif'])) {
            $errors[] = "Status store tidak valid";
        }
        
        return $errors;
    }
    
    public function searchStore($keyword) {
        $keyword = "%$keyword%";
        $stmt = $this->conn->prepare("SELECT * FROM store WHERE nama_store LIKE ? OR alamat_store LIKE ? OR manajer_store LIKE ? ORDER BY nama_store");
        $stmt->bind_param("sss", $keyword, $keyword, $keyword);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $stores = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $stores[] = $row;
            }
        }
        
        return $stores;
    }
    
    public function getTotalStores() {
        $query = "SELECT COUNT(*) as total FROM store";
        $result = $this->conn->query($query);
        return $result ? $result->fetch_assoc()['total'] : 0;
    }
    
    public function getActiveStores() {
        $query = "SELECT * FROM store WHERE status_store = 'aktif' ORDER BY nama_store";
        $result = $this->conn->query($query);
        
        $stores = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $stores[] = $row;
            }
        }
        
        return $stores;
    }
    
    public function getTotalProdukByStore($store_id) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM produk WHERE store_id = ?");
        $stmt->bind_param("i", $store_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_assoc()['total'] : 0;
    }
}