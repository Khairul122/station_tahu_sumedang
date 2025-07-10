<?php
class MemberController {
    private $model;
    private $authModel;
    
    public function __construct() {
        $this->model = new MemberModel();
        $this->authModel = new AuthModel();
    }
    
    public function index() {
        $this->profile();
    }
    
    public function profile() {
        $this->authModel->requireMemberRole();
        
        $userId = $_SESSION['user_id'];
        $memberProfile = $this->model->getMemberProfile($userId);
        
        if (!$memberProfile) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
        
        $nextMembership = $this->model->getNextMembershipProgress($memberProfile['customer_id']);
        $membershipHistory = $this->model->getMembershipHistory($memberProfile['customer_id']);
        $transaksiSummary = $this->model->getTransaksiSummary($memberProfile['customer_id']);
        
        $data = [
            'memberProfile' => $memberProfile,
            'nextMembership' => $nextMembership,
            'membershipHistory' => $membershipHistory,
            'transaksiSummary' => $transaksiSummary,
            'success' => $_GET['success'] ?? '',
            'error' => $_GET['error'] ?? ''
        ];
        
        include 'views/member/profile.php';
    }
    
    public function transaksi() {
        $this->authModel->requireMemberRole();
        
        $userId = $_SESSION['user_id'];
        $memberProfile = $this->model->getMemberProfile($userId);
        
        if (!$memberProfile) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
        
        $customerId = $memberProfile['customer_id'];
        $page = $_GET['page'] ?? 1;
        $limit = 15;
        $offset = ($page - 1) * $limit;
        $search = $_GET['search'] ?? '';
        $startDate = $_GET['start_date'] ?? '';
        $endDate = $_GET['end_date'] ?? '';
        
        if (!empty($search)) {
            $transaksiList = $this->model->searchTransaksi($customerId, $search, $limit, $offset);
            $totalTransaksi = count($transaksiList);
        } else {
            $transaksiList = $this->model->getAllTransaksi($customerId, $limit, $offset, $startDate, $endDate);
            $totalTransaksi = $this->model->getTransaksiCount($customerId, $startDate, $endDate);
        }
        
        $totalPages = ceil($totalTransaksi / $limit);
        $monthlyStats = $this->model->getMonthlyTransaksiStats($customerId, 6);
        $transaksiSummary = $this->model->getTransaksiSummary($customerId);
        
        $data = [
            'memberProfile' => $memberProfile,
            'transaksiList' => $transaksiList,
            'monthlyStats' => $monthlyStats,
            'transaksiSummary' => $transaksiSummary,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalTransaksi' => $totalTransaksi,
            'search' => $search,
            'startDate' => $startDate,
            'endDate' => $endDate
        ];
        
        include 'views/member/transaksi.php';
    }
    
    public function transaksiDetail() {
        $this->authModel->requireMemberRole();
        
        $userId = $_SESSION['user_id'];
        $memberProfile = $this->model->getMemberProfile($userId);
        $transaksiId = $_GET['id'] ?? 0;
        
        if (!$memberProfile) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
        
        $transaksi = $this->model->getTransaksiDetail($transaksiId, $memberProfile['customer_id']);
        
        if (!$transaksi) {
            header('Location: index.php?controller=member&action=transaksi&error=' . urlencode('Transaksi tidak ditemukan'));
            exit;
        }
        
        $data = [
            'memberProfile' => $memberProfile,
            'transaksi' => $transaksi
        ];
        
        include 'views/member/transaksi_detail.php';
    }
    
    public function poin() {
        $this->authModel->requireMemberRole();
        
        $userId = $_SESSION['user_id'];
        $memberProfile = $this->model->getMemberProfile($userId);
        
        if (!$memberProfile) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
        
        $customerId = $memberProfile['customer_id'];
        $page = $_GET['page'] ?? 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;
        $startDate = $_GET['start_date'] ?? '';
        $endDate = $_GET['end_date'] ?? '';
        
        $poinHistory = $this->model->getPoinHistory($customerId, $limit, $offset, $startDate, $endDate);
        $poinStats = $this->model->getPoinStats($customerId);
        $monthlyStats = $this->model->getMonthlyTransaksiStats($customerId, 6);
        $nextMembership = $this->model->getNextMembershipProgress($customerId);
        
        $data = [
            'memberProfile' => $memberProfile,
            'poinHistory' => $poinHistory,
            'poinStats' => $poinStats,
            'monthlyStats' => $monthlyStats,
            'nextMembership' => $nextMembership,
            'currentPage' => $page,
            'hasMore' => count($poinHistory) == $limit,
            'startDate' => $startDate,
            'endDate' => $endDate
        ];
        
        include 'views/member/poin.php';
    }
    
    public function favoriteProducts() {
        $this->authModel->requireMemberRole();
        
        $userId = $_SESSION['user_id'];
        $memberProfile = $this->model->getMemberProfile($userId);
        
        if (!$memberProfile) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
        
        $customerId = $memberProfile['customer_id'];
        $favoriteProducts = $this->model->getFavoriteProducts($customerId, 20);
        $transaksiSummary = $this->model->getTransaksiSummary($customerId);
        
        $data = [
            'memberProfile' => $memberProfile,
            'favoriteProducts' => $favoriteProducts,
            'transaksiSummary' => $transaksiSummary
        ];
        
        include 'views/member/favorite_products.php';
    }
    
    public function updateProfile() {
        $this->authModel->requireMemberRole();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=member&action=profile');
            exit;
        }
        
        $userId = $_SESSION['user_id'];
        
        $profileData = [
            'nama_lengkap' => $_POST['nama_lengkap'] ?? '',
            'email' => $_POST['email'] ?? '',
            'no_telepon' => $_POST['no_telepon'] ?? '',
            'alamat' => $_POST['alamat'] ?? ''
        ];
        
