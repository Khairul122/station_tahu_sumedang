<?php
class DashboardController {
    private $authModel;
    private $dashboardModel;
    
    public function __construct() {
        $this->authModel = new AuthModel();
        $this->dashboardModel = new DashboardModel();
    }
    
    public function index() {
        $this->authModel->requireLogin();
        
        $user = $this->authModel->getLoggedInUser();
        $role = $user['role'];
        
        if ($role === 'admin') {
            $this->admin();
        } elseif ($role === 'pimpinan') {
            $this->pimpinan();
        } elseif ($role === 'manajer') {
            $this->manajer();
        } elseif ($role === 'member') {
            $this->member();
        } else {
            header('Location: index.php?controller=auth&action=unauthorized');
            exit;
        }
    }
    
    public function admin() {
        $this->authModel->requireRole(['admin']);
        
        $user = $this->authModel->getLoggedInUser();
        $stats = $this->dashboardModel->getAdminStats();
        $recentActivities = $this->dashboardModel->getRecentActivities();
        $salesChart = $this->dashboardModel->getSalesChart();
        $topProducts = $this->dashboardModel->getTopProducts();
        $membershipStats = $this->dashboardModel->getMembershipStats();
        $transaksiToday = $this->dashboardModel->getTransaksiToday();
        $newCustomersToday = $this->dashboardModel->getNewCustomersToday();
        $stokRendah = $this->dashboardModel->getStokRendah();
        
        loadView('dashboard/admin', [
            'user' => $user,
            'title' => 'Dashboard Admin',
            'stats' => $stats,
            'activities' => $recentActivities,
            'salesChart' => $salesChart,
            'topProducts' => $topProducts,
            'membershipStats' => $membershipStats,
            'transaksiToday' => $transaksiToday,
            'newCustomersToday' => $newCustomersToday,
            'stokRendah' => $stokRendah
        ]);
    }
    
    public function pimpinan() {
        $this->authModel->requireRole(['pimpinan']);
        
        $user = $this->authModel->getLoggedInUser();
        $stats = $this->dashboardModel->getPimpinanStats();
        $salesChart = $this->dashboardModel->getSalesChart();
        $topProducts = $this->dashboardModel->getTopProducts();
        $membershipStats = $this->dashboardModel->getMembershipStats();
        $recentActivities = $this->dashboardModel->getRecentActivities();
        
        loadView('dashboard/pimpinan', [
            'user' => $user,
            'title' => 'Dashboard Pimpinan',
            'stats' => $stats,
            'salesChart' => $salesChart,
            'topProducts' => $topProducts,
            'membershipStats' => $membershipStats,
            'activities' => $recentActivities
        ]);
    }
    
    public function manajer() {
        $this->authModel->requireRole(['manajer']);
        
        $user = $this->authModel->getLoggedInUser();
        $storeId = $user['store_id'] ?? null;
        
        $stats = $this->dashboardModel->getManagerStats($storeId);
        $salesChart = $this->dashboardModel->getSalesChart($storeId);
        $topProducts = $this->dashboardModel->getTopProducts($storeId);
        $stokRendah = $this->dashboardModel->getStokRendah($storeId);
        $productCategories = $this->dashboardModel->getProductCategories($storeId);
        
        loadView('dashboard/manajer', [
            'user' => $user,
            'title' => 'Dashboard Manajer',
            'stats' => $stats,
            'salesChart' => $salesChart,
            'topProducts' => $topProducts,
            'stokRendah' => $stokRendah,
            'productCategories' => $productCategories
        ]);
    }
    
