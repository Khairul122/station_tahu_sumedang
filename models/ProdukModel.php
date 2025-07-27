<?php
class ProdukModel {
    private $conn;
    
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }
    
    public function getAllProduk() {
        $query = "SELECT p.*, s.nama_store 
                  FROM produk p 
                  LEFT JOIN store s ON p.store_id = s.id_store 
                  ORDER BY p.produk_id DESC";
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
    
    public function getAllStores() {
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
    
    public function getProdukStores($produk_id) {
        $stmt = $this->conn->prepare("SELECT DISTINCT store_id FROM produk WHERE produk_id = ?");
        $stmt->bind_param("i", $produk_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $stores = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $stores[] = $row['store_id'];
            }
        }
        
        return $stores;
    }
    
    public function createProdukMultiStore($data, $store_ids) {
        $this->conn->begin_transaction();
        
        try {
            $success_count = 0;
            $total_stores = count($store_ids);
            
            foreach ($store_ids as $store_id) {
                $stmt = $this->conn->prepare("INSERT INTO produk (nama_produk, harga, stok, kategori, poin_reward, foto_produk, store_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sdisiss", 
                    $data['nama_produk'], 
                    $data['harga'], 
                    $data['stok'], 
                    $data['kategori'], 
                    $data['poin_reward'], 
                    $data['foto_produk'], 
                    $store_id
                );
                
                if ($stmt->execute()) {
                    $success_count++;
                }
            }
            
            if ($success_count == $total_stores) {
                $this->conn->commit();
                return [
                    'success' => true,
                    'message' => "Produk berhasil ditambahkan ke {$success_count} store"
                ];
            } else {
                $this->conn->rollback();
                return [
                    'success' => false,
                    'message' => "Gagal menambahkan produk ke semua store"
                ];
            }
        } catch (Exception $e) {
            $this->conn->rollback();
            return [
                'success' => false,
                'message' => 'Gagal menambahkan produk: ' . $e->getMessage()
            ];
        }
    }
    
    public function updateProdukMultiStore($id, $data, $store_ids) {
        $this->conn->begin_transaction();
        
        try {
            $stmt = $this->conn->prepare("SELECT store_id FROM produk WHERE produk_id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $current_store = $result->fetch_assoc()['store_id'];
            
            if (count($store_ids) == 1 && in_array($current_store, $store_ids)) {
                $stmt = $this->conn->prepare("UPDATE produk SET nama_produk = ?, harga = ?, stok = ?, kategori = ?, poin_reward = ?, foto_produk = ? WHERE produk_id = ?");
                $stmt->bind_param("sdisisi", 
                    $data['nama_produk'], 
                    $data['harga'], 
                    $data['stok'], 
                    $data['kategori'], 
                    $data['poin_reward'], 
                    $data['foto_produk'], 
                    $id
                );
                
                if ($stmt->execute()) {
                    $this->conn->commit();
                    return [
                        'success' => true,
                        'message' => 'Produk berhasil diupdate'
                    ];
                } else {
                    $this->conn->rollback();
                    return [
                        'success' => false,
                        'message' => 'Gagal mengupdate produk'
                    ];
                }
            } else {
                $stmt = $this->conn->prepare("DELETE FROM produk WHERE produk_id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                
                $success_count = 0;
                $total_stores = count($store_ids);
                
                foreach ($store_ids as $store_id) {
                    $stmt = $this->conn->prepare("INSERT INTO produk (nama_produk, harga, stok, kategori, poin_reward, foto_produk, store_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("sdisiss", 
                        $data['nama_produk'], 
                        $data['harga'], 
                        $data['stok'], 
                        $data['kategori'], 
                        $data['poin_reward'], 
                        $data['foto_produk'], 
                        $store_id
                    );
                    
                    if ($stmt->execute()) {
                        $success_count++;
                    }
                }
                
                if ($success_count == $total_stores) {
                    $this->conn->commit();
                    return [
                        'success' => true,
                        'message' => "Produk berhasil diupdate ke {$success_count} store"
                    ];
                } else {
                    $this->conn->rollback();
                    return [
                        'success' => false,
                        'message' => "Gagal mengupdate produk ke semua store"
                    ];
                }
            }
        } catch (Exception $e) {
            $this->conn->rollback();
            return [
                'success' => false,
                'message' => 'Gagal mengupdate produk: ' . $e->getMessage()
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
        $query = "SELECT p.*, s.nama_store 
                  FROM produk p 
                  LEFT JOIN store s ON p.store_id = s.id_store 
                  WHERE p.stok < 20 
                  ORDER BY p.stok ASC 
                  LIMIT ?";
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
        $stmt = $this->conn->prepare("SELECT p.*, s.nama_store 
                                      FROM produk p 
                                      LEFT JOIN store s ON p.store_id = s.id_store 
                                      WHERE p.nama_produk LIKE ? OR p.kategori LIKE ? OR s.nama_store LIKE ? 
                                      ORDER BY p.nama_produk");
        $stmt->bind_param("sss", $keyword, $keyword, $keyword);
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
    
    public function getProdukByStore($store_id) {
        $stmt = $this->conn->prepare("SELECT * FROM produk WHERE store_id = ? ORDER BY nama_produk");
        $stmt->bind_param("i", $store_id);
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
}