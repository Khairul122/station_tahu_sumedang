<?php
class TransaksiAdminController {
    private $authModel;
    private $transaksiModel;
    
    public function __construct() {
        $this->authModel = new AuthModel();
        $this->transaksiModel = new TransaksiAdminModel();
    }
    
    public function index() {
        $this->authModel->requireRole(['admin']);
        
        $search = $_GET['search'] ?? '';
        $start_date = $_GET['start_date'] ?? '';
        $end_date = $_GET['end_date'] ?? '';
        
        if (!empty($search)) {
            $transaksi = $this->transaksiModel->searchTransaksi($search);
        } elseif (!empty($start_date) && !empty($end_date)) {
            $transaksi = $this->transaksiModel->getTransaksiByDate($start_date, $end_date);
        } else {
            $transaksi = $this->transaksiModel->getAllTransaksi();
        }
        
        $stats = $this->transaksiModel->getTransaksiStats();
        
        loadView('transaksi_admin/index', [
            'transaksi' => $transaksi,
            'stats' => $stats,
            'search' => $search,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'title' => 'Kelola Transaksi'
        ]);
    }
    
    public function add() {
        $this->authModel->requireRole(['admin']);
        
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $customer_id = $_POST['customer_id'] ?? null;
            $customer_id = $customer_id ? intval($customer_id) : null;
            $metode_pembayaran = $_POST['metode_pembayaran'] ?? '';
            $detail = $_POST['detail'] ?? [];
            
            $total_sebelum_diskon = 0;
            $diskon_membership = 0;
            $total_poin_didapat = 0;
            $processed_detail = [];
            
            if (!empty($detail)) {
                foreach ($detail as $item) {
                    if (!empty($item['produk_id']) && !empty($item['jumlah'])) {
                        $produk = $this->transaksiModel->getProdukById($item['produk_id']);
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
                'total_sebelum_diskon' => $total_sebelum_diskon,
                'diskon_membership' => $diskon_membership,
                'total_bayar' => $total_bayar,
                'poin_didapat' => $total_poin_didapat,
                'metode_pembayaran' => $metode_pembayaran,
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
        
        $customers = $this->transaksiModel->getAllCustomers();
        $produk = $this->transaksiModel->getAllProduk();
        
        loadView('transaksi_admin/form', [
            'error' => $error,
            'success' => $success,
            'customers' => $customers,
            'produk' => $produk,
            'title' => 'Tambah Transaksi',
            'action' => 'add',
            'transaksi' => null
        ]);
    }
    
    public function edit() {
        $this->authModel->requireRole(['admin']);
        
        $id = $_GET['id'] ?? 0;
        $transaksi = $this->transaksiModel->getTransaksiById($id);
        
        if (!$transaksi) {
            header('Location: index.php?controller=transaksi_admin&error=Transaksi tidak ditemukan');
            exit;
        }
        
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'metode_pembayaran' => $_POST['metode_pembayaran'] ?? ''
            ];
            
            if (empty($data['metode_pembayaran'])) {
                $error = 'Metode pembayaran harus dipilih';
            } else {
                $result = $this->transaksiModel->updateTransaksi($id, $data);
                
                if ($result['success']) {
                    $success = $result['message'];
                    header('Location: index.php?controller=transaksi_admin&success=' . urlencode($success));
                    exit;
                } else {
                    $error = $result['message'];
                }
            }
        }
        
        loadView('transaksi_admin/edit', [
            'error' => $error,
            'success' => $success,
            'transaksi' => $transaksi,
            'title' => 'Edit Transaksi'
        ]);
    }
    
    public function delete() {
        $this->authModel->requireRole(['admin']);
        
        $id = $_GET['id'] ?? 0;
        $result = $this->transaksiModel->deleteTransaksi($id);
        
        if ($result['success']) {
            header('Location: index.php?controller=transaksi_admin&success=' . urlencode($result['message']));
        } else {
            header('Location: index.php?controller=transaksi_admin&error=' . urlencode($result['message']));
        }
        exit;
    }
    
    public function view() {
        $this->authModel->requireRole(['admin']);
        
        $id = $_GET['id'] ?? 0;
        $transaksi = $this->transaksiModel->getTransaksiById($id);
        
        if (!$transaksi) {
            header('Location: index.php?controller=transaksi_admin&error=Transaksi tidak ditemukan');
            exit;
        }
        
        $detail = $this->transaksiModel->getDetailTransaksi($id);
        
        loadView('transaksi_admin/view', [
            'transaksi' => $transaksi,
            'detail' => $detail,
            'title' => 'Detail Transaksi'
        ]);
    }
    
    public function getProduk() {
        $this->authModel->requireRole(['admin']);
        
        $id = $_GET['id'] ?? 0;
        $produk = $this->transaksiModel->getProdukById($id);
        
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
        $this->authModel->requireRole(['admin']);
        
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
}
?>