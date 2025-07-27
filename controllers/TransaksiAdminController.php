<?php
class TransaksiAdminController {
    private $authModel;
    private $transaksiModel;
    
    public function __construct() {
        $this->authModel = new AuthModel();
        $this->transaksiModel = new TransaksiAdminModel();
    }
    
    public function index() {
        $this->authModel->requireRole(['admin', 'manajer']);
        
        $user = $this->authModel->getLoggedInUser();
        $userRole = $user['role'];
        $storeId = ($userRole == 'manajer') ? ($user['store_id'] ?? null) : null;
        
        $search = $_GET['search'] ?? '';
        $start_date = $_GET['start_date'] ?? '';
        $end_date = $_GET['end_date'] ?? '';
        
        if (!empty($search)) {
            $transaksi = $this->transaksiModel->searchTransaksi($search, $storeId);
        } elseif (!empty($start_date) && !empty($end_date)) {
            $transaksi = $this->transaksiModel->getTransaksiByDate($start_date, $end_date, $storeId);
        } else {
            $transaksi = $this->transaksiModel->getAllTransaksi($storeId);
        }
        
        $stats = $this->transaksiModel->getTransaksiStats($storeId);
        $stores = $this->transaksiModel->getAllStores($storeId);
        
        loadView('transaksi_admin/index', [
            'transaksi' => $transaksi,
            'stats' => $stats,
            'stores' => $stores,
            'search' => $search,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'title' => 'Kelola Transaksi',
            'user_role' => $userRole,
            'user_store_id' => $storeId,
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
            $customer_id = $_POST['customer_id'] ?? null;
            $customer_id = $customer_id ? intval($customer_id) : null;
            $store_id = $_POST['store_id'] ?? ($userStoreId ?: 1);
            $metode_pembayaran = $_POST['metode_pembayaran'] ?? '';
            $bukti_pembayaran = null;
            $detail = $_POST['detail'] ?? [];
            
            if ($userRole == 'manajer' && $userStoreId) {
                $store_id = $userStoreId;
            }
            
            if ($metode_pembayaran === 'transfer' && isset($_FILES['bukti_pembayaran'])) {
                $uploadResult = $this->handleBuktiUpload($_FILES['bukti_pembayaran']);
                if ($uploadResult['success']) {
                    $bukti_pembayaran = $uploadResult['filename'];
                } else {
                    $error = $uploadResult['message'];
                }
            }
            
            if (empty($error)) {
                $total_sebelum_diskon = 0;
                $diskon_membership = 0;
                $total_poin_didapat = 0;
                $processed_detail = [];
                
                if (!empty($detail)) {
                    foreach ($detail as $item) {
                        if (!empty($item['produk_id']) && !empty($item['jumlah'])) {
                            $produk = $this->transaksiModel->getProdukById($item['produk_id'], $store_id);
                            if ($produk) {
                                $jumlah = intval($item['jumlah']);
                                $harga_satuan = $produk['harga'];
                                $subtotal = $harga_satuan * $jumlah;
                                
                                $poin_produk = $produk['poin_reward'];
                                $total_poin_item = $poin_produk * $jumlah;
                                
                                if ($customer_id) {
                                    $customer = $this->transaksiModel->getCustomerById($customer_id);
                                    if ($customer) {
                                        $total_poin_item *= $customer['poin_per_pembelian'];
                                    }
                                }
                                
                                $processed_detail[] = [
                                    'produk_id' => $item['produk_id'],
                                    'jumlah' => $jumlah,
                                    'harga_satuan' => $harga_satuan,
                                    'subtotal' => $subtotal,
                                    'poin_produk' => $poin_produk,
                                    'total_poin_item' => $total_poin_item
                                ];
                                
                                $total_sebelum_diskon += $subtotal;
                                $total_poin_didapat += $total_poin_item;
                            }
                        }
                    }
                }
                
                if ($customer_id) {
                    $customer = $this->transaksiModel->getCustomerById($customer_id);
                    if ($customer) {
                        $diskon_membership = $total_sebelum_diskon * ($customer['diskon_persen'] / 100);
                    }
                }
                
                $total_bayar = $total_sebelum_diskon - $diskon_membership;
                
                $transaksi_data = [
                    'customer_id' => $customer_id,
                    'store_id' => $store_id,
                    'total_sebelum_diskon' => $total_sebelum_diskon,
                    'diskon_membership' => $diskon_membership,
                    'total_bayar' => $total_bayar,
                    'poin_didapat' => $total_poin_didapat,
                    'metode_pembayaran' => $metode_pembayaran,
                    'bukti_pembayaran' => $bukti_pembayaran,
                    'detail' => $processed_detail
                ];
                
                $errors = $this->transaksiModel->validateTransaksi($transaksi_data);
                
                if (empty($errors)) {
                    $result = $this->transaksiModel->createTransaksi($transaksi_data);
                    
                    if ($result['success']) {
                        $success = $result['message'];
                        header('Location: index.php?controller=transaksi_admin&success=' . urlencode($success));
                        exit;
                    } else {
                        $error = $result['message'];
                    }
                } else {
                    $error = implode('<br>', $errors);
                }
            }
        }
        
        $customers = $this->transaksiModel->getAllCustomers();
        $produk = $this->transaksiModel->getAllProduk($userStoreId);
        $stores = $this->transaksiModel->getAllStores($userStoreId);
        
        loadView('transaksi_admin/form', [
            'error' => $error,
            'success' => $success,
            'customers' => $customers,
            'produk' => $produk,
            'stores' => $stores,
            'title' => 'Tambah Transaksi',
            'action' => 'add',
            'transaksi' => null,
            'user_role' => $userRole,
            'user_store_id' => $userStoreId,
            'store_info' => $this->getStoreInfo($user)
        ]);
    }
    
    private function handleBuktiUpload($file) {
        $uploadDir = 'bukti_pembayaran/';
        
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                return [
                    'success' => false,
                    'message' => 'Gagal membuat direktori upload.'
                ];
            }
        }
        
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        $maxSize = 5 * 1024 * 1024;
        
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return [
                'success' => false,
                'message' => 'Gagal upload bukti pembayaran.'
            ];
        }
        
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
                'message' => 'Ukuran file terlalu besar. Maksimal 5MB.'
            ];
        }
        
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename = 'bukti_admin_' . time() . '_' . uniqid() . '.' . $extension;
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
    
    public function edit() {
        $this->authModel->requireRole(['admin', 'manajer']);
        
        $user = $this->authModel->getLoggedInUser();
        $userRole = $user['role'];
        $userStoreId = ($userRole == 'manajer') ? ($user['store_id'] ?? null) : null;
        
        $id = $_GET['id'] ?? 0;
        $transaksi = $this->transaksiModel->getTransaksiById($id, $userStoreId);
        
        if (!$transaksi) {
            header('Location: index.php?controller=transaksi_admin&error=Transaksi tidak ditemukan');
            exit;
        }
        
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'metode_pembayaran' => $_POST['metode_pembayaran'] ?? '',
                'bukti_pembayaran' => $transaksi['bukti_pembayaran']
            ];
            
            if ($data['metode_pembayaran'] === 'transfer' && isset($_FILES['bukti_pembayaran'])) {
                if ($_FILES['bukti_pembayaran']['error'] === UPLOAD_ERR_OK) {
                    if (!empty($transaksi['bukti_pembayaran']) && file_exists('bukti_pembayaran/' . $transaksi['bukti_pembayaran'])) {
                        unlink('bukti_pembayaran/' . $transaksi['bukti_pembayaran']);
                    }
                    
                    $uploadResult = $this->handleBuktiUpload($_FILES['bukti_pembayaran']);
                    if ($uploadResult['success']) {
                        $data['bukti_pembayaran'] = $uploadResult['filename'];
                    } else {
                        $error = $uploadResult['message'];
                    }
                }
            } elseif ($data['metode_pembayaran'] !== 'transfer') {
                if (!empty($transaksi['bukti_pembayaran']) && file_exists('bukti_pembayaran/' . $transaksi['bukti_pembayaran'])) {
                    unlink('bukti_pembayaran/' . $transaksi['bukti_pembayaran']);
                }
                $data['bukti_pembayaran'] = null;
            }
            
            if (empty($error)) {
                if (empty($data['metode_pembayaran'])) {
                    $error = 'Metode pembayaran harus dipilih';
                } else {
                    $result = $this->transaksiModel->updateTransaksi($id, $data, $userStoreId);
                    
                    if ($result['success']) {
                        $success = $result['message'];
                        header('Location: index.php?controller=transaksi_admin&success=' . urlencode($success));
                        exit;
                    } else {
                        $error = $result['message'];
                    }
                }
            }
        }
        
        loadView('transaksi_admin/edit', [
            'error' => $error,
            'success' => $success,
            'transaksi' => $transaksi,
            'title' => 'Edit Transaksi',
            'user_role' => $userRole,
            'store_info' => $this->getStoreInfo($user)
        ]);
    }
    
    public function delete() {
        $this->authModel->requireRole(['admin', 'manajer']);
        
        $user = $this->authModel->getLoggedInUser();
        $userRole = $user['role'];
        $userStoreId = ($userRole == 'manajer') ? ($user['store_id'] ?? null) : null;
        
        $id = $_GET['id'] ?? 0;
        $result = $this->transaksiModel->deleteTransaksi($id, $userStoreId);
        
        if ($result['success']) {
            header('Location: index.php?controller=transaksi_admin&success=' . urlencode($result['message']));
        } else {
            header('Location: index.php?controller=transaksi_admin&error=' . urlencode($result['message']));
        }
        exit;
    }
    
    public function view() {
        $this->authModel->requireRole(['admin', 'manajer']);
        
        $user = $this->authModel->getLoggedInUser();
        $userRole = $user['role'];
        $userStoreId = ($userRole == 'manajer') ? ($user['store_id'] ?? null) : null;
        
        $id = $_GET['id'] ?? 0;
        $transaksi = $this->transaksiModel->getTransaksiById($id, $userStoreId);
        
        if (!$transaksi) {
            header('Location: index.php?controller=transaksi_admin&error=Transaksi tidak ditemukan');
            exit;
        }
        
        $detail = $this->transaksiModel->getDetailTransaksi($id);
        
        loadView('transaksi_admin/view', [
            'transaksi' => $transaksi,
            'detail' => $detail,
            'title' => 'Detail Transaksi',
            'user_role' => $userRole,
            'store_info' => $this->getStoreInfo($user)
        ]);
    }
    
    public function getProduk() {
        $this->authModel->requireRole(['admin', 'manajer']);
        
        $user = $this->authModel->getLoggedInUser();
        $userRole = $user['role'];
        $userStoreId = ($userRole == 'manajer') ? ($user['store_id'] ?? null) : null;
        
        $id = $_GET['id'] ?? 0;
        $storeId = $_GET['store_id'] ?? $userStoreId;
        
        if ($userRole == 'manajer' && $userStoreId) {
            $storeId = $userStoreId;
        }
        
        $produk = $this->transaksiModel->getProdukById($id, $storeId);
        
        if ($produk) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'data' => $produk
            ]);
        } else {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Produk tidak ditemukan'
            ]);
        }
    }
    
    public function getCustomer() {
        $this->authModel->requireRole(['admin', 'manajer']);
        
        $id = $_GET['id'] ?? 0;
        $customer = $this->transaksiModel->getCustomerById($id);
        
        if ($customer) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'data' => $customer
            ]);
        } else {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Customer tidak ditemukan'
            ]);
        }
    }
    
    public function getProdukByStore() {
        $this->authModel->requireRole(['admin', 'manajer']);
        
        $user = $this->authModel->getLoggedInUser();
        $userRole = $user['role'];
        $userStoreId = ($userRole == 'manajer') ? ($user['store_id'] ?? null) : null;
        
        $storeId = $_GET['store_id'] ?? 0;
        
        if ($userRole == 'manajer' && $userStoreId) {
            $storeId = $userStoreId;
        }
        
        $produk = $this->transaksiModel->getProdukByStore($storeId);
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'data' => $produk
        ]);
    }
    
    public function exportTransaksi() {
        $this->authModel->requireRole(['admin', 'manajer']);
        
        $user = $this->authModel->getLoggedInUser();
        $userRole = $user['role'];
        $storeId = ($userRole == 'manajer') ? ($user['store_id'] ?? null) : null;
        
        $start_date = $_GET['start_date'] ?? date('Y-m-01');
        $end_date = $_GET['end_date'] ?? date('Y-m-d');
        
        $transaksi = $this->transaksiModel->getTransaksiByDate($start_date, $end_date, $storeId);
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="transaksi_' . $start_date . '_to_' . $end_date . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        fputcsv($output, ['ID Transaksi', 'Tanggal', 'Customer', 'Store', 'Total Sebelum Diskon', 'Diskon', 'Total Bayar', 'Poin', 'Metode Pembayaran']);
        
        foreach ($transaksi as $row) {
            fputcsv($output, [
                $row['transaksi_id'],
                $row['tanggal_transaksi'],
                $row['nama_customer'] ?? 'Guest',
                $row['nama_store'] ?? 'N/A',
                $row['total_sebelum_diskon'],
                $row['diskon_membership'],
                $row['total_bayar'],
                $row['poin_didapat'],
                $row['metode_pembayaran']
            ]);
        }
        
        fclose($output);
        exit;
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
}
?>