<?php
class StoreController {
    private $authModel;
    private $storeModel;
    
    public function __construct() {
        $this->authModel = new AuthModel();
        $this->storeModel = new StoreModel();
    }
    
    public function index() {
        $this->authModel->requireRole(['admin', 'pimpinan']);
        
        $search = $_GET['search'] ?? '';
        $stores = !empty($search) ? $this->storeModel->searchStore($search) : $this->storeModel->getAllStores();
        
        loadView('store/index', [
            'stores' => $stores,
            'search' => $search,
            'title' => 'Kelola Store'
        ]);
    }
    
    public function add() {
        $this->authModel->requireRole(['admin']);
        
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nama_store' => trim($_POST['nama_store'] ?? ''),
                'alamat_store' => trim($_POST['alamat_store'] ?? ''),
                'manajer_store' => trim($_POST['manajer_store'] ?? '') ?: null,
                'status_store' => $_POST['status_store'] ?? 'aktif'
            ];
            
            $errors = $this->storeModel->validateStore($data);
            
            if (empty($errors)) {
                $result = $this->storeModel->createStore($data);
                
                if ($result['success']) {
                    header('Location: index.php?controller=store&success=' . urlencode($result['message']));
                    exit;
                } else {
                    $error = $result['message'];
                }
            } else {
                $error = implode('<br>', $errors);
            }
        }
        
        loadView('store/form', [
            'error' => $error,
            'success' => $success,
            'title' => 'Tambah Store',
            'action' => 'add',
            'store' => null
        ]);
    }
    
    public function edit() {
        $this->authModel->requireRole(['admin']);
        
        $id = $_GET['id'] ?? 0;
        $store = $this->storeModel->getStoreById($id);
        
        if (!$store) {
            header('Location: index.php?controller=store&error=Store tidak ditemukan');
            exit;
        }
        
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nama_store' => trim($_POST['nama_store'] ?? ''),
                'alamat_store' => trim($_POST['alamat_store'] ?? ''),
                'manajer_store' => trim($_POST['manajer_store'] ?? '') ?: null,
                'status_store' => $_POST['status_store'] ?? 'aktif'
            ];
            
            $errors = $this->storeModel->validateStore($data);
            
            if (empty($errors)) {
                $result = $this->storeModel->updateStore($id, $data);
                
                if ($result['success']) {
                    header('Location: index.php?controller=store&success=' . urlencode($result['message']));
                    exit;
                } else {
                    $error = $result['message'];
                }
            } else {
                $error = implode('<br>', $errors);
            }
        }
        
        loadView('store/form', [
            'error' => $error,
            'success' => $success,
            'title' => 'Edit Store',
            'action' => 'edit',
            'store' => $store
        ]);
    }
    
    public function delete() {
        $this->authModel->requireRole(['admin']);
        
        $id = $_GET['id'] ?? 0;
        $result = $this->storeModel->deleteStore($id);
        
        if ($result['success']) {
            header('Location: index.php?controller=store&success=' . urlencode($result['message']));
        } else {
            header('Location: index.php?controller=store&error=' . urlencode($result['message']));
        }
        exit;
    }
    
    public function view() {
        $this->authModel->requireRole(['admin', 'pimpinan']);
        
        $id = $_GET['id'] ?? 0;
        $store = $this->storeModel->getStoreById($id);
        
        if (!$store) {
            header('Location: index.php?controller=store&error=Store tidak ditemukan');
            exit;
        }
        
        $totalProduk = $this->storeModel->getTotalProdukByStore($id);
        
        loadView('store/view', [
            'store' => $store,
            'total_produk' => $totalProduk,
            'title' => 'Detail Store'
        ]);
    }
}