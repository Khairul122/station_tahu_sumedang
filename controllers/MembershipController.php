<?php
class MembershipController {
    private $authModel;
    private $membershipModel;
    
    public function __construct() {
        $this->authModel = new AuthModel();
        $this->membershipModel = new MembershipModel();
    }
    
    public function index() {
        $this->authModel->requireRole(['admin']);
        
        $memberships = $this->membershipModel->getAllMemberships();
        
        foreach ($memberships as &$membership) {
            $stats = $this->membershipModel->getMembershipStats($membership['membership_id']);
            $membership['total_customers'] = $stats['total_customers'];
            $membership['total_revenue'] = $stats['total_revenue'];
        }
        
        loadView('membership/index', [
            'memberships' => $memberships,
            'title' => 'Kelola Membership'
        ]);
    }
    
    public function add() {
        $this->authModel->requireRole(['admin']);
        
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nama_membership' => trim($_POST['nama_membership'] ?? ''),
                'minimal_pembelian' => floatval($_POST['minimal_pembelian'] ?? 0),
                'diskon_persen' => intval($_POST['diskon_persen'] ?? 0),
                'poin_per_pembelian' => intval($_POST['poin_per_pembelian'] ?? 1),
                'benefit' => trim($_POST['benefit'] ?? '')
            ];
            
            $errors = $this->membershipModel->validateMembership($data);
            
            if ($this->membershipModel->checkDuplicateName($data['nama_membership'])) {
                $errors[] = "Nama membership sudah ada";
            }
            
            if (empty($errors)) {
                $result = $this->membershipModel->createMembership($data);
                
                if ($result['success']) {
                    $success = $result['message'];
                    header('Location: index.php?controller=membership&success=' . urlencode($success));
                    exit;
                } else {
                    $error = $result['message'];
                }
            } else {
                $error = implode('<br>', $errors);
            }
        }
        
        loadView('membership/form', [
            'error' => $error,
            'success' => $success,
            'title' => 'Tambah Membership',
            'action' => 'add',
            'membership' => null
        ]);
    }
    
    public function edit() {
        $this->authModel->requireRole(['admin']);
        
        $id = $_GET['id'] ?? 0;
        $membership = $this->membershipModel->getMembershipById($id);
        
        if (!$membership) {
            header('Location: index.php?controller=membership&error=Membership tidak ditemukan');
            exit;
        }
        
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nama_membership' => trim($_POST['nama_membership'] ?? ''),
                'minimal_pembelian' => floatval($_POST['minimal_pembelian'] ?? 0),
                'diskon_persen' => intval($_POST['diskon_persen'] ?? 0),
                'poin_per_pembelian' => intval($_POST['poin_per_pembelian'] ?? 1),
                'benefit' => trim($_POST['benefit'] ?? '')
            ];
            
            $errors = $this->membershipModel->validateMembership($data);
            
            if ($this->membershipModel->checkDuplicateName($data['nama_membership'], $id)) {
                $errors[] = "Nama membership sudah ada";
            }
            
            if (empty($errors)) {
                $result = $this->membershipModel->updateMembership($id, $data);
                
                if ($result['success']) {
                    $success = $result['message'];
                    header('Location: index.php?controller=membership&success=' . urlencode($success));
                    exit;
                } else {
                    $error = $result['message'];
                }
            } else {
                $error = implode('<br>', $errors);
            }
        }
        
        loadView('membership/form', [
            'error' => $error,
            'success' => $success,
            'title' => 'Edit Membership',
            'action' => 'edit',
            'membership' => $membership
        ]);
    }
    
    public function delete() {
        $this->authModel->requireRole(['admin']);
        
        $id = $_GET['id'] ?? 0;
        $result = $this->membershipModel->deleteMembership($id);
        
        if ($result['success']) {
            header('Location: index.php?controller=membership&success=' . urlencode($result['message']));
        } else {
            header('Location: index.php?controller=membership&error=' . urlencode($result['message']));
        }
        exit;
    }
    
    public function view() {
        $this->authModel->requireRole(['admin']);
        
        $id = $_GET['id'] ?? 0;
        $membership = $this->membershipModel->getMembershipById($id);
        
        if (!$membership) {
            header('Location: index.php?controller=membership&error=Membership tidak ditemukan');
            exit;
        }
        
        $stats = $this->membershipModel->getMembershipStats($id);
        
        loadView('membership/view', [
            'membership' => $membership,
            'stats' => $stats,
            'title' => 'Detail Membership'
        ]);
    }
}
?>