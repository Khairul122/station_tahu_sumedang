<?php
require_once 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class RegistrasiModel {
    private $conn;
    
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }
    
    public function register($userData, $asMembership = false) {
        $this->conn->begin_transaction();
        
        try {
            $kodeKonfirmasi = $this->generateKodeKonfirmasi();
            $expired = date('Y-m-d H:i:s', strtotime('+24 hours'));
            
            $stmtUser = $this->conn->prepare("INSERT INTO users (username, password, nama_lengkap, email, role, status, tanggal_dibuat, kode_konfirmasi, kode_expired, email_verified) VALUES (?, ?, ?, ?, 'member', 'tidak_aktif', NOW(), ?, ?, 0)");
            $stmtUser->bind_param("ssssss", $userData['username'], $userData['password'], $userData['nama_lengkap'], $userData['email'], $kodeKonfirmasi, $expired);
            $stmtUser->execute();
            $userId = $this->conn->insert_id;
            
            if ($asMembership) {
                $stmtCustomer = $this->conn->prepare("INSERT INTO customers (user_id, nama_customer, no_telepon, alamat, email, membership_id, total_pembelian, total_poin, tanggal_daftar, status_aktif) VALUES (?, ?, ?, ?, ?, 1, 0.00, 0, CURDATE(), 'tidak_aktif')");
                $stmtCustomer->bind_param("issss", $userId, $userData['nama_lengkap'], $userData['no_telepon'], $userData['alamat'], $userData['email']);
                $stmtCustomer->execute();
            }
            
            $emailSent = $this->sendKonfirmasiEmail($userData['email'], $userData['nama_lengkap'], $kodeKonfirmasi);
            
            if (!$emailSent) {
                throw new Exception('Gagal mengirim email konfirmasi');
            }
            
            $this->conn->commit();
            return ['success' => true, 'message' => 'Registrasi berhasil! Silakan cek email untuk kode konfirmasi.'];
            
        } catch (Exception $e) {
            $this->conn->rollback();
            return ['success' => false, 'message' => 'Registrasi gagal: ' . $e->getMessage()];
        }
    }
    
    public function verifikasiEmail($email, $kodeKonfirmasi) {
        try {
            $stmt = $this->conn->prepare("SELECT user_id, kode_konfirmasi, kode_expired, email_verified FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 0) {
                return ['success' => false, 'message' => 'Email tidak ditemukan'];
            }
            
            $user = $result->fetch_assoc();
            
            if ($user['email_verified'] == 1) {
                return ['success' => false, 'message' => 'Email sudah terverifikasi'];
            }
            
            if (strtotime($user['kode_expired']) < time()) {
                return ['success' => false, 'message' => 'Kode sudah expired'];
            }
            
            if ($user['kode_konfirmasi'] !== $kodeKonfirmasi) {
                return ['success' => false, 'message' => 'Kode konfirmasi salah'];
            }
            
            $this->conn->begin_transaction();
            
            $stmtUser = $this->conn->prepare("UPDATE users SET email_verified = 1, status = 'aktif', kode_konfirmasi = NULL, kode_expired = NULL WHERE user_id = ?");
            $stmtUser->bind_param("i", $user['user_id']);
            $stmtUser->execute();
            
            $stmtCustomer = $this->conn->prepare("UPDATE customers SET status_aktif = 'aktif' WHERE user_id = ?");
            $stmtCustomer->bind_param("i", $user['user_id']);
            $stmtCustomer->execute();
            
            $this->conn->commit();
            
            return ['success' => true, 'message' => 'Email berhasil diverifikasi! Silakan login.'];
            
        } catch (Exception $e) {
            $this->conn->rollback();
            return ['success' => false, 'message' => 'Verifikasi gagal: ' . $e->getMessage()];
        }
    }
    
    public function resendKodeKonfirmasi($email) {
        try {
            $stmt = $this->conn->prepare("SELECT user_id, nama_lengkap, email_verified FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 0) {
                return ['success' => false, 'message' => 'Email tidak ditemukan'];
            }
            
            $user = $result->fetch_assoc();
            
            if ($user['email_verified'] == 1) {
                return ['success' => false, 'message' => 'Email sudah terverifikasi'];
            }
            
            $kodeKonfirmasi = $this->generateKodeKonfirmasi();
            $expired = date('Y-m-d H:i:s', strtotime('+24 hours'));
            
            $stmtUpdate = $this->conn->prepare("UPDATE users SET kode_konfirmasi = ?, kode_expired = ? WHERE user_id = ?");
            $stmtUpdate->bind_param("ssi", $kodeKonfirmasi, $expired, $user['user_id']);
            $stmtUpdate->execute();
            
            $emailSent = $this->sendKonfirmasiEmail($email, $user['nama_lengkap'], $kodeKonfirmasi);
            
            if (!$emailSent) {
                return ['success' => false, 'message' => 'Gagal mengirim email'];
            }
            
            return ['success' => true, 'message' => 'Kode baru telah dikirim ke email'];
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Gagal mengirim ulang: ' . $e->getMessage()];
        }
    }
    
    private function generateKodeKonfirmasi() {
        return sprintf('%06d', mt_rand(100000, 999999));
    }
    
    private function sendKonfirmasiEmail($email, $namaLengkap, $kodeKonfirmasi) {
        $mail = new PHPMailer(true);
        
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'khairulhuda242@gmail.com';
            $mail->Password = 'xvom gaxf itrr msol';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            
            $mail->setFrom('khairulhuda242@gmail.com', 'Station Tahu Sumedang');
            $mail->addAddress($email, $namaLengkap);
            
            $mail->isHTML(true);
            $mail->Subject = 'Kode Konfirmasi Email - Station Tahu Sumedang';
            
            $mail->Body = "
            <div style='font-family: Arial, sans-serif; max-width: 500px; margin: 0 auto; padding: 20px;'>
                <div style='background: #667eea; color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0;'>
                    <h2>Station Tahu Sumedang</h2>
                    <p>Konfirmasi Email</p>
                </div>
                <div style='background: #f9f9f9; padding: 20px; border-radius: 0 0 10px 10px;'>
                    <h3>Halo, $namaLengkap!</h3>
                    <p>Kode konfirmasi email Anda adalah:</p>
                    <div style='background: white; border: 2px dashed #667eea; padding: 15px; text-align: center; font-size: 24px; font-weight: bold; color: #667eea; margin: 20px 0;'>
                        $kodeKonfirmasi
                    </div>
                    <p><strong>Catatan:</strong></p>
                    <ul>
                        <li>Kode berlaku 24 jam</li>
                        <li>Jangan bagikan kode ini</li>
                    </ul>
                    <p>Terima kasih!</p>
                </div>
            </div>
            ";
            
            $mail->send();
            return true;
            
        } catch (Exception $e) {
            error_log("Gagal kirim email: " . $mail->ErrorInfo);
            return false;
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
        
        if (empty($data['no_telepon'])) {
            $errors[] = "Nomor telepon wajib diisi";
        }
        
        return $errors;
    }
}
?>