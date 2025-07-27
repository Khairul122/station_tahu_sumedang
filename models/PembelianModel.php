<?php
class PembelianModel {
    private $conn;
    
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }
    
    public function getAllProduk($storeId = null) {
        if ($storeId) {
            $query = "SELECT produk_id, nama_produk, harga, stok, kategori, poin_reward, foto_produk FROM produk WHERE stok > 0 AND store_id = ? ORDER BY nama_produk";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $storeId);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $query = "SELECT produk_id, nama_produk, harga, stok, kategori, poin_reward, foto_produk FROM produk WHERE stok > 0 ORDER BY nama_produk";
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
    
    public function getProdukByCategory($storeId = null) {
        if ($storeId) {
            $query = "SELECT kategori, produk_id, nama_produk, harga, stok, poin_reward, foto_produk FROM produk WHERE stok > 0 AND store_id = ? ORDER BY kategori, nama_produk";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $storeId);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $query = "SELECT kategori, produk_id, nama_produk, harga, stok, poin_reward, foto_produk FROM produk WHERE stok > 0 ORDER BY kategori, nama_produk";
            $result = $this->conn->query($query);
        }
        
        $produkByCategory = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $produkByCategory[$row['kategori']][] = $row;
            }
        }
        
        return $produkByCategory;
    }
    
    public function getProdukById($produkId, $storeId = null) {
        if ($storeId) {
            $stmt = $this->conn->prepare("SELECT produk_id, nama_produk, harga, stok, kategori, poin_reward, foto_produk FROM produk WHERE produk_id = ? AND store_id = ?");
            $stmt->bind_param("ii", $produkId, $storeId);
        } else {
            $stmt = $this->conn->prepare("SELECT produk_id, nama_produk, harga, stok, kategori, poin_reward, foto_produk FROM produk WHERE produk_id = ?");
            $stmt->bind_param("i", $produkId);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
    
    public function getAllStores() {
        $query = "SELECT id_store, nama_store, alamat_store FROM store WHERE status_store = 'aktif' ORDER BY nama_store";
        $result = $this->conn->query($query);
        
        $stores = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $stores[] = $row;
            }
        }
        
        return $stores;
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
    
    public function calculateTotal($items, $diskonPersen = 0, $storeId = null) {
        $totalSebelumDiskon = 0;
        $totalPoinItem = 0;
        
        foreach ($items as $item) {
            $produk = $this->getProdukById($item['produk_id'], $storeId);
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
    
    public function createTransaksi($customerId, $items, $metodePembayaran = 'tunai', $storeId = 1, $buktiPembayaran = null) {
        try {
            $this->conn->begin_transaction();
            
            $memberData = $this->getMemberDataByCustomerId($customerId);
            if (!$memberData) {
                throw new Exception("Data member tidak ditemukan untuk customer_id: $customerId");
            }
            
            $calculation = $this->calculateTotal($items, $memberData['diskon_persen'], $storeId);
            $poinDidapat = ($calculation['total_poin_item'] * $memberData['poin_per_pembelian']) + floor($calculation['total_bayar'] / 10000);
            
            $newTotalPembelian = $memberData['total_pembelian'] + $calculation['total_bayar'];
            $newTotalPoin = $memberData['total_poin'] + $poinDidapat;
            
            $currentMembership = $memberData['membership_id'];
            $newMembership = $this->calculateNewMembership($newTotalPembelian);
            
            $stmt = $this->conn->prepare("
                INSERT INTO transaksi (customer_id, store_id, total_sebelum_diskon, diskon_membership, total_bayar, poin_didapat, metode_pembayaran, bukti_pembayaran) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            if (!$stmt) {
                throw new Exception("Gagal menyiapkan statement transaksi: " . $this->conn->error);
            }
            
            $stmt->bind_param("iiddisss", 
                $customerId, 
                $storeId,
                $calculation['total_sebelum_diskon'], 
                $calculation['diskon_amount'], 
                $calculation['total_bayar'], 
                $poinDidapat, 
                $metodePembayaran,
                $buktiPembayaran
            );
            
            if (!$stmt->execute()) {
                throw new Exception("Gagal menyimpan transaksi: " . $stmt->error);
            }
            
            $transaksiId = $this->conn->insert_id;
            if (!$transaksiId) {
                throw new Exception("Gagal mendapatkan ID transaksi");
            }
            
            foreach ($items as $item) {
                $produk = $this->getProdukById($item['produk_id'], $storeId);
                if (!$produk) {
                    throw new Exception("Produk dengan ID {$item['produk_id']} tidak ditemukan di store ini");
                }
                
                if ($produk['stok'] < $item['jumlah']) {
                    throw new Exception("Stok {$produk['nama_produk']} tidak mencukupi. Tersedia: {$produk['stok']}, Diminta: {$item['jumlah']}");
                }
                
                $subtotal = $produk['harga'] * $item['jumlah'];
                $totalPoinItem = $produk['poin_reward'] * $item['jumlah'] * $memberData['poin_per_pembelian'];
                
                $detailStmt = $this->conn->prepare("
                    INSERT INTO detail_transaksi (transaksi_id, produk_id, jumlah, harga_satuan, subtotal, poin_produk, total_poin_item) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                ");
                
                if (!$detailStmt) {
                    throw new Exception("Gagal menyiapkan statement detail: " . $this->conn->error);
                }
                
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
                
                $updateStokStmt = $this->conn->prepare("UPDATE produk SET stok = stok - ? WHERE produk_id = ? AND store_id = ?");
                if (!$updateStokStmt) {
                    throw new Exception("Gagal menyiapkan statement update stok: " . $this->conn->error);
                }
                
                $updateStokStmt->bind_param("iii", $item['jumlah'], $item['produk_id'], $storeId);
                
                if (!$updateStokStmt->execute()) {
                    throw new Exception("Gagal mengupdate stok produk {$produk['nama_produk']}: " . $updateStokStmt->error);
                }
                
                if ($updateStokStmt->affected_rows === 0) {
                    throw new Exception("Produk {$produk['nama_produk']} tidak ditemukan di store yang dipilih");
                }
            }
            
            $updateFields = [];
            $updateValues = [];
            $updateTypes = "";
            
            $updateFields[] = "total_pembelian = ?";
            $updateValues[] = $newTotalPembelian;
            $updateTypes .= "d";
            
            $updateFields[] = "total_poin = ?";
            $updateValues[] = $newTotalPoin;
            $updateTypes .= "i";
            
            if ($newMembership['id'] > $currentMembership) {
                $updateFields[] = "membership_id = ?";
                $updateValues[] = $newMembership['id'];
                $updateTypes .= "i";
            }
            
            $updateValues[] = $customerId;
            $updateTypes .= "i";
            
            $updateSql = "UPDATE customers SET " . implode(", ", $updateFields) . " WHERE customer_id = ?";
            
            $updateCustomerStmt = $this->conn->prepare($updateSql);
            if (!$updateCustomerStmt) {
                throw new Exception("Gagal menyiapkan statement update customer: " . $this->conn->error);
            }
            
            $updateCustomerStmt->bind_param($updateTypes, ...$updateValues);
            
            if (!$updateCustomerStmt->execute()) {
                throw new Exception("Gagal mengupdate data customer: " . $updateCustomerStmt->error);
            }
            
            try {
                $this->insertAktivitasCustomer($customerId, 'pembelian', "Transaksi #$transaksiId - Total: Rp " . number_format($calculation['total_bayar']));
            } catch (Exception $e) {
                error_log("Warning: Gagal insert aktivitas: " . $e->getMessage());
            }
            
            $membershipUpdate = ['upgraded' => false];
            if ($newMembership['id'] > $currentMembership) {
                try {
                    $this->insertAktivitasCustomer($customerId, 'follow_up', "Naik tier menjadi {$newMembership['name']}");
                } catch (Exception $e) {
                    error_log("Warning: Gagal insert aktivitas membership: " . $e->getMessage());
                }
                
                $membershipUpdate = [
                    'upgraded' => true,
                    'new_membership' => $newMembership['name'],
                    'old_membership_id' => $currentMembership,
                    'new_membership_id' => $newMembership['id']
                ];
            }
            
            $this->conn->commit();
            
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
            error_log("Error dalam createTransaksi: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }
    
    private function calculateNewMembership($totalPembelian) {
        if ($totalPembelian >= 500000) {
            return ['id' => 4, 'name' => 'Platinum'];
        } elseif ($totalPembelian >= 300000) {
            return ['id' => 3, 'name' => 'Gold'];
        } elseif ($totalPembelian >= 100000) {
            return ['id' => 2, 'name' => 'Silver'];
        } else {
            return ['id' => 1, 'name' => 'Bronze'];
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
                c.status_aktif,
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
                m.nama_membership,
                s.nama_store,
                s.alamat_store
            FROM transaksi t
            JOIN customers c ON t.customer_id = c.customer_id
            JOIN membership m ON c.membership_id = m.membership_id
            JOIN store s ON t.store_id = s.id_store
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
                    p.kategori,
                    p.foto_produk
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
                t.bukti_pembayaran,
                s.nama_store,
                COUNT(dt.detail_id) as total_item
            FROM transaksi t
            LEFT JOIN detail_transaksi dt ON t.transaksi_id = dt.transaksi_id
            LEFT JOIN store s ON t.store_id = s.id_store
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
    
    public function validateStock($items, $storeId = null) {
        $errors = [];
        
        foreach ($items as $item) {
            $produk = $this->getProdukById($item['produk_id'], $storeId);
            if (!$produk) {
                $errors[] = "Produk dengan ID {$item['produk_id']} tidak ditemukan di store yang dipilih";
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
        if ($stmt) {
            $stmt->bind_param("iss", $customerId, $jenisAktivitas, $catatan);
            $stmt->execute();
        }
    }
    
    public function searchProduk($keyword, $storeId = null) {
        $searchTerm = "%$keyword%";
        
        if ($storeId) {
            $stmt = $this->conn->prepare("
                SELECT produk_id, nama_produk, harga, stok, kategori, poin_reward, foto_produk 
                FROM produk 
                WHERE (nama_produk LIKE ? OR kategori LIKE ?) AND stok > 0 AND store_id = ?
                ORDER BY nama_produk
            ");
            $stmt->bind_param("ssi", $searchTerm, $searchTerm, $storeId);
        } else {
            $stmt = $this->conn->prepare("
                SELECT produk_id, nama_produk, harga, stok, kategori, poin_reward, foto_produk 
                FROM produk 
                WHERE (nama_produk LIKE ? OR kategori LIKE ?) AND stok > 0 
                ORDER BY nama_produk
            ");
            $stmt->bind_param("ss", $searchTerm, $searchTerm);
        }
        
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
        return ['upgraded' => false];
    }
}
?>