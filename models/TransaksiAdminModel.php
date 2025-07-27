<?php
class TransaksiAdminModel {
    private $conn;
    
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }
    
    public function getAllTransaksi($storeId = null) {
        if ($storeId) {
            $query = "SELECT t.*, c.nama_customer, c.no_telepon, m.nama_membership, s.nama_store 
                      FROM transaksi t 
                      LEFT JOIN customers c ON t.customer_id = c.customer_id 
                      LEFT JOIN membership m ON c.membership_id = m.membership_id 
                      LEFT JOIN store s ON t.store_id = s.id_store
                      WHERE t.store_id = ?
                      ORDER BY t.tanggal_transaksi DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $storeId);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $query = "SELECT t.*, c.nama_customer, c.no_telepon, m.nama_membership, s.nama_store 
                      FROM transaksi t 
                      LEFT JOIN customers c ON t.customer_id = c.customer_id 
                      LEFT JOIN membership m ON c.membership_id = m.membership_id 
                      LEFT JOIN store s ON t.store_id = s.id_store
                      ORDER BY t.tanggal_transaksi DESC";
            $result = $this->conn->query($query);
        }
        
        $transaksi = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $transaksi[] = $row;
            }
        }
        
        return $transaksi;
    }
    
    public function getTransaksiById($id, $storeId = null) {
        if ($storeId) {
            $stmt = $this->conn->prepare("SELECT t.*, c.nama_customer, c.no_telepon, c.email, m.nama_membership, s.nama_store, s.alamat_store
                                          FROM transaksi t 
                                          LEFT JOIN customers c ON t.customer_id = c.customer_id 
                                          LEFT JOIN membership m ON c.membership_id = m.membership_id 
                                          LEFT JOIN store s ON t.store_id = s.id_store
                                          WHERE t.transaksi_id = ? AND t.store_id = ?");
            $stmt->bind_param("ii", $id, $storeId);
        } else {
            $stmt = $this->conn->prepare("SELECT t.*, c.nama_customer, c.no_telepon, c.email, m.nama_membership, s.nama_store, s.alamat_store
                                          FROM transaksi t 
                                          LEFT JOIN customers c ON t.customer_id = c.customer_id 
                                          LEFT JOIN membership m ON c.membership_id = m.membership_id 
                                          LEFT JOIN store s ON t.store_id = s.id_store
                                          WHERE t.transaksi_id = ?");
            $stmt->bind_param("i", $id);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result ? $result->fetch_assoc() : null;
    }
    
    public function getDetailTransaksi($transaksi_id) {
        $stmt = $this->conn->prepare("SELECT dt.*, p.nama_produk, p.kategori, p.foto_produk
                                      FROM detail_transaksi dt 
                                      JOIN produk p ON dt.produk_id = p.produk_id 
                                      WHERE dt.transaksi_id = ?
                                      ORDER BY dt.detail_id");
        $stmt->bind_param("i", $transaksi_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $detail = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $detail[] = $row;
            }
        }
        
        return $detail;
    }
    
    public function createTransaksi($data) {
        $this->conn->begin_transaction();
        
        try {
            $stmt = $this->conn->prepare("INSERT INTO transaksi (customer_id, store_id, total_sebelum_diskon, diskon_membership, total_bayar, poin_didapat, metode_pembayaran, bukti_pembayaran) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iiddisss", $data['customer_id'], $data['store_id'], $data['total_sebelum_diskon'], $data['diskon_membership'], $data['total_bayar'], $data['poin_didapat'], $data['metode_pembayaran'], $data['bukti_pembayaran']);
            $stmt->execute();
            
            $transaksi_id = $this->conn->insert_id;
            
            foreach ($data['detail'] as $detail) {
                $stmt = $this->conn->prepare("INSERT INTO detail_transaksi (transaksi_id, produk_id, jumlah, harga_satuan, subtotal, poin_produk, total_poin_item) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("iiiddii", $transaksi_id, $detail['produk_id'], $detail['jumlah'], $detail['harga_satuan'], $detail['subtotal'], $detail['poin_produk'], $detail['total_poin_item']);
                $stmt->execute();
                
                $stmt = $this->conn->prepare("UPDATE produk SET stok = stok - ? WHERE produk_id = ? AND store_id = ?");
                $stmt->bind_param("iii", $detail['jumlah'], $detail['produk_id'], $data['store_id']);
                $stmt->execute();
            }
            
            if ($data['customer_id']) {
                $stmt = $this->conn->prepare("UPDATE customers SET total_pembelian = total_pembelian + ?, total_poin = total_poin + ? WHERE customer_id = ?");
                $stmt->bind_param("dii", $data['total_bayar'], $data['poin_didapat'], $data['customer_id']);
                $stmt->execute();
            }
            
            $this->conn->commit();
            
            return [
                'success' => true,
                'message' => 'Transaksi berhasil dibuat',
                'id' => $transaksi_id
            ];
            
        } catch (Exception $e) {
            $this->conn->rollback();
            return [
                'success' => false,
                'message' => 'Gagal membuat transaksi: ' . $e->getMessage()
            ];
        }
    }
    
    public function updateTransaksi($id, $data, $storeId = null) {
        if ($storeId) {
            $stmt = $this->conn->prepare("UPDATE transaksi SET metode_pembayaran = ?, bukti_pembayaran = ? WHERE transaksi_id = ? AND store_id = ?");
            $stmt->bind_param("ssii", $data['metode_pembayaran'], $data['bukti_pembayaran'], $id, $storeId);
        } else {
            $stmt = $this->conn->prepare("UPDATE transaksi SET metode_pembayaran = ?, bukti_pembayaran = ? WHERE transaksi_id = ?");
            $stmt->bind_param("ssi", $data['metode_pembayaran'], $data['bukti_pembayaran'], $id);
        }
        
        if ($stmt->execute()) {
            return [
                'success' => true,
                'message' => 'Transaksi berhasil diupdate'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Gagal mengupdate transaksi: ' . $stmt->error
            ];
        }
    }
    
    public function deleteTransaksi($id, $storeId = null) {
        $this->conn->begin_transaction();
        
        try {
            $transaksi = $this->getTransaksiById($id, $storeId);
            if (!$transaksi) {
                throw new Exception("Transaksi tidak ditemukan");
            }
            
            $detail = $this->getDetailTransaksi($id);
            
            foreach ($detail as $item) {
                $stmt = $this->conn->prepare("UPDATE produk SET stok = stok + ? WHERE produk_id = ? AND store_id = ?");
                $stmt->bind_param("iii", $item['jumlah'], $item['produk_id'], $transaksi['store_id']);
                $stmt->execute();
            }
            
            if ($transaksi['customer_id']) {
                $stmt = $this->conn->prepare("UPDATE customers SET total_pembelian = total_pembelian - ?, total_poin = total_poin - ? WHERE customer_id = ?");
                $stmt->bind_param("dii", $transaksi['total_bayar'], $transaksi['poin_didapat'], $transaksi['customer_id']);
                $stmt->execute();
            }
            
            $stmt = $this->conn->prepare("DELETE FROM detail_transaksi WHERE transaksi_id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            
            if ($storeId) {
                $stmt = $this->conn->prepare("DELETE FROM transaksi WHERE transaksi_id = ? AND store_id = ?");
                $stmt->bind_param("ii", $id, $storeId);
            } else {
                $stmt = $this->conn->prepare("DELETE FROM transaksi WHERE transaksi_id = ?");
                $stmt->bind_param("i", $id);
            }
            $stmt->execute();
            
            $this->conn->commit();
            
            return [
                'success' => true,
                'message' => 'Transaksi berhasil dihapus'
            ];
            
        } catch (Exception $e) {
            $this->conn->rollback();
            return [
                'success' => false,
                'message' => 'Gagal menghapus transaksi: ' . $e->getMessage()
            ];
        }
    }
    
    public function getAllCustomers() {
        $query = "SELECT c.*, m.nama_membership, m.diskon_persen, m.poin_per_pembelian 
                  FROM customers c 
                  JOIN membership m ON c.membership_id = m.membership_id 
                  WHERE c.status_aktif = 'aktif' 
                  ORDER BY c.nama_customer";
        $result = $this->conn->query($query);
        
        $customers = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $customers[] = $row;
            }
        }
        
        return $customers;
    }
    
    public function getAllProduk($storeId = null) {
        if ($storeId) {
            $query = "SELECT * FROM produk WHERE stok > 0 AND store_id = ? ORDER BY nama_produk";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $storeId);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $query = "SELECT p.*, s.nama_store FROM produk p 
                      LEFT JOIN store s ON p.store_id = s.id_store 
                      WHERE p.stok > 0 
                      ORDER BY p.nama_produk";
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
    
    public function getAllStores($userStoreId = null) {
        if ($userStoreId) {
            $query = "SELECT * FROM store WHERE id_store = ? AND status_store = 'aktif' ORDER BY nama_store";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $userStoreId);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $query = "SELECT * FROM store WHERE status_store = 'aktif' ORDER BY nama_store";
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
    
    public function getProdukById($id, $storeId = null) {
        if ($storeId) {
            $stmt = $this->conn->prepare("SELECT * FROM produk WHERE produk_id = ? AND store_id = ?");
            $stmt->bind_param("ii", $id, $storeId);
        } else {
            $stmt = $this->conn->prepare("SELECT p.*, s.nama_store FROM produk p 
                                          LEFT JOIN store s ON p.store_id = s.id_store 
                                          WHERE p.produk_id = ?");
            $stmt->bind_param("i", $id);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result ? $result->fetch_assoc() : null;
    }
    
    public function getCustomerById($id) {
        $stmt = $this->conn->prepare("SELECT c.*, m.nama_membership, m.diskon_persen, m.poin_per_pembelian 
                                      FROM customers c 
                                      JOIN membership m ON c.membership_id = m.membership_id 
                                      WHERE c.customer_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result ? $result->fetch_assoc() : null;
    }
    
    public function searchTransaksi($keyword, $storeId = null) {
        $keyword = "%$keyword%";
        
        if ($storeId) {
            $stmt = $this->conn->prepare("SELECT t.*, c.nama_customer, c.no_telepon, m.nama_membership, s.nama_store
                                          FROM transaksi t 
                                          LEFT JOIN customers c ON t.customer_id = c.customer_id 
                                          LEFT JOIN membership m ON c.membership_id = m.membership_id 
                                          LEFT JOIN store s ON t.store_id = s.id_store
                                          WHERE (c.nama_customer LIKE ? OR c.no_telepon LIKE ? OR t.transaksi_id LIKE ?) 
                                          AND t.store_id = ?
                                          ORDER BY t.tanggal_transaksi DESC");
            $stmt->bind_param("sssi", $keyword, $keyword, $keyword, $storeId);
        } else {
            $stmt = $this->conn->prepare("SELECT t.*, c.nama_customer, c.no_telepon, m.nama_membership, s.nama_store
                                          FROM transaksi t 
                                          LEFT JOIN customers c ON t.customer_id = c.customer_id 
                                          LEFT JOIN membership m ON c.membership_id = m.membership_id 
                                          LEFT JOIN store s ON t.store_id = s.id_store
                                          WHERE c.nama_customer LIKE ? OR c.no_telepon LIKE ? OR t.transaksi_id LIKE ?
                                          ORDER BY t.tanggal_transaksi DESC");
            $stmt->bind_param("sss", $keyword, $keyword, $keyword);
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
    
    public function getTransaksiByDate($start_date, $end_date, $storeId = null) {
        if ($storeId) {
            $stmt = $this->conn->prepare("SELECT t.*, c.nama_customer, c.no_telepon, m.nama_membership, s.nama_store
                                          FROM transaksi t 
                                          LEFT JOIN customers c ON t.customer_id = c.customer_id 
                                          LEFT JOIN membership m ON c.membership_id = m.membership_id 
                                          LEFT JOIN store s ON t.store_id = s.id_store
                                          WHERE DATE(t.tanggal_transaksi) BETWEEN ? AND ? AND t.store_id = ?
                                          ORDER BY t.tanggal_transaksi DESC");
            $stmt->bind_param("ssi", $start_date, $end_date, $storeId);
        } else {
            $stmt = $this->conn->prepare("SELECT t.*, c.nama_customer, c.no_telepon, m.nama_membership, s.nama_store
                                          FROM transaksi t 
                                          LEFT JOIN customers c ON t.customer_id = c.customer_id 
                                          LEFT JOIN membership m ON c.membership_id = m.membership_id 
                                          LEFT JOIN store s ON t.store_id = s.id_store
                                          WHERE DATE(t.tanggal_transaksi) BETWEEN ? AND ?
                                          ORDER BY t.tanggal_transaksi DESC");
            $stmt->bind_param("ss", $start_date, $end_date);
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
    
    public function getTransaksiStats($storeId = null) {
        $stats = [];
        
        if ($storeId) {
            $query = "SELECT COUNT(*) as total_transaksi, SUM(total_bayar) as total_revenue FROM transaksi WHERE store_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $storeId);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $query = "SELECT COUNT(*) as total_transaksi, SUM(total_bayar) as total_revenue FROM transaksi";
            $result = $this->conn->query($query);
        }
        
        $row = $result ? $result->fetch_assoc() : null;
        $stats['total_transaksi'] = $row ? $row['total_transaksi'] : 0;
        $stats['total_revenue'] = $row ? $row['total_revenue'] : 0;
        
        if ($storeId) {
            $query = "SELECT COUNT(*) as transaksi_hari_ini, SUM(total_bayar) as revenue_hari_ini FROM transaksi WHERE DATE(tanggal_transaksi) = CURDATE() AND store_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $storeId);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $query = "SELECT COUNT(*) as transaksi_hari_ini, SUM(total_bayar) as revenue_hari_ini FROM transaksi WHERE DATE(tanggal_transaksi) = CURDATE()";
            $result = $this->conn->query($query);
        }
        
        $row = $result ? $result->fetch_assoc() : null;
        $stats['transaksi_hari_ini'] = $row ? $row['transaksi_hari_ini'] : 0;
        $stats['revenue_hari_ini'] = $row ? $row['revenue_hari_ini'] : 0;
        
        if ($storeId) {
            $query = "SELECT AVG(total_bayar) as avg_transaksi FROM transaksi WHERE store_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $storeId);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $query = "SELECT AVG(total_bayar) as avg_transaksi FROM transaksi";
            $result = $this->conn->query($query);
        }
        
        $row = $result ? $result->fetch_assoc() : null;
        $stats['avg_transaksi'] = $row ? $row['avg_transaksi'] : 0;
        
        return $stats;
    }
    
    public function validateTransaksi($data) {
        $errors = [];
        
        if (empty($data['detail']) || !is_array($data['detail'])) {
            $errors[] = "Detail transaksi tidak boleh kosong";
        }
        
        if (empty($data['metode_pembayaran'])) {
            $errors[] = "Metode pembayaran harus dipilih";
        }
        
        if (empty($data['store_id'])) {
            $errors[] = "Store harus dipilih";
        }
        
        if (isset($data['detail']) && is_array($data['detail'])) {
            foreach ($data['detail'] as $index => $detail) {
                if (empty($detail['produk_id'])) {
                    $errors[] = "Produk pada item " . ($index + 1) . " harus dipilih";
                }
                
                if (empty($detail['jumlah']) || $detail['jumlah'] <= 0) {
                    $errors[] = "Jumlah pada item " . ($index + 1) . " harus lebih dari 0";
                }
                
                if (isset($detail['produk_id']) && $detail['produk_id']) {
                    $produk = $this->getProdukById($detail['produk_id'], $data['store_id']);
                    if (!$produk) {
                        $errors[] = "Produk pada item " . ($index + 1) . " tidak ditemukan di store yang dipilih";
                    } elseif ($produk['stok'] < $detail['jumlah']) {
                        $errors[] = "Stok produk " . $produk['nama_produk'] . " tidak mencukupi (tersedia: " . $produk['stok'] . ")";
                    }
                }
            }
        }
        
        return $errors;
    }
    
    public function getProdukByStore($storeId) {
        $query = "SELECT * FROM produk WHERE store_id = ? AND stok > 0 ORDER BY nama_produk";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $storeId);
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
?>