        $errors = $this->validateProfileData($profileData);
        
        if (!empty($errors)) {
            $errorMessage = implode('. ', $errors);
            header('Location: index.php?controller=member&action=profile&error=' . urlencode($errorMessage));
            exit;
        }
        
        $result = $this->model->updateProfile($userId, $profileData);
        
        if ($result['success']) {
            $_SESSION['nama_lengkap'] = $profileData['nama_lengkap'];
            $_SESSION['email'] = $profileData['email'];
            header('Location: index.php?controller=member&action=profile&success=' . urlencode($result['message']));
        } else {
            header('Location: index.php?controller=member&action=profile&error=' . urlencode($result['message']));
        }
        exit;
    }
    
    public function changePassword() {
        $this->authModel->requireMemberRole();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=member&action=profile');
            exit;
        }
        
        $userId = $_SESSION['user_id'];
        $oldPassword = $_POST['old_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        $errors = [];
        
        if (empty($oldPassword)) {
            $errors[] = "Password lama tidak boleh kosong";
        }
        
        if (empty($newPassword)) {
            $errors[] = "Password baru tidak boleh kosong";
        } elseif (strlen($newPassword) < 6) {
            $errors[] = "Password baru minimal 6 karakter";
        }
        
        if ($newPassword !== $confirmPassword) {
            $errors[] = "Konfirmasi password tidak cocok";
        }
        
        if (!empty($errors)) {
            $errorMessage = implode('. ', $errors);
            header('Location: index.php?controller=member&action=profile&error=' . urlencode($errorMessage));
            exit;
        }
        
        $result = $this->model->changePassword($userId, $oldPassword, $newPassword);
        
        if ($result['success']) {
            header('Location: index.php?controller=member&action=profile&success=' . urlencode($result['message']));
        } else {
            header('Location: index.php?controller=member&action=profile&error=' . urlencode($result['message']));
        }
        exit;
    }
    
    public function exportTransaksi() {
        $this->authModel->requireMemberRole();
        
        $userId = $_SESSION['user_id'];
        $memberProfile = $this->model->getMemberProfile($userId);
        
        if (!$memberProfile) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
        
        $customerId = $memberProfile['customer_id'];
        $startDate = $_GET['start_date'] ?? '';
        $endDate = $_GET['end_date'] ?? '';
        
        $transaksiList = $this->model->getAllTransaksi($customerId, 1000, 0, $startDate, $endDate);
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="transaksi_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        fputcsv($output, [
            'ID Transaksi',
            'Tanggal',
            'Total Item',
            'Total Quantity',
            'Subtotal',
            'Diskon',
            'Total Bayar',
            'Poin Didapat',
            'Metode Pembayaran'
        ]);
        
        foreach ($transaksiList as $transaksi) {
            fputcsv($output, [
                $transaksi['transaksi_id'],
                date('d/m/Y H:i', strtotime($transaksi['tanggal_transaksi'])),
                $transaksi['total_item'],
                $transaksi['total_quantity'],
                number_format($transaksi['total_sebelum_diskon'], 0, ',', '.'),
                number_format($transaksi['diskon_membership'], 0, ',', '.'),
                number_format($transaksi['total_bayar'], 0, ',', '.'),
                $transaksi['poin_didapat'],
                ucfirst($transaksi['metode_pembayaran'])
            ]);
        }
        
        fclose($output);
        exit;
    }
    
    public function getMonthlyChart() {
        $this->authModel->requireMemberRole();
        
        $userId = $_SESSION['user_id'];
        $memberProfile = $this->model->getMemberProfile($userId);
        
        if (!$memberProfile) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }
        
        $customerId = $memberProfile['customer_id'];
        $months = $_GET['months'] ?? 6;
        $monthlyStats = $this->model->getMonthlyTransaksiStats($customerId, $months);
        
        header('Content-Type: application/json');
        echo json_encode($monthlyStats);
    }
    
    public function getPoinChart() {
        $this->authModel->requireMemberRole();
        
        $userId = $_SESSION['user_id'];
        $memberProfile = $this->model->getMemberProfile($userId);
        
        if (!$memberProfile) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }
        
        $customerId = $memberProfile['customer_id'];
        $months = $_GET['months'] ?? 6;
        $monthlyStats = $this->model->getMonthlyTransaksiStats($customerId, $months);
        
        $chartData = [];
        foreach ($monthlyStats as $stat) {
            $chartData[] = [
                'month' => $stat['month'],
                'poin' => $stat['total_poin']
            ];
        }
        
        header('Content-Type: application/json');
        echo json_encode($chartData);
    }
    
    public function searchTransaksi() {
        $this->authModel->requireMemberRole();
        
        $userId = $_SESSION['user_id'];
        $memberProfile = $this->model->getMemberProfile($userId);
        $keyword = $_GET['q'] ?? '';
        
        if (!$memberProfile || empty($keyword)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid request']);
            return;
        }
        
        $customerId = $memberProfile['customer_id'];
        $transaksiList = $this->model->searchTransaksi($customerId, $keyword, 10, 0);
        
        header('Content-Type: application/json');
        echo json_encode($transaksiList);
    }
    
    private function validateProfileData($data) {
        $errors = [];
        
        if (empty($data['nama_lengkap'])) {
            $errors[] = "Nama lengkap tidak boleh kosong";
        }
        
        if (empty($data['email'])) {
            $errors[] = "Email tidak boleh kosong";
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Format email tidak valid";
        }
        
        if (!empty($data['no_telepon']) && !preg_match('/^[0-9+\-\s()]+$/', $data['no_telepon'])) {
            $errors[] = "Format nomor telepon tidak valid";
        }
        
        return $errors;
    }
}
?>