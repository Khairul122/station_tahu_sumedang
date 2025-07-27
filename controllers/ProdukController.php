<?php
class ProdukController {
    private $authModel;
    private $produkModel;
    
    public function __construct() {
        $this->authModel = new AuthModel();
        $this->produkModel = new ProdukModel();
    }
    
    public function index() {
        $this->authModel->requireRole(['admin', 'manajer']);
        
        $user = $this->authModel->getLoggedInUser();
        $userRole = $user['role'];
        $storeId = ($userRole == 'manajer') ? ($user['store_id'] ?? null) : null;
        
        $search = $_GET['search'] ?? '';
        $produk = !empty($search) ? $this->produkModel->searchProduk($search, $storeId) : $this->produkModel->getAllProduk($storeId);
        
        loadView('produk/index', [
            'produk' => $produk,
            'search' => $search,
            'title' => 'Kelola Produk',
            'user_role' => $userRole,
            'store_info' => $this->getStoreInfo($user)
        ]);
    }
    
    public function add() {
        $this->authModel->requireRole(['admin', 'manajer']);
        
        $user = $this->authModel->getLoggedInUser();
        $userRole = $user['role'];
        $userStoreId = ($userRole == 'manajer') ? ($user['store_id'] ?? null) : null;
        
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nama_produk' => trim($_POST['nama_produk'] ?? ''),
                'harga' => floatval($_POST['harga'] ?? 0),
                'stok' => intval($_POST['stok'] ?? 0),
                'kategori' => trim($_POST['kategori'] ?? ''),
                'poin_reward' => intval($_POST['poin_reward'] ?? 0),
                'foto_produk' => null
            ];
            
            if (isset($_FILES['foto_produk']) && $_FILES['foto_produk']['error'] === UPLOAD_ERR_OK) {
                $uploadResult = $this->handleFileUpload($_FILES['foto_produk']);
                if ($uploadResult['success']) {
                    $data['foto_produk'] = $uploadResult['filename'];
                } else {
                    $error = $uploadResult['message'];
                }
            }
            
            $store_ids = $_POST['store_ids'] ?? [];
            
            if ($userRole == 'manajer' && $userStoreId) {
                $store_ids = [$userStoreId];
            }
            
