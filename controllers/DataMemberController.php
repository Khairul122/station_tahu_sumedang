<?php
require_once 'models/DataMemberModel.php';

class DataMemberController {
    private $dataMemberModel;
    
    public function __construct() {
        $this->dataMemberModel = new DataMemberModel();
    }
    
    public function index() {
        $members = $this->dataMemberModel->getAllMembers();
        
        include 'views/data_member/index.php';
    }
    
    public function delete() {
        $customerId = $_GET['id'] ?? 0;
        
        if (empty($customerId)) {
            header('Location: index.php?controller=datamember&error=' . urlencode('ID member tidak valid'));
            exit;
        }
        
        $member = $this->dataMemberModel->getMemberById($customerId);
        
        if (!$member) {
            header('Location: index.php?controller=datamember&error=' . urlencode('Member tidak ditemukan'));
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->dataMemberModel->deleteMember($customerId);
            
            if ($result['success']) {
                header('Location: index.php?controller=datamember&success=' . urlencode($result['message']));
            } else {
                header('Location: index.php?controller=datamember&error=' . urlencode($result['message']));
            }
            exit;
        }
        
        include 'views/data_member/delete.php';
    }
}