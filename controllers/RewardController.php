    <?php
class RewardController {
    private $model;
    private $authModel;
    
    public function __construct() {
        $this->model = new RewardModel();
        $this->authModel = new AuthModel();
    }
    
    public function index() {
        $this->authModel->requireMemberRole();
        
        $customerId = $_SESSION['customer_id'];
        $rewards = $this->model->getAllRewards();
        $memberPoin = $this->model->getMemberPoin($customerId);
        
        $data = [
            'rewards' => $rewards,
            'memberPoin' => $memberPoin,
            'success' => $_GET['success'] ?? '',
            'error' => $_GET['error'] ?? ''
        ];
        
        include 'views/reward/index.php';
    }
    
    public function tukar() {
        $this->authModel->requireMemberRole();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=reward');
            exit;
        }
        
        $customerId = $_SESSION['customer_id'];
        $rewardId = $_POST['reward_id'] ?? 0;
        
        $result = $this->model->tukarReward($customerId, $rewardId);
        
        if ($result['success']) {
            $this->authModel->updateMemberSession($_SESSION['user_id']);
            header('Location: index.php?controller=reward&success=' . urlencode($result['message']));
        } else {
            header('Location: index.php?controller=reward&error=' . urlencode($result['message']));
        }
        exit;
    }
    
    public function history() {
        $this->authModel->requireMemberRole();
        
        $customerId = $_SESSION['customer_id'];
        $history = $this->model->getMemberRewardHistory($customerId);
        $memberPoin = $this->model->getMemberPoin($customerId);
        
        $data = [
            'history' => $history,
            'memberPoin' => $memberPoin
        ];
        
        include 'views/reward/history.php';
    }
}
?>