            if (empty($error)) {
                $errors = $this->produkModel->validateProduk($data);
                
                if (empty($store_ids)) {
                    $errors[] = "Pilih minimal satu store";
                }
                
                if (empty($errors)) {
                    $result = $this->produkModel->createProdukMultiStore($data, $store_ids);
                    
                    if ($result['success']) {
                        $this->redirect('index.php?controller=produk&success=' . urlencode($result['message']));
                        return;
                    } else {
                        $error = $result['message'];
                    }
                } else {
                    $error = implode('<br>', $errors);
                }
            }
        }
        
        $stores = $this->produkModel->getAllStores($userStoreId);
        
        loadView('produk/form', [
            'error' => $error,
            'success' => $success,
            'title' => 'Tambah Produk',
            'action' => 'add',
            'produk' => null,
            'stores' => $stores,
            'user_role' => $userRole,
            'user_store_id' => $userStoreId,
            'store_info' => $this->getStoreInfo($user)
        ]);
    }
    
    public function edit() {
        $this->authModel->requireRole(['admin', 'manajer']);
        
        $user = $this->authModel->getLoggedInUser();
        $userRole = $user['role'];
        $userStoreId = ($userRole == 'manajer') ? ($user['store_id'] ?? null) : null;
        
        $id = $_GET['id'] ?? 0;
        $produk = $this->produkModel->getProdukById($id);
        
        if (!$produk) {
            $this->redirect('index.php?controller=produk&error=Produk tidak ditemukan');
            return;
        }
        
        if (!$this->produkModel->checkProdukAccess($id, $userRole, $userStoreId)) {
            $this->redirect('index.php?controller=produk&error=Anda tidak memiliki akses untuk mengedit produk ini');
            return;
        }
        
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nama_produk' => trim($_POST['nama_produk'] ?? ''),
                'harga' => floatval($_POST['harga'] ?? 0),
                'stok' => intval($_POST['stok'] ?? 0),
                'kategori' => trim($_POST['kategori'] ?? ''),
                'poin_reward' => intval($_POST['poin_reward'] ?? 0),
                'foto_produk' => $produk['foto_produk']
            ];
            
            if (isset($_FILES['foto_produk']) && $_FILES['foto_produk']['error'] === UPLOAD_ERR_OK) {
                if (!empty($produk['foto_produk']) && file_exists('foto_produk/' . $produk['foto_produk'])) {
                    unlink('foto_produk/' . $produk['foto_produk']);
                }
                
                $uploadResult = $this->handleFileUpload($_FILES['foto_produk']);
                if ($uploadResult['success']) {
                    $data['foto_produk'] = $uploadResult['filename'];
                } else {
                    $error = $uploadResult['message'];
                }
            }
            
            $store_ids = $_POST['store_ids'] ?? [];
            
            if ($userRole == 'manajer' && $userStoreId) {
                $store_ids = [$userStoreId];
            }
            
            if (empty($error)) {
                $errors = $this->produkModel->validateProduk($data);
                
                if (empty($store_ids)) {
                    $errors[] = "Pilih minimal satu store";
                }
                
                if (empty($errors)) {
                    $result = $this->produkModel->updateProdukMultiStore($id, $data, $store_ids);
                    
                    if ($result['success']) {
                        $this->redirect('index.php?controller=produk&success=' . urlencode($result['message']));
                        return;
                    } else {
                        $error = $result['message'];
                    }
                } else {
                    $error = implode('<br>', $errors);
                }
            }
        }
        
        $stores = $this->produkModel->getAllStores($userStoreId);
        $produkStores = $this->produkModel->getProdukStores($id);
        
        loadView('produk/form', [
            'error' => $error,
            'success' => $success,
            'title' => 'Edit Produk',
            'action' => 'edit',
            'produk' => $produk,
            'stores' => $stores,
            'produk_stores' => $produkStores,
            'user_role' => $userRole,
            'user_store_id' => $userStoreId,
            'store_info' => $this->getStoreInfo($user)
        ]);
    }
    
    public function delete() {
        $this->authModel->requireRole(['admin', 'manajer']);
        
        $user = $this->authModel->getLoggedInUser();
        $userRole = $user['role'];
        $userStoreId = ($userRole == 'manajer') ? ($user['store_id'] ?? null) : null;
        
        $id = $_GET['id'] ?? 0;
        $produk = $this->produkModel->getProdukById($id);
        
        if (!$produk) {
            $this->redirect('index.php?controller=produk&error=Produk tidak ditemukan');
            return;
        }
        
        if (!$this->produkModel->checkProdukAccess($id, $userRole, $userStoreId)) {
            $this->redirect('index.php?controller=produk&error=Anda tidak memiliki akses untuk menghapus produk ini');
            return;
        }
        
        if ($produk && !empty($produk['foto_produk']) && file_exists('foto_produk/' . $produk['foto_produk'])) {
            unlink('foto_produk/' . $produk['foto_produk']);
        }
        
        $result = $this->produkModel->deleteProduk($id);
        
        if ($result['success']) {
            $this->redirect('index.php?controller=produk&success=' . urlencode($result['message']));
        } else {
            $this->redirect('index.php?controller=produk&error=' . urlencode($result['message']));
        }
    }
    
    public function view() {
        $this->authModel->requireRole(['admin', 'manajer']);
        
        $user = $this->authModel->getLoggedInUser();
        $userRole = $user['role'];
        $userStoreId = ($userRole == 'manajer') ? ($user['store_id'] ?? null) : null;
        
        $id = $_GET['id'] ?? 0;
        $produk = $this->produkModel->getProdukById($id);
        
        if (!$produk) {
            $this->redirect('index.php?controller=produk&error=Produk tidak ditemukan');
            return;
        }
        
        if (!$this->produkModel->checkProdukAccess($id, $userRole, $userStoreId)) {
            $this->redirect('index.php?controller=produk&error=Anda tidak memiliki akses untuk melihat produk ini');
            return;
        }
        
        $produkStores = $this->produkModel->getProdukStores($id);
        $storesByProduk = $this->produkModel->getStoresByProduk($id);
        
        loadView('produk/view', [
            'produk' => $produk,
            'produk_stores' => $produkStores,
            'stores_by_produk' => $storesByProduk,
            'title' => 'Detail Produk',
            'user_role' => $userRole,
            'store_info' => $this->getStoreInfo($user)
        ]);
    }
    
    public function stats() {
        $this->authModel->requireRole(['admin', 'manajer']);
        
        $user = $this->authModel->getLoggedInUser();
        $userRole = $user['role'];
        $storeId = ($userRole == 'manajer') ? ($user['store_id'] ?? null) : null;
        
        $stats = $this->produkModel->getStatsByStore($storeId);
        $stokRendah = $this->produkModel->getStokRendah(20, $storeId);
        $kategoriList = $this->produkModel->getKategoriList($storeId);
        
        loadView('produk/stats', [
            'stats' => $stats,
            'stok_rendah' => $stokRendah,
            'kategori_list' => $kategoriList,
            'title' => 'Statistik Produk',
            'user_role' => $userRole,
            'store_info' => $this->getStoreInfo($user)
        ]);
    }
    
    private function getStoreInfo($user) {
        if ($user['role'] == 'manajer' && isset($user['store_id'])) {
            return [
                'store_id' => $user['store_id'],
                'nama_store' => $user['nama_store'] ?? 'Store Tidak Diketahui',
                'alamat_store' => $user['alamat_store'] ?? '',
                'manajer_store' => $user['manajer_store'] ?? $user['nama_lengkap']
            ];
        }
        return null;
    }
    
    private function handleFileUpload($file) {
        $uploadDir = 'foto_produk/';
        
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                return [
                    'success' => false,
                    'message' => 'Gagal membuat direktori upload.'
                ];
            }
        }
        
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        $maxSize = 2 * 1024 * 1024;
        
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mimeType, $allowedTypes)) {
            return [
                'success' => false,
                'message' => 'Format file tidak didukung. Gunakan JPG atau PNG.'
            ];
        }
        
        if ($file['size'] > $maxSize) {
            return [
                'success' => false,
                'message' => 'Ukuran file terlalu besar. Maksimal 2MB.'
            ];
        }
        
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename = 'produk_' . time() . '_' . uniqid() . '.' . $extension;
        $targetPath = $uploadDir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return [
                'success' => true,
                'filename' => $filename
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Gagal mengupload file.'
            ];
        }
    }
    
    private function redirect($url) {
        if (!headers_sent()) {
            header('Location: ' . $url);
            exit;
        } else {
            echo '<script>window.location.href = "' . $url . '";</script>';
            exit;
        }
    }
}