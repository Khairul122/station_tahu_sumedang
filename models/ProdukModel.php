<?php
class ProdukModel {
    private $conn;
    
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }
    
    public function getAllProduk() {
        $query = "SELECT * FROM produk ORDER BY produk_id DESC";
        $result = $this->conn->query($query);
        
        $produk = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $produk[] = $row;
            }
        }
        
        return $produk;
    }
    
    public function getProdukById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM produk WHERE produk_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result ? $result->fetch_assoc() : null;
    }
    
    public function createProduk($data) {
        $stmt = $this->conn->prepare("INSERT INTO produk (nama_produk, harga, stok, kategori, poin_reward) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sdisi", $data['nama_produk'], $data['harga'], $data['stok'], $data['kategori'], $data['poin_reward']);
        
        if ($stmt->execute()) {
            return [
                'success' => true,
                'message' => 'Produk berhasil ditambahkan',
                'id' => $this->conn->insert_id
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Gagal menambahkan produk: ' . $stmt->error
            ];
        }
    }
    
    public function updateProduk($id, $data) {
        $stmt = $this->conn->prepare("UPDATE produk SET nama_produk = ?, harga = ?, stok = ?, kategori = ?, poin_reward = ? WHERE produk_id = ?");
        $stmt->bind_param("sdisii", $data['nama_produk'], $data['harga'], $data['stok'], $data['kategori'], $data['poin_reward'], $id);
        
        if ($stmt->execute()) {
            return [
                'success' => true,
                'message' => 'Produk berhasil diupdate'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Gagal mengupdate produk: ' . $stmt->error
            ];
        }
    }
    
    public function deleteProduk($id) {
        $stmt = $this->conn->prepare("DELETE FROM produk WHERE produk_id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            return [
                'success' => true,
                'message' => 'Produk berhasil dihapus'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Gagal menghapus produk: ' . $stmt->error
            ];
        }
    }
    
    public function getKategoriList() {
        $query = "SELECT DISTINCT kategori FROM produk WHERE kategori IS NOT NULL ORDER BY kategori";
        $result = $this->conn->query($query);
        
        $kategori = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $kategori[] = $row['kategori'];
            }
        }
        
        return $kategori;
    }
    
    public function getStokRendah($limit = 10) {
        $query = "SELECT * FROM produk WHERE stok < 20 ORDER BY stok ASC LIMIT ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $produk = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $produk[] = $row;
            }
        }
        
        return $produk;
    }
    
    public function validateProduk($data) {
        $errors = [];
        
        if (empty($data['nama_produk'])) {
            $errors[] = "Nama produk tidak boleh kosong";
        }
        
        if (empty($data['harga']) || $data['harga'] <= 0) {
            $errors[] = "Harga harus lebih dari 0";
        }
        
        if (!isset($data['stok']) || $data['stok'] < 0) {
            $errors[] = "Stok tidak boleh negatif";
        }
        
        if (empty($data['kategori'])) {
            $errors[] = "Kategori tidak boleh kosong";
        }
        
        if (!isset($data['poin_reward']) || $data['poin_reward'] < 0) {
            $errors[] = "Poin reward tidak boleh negatif";
        }
        
        return $errors;
    }
    
    public function searchProduk($keyword) {
        $keyword = "%$keyword%";
        $stmt = $this->conn->prepare("SELECT * FROM produk WHERE nama_produk LIKE ? OR kategori LIKE ? ORDER BY nama_produk");
        $stmt->bind_param("ss", $keyword, $keyword);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $produk = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $produk[] = $row;
            }
        }
        
        return $produk;
    }
    
    public function getTotalProduk() {
        $query = "SELECT COUNT(*) as total FROM produk";
        $result = $this->conn->query($query);
        return $result ? $result->fetch_assoc()['total'] : 0;
    }
}
?>