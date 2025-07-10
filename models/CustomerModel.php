<?php
class CustomerModel {
    private $conn;
    
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }
    
    public function getAllCustomers() {
        $query = "SELECT c.*, m.nama_membership 
                  FROM customers c 
                  JOIN membership m ON c.membership_id = m.membership_id 
                  ORDER BY c.customer_id DESC";
        $result = $this->conn->query($query);
        
        $customers = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $customers[] = $row;
            }
        }
        
        return $customers;
    }
    
    public function getCustomerById($id) {
        $stmt = $this->conn->prepare("SELECT c.*, m.nama_membership 
                                      FROM customers c 
                                      JOIN membership m ON c.membership_id = m.membership_id 
                                      WHERE c.customer_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result ? $result->fetch_assoc() : null;
    }
    
    public function createCustomer($data) {
        $stmt = $this->conn->prepare("INSERT INTO customers (nama_customer, no_telepon, alamat, email, membership_id) 
                                      VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $data['nama_customer'], $data['no_telepon'], $data['alamat'], 
                          $data['email'], $data['membership_id']);
        
        if ($stmt->execute()) {
            return [
                'success' => true,
                'message' => 'Customer berhasil ditambahkan',
                'id' => $this->conn->insert_id
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Gagal menambahkan customer: ' . $stmt->error
            ];
        }
    }
    
    public function updateCustomer($id, $data) {
        $stmt = $this->conn->prepare("UPDATE customers SET nama_customer = ?, no_telepon = ?, alamat = ?, 
                                      email = ?, membership_id = ? WHERE customer_id = ?");
        $stmt->bind_param("ssssii", $data['nama_customer'], $data['no_telepon'], $data['alamat'], 
                          $data['email'], $data['membership_id'], $id);
        
        if ($stmt->execute()) {
            return [
                'success' => true,
                'message' => 'Customer berhasil diupdate'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Gagal mengupdate customer: ' . $stmt->error
            ];
        }
    }
    
    public function deleteCustomer($id) {
        $stmt = $this->conn->prepare("DELETE FROM customers WHERE customer_id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            return [
                'success' => true,
                'message' => 'Customer berhasil dihapus'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Gagal menghapus customer: ' . $stmt->error
            ];
        }
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
    
    public function searchCustomers($keyword) {
        $keyword = "%$keyword%";
        $stmt = $this->conn->prepare("SELECT c.*, m.nama_membership 
                                      FROM customers c 
                                      JOIN membership m ON c.membership_id = m.membership_id 
                                      WHERE c.nama_customer LIKE ? OR c.no_telepon LIKE ? OR c.email LIKE ?
                                      ORDER BY c.nama_customer");
        $stmt->bind_param("sss", $keyword, $keyword, $keyword);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $customers = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $customers[] = $row;
            }
        }
        
        return $customers;
    }
    
    public function getCustomersByMembership($membership_id) {
        $stmt = $this->conn->prepare("SELECT c.*, m.nama_membership 
                                      FROM customers c 
                                      JOIN membership m ON c.membership_id = m.membership_id 
                                      WHERE c.membership_id = ?
                                      ORDER BY c.nama_customer");
        $stmt->bind_param("i", $membership_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $customers = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $customers[] = $row;
            }
        }
        
        return $customers;
    }
    
    public function getCustomerTransactions($customer_id) {
        $stmt = $this->conn->prepare("SELECT t.*, COUNT(dt.detail_id) as total_items
                                      FROM transaksi t
                                      LEFT JOIN detail_transaksi dt ON t.transaksi_id = dt.transaksi_id
                                      WHERE t.customer_id = ?
                                      GROUP BY t.transaksi_id
                                      ORDER BY t.tanggal_transaksi DESC
                                      LIMIT 10");
        $stmt->bind_param("i", $customer_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $transactions = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $transactions[] = $row;
            }
        }
        
        return $transactions;
    }
    
    public function validateCustomer($data) {
        $errors = [];
        
        if (empty($data['nama_customer'])) {
            $errors[] = "Nama customer tidak boleh kosong";
        }
        
        if (empty($data['no_telepon'])) {
            $errors[] = "No telepon tidak boleh kosong";
        } elseif (!preg_match('/^[0-9+\-\s]+$/', $data['no_telepon'])) {
            $errors[] = "Format no telepon tidak valid";
        }
        
        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Format email tidak valid";
        }
        
        if (empty($data['membership_id'])) {
            $errors[] = "Membership harus dipilih";
        }
        
        return $errors;
    }
    
    public function updateMembershipByPembelian($customer_id, $total_pembelian) {
        $stmt = $this->conn->prepare("UPDATE customers SET total_pembelian = total_pembelian + ? WHERE customer_id = ?");
        $stmt->bind_param("di", $total_pembelian, $customer_id);
        $stmt->execute();
        
        $customer = $this->getCustomerById($customer_id);
        $new_total = $customer['total_pembelian'];
        
        $new_membership_id = 1;
        if ($new_total >= 500000) {
            $new_membership_id = 4;
        } elseif ($new_total >= 300000) {
            $new_membership_id = 3;
        } elseif ($new_total >= 100000) {
            $new_membership_id = 2;
        }
        
        if ($new_membership_id != $customer['membership_id']) {
            $stmt = $this->conn->prepare("UPDATE customers SET membership_id = ? WHERE customer_id = ?");
            $stmt->bind_param("ii", $new_membership_id, $customer_id);
            $stmt->execute();
        }
        
        return $new_membership_id;
    }
    
    public function addPoints($customer_id, $points) {
        $stmt = $this->conn->prepare("UPDATE customers SET total_poin = total_poin + ? WHERE customer_id = ?");
        $stmt->bind_param("ii", $points, $customer_id);
        return $stmt->execute();
    }
    
    public function getTotalCustomers() {
        $query = "SELECT COUNT(*) as total FROM customers WHERE status_aktif = 'aktif'";
        $result = $this->conn->query($query);
        return $result ? $result->fetch_assoc()['total'] : 0;
    }
    
    public function getCustomerStats() {
        $stats = [];
        
        $query = "SELECT m.nama_membership, COUNT(c.customer_id) as total
                  FROM membership m
                  LEFT JOIN customers c ON m.membership_id = c.membership_id AND c.status_aktif = 'aktif'
                  GROUP BY m.membership_id
                  ORDER BY m.membership_id";
        $result = $this->conn->query($query);
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $stats[] = $row;
            }
        }
        
        return $stats;
    }
}
?>