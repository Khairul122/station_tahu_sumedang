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
                    header("refresh:2;url=index.php?controller=auth&action=login");
                } else {
                    $error = $result['message'];
                }
            } else {
                $error = implode('<br>', $errors);
            }
        }
        
        include 'views/auth/register.php';
    }
}
?>