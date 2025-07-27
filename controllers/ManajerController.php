<?php
class ManajerController
{
    private $authModel;
    private $manajerModel;
    private $storeModel;

    public function __construct()
    {
        $this->authModel = new AuthModel();
        $this->manajerModel = new ManajerModel();
        $this->storeModel = new StoreModel();
    }

    public function registrasi()
    {
        $this->authModel->requireRole(['admin']);

        $stores = $this->storeModel->getActiveStores();
        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'store_id' => $_POST['store_id'] ?? '',
                'username' => trim($_POST['username'] ?? ''),
                'password' => $_POST['password'] ?? '',
                'confirm_password' => $_POST['confirm_password'] ?? '',
                'nama_lengkap' => trim($_POST['nama_lengkap'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'role' => 'manajer'
            ];

            $errors = $this->manajerModel->validateRegistrasi($data);

            if (empty($errors)) {
                $result = $this->manajerModel->createManajer($data);

                if ($result['success']) {
                    $success = $result['message'];
                    $_POST = [];
                } else {
                    $error = $result['message'];
                }
            } else {
                $error = implode('<br>', $errors);
            }
        }

        loadView('store/registrasi_manajer', [
            'stores' => $stores,
            'error' => $error,
            'success' => $success,
            'title' => 'Registrasi Manajer Store'
        ]);
    }

    public function daftar()
    {
        $this->authModel->requireRole(['admin', 'pimpinan']);

        $search = $_GET['search'] ?? '';
        $store_filter = $_GET['store_id'] ?? '';

        $managers = $this->manajerModel->getAllManagers($search, $store_filter, 'manajer');

        $stores = $this->storeModel->getActiveStores();

        loadView('store/daftar_manajer', [
            'managers' => $managers,
            'stores' => $stores,
            'search' => $search,
            'store_filter' => $store_filter,
            'title' => 'Daftar Manajer Store'
        ]);
    }


    public function edit()
    {
        $this->authModel->requireRole(['admin']);

        $id = $_GET['id'] ?? 0;
        $manager = $this->manajerModel->getManagerById($id);

        if (!$manager) {
            header('Location: index.php?controller=manajer&action=daftar&error=Manajer tidak ditemukan');
            exit;
        }

        $stores = $this->storeModel->getActiveStores();
        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'store_id' => $_POST['store_id'] ?? '',
                'username' => trim($_POST['username'] ?? ''),
                'nama_lengkap' => trim($_POST['nama_lengkap'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'role' => $_POST['role'] ?? 'manajer',
                'status' => $_POST['status'] ?? 'aktif',
                'password' => $_POST['password'] ?? ''
            ];

            $errors = $this->manajerModel->validateEdit($data, $id);

            if (empty($errors)) {
                $result = $this->manajerModel->updateManajer($id, $data);

                if ($result['success']) {
                    header('Location: index.php?controller=manajer&action=daftar&success=' . urlencode($result['message']));
                    exit;
                } else {
                    $error = $result['message'];
                }
            } else {
                $error = implode('<br>', $errors);
            }
        }

        loadView('store/edit_manajer', [
            'manager' => $manager,
            'stores' => $stores,
            'error' => $error,
            'success' => $success,
            'title' => 'Edit Manajer Store'
        ]);
    }

    public function delete()
    {
        $this->authModel->requireRole(['admin']);

        $id = $_GET['id'] ?? 0;
        $result = $this->manajerModel->deleteManajer($id);

        if ($result['success']) {
            header('Location: index.php?controller=manajer&action=daftar&success=' . urlencode($result['message']));
        } else {
            header('Location: index.php?controller=manajer&action=daftar&error=' . urlencode($result['message']));
        }
        exit;
    }

    public function toggleStatus()
    {
        $this->authModel->requireRole(['admin']);

        $id = $_GET['id'] ?? 0;
        $result = $this->manajerModel->toggleStatus($id);

        if ($result['success']) {
            header('Location: index.php?controller=manajer&action=daftar&success=' . urlencode($result['message']));
        } else {
            header('Location: index.php?controller=manajer&action=daftar&error=' . urlencode($result['message']));
        }
        exit;
    }
}
