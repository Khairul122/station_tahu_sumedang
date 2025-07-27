<?php
class AuthController
{
    private $authModel;

    public function __construct()
    {
        $this->authModel = new AuthModel();
    }

    public function index()
    {
        if ($this->authModel->isLoggedIn()) {
            $this->redirectToDashboard();
        } else {
            header('Location: index.php?controller=auth&action=login');
        }
        exit;
    }

    public function login()
    {
        if ($this->authModel->isLoggedIn()) {
            $this->redirectToDashboard();
            exit;
        }

        $error = '';
        $success = $_GET['success'] ?? '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $errors = $this->authModel->validateInput($username, $password);

            if (empty($errors)) {
                $result = $this->authModel->login($username, $password);

                if ($result['success']) {
                    $this->redirectToDashboard();
                    exit;
                } else {
                    $error = $result['message'];
                }
            } else {
                $error = implode('<br>', $errors);
            }
        }

        loadView('auth/login', [
            'error' => $error,
            'success' => $success
        ]);
    }

    public function logout()
    {
        $this->authModel->logout();
        header('Location: index.php?controller=auth&action=login&success=' . urlencode('Logout berhasil'));
        exit;
    }

    public function unauthorized()
    {
        loadView('auth/unauthorized', [
            'message' => 'Anda tidak memiliki akses ke halaman ini'
        ]);
    }

    public function register()
    {
        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userData = [
                'username' => $_POST['username'] ?? '',
                'password' => $_POST['password'] ?? '',
                'confirm_password' => $_POST['confirm_password'] ?? '',
                'nama_lengkap' => $_POST['nama_lengkap'] ?? '',
                'email' => $_POST['email'] ?? '',
                'no_telepon' => $_POST['no_telepon'] ?? '',
                'alamat' => $_POST['alamat'] ?? ''
            ];

            $errors = $this->validateRegistrationInput($userData);

            if (empty($errors)) {
                $result = $this->authModel->register($userData);

                if ($result['success']) {
                    header('Location: index.php?controller=auth&action=login&success=' . urlencode($result['message']));
                    exit;
                } else {
                    $error = $result['message'];
                }
            } else {
                $error = implode('<br>', $errors);
            }
        }

        loadView('auth/register', [
            'error' => $error,
            'success' => $success
        ]);
    }

    public function profile()
    {
        $this->authModel->requireLogin();

        $user = $this->authModel->getLoggedInUser();
        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $profileData = [
                'nama_lengkap' => $_POST['nama_lengkap'] ?? '',
                'email' => $_POST['email'] ?? '',
                'no_telepon' => $_POST['no_telepon'] ?? '',
                'alamat' => $_POST['alamat'] ?? ''
            ];

            if (empty($profileData['nama_lengkap']) || empty($profileData['email'])) {
                $error = 'Nama lengkap dan email tidak boleh kosong';
            } else {
                $result = $this->authModel->updateProfile($_SESSION['user_id'], $profileData);

                if ($result['success']) {
                    $success = $result['message'];
                    $user = $this->authModel->getLoggedInUser();
                } else {
                    $error = $result['message'];
                }
            }
        }

        loadView('auth/profile', [
            'user' => $user,
            'error' => $error,
            'success' => $success
        ]);
    }

    public function changePassword()
    {
        $this->authModel->requireLogin();

        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $oldPassword = $_POST['old_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if (empty($oldPassword) || empty($newPassword) || empty($confirmPassword)) {
                $error = 'Semua field harus diisi';
            } elseif ($newPassword !== $confirmPassword) {
                $error = 'Konfirmasi password tidak sesuai';
            } elseif (strlen($newPassword) < 6) {
                $error = 'Password minimal 6 karakter';
            } else {
                $result = $this->authModel->changePassword($_SESSION['user_id'], $oldPassword, $newPassword);

                if ($result['success']) {
                    $success = $result['message'];
                } else {
                    $error = $result['message'];
                }
            }
        }

        loadView('auth/change_password', [
            'error' => $error,
            'success' => $success
        ]);
    }

    private function redirectToDashboard()
    {
        $role = $_SESSION['role'];
        if ($role === 'admin') {
            header('Location: index.php?controller=dashboard&action=admin');
        } elseif ($role === 'pimpinan') {
            header('Location: index.php?controller=dashboard&action=pimpinan');
        } elseif ($role === 'member') {
            header('Location: index.php?controller=dashboard&action=member');
        } elseif ($role === 'manajer') {
            header('Location: index.php?controller=dashboard&action=manajer');
        } else {
            header('Location: index.php?controller=auth&action=login');
        }
    }

    private function validateRegistrationInput($userData)
    {
        $errors = [];

        if (empty($userData['username'])) {
            $errors[] = 'Username tidak boleh kosong';
        } elseif (strlen($userData['username']) < 3) {
            $errors[] = 'Username minimal 3 karakter';
        }

        if (empty($userData['password'])) {
            $errors[] = 'Password tidak boleh kosong';
        } elseif (strlen($userData['password']) < 6) {
            $errors[] = 'Password minimal 6 karakter';
        }

        if ($userData['password'] !== $userData['confirm_password']) {
            $errors[] = 'Konfirmasi password tidak sesuai';
        }

        if (empty($userData['nama_lengkap'])) {
            $errors[] = 'Nama lengkap tidak boleh kosong';
        }

        if (empty($userData['email'])) {
            $errors[] = 'Email tidak boleh kosong';
        } elseif (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Format email tidak valid';
        }

        return $errors;
    }
}