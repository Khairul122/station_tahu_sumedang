<?php
class ProdukController {
    private $authModel;
    private $produkModel;
    
    public function __construct() {
        $this->authModel = new AuthModel();
        $this->produkModel = new ProdukModel();
    }
    
    public function index() {
        $this->authModel->requireRole(['admin']);
        
        $search = $_GET['search'] ?? '';
        $produk = !empty($search) ? $this->produkModel->searchProduk($search) : $this->produkModel->getAllProduk();
        
        loadView('produk/index', [
            'produk' => $produk,
            'search' => $search,
            'title' => 'Kelola Produk'
        ]);
    }
    
    public function add() {
        $this->authModel->requireRole(['admin']);
        
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nama_produk' => trim($_POST['nama_produk'] ?? ''),
                'harga' => floatval($_POST['harga'] ?? 0),
                'stok' => intval($_POST['stok'] ?? 0),
                'kategori' => trim($_POST['kategori'] ?? ''),
                'poin_reward' => intval($_POST['poin_reward'] ?? 0)
            ];
            
            $errors = $this->produkModel->validateProduk($data);
            
            if (empty($errors)) {
                $result = $this->produkModel->createProduk($data);
                
                if ($result['success']) {
                    $success = $result['message'];
                    header('Location: index.php?controller=produk&success=' . urlencode($success));
                    exit;
                } else {
                    $error = $result['message'];
                }
            } else {
                $error = implode('<br>', $errors);
            }
        }
        
        loadView('produk/form', [
            'error' => $error,
            'success' => $success,
            'title' => 'Tambah Produk',
            'action' => 'add',
            'produk' => null
        ]);
    }
    
    public function edit() {
        $this->authModel->requireRole(['admin']);
        
        $id = $_GET['id'] ?? 0;
        $produk = $this->produkModel->getProdukById($id);
        
        if (!$produk) {
            header('Location: index.php?controller=produk&error=Produk tidak ditemukan');
            exit;
        }
        
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nama_produk' => trim($_POST['nama_produk'] ?? ''),
                'harga' => floatval($_POST['harga'] ?? 0),
                'stok' => intval($_POST['stok'] ?? 0),
                'kategori' => trim($_POST['kategori'] ?? ''),
                'poin_reward' => intval($_POST['poin_reward'] ?? 0)
            ];
            
            $errors = $this->produkModel->validateProduk($data);
            
            if (empty($errors)) {
                $result = $this->produkModel->updateProduk($id, $data);
                
                if ($result['success']) {
                    $success = $result['message'];
                    header('Location: index.php?controller=produk&success=' . urlencode($success));
                    exit;
                } else {
                    $error = $result['message'];
                }
            } else {
                $error = implode('<br>', $errors);
            }
        }
        
        loadView('produk/form', [
            'error' => $error,
            'success' => $success,
            'title' => 'Edit Produk',
            'action' => 'edit',
            'produk' => $produk
        ]);
    }
    
    public function delete() {
        $this->authModel->requireRole(['admin']);
        
        $id = $_GET['id'] ?? 0;
        $result = $this->produkModel->deleteProduk($id);
        
        if ($result['success']) {
            header('Location: index.php?controller=produk&success=' . urlencode($result['message']));
        } else {
            header('Location: index.php?controller=produk&error=' . urlencode($result['message']));
        }
        exit;
    }
    
    public function view() {
        $this->authModel->requireRole(['admin']);
        
        $id = $_GET['id'] ?? 0;
        $produk = $this->produkModel->getProdukById($id);
        
        if (!$produk) {
            header('Location: index.php?controller=produk&error=Produk tidak ditemukan');
            exit;
        }
        
        loadView('produk/view', [
            'produk' => $produk,
            'title' => 'Detail Produk'
        ]);
    }
}
?>