    public function member() {
        $this->authModel->requireRole(['member']);
        
        $user = $this->authModel->getLoggedInUser();
        $userId = $user['user_id'];
        
        $memberStats = $this->dashboardModel->getMemberStats($userId);
        $transaksiHistory = $this->dashboardModel->getMemberTransaksiHistory($userId, 10);
        $favoriteProducts = $this->dashboardModel->getMemberFavoriteProducts($userId, 5);
        $poinHistory = $this->dashboardModel->getMemberPoinHistory($userId, 10);
        $nextMembership = $this->dashboardModel->getMemberNextMembership($userId);
        $monthlySpending = $this->dashboardModel->getMemberMonthlySpending($userId);
        $recommendations = $this->dashboardModel->getMemberRecommendations($userId, 5);
        $totalSavings = $this->dashboardModel->getMemberTotalSavings($userId);
        
        loadView('dashboard/member', [
            'user' => $user,
            'title' => 'Dashboard Member',
            'memberStats' => $memberStats,
            'transaksiHistory' => $transaksiHistory,
            'favoriteProducts' => $favoriteProducts,
            'poinHistory' => $poinHistory,
            'nextMembership' => $nextMembership,
            'monthlySpending' => $monthlySpending,
            'recommendations' => $recommendations,
            'totalSavings' => $totalSavings
        ]);
    }
    
    public function memberProfile() {
        $this->authModel->requireRole(['member']);
        
        $user = $this->authModel->getLoggedInUser();
        $userId = $user['user_id'];
        
        $memberStats = $this->dashboardModel->getMemberStats($userId);
        $nextMembership = $this->dashboardModel->getMemberNextMembership($userId);
        $totalSavings = $this->dashboardModel->getMemberTotalSavings($userId);
        
        loadView('dashboard/member_profile', [
            'user' => $user,
            'title' => 'Profile Member',
            'memberStats' => $memberStats,
            'nextMembership' => $nextMembership,
            'totalSavings' => $totalSavings
        ]);
    }
    
    public function memberTransaksi() {
        $this->authModel->requireRole(['member']);
        
        $user = $this->authModel->getLoggedInUser();
        $userId = $user['user_id'];
        
        $transaksiHistory = $this->dashboardModel->getMemberTransaksiHistory($userId, 20);
        $monthlySpending = $this->dashboardModel->getMemberMonthlySpending($userId);
        
        loadView('dashboard/member_transaksi', [
            'user' => $user,
            'title' => 'Riwayat Transaksi',
            'transaksiHistory' => $transaksiHistory,
            'monthlySpending' => $monthlySpending
        ]);
    }
    
    public function memberPoin() {
        $this->authModel->requireRole(['member']);
        
        $user = $this->authModel->getLoggedInUser();
        $userId = $user['user_id'];
        
        $memberStats = $this->dashboardModel->getMemberStats($userId);
        $poinHistory = $this->dashboardModel->getMemberPoinHistory($userId, 20);
        $nextMembership = $this->dashboardModel->getMemberNextMembership($userId);
        
        loadView('dashboard/member_poin', [
            'user' => $user,
            'title' => 'Poin & Reward',
            'memberStats' => $memberStats,
            'poinHistory' => $poinHistory,
            'nextMembership' => $nextMembership
        ]);
    }
    
    public function memberRekomendasi() {
        $this->authModel->requireRole(['member']);
        
        $user = $this->authModel->getLoggedInUser();
        $userId = $user['user_id'];
        
        $recommendations = $this->dashboardModel->getMemberRecommendations($userId, 10);
        $favoriteProducts = $this->dashboardModel->getMemberFavoriteProducts($userId, 5);
        
        loadView('dashboard/member_rekomendasi', [
            'user' => $user,
            'title' => 'Rekomendasi Produk',
            'recommendations' => $recommendations,
            'favoriteProducts' => $favoriteProducts
        ]);
    }
    
    public function memberChart() {
        $this->authModel->requireRole(['member']);
        
        $user = $this->authModel->getLoggedInUser();
        $userId = $user['user_id'];
        
        $monthlySpending = $this->dashboardModel->getMemberMonthlySpending($userId);
        
        header('Content-Type: application/json');
        echo json_encode($monthlySpending);
    }
    
    public function memberApiStats() {
        $this->authModel->requireRole(['member']);
        
        $user = $this->authModel->getLoggedInUser();
        $userId = $user['user_id'];
        
        $memberStats = $this->dashboardModel->getMemberStats($userId);
        $totalSavings = $this->dashboardModel->getMemberTotalSavings($userId);
        $nextMembership = $this->dashboardModel->getMemberNextMembership($userId);
        
        header('Content-Type: application/json');
        echo json_encode([
            'memberStats' => $memberStats,
            'totalSavings' => $totalSavings,
            'nextMembership' => $nextMembership
        ]);
    }
}