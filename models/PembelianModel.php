<?php
class PembelianModel {
    private $conn;
    
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }
    
    public function getAllProduk() {
        $query = "SELECT produk_id, nama_produk, harga, stok, kategori, poin_reward FROM produk WHERE stok > 0 ORDER BY nama_produk";
        $result = $this->conn->query($query);
        
        $produk = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $produk[] = $row;
            }
        }
        
        return $produk;
    }
    
    public function getProdukByCategory() {
        $query = "SELECT kategori, produk_id, nama_produk, harga, stok, poin_reward FROM produk WHERE stok > 0 ORDER BY kategori, nama_produk";
        $result = $this->conn->query($query);
        
        $produkByCategory = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $produkByCategory[$row['kategori']][] = $row;
            }
        }
        
        return $produkByCategory;
    }
    
    public function getProdukById($produkId) {
        $stmt = $this->conn->prepare("SELECT produk_id, nama_produk, harga, stok, kategori, poin_reward FROM produk WHERE produk_id = ?");
        $stmt->bind_param("i", $produkId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
    
    public function getMemberData($userId) {
        $stmt = $this->conn->prepare("
            SELECT 
                c.customer_id,
                c.nama_customer,
                c.no_telepon,
                c.alamat,
                c.email,
                c.membership_id,
                c.total_pembelian,
                c.total_poin,
                m.nama_membership,
                m.diskon_persen,
                m.poin_per_pembelian
            FROM customers c
            JOIN membership m ON c.membership_id = m.membership_id
            WHERE c.user_id = ? AND c.status_aktif = 'aktif'
        ");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
    
    public function calculateTotal($items, $diskonPersen = 0) {
        $totalSebelumDiskon = 0;
        $totalPoinItem = 0;
        
        foreach ($items as $item) {
            $produk = $this->getProdukById($item['produk_id']);
            if ($produk) {
                $subtotal = $produk['harga'] * $item['jumlah'];
                $totalSebelumDiskon += $subtotal;
                $totalPoinItem += $produk['poin_reward'] * $item['jumlah'];
            }
        }
        
        $diskonAmount = ($totalSebelumDiskon * $diskonPersen) / 100;
        $totalBayar = $totalSebelumDiskon - $diskonAmount;
        
        return [
            'total_sebelum_diskon' => $totalSebelumDiskon,
            'diskon_amount' => $diskonAmount,
            'total_bayar' => $totalBayar,
            'total_poin_item' => $totalPoinItem
        ];
    }
    
    public function createTransaksi($customerId, $items, $metodePembayaran = 'tunai') {
        $this->conn->autocommit(false);
        $this->conn->begin_transaction();
        
        try {
            $memberData = $this->getMemberDataByCustomerId($customerId);
            if (!$memberData) {
                throw new Exception("Data member tidak ditemukan");
            }
            
            $calculation = $this->calculateTotal($items, $memberData['diskon_persen']);
            
            $poinDidapat = ($calculation['total_poin_item'] * $memberData['poin_per_pembelian']) + 
                          floor($calculation['total_bayar'] / 10000);
            
            $stmt = $this->conn->prepare("
                INSERT INTO transaksi (customer_id, total_sebelum_diskon, diskon_membership, total_bayar, poin_didapat, metode_pembayaran) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param("idddis", 
                $customerId, 
                $calculation['total_sebelum_diskon'], 
                $calculation['diskon_amount'], 
                $calculation['total_bayar'], 
                $poinDidapat, 
                $metodePembayaran
            );
            
            if (!$stmt->execute()) {
                throw new Exception("Gagal menyimpan transaksi: " . $stmt->error);
            }
            
            $transaksiId = $this->conn->insert_id;
            
            foreach ($items as $item) {
                $produk = $this->getProdukById($item['produk_id']);
                if (!$produk) {
                    throw new Exception("Produk dengan ID {$item['produk_id']} tidak ditemukan");
                }
                
                $currentStockStmt = $this->conn->prepare("SELECT stok FROM produk WHERE produk_id = ? FOR UPDATE");
                $currentStockStmt->bind_param("i", $item['produk_id']);
                $currentStockStmt->execute();
                $currentStockResult = $currentStockStmt->get_result();
                $currentStock = $currentStockResult->fetch_assoc();
                
                if (!$currentStock || $currentStock['stok'] < $item['jumlah']) {
                    throw new Exception("Stok {$produk['nama_produk']} tidak mencukupi. Tersedia: " . ($currentStock['stok'] ?? 0) . ", Diminta: {$item['jumlah']}");
                }
                
                $subtotal = $produk['harga'] * $item['jumlah'];
                $totalPoinItem = $produk['poin_reward'] * $item['jumlah'] * $memberData['poin_per_pembelian'];
                
                $detailStmt = $this->conn->prepare("
                    INSERT INTO detail_transaksi (transaksi_id, produk_id, jumlah, harga_satuan, subtotal, poin_produk, total_poin_item) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                ");
                $detailStmt->bind_param("iiiddii", 
                    $transaksiId, 
                    $item['produk_id'], 
                    $item['jumlah'], 
                    $produk['harga'], 
                    $subtotal, 
                    $produk['poin_reward'], 
                    $totalPoinItem
                );
                
                if (!$detailStmt->execute()) {
                    throw new Exception("Gagal menyimpan detail transaksi: " . $detailStmt->error);
                }
                
                $updateStokStmt = $this->conn->prepare("UPDATE produk SET stok = stok - ? WHERE produk_id = ?");
                $updateStokStmt->bind_param("ii", $item['jumlah'], $item['produk_id']);
                
                if (!$updateStokStmt->execute() || $updateStokStmt->affected_rows === 0) {
                    throw new Exception("Gagal mengupdate stok produk {$produk['nama_produk']}");
                }
            }
            
            $updateCustomerStmt = $this->conn->prepare("
                UPDATE customers 
                SET total_pembelian = total_pembelian + ?, total_poin = total_poin + ? 
                WHERE customer_id = ?
            ");
            $updateCustomerStmt->bind_param("dii", $calculation['total_bayar'], $poinDidapat, $customerId);
            
            if (!$updateCustomerStmt->execute() || $updateCustomerStmt->affected_rows === 0) {
                throw new Exception("Gagal mengupdate data customer");
            }
            
            $this->insertAktivitasCustomer($customerId, 'pembelian', "Transaksi #$transaksiId - Total: Rp " . number_format($calculation['total_bayar']));
            
            $membershipUpdate = $this->checkAndUpdateMembership($customerId);
            
            $this->conn->commit();
            $this->conn->autocommit(true);
            
            return [
                'success' => true,
                'message' => 'Transaksi berhasil disimpan',
                'transaksi_id' => $transaksiId,
                'total_bayar' => $calculation['total_bayar'],
                'poin_didapat' => $poinDidapat,
                'membership_update' => $membershipUpdate
            ];
            
        } catch (Exception $e) {
            $this->conn->rollback();
            $this->conn->autocommit(true);
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }
    
    public function getMemberDataByCustomerId($customerId) {
        $stmt = $this->conn->prepare("
            SELECT 
                c.customer_id,
                c.nama_customer,
                c.no_telepon,
                c.alamat,
                c.email,
                c.membership_id,
                c.total_pembelian,
                c.total_poin,
                m.nama_membership,
                m.diskon_persen,
                m.poin_per_pembelian
            FROM customers c
            JOIN membership m ON c.membership_id = m.membership_id
            WHERE c.customer_id = ? AND c.status_aktif = 'aktif'
        ");
        $stmt->bind_param("i", $customerId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
    
    public function getTransaksiDetail($transaksiId) {
        $stmt = $this->conn->prepare("
            SELECT 
                t.*,
                c.nama_customer,
                c.no_telepon,
                m.nama_membership
            FROM transaksi t
            JOIN customers c ON t.customer_id = c.customer_id
            JOIN membership m ON c.membership_id = m.membership_id
            WHERE t.transaksi_id = ?
        ");
        $stmt->bind_param("i", $transaksiId);
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
    
    public function getMemberTransaksiHistory($customerId, $limit = 10, $offset = 0) {
        $stmt = $this->conn->prepare("
            SELECT 
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
            WHERE t.customer_id = ?
            GROUP BY t.transaksi_id
            ORDER BY t.tanggal_transaksi DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->bind_param("iii", $customerId, $limit, $offset);
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
    
    public function validateStock($items) {
        $errors = [];
        
        foreach ($items as $item) {
            $produk = $this->getProdukById($item['produk_id']);
            if (!$produk) {
                $errors[] = "Produk dengan ID {$item['produk_id']} tidak ditemukan";
                continue;
            }
            
            if ($produk['stok'] < $item['jumlah']) {
                $errors[] = "Stok {$produk['nama_produk']} tidak mencukupi. Tersedia: {$produk['stok']}, Diminta: {$item['jumlah']}";
            }
            
            if ($item['jumlah'] <= 0) {
                $errors[] = "Jumlah {$produk['nama_produk']} harus lebih dari 0";
            }
        }
        
        return $errors;
    }
    
    public function insertAktivitasCustomer($customerId, $jenisAktivitas, $catatan) {
        $stmt = $this->conn->prepare("INSERT INTO aktivitas_customer (customer_id, jenis_aktivitas, catatan) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $customerId, $jenisAktivitas, $catatan);
        $stmt->execute();
    }
    
    public function searchProduk($keyword) {
        $searchTerm = "%$keyword%";
        $stmt = $this->conn->prepare("
            SELECT produk_id, nama_produk, harga, stok, kategori, poin_reward 
            FROM produk 
            WHERE (nama_produk LIKE ? OR kategori LIKE ?) AND stok > 0 
            ORDER BY nama_produk
        ");
        $stmt->bind_param("ss", $searchTerm, $searchTerm);
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
    
    public function getMetodePembayaran() {
        return [
            'tunai' => 'Tunai',
            'transfer' => 'Transfer Bank',
            'kartu' => 'Kartu Debit/Credit'
        ];
    }
    
    public function checkAndUpdateMembership($customerId) {
        $memberData = $this->getMemberDataByCustomerId($customerId);
        if (!$memberData) return ['upgraded' => false];
        
        $totalPembelian = $memberData['total_pembelian'];
        $currentMembership = $memberData['membership_id'];
        
        $newMembership = 1;
        $membershipName = 'Bronze';
        
        if ($totalPembelian >= 500000) {
            $newMembership = 4;
            $membershipName = 'Platinum';
        } elseif ($totalPembelian >= 300000) {
            $newMembership = 3;
            $membershipName = 'Gold';
        } elseif ($totalPembelian >= 100000) {
            $newMembership = 2;
            $membershipName = 'Silver';
        }
        
        if ($newMembership > $currentMembership) {
            $stmt = $this->conn->prepare("UPDATE customers SET membership_id = ? WHERE customer_id = ?");
            $stmt->bind_param("ii", $newMembership, $customerId);
            $stmt->execute();
            
            $this->insertAktivitasCustomer($customerId, 'follow_up', "Naik tier menjadi $membershipName");
            
            return [
                'upgraded' => true,
                'new_membership' => $membershipName,
                'old_membership_id' => $currentMembership,
                'new_membership_id' => $newMembership
            ];
        }
        
        return ['upgraded' => false];
    }
}
?>