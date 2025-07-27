<?php
class RegistrasiController {
    private $registrasiModel;
    
    public function __construct() {
        $this->registrasiModel = new RegistrasiModel();
    }
    
    public function index() {
        $this->register();
    }
    
    public function register() {
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'username' => $_POST['username'] ?? '',
                'password' => $_POST['password'] ?? '',
                'confirm_password' => $_POST['confirm_password'] ?? '',
                'nama_lengkap' => $_POST['nama_lengkap'] ?? '',
                'email' => $_POST['email'] ?? '',
                'no_telepon' => $_POST['no_telepon'] ?? '',
                'alamat' => $_POST['alamat'] ?? ''
            ];
            
            $asMembership = isset($_POST['as_membership']);
            
            $errors = $this->registrasiModel->validateInput($data);
            
            if (empty($errors)) {
                $result = $this->registrasiModel->register($data, $asMembership);
                
                if ($result['success']) {
                    $success = $result['message'];
                    header("refresh:3;url=index.php?controller=registrasi&action=konfirmasi&email=" . urlencode($data['email']));
                } else {
                    $error = $result['message'];
                }
            } else {
                $error = implode('<br>', $errors);
            }
        }
        
        include 'views/auth/register.php';
    }
    
    public function konfirmasi() {
        $email = $_GET['email'] ?? '';
        $error = '';
        $success = '';
        
        if (empty($email)) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $kodeKonfirmasi = $_POST['kode_konfirmasi'] ?? '';
            
            if (empty($kodeKonfirmasi)) {
                $error = 'Kode konfirmasi wajib diisi';
            } else {
                $result = $this->registrasiModel->verifikasiEmail($email, $kodeKonfirmasi);
                
                if ($result['success']) {
                    $success = $result['message'];
                    header("refresh:2;url=index.php?controller=auth&action=login");
                } else {
                    $error = $result['message'];
                }
            }
        }
        
        $data = [
            'email' => $email,
            'error' => $error,
            'success' => $success
        ];
        
        include 'views/auth/konfirmasi.php';
    }
    
    public function resendKode() {
        $email = $_POST['email'] ?? '';
        
        if (empty($email)) {
            echo json_encode(['success' => false, 'message' => 'Email tidak valid']);
            return;
        }
        
        $result = $this->registrasiModel->resendKodeKonfirmasi($email);
        
        header('Content-Type: application/json');
        echo json_encode($result);
    }
}
?>