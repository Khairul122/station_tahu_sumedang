<?php
class AdminRewardController {
    private $model;
    private $authModel;
    
    public function __construct() {
        $this->model = new AdminRewardModel();
        $this->authModel = new AuthModel();
    }
    
    public function index() {
        $this->authModel->requireAdminRole();
        
        $rewards = $this->model->getAllRewards();
        $statistics = $this->model->getRewardStatistics();
        $dashboardStats = $this->model->getDashboardStats();
        
        $data = [
            'rewards' => $rewards,
            'statistics' => $statistics,
            'dashboardStats' => $dashboardStats,
            'success' => $_GET['success'] ?? '',
            'error' => $_GET['error'] ?? ''
        ];
        
        include 'views/admin/reward/index.php';
    }
    
    public function create() {
        $this->authModel->requireAdminRole();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $namaReward = trim($_POST['nama_reward'] ?? '');
            $poinRequired = (int)($_POST['poin_required'] ?? 0);
            $stock = (int)($_POST['stock'] ?? 0);
            $status = $_POST['status'] ?? 'aktif';
            
            if (empty($namaReward)) {
                header('Location: index.php?controller=adminreward&error=' . urlencode('Nama reward tidak boleh kosong'));
                exit;
            }
            
            if ($poinRequired <= 0) {
                header('Location: index.php?controller=adminreward&error=' . urlencode('Poin required harus lebih dari 0'));
                exit;
            }
            
            if ($stock < 0) {
                header('Location: index.php?controller=adminreward&error=' . urlencode('Stock tidak boleh negatif'));
                exit;
            }
            
            $result = $this->model->createReward($namaReward, $poinRequired, $stock, $status);
            
            if ($result['success']) {
                header('Location: index.php?controller=adminreward&success=' . urlencode($result['message']));
            } else {
                header('Location: index.php?controller=adminreward&error=' . urlencode($result['message']));
            }
            exit;
        }
        
        $data = [];
        include 'views/admin/reward/form.php';
    }
    
    public function edit() {
        $this->authModel->requireAdminRole();
        
        $rewardId = $_GET['id'] ?? 0;
        $reward = $this->model->getRewardById($rewardId);
        
        if (!$reward) {
            header('Location: index.php?controller=adminreward&error=' . urlencode('Reward tidak ditemukan'));
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $namaReward = trim($_POST['nama_reward'] ?? '');
            $poinRequired = (int)($_POST['poin_required'] ?? 0);
            $stock = (int)($_POST['stock'] ?? 0);
            $status = $_POST['status'] ?? 'aktif';
            
            if (empty($namaReward)) {
                $data = ['reward' => $reward, 'error' => 'Nama reward tidak boleh kosong'];
                include 'views/admin/reward/form.php';
                return;
            }
            
            if ($poinRequired <= 0) {
                $data = ['reward' => $reward, 'error' => 'Poin required harus lebih dari 0'];
                include 'views/admin/reward/form.php';
                return;
            }
            
            if ($stock < 0) {
                $data = ['reward' => $reward, 'error' => 'Stock tidak boleh negatif'];
                include 'views/admin/reward/form.php';
                return;
            }
            
            $result = $this->model->updateReward($rewardId, $namaReward, $poinRequired, $stock, $status);
            
            if ($result['success']) {
                header('Location: index.php?controller=adminreward&success=' . urlencode($result['message']));
            } else {
                $data = ['reward' => $reward, 'error' => $result['message']];
                include 'views/admin/reward/form.php';
            }
            exit;
        }
        
        $data = ['reward' => $reward];
        include 'views/admin/reward/form.php';
    }
    
    public function delete() {
        $this->authModel->requireAdminRole();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $rewardId = $_POST['reward_id'] ?? 0;
            $result = $this->model->deleteReward($rewardId);
            
            if ($result['success']) {
                header('Location: index.php?controller=adminreward&success=' . urlencode($result['message']));
            } else {
                header('Location: index.php?controller=adminreward&error=' . urlencode($result['message']));
            }
        } else {
            header('Location: index.php?controller=adminreward&error=' . urlencode('Invalid request method'));
        }
        exit;
    }
    
    public function tukarPoin() {
        $this->authModel->requireAdminRole();
        
        $page = (int)($_GET['page'] ?? 1);
        $status = $_GET['status'] ?? '';
        $limit = 20;
        $offset = ($page - 1) * $limit;
        
        $tukarPoinList = $this->model->getAllTukarPoin($limit, $offset, $status);
        $totalCount = $this->model->getCountTukarPoin($status);
        $totalPages = ceil($totalCount / $limit);
        
        $data = [
            'tukarPoinList' => $tukarPoinList,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalCount' => $totalCount,
            'selectedStatus' => $status,
            'success' => $_GET['success'] ?? '',
            'error' => $_GET['error'] ?? ''
        ];
        
        include 'views/admin/reward/tukar_poin.php';
    }
    
    public function detailTukarPoin() {
        $this->authModel->requireAdminRole();
        
        $tukarId = $_GET['id'] ?? 0;
        $tukarPoin = $this->model->getTukarPoinById($tukarId);
        
        if (!$tukarPoin) {
            header('Location: index.php?controller=adminreward&action=tukarPoin&error=' . urlencode('Data penukaran poin tidak ditemukan'));
            exit;
        }
        
        $data = ['tukarPoin' => $tukarPoin];
        include 'views/admin/reward/detail_tukar_poin.php';
    }
    
    public function updateStatusTukarPoin() {
        $this->authModel->requireAdminRole();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tukarId = $_POST['tukar_id'] ?? 0;
            $status = $_POST['status'] ?? '';
            
            if (!in_array($status, ['pending', 'selesai'])) {
                header('Location: index.php?controller=adminreward&action=tukarPoin&error=' . urlencode('Status tidak valid'));
                exit;
            }
            
            $result = $this->model->updateStatusTukarPoin($tukarId, $status);
            
            if ($result['success']) {
                header('Location: index.php?controller=adminreward&action=tukarPoin&success=' . urlencode($result['message']));
            } else {
                header('Location: index.php?controller=adminreward&action=tukarPoin&error=' . urlencode($result['message']));
            }
        } else {
            header('Location: index.php?controller=adminreward&action=tukarPoin&error=' . urlencode('Invalid request method'));
        }
        exit;
    }
}
?>