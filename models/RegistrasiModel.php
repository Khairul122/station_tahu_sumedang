<?php
class RegistrasiModel {
    private $conn;
    
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }
    
    public function register($userData, $asMembership = false) {
        $this->conn->begin_transaction();
        
        try {
            $stmtUser = $this->conn->prepare("INSERT INTO users (username, password, nama_lengkap, email, role, status, tanggal_dibuat) VALUES (?, ?, ?, ?, 'member', 'aktif', NOW())");
            $stmtUser->bind_param("ssss", $userData['username'], $userData['password'], $userData['nama_lengkap'], $userData['email']);
            $stmtUser->execute();
            $userId = $this->conn->insert_id;
            
            if ($asMembership) {
                $stmtCustomer = $this->conn->prepare("INSERT INTO customers (user_id, nama_customer, no_telepon, alamat, email, membership_id, total_pembelian, total_poin, tanggal_daftar, status_aktif) VALUES (?, ?, ?, ?, ?, 1, 0.00, 0, CURDATE(), 'aktif')");
                $stmtCustomer->bind_param("issss", $userId, $userData['nama_lengkap'], $userData['no_telepon'], $userData['alamat'], $userData['email']);
                $stmtCustomer->execute();
            } else {
                $stmtCustomer = $this->conn->prepare("INSERT INTO customers (user_id, nama_customer, no_telepon, alamat, email, membership_id, total_pembelian, total_poin, tanggal_daftar, status_aktif) VALUES (?, ?, ?, ?, ?, NULL, 0.00, 0, CURDATE(), NULL)");
                $stmtCustomer->bind_param("issss", $userId, $userData['nama_lengkap'], $userData['no_telepon'], $userData['alamat'], $userData['email']);
                $stmtCustomer->execute();
            }
            
            $this->conn->commit();
            return ['success' => true, 'message' => 'Registrasi berhasil'];
            
        } catch (Exception $e) {
            $this->conn->rollback();
            return ['success' => false, 'message' => 'Registrasi gagal: ' . $e->getMessage()];
        }
    }
    
    public function checkUsername($username) {
        $stmt = $this->conn->prepare("SELECT user_id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }
    
    public function checkEmail($email) {
        $stmt = $this->conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }
    
    public function validateInput($data) {
        $errors = [];
        
        if (empty($data['username']) || strlen($data['username']) < 3) {
            $errors[] = "Username minimal 3 karakter";
        } elseif ($this->checkUsername($data['username'])) {
            $errors[] = "Username sudah digunakan";
        }
        
        if (empty($data['password']) || strlen($data['password']) < 6) {
            $errors[] = "Password minimal 6 karakter";
        }
        
        if ($data['password'] !== $data['confirm_password']) {
            $errors[] = "Password tidak cocok";
        }
        
        if (empty($data['nama_lengkap'])) {
            $errors[] = "Nama lengkap wajib diisi";
        }
        
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email tidak valid";
        } elseif ($this->checkEmail($data['email'])) {
            $errors[] = "Email sudah digunakan";
        }
        
        return $errors;
    }
}
?>