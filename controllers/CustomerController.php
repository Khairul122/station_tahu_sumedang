<?php
class CustomerController {
    private $authModel;
    private $customerModel;
    
    public function __construct() {
        $this->authModel = new AuthModel();
        $this->customerModel = new CustomerModel();
    }
    
    public function index() {
        $this->authModel->requireRole(['admin']);
        
        $search = $_GET['search'] ?? '';
        $membership_filter = $_GET['membership'] ?? '';
        
        if (!empty($search)) {
            $customers = $this->customerModel->searchCustomers($search);
        } elseif (!empty($membership_filter)) {
            $customers = $this->customerModel->getCustomersByMembership($membership_filter);
        } else {
            $customers = $this->customerModel->getAllCustomers();
        }
        
        $memberships = $this->customerModel->getAllMemberships();
        
        loadView('customer/index', [
            'customers' => $customers,
            'memberships' => $memberships,
            'search' => $search,
            'membership_filter' => $membership_filter,
            'title' => 'Kelola Customer'
        ]);
    }
    
    public function add() {
        $this->authModel->requireRole(['admin']);
        
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nama_customer' => trim($_POST['nama_customer'] ?? ''),
                'no_telepon' => trim($_POST['no_telepon'] ?? ''),
                'alamat' => trim($_POST['alamat'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'membership_id' => intval($_POST['membership_id'] ?? 1)
            ];
            
            $errors = $this->customerModel->validateCustomer($data);
            
            if (empty($errors)) {
                $result = $this->customerModel->createCustomer($data);
                
                if ($result['success']) {
                    $success = $result['message'];
                    header('Location: index.php?controller=customer&success=' . urlencode($success));
                    exit;
                } else {
                    $error = $result['message'];
                }
            } else {
                $error = implode('<br>', $errors);
            }
        }
        
        $memberships = $this->customerModel->getAllMemberships();
        
        loadView('customer/form', [
            'error' => $error,
            'success' => $success,
            'memberships' => $memberships,
            'title' => 'Tambah Customer',
            'action' => 'add',
            'customer' => null
        ]);
    }
    
    public function edit() {
        $this->authModel->requireRole(['admin']);
        
        $id = $_GET['id'] ?? 0;
        $customer = $this->customerModel->getCustomerById($id);
        
        if (!$customer) {
            header('Location: index.php?controller=customer&error=Customer tidak ditemukan');
            exit;
        }
        
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nama_customer' => trim($_POST['nama_customer'] ?? ''),
                'no_telepon' => trim($_POST['no_telepon'] ?? ''),
                'alamat' => trim($_POST['alamat'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'membership_id' => intval($_POST['membership_id'] ?? 1)
            ];
            
            $errors = $this->customerModel->validateCustomer($data);
            
            if (empty($errors)) {
                $result = $this->customerModel->updateCustomer($id, $data);
                
                if ($result['success']) {
                    $success = $result['message'];
                    header('Location: index.php?controller=customer&success=' . urlencode($success));
                    exit;
                } else {
                    $error = $result['message'];
                }
            } else {
                $error = implode('<br>', $errors);
            }
        }
        
        $memberships = $this->customerModel->getAllMemberships();
        
        loadView('customer/form', [
            'error' => $error,
            'success' => $success,
            'memberships' => $memberships,
            'title' => 'Edit Customer',
            'action' => 'edit',
            'customer' => $customer
        ]);
    }
    
    public function delete() {
        $this->authModel->requireRole(['admin']);
        
        $id = $_GET['id'] ?? 0;
        $result = $this->customerModel->deleteCustomer($id);
        
        if ($result['success']) {
            header('Location: index.php?controller=customer&success=' . urlencode($result['message']));
        } else {
            header('Location: index.php?controller=customer&error=' . urlencode($result['message']));
        }
        exit;
    }
    
    public function view() {
        $this->authModel->requireRole(['admin']);
        
        $id = $_GET['id'] ?? 0;
        $customer = $this->customerModel->getCustomerById($id);
        
        if (!$customer) {
            header('Location: index.php?controller=customer&error=Customer tidak ditemukan');
            exit;
        }
        
        $transactions = $this->customerModel->getCustomerTransactions($id);
        
        loadView('customer/view', [
            'customer' => $customer,
            'transactions' => $transactions,
            'title' => 'Detail Customer'
        ]);
    }
    
    public function stats() {
        $this->authModel->requireRole(['admin']);
        
        $stats = $this->customerModel->getCustomerStats();
        $total = $this->customerModel->getTotalCustomers();
        
        loadView('customer/stats', [
            'stats' => $stats,
            'total' => $total,
            'title' => 'Statistik Customer'
        ]);
    }
}
?>