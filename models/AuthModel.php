<?php
class AuthModel {
    private $conn;
    
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }
    
    public function login($username, $password) {
        $stmt = $this->conn->prepare("SELECT user_id, username, password, nama_lengkap, email, role, status, store_id FROM users WHERE username = ? AND status = 'aktif'");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            if ($password === $user['password']) {
                $this->updateLastLogin($user['user_id']);
                
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['status'] = $user['status'];
                $_SESSION['store_id'] = $user['store_id'];
                $_SESSION['logged_in'] = true;
                
                if ($user['role'] === 'member') {
                    $memberData = $this->getMemberData($user['user_id']);
                    if ($memberData) {
                        $_SESSION['customer_id'] = $memberData['customer_id'];
                        $_SESSION['nama_customer'] = $memberData['nama_customer'];
                        $_SESSION['membership_id'] = $memberData['membership_id'];
                        $_SESSION['nama_membership'] = $memberData['nama_membership'];
                        $_SESSION['total_poin'] = $memberData['total_poin'];
                        $_SESSION['total_pembelian'] = $memberData['total_pembelian'];
                    }
                }
                
                if ($user['role'] === 'manajer' && $user['store_id']) {
                    $storeData = $this->getStoreData($user['store_id']);
                    if ($storeData) {
                        $_SESSION['nama_store'] = $storeData['nama_store'];
                        $_SESSION['alamat_store'] = $storeData['alamat_store'];
                        $_SESSION['manajer_store'] = $storeData['manajer_store'];
                    }
                }
                
                return [
                    'success' => true,
                    'message' => 'Login berhasil',
                    'user' => $user
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Password salah'
                ];
            }
        } else {
            return [
                'success' => false,
                'message' => 'Username tidak ditemukan atau akun tidak aktif'
            ];
        }
    }
    
    public function logout() {
        session_destroy();
        return [
            'success' => true,
            'message' => 'Logout berhasil'
        ];
    }
    
    public function isLoggedIn() {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }
    
    public function getLoggedInUser() {
        if ($this->isLoggedIn()) {
            $userData = [
                'user_id' => $_SESSION['user_id'],
                'username' => $_SESSION['username'],
                'nama_lengkap' => $_SESSION['nama_lengkap'],
                'email' => $_SESSION['email'],
                'role' => $_SESSION['role'],
                'status' => $_SESSION['status'],
                'store_id' => $_SESSION['store_id'] ?? null
            ];
            
            if ($_SESSION['role'] === 'member') {
                $userData['customer_id'] = $_SESSION['customer_id'] ?? null;
                $userData['nama_customer'] = $_SESSION['nama_customer'] ?? null;
                $userData['membership_id'] = $_SESSION['membership_id'] ?? null;
                $userData['nama_membership'] = $_SESSION['nama_membership'] ?? null;
                $userData['total_poin'] = $_SESSION['total_poin'] ?? 0;
                $userData['total_pembelian'] = $_SESSION['total_pembelian'] ?? 0;
            }
            
            if ($_SESSION['role'] === 'manajer') {
                $userData['nama_store'] = $_SESSION['nama_store'] ?? null;
                $userData['alamat_store'] = $_SESSION['alamat_store'] ?? null;
                $userData['manajer_store'] = $_SESSION['manajer_store'] ?? null;
            }
            
            return $userData;
        }
        return null;
    }
    
    public function checkRole($allowedRoles) {
        if (!$this->isLoggedIn()) {
            return false;
        }
        
        $userRole = $_SESSION['role'];
        return in_array($userRole, $allowedRoles);
    }
    
    public function requireLogin() {
        if (!$this->isLoggedIn()) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
    }
    
    public function requireRole($allowedRoles) {
        $this->requireLogin();
        
        if (!$this->checkRole($allowedRoles)) {
            header('Location: index.php?controller=auth&action=unauthorized');
            exit;
        }
    }
    
    public function requireAdminRole() {
        $this->requireRole(['admin']);
    }
    
    public function requirePimpinanRole() {
        $this->requireRole(['pimpinan']);
    }
    
    public function requireMemberRole() {
        $this->requireRole(['member']);
    }
    
    public function requireManajerRole() {
        $this->requireRole(['manajer']);
    }
    
    public function requireAdminOrPimpinanRole() {
        $this->requireRole(['admin', 'pimpinan']);
    }
    
    public function requireAdminOrManajerRole() {
        $this->requireRole(['admin', 'manajer']);
    }
    
    public function requireAllStaffRole() {
        $this->requireRole(['admin', 'pimpinan', 'manajer']);
    }
    
    public function getUserStoreId() {
        if ($this->isLoggedIn() && $_SESSION['role'] === 'manajer') {
            return $_SESSION['store_id'] ?? null;
        }
        return null;
    }
    
    public function isManagerOfStore($storeId) {
        if ($this->isLoggedIn() && $_SESSION['role'] === 'manajer') {
            return $_SESSION['store_id'] == $storeId;
        }
        return false;
    }
    
    public function canAccessStore($storeId) {
        if (!$this->isLoggedIn()) {
            return false;
        }
        
        $role = $_SESSION['role'];
        
        if ($role === 'admin' || $role === 'pimpinan') {
            return true;
        }
        
        if ($role === 'manajer') {
            return $_SESSION['store_id'] == $storeId;
        }
        
        return false;
    }
    
    public function getMemberData($userId) {
        $stmt = $this->conn->prepare("
            SELECT 
                c.customer_id,
                c.nama_customer,
                c.membership_id,
                c.total_poin,
                c.total_pembelian,
                m.nama_membership
            FROM customers c
            JOIN membership m ON c.membership_id = m.membership_id
            WHERE c.user_id = ? AND c.status_aktif = 'aktif'
        ");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
    
    public function getStoreData($storeId) {
        if (empty($storeId)) {
            return null;
        }
        
        $stmt = $this->conn->prepare("SELECT nama_store, alamat_store, manajer_store FROM store WHERE id_store = ? AND status_store = 'aktif'");
        $stmt->bind_param("i", $storeId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
    
    public function updateMemberSession($userId) {
        if ($_SESSION['role'] === 'member') {
            $memberData = $this->getMemberData($userId);
            if ($memberData) {
                $_SESSION['customer_id'] = $memberData['customer_id'];
                $_SESSION['nama_customer'] = $memberData['nama_customer'];
                $_SESSION['membership_id'] = $memberData['membership_id'];
                $_SESSION['nama_membership'] = $memberData['nama_membership'];
                $_SESSION['total_poin'] = $memberData['total_poin'];
                $_SESSION['total_pembelian'] = $memberData['total_pembelian'];
                return true;
            }
        }
        return false;
    }
    
    public function updateManagerSession($userId) {
        if ($_SESSION['role'] === 'manajer' && $_SESSION['store_id']) {
            $storeData = $this->getStoreData($_SESSION['store_id']);
            if ($storeData) {
                $_SESSION['nama_store'] = $storeData['nama_store'];
                $_SESSION['alamat_store'] = $storeData['alamat_store'];
                $_SESSION['manajer_store'] = $storeData['manajer_store'];
                return true;
            }
        }
        return false;
    }
    
    public function register($userData) {
        $username = $userData['username'];
        $password = $userData['password'];
        $nama_lengkap = $userData['nama_lengkap'];
        $email = $userData['email'];
        $no_telepon = $userData['no_telepon'] ?? null;
        $alamat = $userData['alamat'] ?? null;
        
        $this->conn->begin_transaction();
        
        try {
            $checkStmt = $this->conn->prepare("SELECT user_id FROM users WHERE username = ? OR email = ?");
            $checkStmt->bind_param("ss", $username, $email);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();
            
            if ($checkResult->num_rows > 0) {
                throw new Exception("Username atau email sudah terdaftar");
            }
            
            $userStmt = $this->conn->prepare("INSERT INTO users (username, password, nama_lengkap, email, role, status) VALUES (?, ?, ?, ?, 'member', 'aktif')");
            $userStmt->bind_param("ssss", $username, $password, $nama_lengkap, $email);
            $userStmt->execute();
            
            $userId = $this->conn->insert_id;
            
            $customerStmt = $this->conn->prepare("INSERT INTO customers (user_id, nama_customer, no_telepon, alamat, email, membership_id, total_pembelian, total_poin, status_aktif) VALUES (?, ?, ?, ?, ?, 1, 0.00, 0, 'aktif')");
            $customerStmt->bind_param("issss", $userId, $nama_lengkap, $no_telepon, $alamat, $email);
            $customerStmt->execute();
            
            $this->conn->commit();
            
            return [
                'success' => true,
                'message' => 'Registrasi berhasil',
                'user_id' => $userId
            ];
            
        } catch (Exception $e) {
            $this->conn->rollback();
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    public function changePassword($userId, $oldPassword, $newPassword) {
        $stmt = $this->conn->prepare("SELECT password FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            if ($oldPassword === $user['password']) {
                $updateStmt = $this->conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
                $updateStmt->bind_param("si", $newPassword, $userId);
                $updateStmt->execute();
                
                return [
                    'success' => true,
                    'message' => 'Password berhasil diubah'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Password lama salah'
                ];
            }
        } else {
            return [
                'success' => false,
                'message' => 'User tidak ditemukan'
            ];
        }
    }
    
    public function updateProfile($userId, $profileData) {
        $nama_lengkap = $profileData['nama_lengkap'];
        $email = $profileData['email'];
        $no_telepon = $profileData['no_telepon'] ?? null;
        $alamat = $profileData['alamat'] ?? null;
        
        $this->conn->begin_transaction();
        
        try {
            $userStmt = $this->conn->prepare("UPDATE users SET nama_lengkap = ?, email = ? WHERE user_id = ?");
            $userStmt->bind_param("ssi", $nama_lengkap, $email, $userId);
            $userStmt->execute();
            
            if ($_SESSION['role'] === 'member') {
                $customerStmt = $this->conn->prepare("UPDATE customers SET nama_customer = ?, email = ?, no_telepon = ?, alamat = ? WHERE user_id = ?");
                $customerStmt->bind_param("ssssi", $nama_lengkap, $email, $no_telepon, $alamat, $userId);
                $customerStmt->execute();
                
                $this->updateMemberSession($userId);
            }
            
            $_SESSION['nama_lengkap'] = $nama_lengkap;
            $_SESSION['email'] = $email;
            
            $this->conn->commit();
            
            return [
                'success' => true,
                'message' => 'Profil berhasil diperbarui'
            ];
            
        } catch (Exception $e) {
            $this->conn->rollback();
            return [
                'success' => false,
                'message' => 'Gagal memperbarui profil: ' . $e->getMessage()
            ];
        }
    }
    
    private function updateLastLogin($userId) {
        $stmt = $this->conn->prepare("UPDATE users SET terakhir_login = NOW() WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
    }
    
    public function validateInput($username, $password) {
        $errors = [];
        
        if (empty($username)) {
            $errors[] = "Username tidak boleh kosong";
        }
        
        if (empty($password)) {
            $errors[] = "Password tidak boleh kosong";
        }
        
        if (strlen($username) < 3) {
            $errors[] = "Username minimal 3 karakter";
        }
        
        return $errors;
    }
    
    public function validateRegistration($userData) {
        $errors = [];
        
        if (empty($userData['username'])) {
            $errors[] = "Username tidak boleh kosong";
        } elseif (strlen($userData['username']) < 3) {
            $errors[] = "Username minimal 3 karakter";
        }
        
        if (empty($userData['password'])) {
            $errors[] = "Password tidak boleh kosong";
        } elseif (strlen($userData['password']) < 6) {
            $errors[] = "Password minimal 6 karakter";
        }
        
        if (empty($userData['nama_lengkap'])) {
            $errors[] = "Nama lengkap tidak boleh kosong";
        }
        
        if (empty($userData['email'])) {
            $errors[] = "Email tidak boleh kosong";
        } elseif (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Format email tidak valid";
        }
        
        return $errors;
    }
    
    public function getUserRole() {
        return $_SESSION['role'] ?? null;
    }
    
    public function isAdmin() {
        return $this->getUserRole() === 'admin';
    }
    
    public function isPimpinan() {
        return $this->getUserRole() === 'pimpinan';
    }
    
    public function isMember() {
        return $this->getUserRole() === 'member';
    }
    
    public function isManajer() {
        return $this->getUserRole() === 'manajer';
    }
    
    public function isStaff() {
        return in_array($this->getUserRole(), ['admin', 'pimpinan', 'manajer']);
    }
}