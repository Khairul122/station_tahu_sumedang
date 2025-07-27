<?php
class PembelianController {
    private $model;
    private $authModel;
    
    public function __construct() {
        $this->model = new PembelianModel();
        $this->authModel = new AuthModel();
    }
    
    public function index() {
        $this->authModel->requireMemberRole();
        
        $userId = $_SESSION['user_id'];
        $memberData = $this->model->getMemberData($userId);
        
        if (!$memberData) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
        
        $produkByCategory = $this->model->getProdukByCategory();
        $metodePembayaran = $this->model->getMetodePembayaran();
        $recentTransactions = $this->model->getMemberTransaksiHistory($memberData['customer_id'], 5, 0);
        
        $data = [
            'memberData' => $memberData,
            'produkByCategory' => $produkByCategory,
            'metodePembayaran' => $metodePembayaran,
            'recentTransactions' => $recentTransactions,
            'success' => $_GET['success'] ?? '',
            'error' => $_GET['error'] ?? ''
        ];
        
        include 'views/pembelian/index.php';
    }
    
    public function create() {
        $this->authModel->requireMemberRole();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processOrder();
        } else {
            $this->index();
        }
    }
    
    private function processOrder() {
        $userId = $_SESSION['user_id'];
        $memberData = $this->model->getMemberData($userId);
        
        if (!$memberData) {
            header('Location: index.php?controller=pembelian&error=' . urlencode('Data member tidak ditemukan'));
            exit;
        }
        
        $customerId = $memberData['customer_id'];
        $metodePembayaran = $_POST['metode_pembayaran'] ?? 'tunai';
        
        $items = [];
        if (isset($_POST['produk_id']) && is_array($_POST['produk_id'])) {
            foreach ($_POST['produk_id'] as $index => $produkId) {
                $jumlah = $_POST['jumlah'][$index] ?? 0;
                
                $produkId = (int)$produkId;
                $jumlah = (int)$jumlah;
                
                if ($produkId > 0 && $jumlah > 0) {
                    $items[] = [
                        'produk_id' => $produkId,
                        'jumlah' => $jumlah
                    ];
                }
            }
        }
        
        if (empty($items)) {
            header('Location: index.php?controller=pembelian&error=' . urlencode('Tidak ada produk yang dipilih'));
            exit;
        }
        
        $stockErrors = $this->model->validateStock($items);
        if (!empty($stockErrors)) {
            $errorMessage = implode('. ', $stockErrors);
            header('Location: index.php?controller=pembelian&error=' . urlencode($errorMessage));
            exit;
        }
        
        $result = $this->model->createTransaksi($customerId, $items, $metodePembayaran);
        
        if ($result['success']) {
            $this->authModel->updateMemberSession($userId);
            
            $successMessage = "Transaksi berhasil! Total: Rp " . number_format($result['total_bayar']) . 
                            ", Poin: " . $result['poin_didapat'];
            
            if (isset($result['membership_update']) && $result['membership_update']['upgraded']) {
                $successMessage .= ". Selamat! Anda naik ke tier " . $result['membership_update']['new_membership'];
            }
            
            header('Location: index.php?controller=pembelian&action=success&id=' . $result['transaksi_id'] . 
                   '&success=' . urlencode($successMessage));
        } else {
            header('Location: index.php?controller=pembelian&error=' . urlencode($result['message']));
        }
        exit;
    }
    
    public function success() {
        $this->authModel->requireMemberRole();
        
        $transaksiId = $_GET['id'] ?? 0;
        $transaksi = $this->model->getTransaksiDetail($transaksiId);
        
        if (!$transaksi) {
            header('Location: index.php?controller=pembelian&error=' . urlencode('Transaksi tidak ditemukan'));
            exit;
        }
        
        $userId = $_SESSION['user_id'];
        $memberData = $this->model->getMemberData($userId);
        
        $data = [
            'transaksi' => $transaksi,
            'memberData' => $memberData,
            'success' => $_GET['success'] ?? ''
        ];
        
        include 'views/pembelian/success.php';
    }
    
    public function history() {
        $this->authModel->requireMemberRole();
        
        $userId = $_SESSION['user_id'];
        $memberData = $this->model->getMemberData($userId);
        
        if (!$memberData) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
        
        $page = $_GET['page'] ?? 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        $history = $this->model->getMemberTransaksiHistory($memberData['customer_id'], $limit, $offset);
        
        $data = [
            'memberData' => $memberData,
            'history' => $history,
            'currentPage' => $page,
            'hasMore' => count($history) == $limit
        ];
        
        include 'views/pembelian/history.php';
    }
    
    public function detail() {
        $this->authModel->requireMemberRole();
        
        $transaksiId = $_GET['id'] ?? 0;
        $transaksi = $this->model->getTransaksiDetail($transaksiId);
        
        if (!$transaksi) {
            header('Location: index.php?controller=pembelian&action=history&error=' . urlencode('Transaksi tidak ditemukan'));
            exit;
        }
        
        $userId = $_SESSION['user_id'];
        $memberData = $this->model->getMemberData($userId);
        
        if ($transaksi['customer_id'] != $memberData['customer_id']) {
            header('Location: index.php?controller=pembelian&action=history&error=' . urlencode('Akses ditolak'));
            exit;
        }
        
        $data = [
            'transaksi' => $transaksi,
            'memberData' => $memberData
        ];
        
        include 'views/pembelian/detail.php';
    }
    
    public function searchProduk() {
        $this->authModel->requireMemberRole();
        
        $keyword = $_GET['q'] ?? '';
        $produk = [];
        
        if (!empty($keyword)) {
            $produk = $this->model->searchProduk($keyword);
        }
        
        header('Content-Type: application/json');
        echo json_encode($produk);
    }
    
    public function getProdukDetail() {
        $this->authModel->requireMemberRole();
        
        $produkId = $_GET['id'] ?? 0;
        $produk = $this->model->getProdukById($produkId);
        
        if ($produk) {
            header('Content-Type: application/json');
            echo json_encode($produk);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Produk tidak ditemukan']);
        }
    }
    
    public function calculatePreview() {
        $this->authModel->requireMemberRole();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }
        
        $userId = $_SESSION['user_id'];
        $memberData = $this->model->getMemberData($userId);
        
        if (!$memberData) {
            http_response_code(400);
            echo json_encode(['error' => 'Data member tidak ditemukan']);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        $items = $input['items'] ?? [];
        
        if (empty($items)) {
            http_response_code(400);
            echo json_encode(['error' => 'Tidak ada item']);
            return;
        }
        
        $stockErrors = $this->model->validateStock($items);
        if (!empty($stockErrors)) {
            http_response_code(400);
            echo json_encode(['error' => implode('. ', $stockErrors)]);
            return;
        }
        
        $calculation = $this->model->calculateTotal($items, $memberData['diskon_persen']);
        $poinDidapat = ($calculation['total_poin_item'] * $memberData['poin_per_pembelian']) + 
                      floor($calculation['total_bayar'] / 10000);
        
        $response = [
            'total_sebelum_diskon' => $calculation['total_sebelum_diskon'],
            'diskon_persen' => $memberData['diskon_persen'],
            'diskon_amount' => $calculation['diskon_amount'],
            'total_bayar' => $calculation['total_bayar'],
            'poin_didapat' => $poinDidapat,
            'total_poin_item' => $calculation['total_poin_item']
        ];
        
        header('Content-Type: application/json');
        echo json_encode($response);
    }
    
    public function getTransactionItems() {
        $this->authModel->requireMemberRole();
        
        $transaksiId = $_GET['id'] ?? 0;
        $userId = $_SESSION['user_id'];
        $memberData = $this->model->getMemberData($userId);
        
        if (!$memberData) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Data member tidak ditemukan']);
            return;
        }
        
        $transaksi = $this->model->getTransaksiDetail($transaksiId);
        
        if (!$transaksi || $transaksi['customer_id'] != $memberData['customer_id']) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Transaksi tidak ditemukan']);
            return;
        }
        
        $items = [];
        foreach ($transaksi['details'] as $detail) {
            $produk = $this->model->getProdukById($detail['produk_id']);
            if ($produk) {
                $items[] = [
                    'produk_id' => $detail['produk_id'],
                    'nama_produk' => $detail['nama_produk'],
                    'harga_satuan' => $detail['harga_satuan'],
                    'jumlah' => $detail['jumlah'],
                    'poin_reward' => $produk['poin_reward'],
                    'stok_tersedia' => $produk['stok']
                ];
            }
        }
        
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'items' => $items]);
    }
}
?>