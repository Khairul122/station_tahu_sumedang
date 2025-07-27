<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Station Tahu Sumedang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #ff6b6b 0%, #ffd93d 50%, #6bcf7f 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px 0;
        }
        
        .register-container {
            background: white;
            border-radius: 25px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-top: 2rem;
            margin-bottom: 2rem;
        }
        
        .register-header {
            background: linear-gradient(135deg, #ff6b6b 0%, #ffd93d 100%);
            color: white;
            padding: 2rem;
            text-align: center;
            position: relative;
        }
        
        .register-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
            animation: float 20s linear infinite;
        }
        
        @keyframes float {
            0% { transform: translate(0, 0) rotate(0deg); }
            100% { transform: translate(-50px, -50px) rotate(360deg); }
        }
        
        .register-header h3 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
        }
        
        .register-header p {
            font-size: 1rem;
            opacity: 0.9;
            margin-bottom: 0;
            position: relative;
            z-index: 1;
        }
        
        .register-body {
            padding: 2.5rem;
        }
        
        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
        }
        
        .form-control {
            border-radius: 15px;
            border: 2px solid #e9ecef;
            padding: 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }
        
        .form-control:focus {
            border-color: #ff6b6b;
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 107, 0.25);
            background: white;
        }
        
        .form-check-input:checked {
            background-color: #ff6b6b;
            border-color: #ff6b6b;
        }
        
        .form-check-input:focus {
            border-color: #ff6b6b;
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 107, 0.25);
        }
        
        .btn-register {
            background: linear-gradient(135deg, #ff6b6b 0%, #ffd93d 100%);
            border: none;
            border-radius: 15px;
            padding: 1rem;
            font-size: 1.1rem;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-register::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-register:hover::before {
            left: 100%;
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(255, 107, 107, 0.3);
        }
        
        .role-selector {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .role-option {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 15px;
            padding: 1rem;
            margin: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-block;
            min-width: 150px;
        }
        
        .role-option.active {
            border-color: #ff6b6b;
            background: linear-gradient(135deg, #fff5f5 0%, #ffe6e6 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 107, 0.2);
        }
        
        .role-option:hover {
            border-color: #ff6b6b;
            transform: translateY(-2px);
        }
        
        .alert {
            border-radius: 15px;
            margin-bottom: 1.5rem;
            border: none;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #ff6b6b 0%, #ff8e8e 100%);
            color: white;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #6bcf7f 0%, #8dd99e 100%);
            color: white;
        }
        
        .membership-section, .manager-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border: 2px dashed #dee2e6;
            transition: all 0.3s ease;
        }
        
        .membership-section.show, .manager-section.show {
            border-color: #ff6b6b;
            background: linear-gradient(135deg, #fff5f5 0%, #ffe6e6 100%);
        }
        
        .form-section {
            display: none;
        }
        
        .form-section.active {
            display: block;
            animation: fadeIn 0.3s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .loading-spinner {
            border: 2px solid #f3f3f3;
            border-top: 2px solid #ff6b6b;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
            display: inline-block;
            margin-right: 8px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid #e9ecef;
        }
        
        .login-link a {
            color: #ff6b6b;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .login-link a:hover {
            color: #ff5252;
            text-decoration: underline;
        }
        
        @media (max-width: 768px) {
            .register-header {
                padding: 1.5rem;
            }
            
            .register-header h3 {
                font-size: 1.5rem;
            }
            
            .register-body {
                padding: 1.5rem;
            }
            
            .role-option {
                display: block;
                margin: 0.5rem 0;
                min-width: auto;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="register-container">
                    <div class="register-header">
                        <h3><i class="fas fa-user-plus me-2"></i>Bergabung dengan Kami</h3>
                        <p>Pilih jenis akun dan daftar sekarang</p>
                    </div>
                    
                    <div class="register-body">
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle me-2"></i><?= $error ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($success)): ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i><?= $success ?>
                                <br><small>Anda akan diarahkan ke halaman login...</small>
                            </div>
                        <?php endif; ?>
                        
                        <div class="role-selector">
                            <h5 class="mb-3">Pilih Jenis Akun</h5>
                            <div class="role-option" data-role="member">
                                <i class="fas fa-user text-primary mb-2"></i>
                                <h6>Member</h6>
                                <small>Pelanggan biasa</small>
                            </div>
                            <div class="role-option" data-role="manajer">
                                <i class="fas fa-user-tie text-success mb-2"></i>
                                <h6>Manajer</h6>
                                <small>Pengelola toko</small>
                            </div>
                        </div>
                        
                        <form method="POST" id="registerForm">
                            <input type="hidden" name="role" id="selectedRole" value="">
                            
                            <div id="memberForm" class="form-section">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" 
                                           value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" 
                                           value="<?= htmlspecialchars($_POST['nama_lengkap'] ?? '') ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Konfirmasi Password</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                </div>
                                
                                <div class="membership-section" id="membershipSection" style="display: none;">
                                    <div class="mb-3">
                                        <label for="no_telepon" class="form-label">No Telepon</label>
                                        <input type="text" class="form-control" id="no_telepon" name="no_telepon" 
                                               value="<?= htmlspecialchars($_POST['no_telepon'] ?? '') ?>">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="alamat" class="form-label">Alamat</label>
                                        <textarea class="form-control" id="alamat" name="alamat" rows="3"><?= htmlspecialchars($_POST['alamat'] ?? '') ?></textarea>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="as_membership" name="as_membership" 
                                               <?= isset($_POST['as_membership']) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="as_membership">
                                            <i class="fas fa-crown text-warning me-1"></i>
                                            Daftar sebagai Membership (dapat poin & diskon)
                                        </label>
                                    </div>
                                    <small class="text-muted">
                                        Centang jika ingin mendaftar sebagai membership untuk mendapatkan benefit khusus
                                    </small>
                                </div>
                            </div>
                            
                            <div id="managerForm" class="form-section">
                                <div class="mb-3">
                                    <label for="username_mgr" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username_mgr" name="username" 
                                           value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="nama_lengkap_mgr" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="nama_lengkap_mgr" name="nama_lengkap" 
                                           value="<?= htmlspecialchars($_POST['nama_lengkap'] ?? '') ?>">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email_mgr" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email_mgr" name="email" 
                                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="password_mgr" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password_mgr" name="password">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="confirm_password_mgr" class="form-label">Konfirmasi Password</label>
                                    <input type="password" class="form-control" id="confirm_password_mgr" name="confirm_password">
                                </div>
                                
                                <div class="manager-section show">
                                    <div class="mb-3">
                                        <label for="store_id" class="form-label">Pilih Store</label>
                                        <select class="form-control" id="store_id" name="store_id">
                                            <option value="">-- Loading stores... --</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="no_telepon_mgr" class="form-label">No Telepon</label>
                                        <input type="text" class="form-control" id="no_telepon_mgr" name="no_telepon" 
                                               value="<?= htmlspecialchars($_POST['no_telepon'] ?? '') ?>">
                                    </div>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-register" id="submitBtn" style="display: none;">
                                <i class="fas fa-user-plus me-2"></i>Daftar
                            </button>
                        </form>
                        
                        <div class="login-link">
                            <a href="index.php?controller=auth&action=login">
                                <i class="fas fa-sign-in-alt me-1"></i>Sudah punya akun? Login di sini
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleOptions = document.querySelectorAll('.role-option');
            const selectedRoleInput = document.getElementById('selectedRole');
            const memberForm = document.getElementById('memberForm');
            const managerForm = document.getElementById('managerForm');
            const submitBtn = document.getElementById('submitBtn');
            const membershipCheckbox = document.getElementById('as_membership');
            const membershipSection = document.getElementById('membershipSection');
            const storeSelect = document.getElementById('store_id');
            
            let storesLoaded = false;
            
            async function loadStores() {
                if (storesLoaded) return;
                
                try {
                    storeSelect.innerHTML = '<option value=""><div class="loading-spinner"></div>Loading stores...</option>';
                    
                    const response = await fetch('auth/get_store.php');
                    const data = await response.json();
                    
                    if (data.success && data.data) {
                        storeSelect.innerHTML = '<option value="">-- Pilih Store --</option>';
                        
                        data.data.forEach(store => {
                            const option = document.createElement('option');
                            option.value = store.store_id;
                            option.textContent = store.nama_store;
                            
                            const selectedStoreId = '<?= $_POST['store_id'] ?? '' ?>';
                            if (selectedStoreId && selectedStoreId == store.store_id) {
                                option.selected = true;
                            }
                            
                            storeSelect.appendChild(option);
                        });
                        
                        storesLoaded = true;
                    } else {
                        storeSelect.innerHTML = '<option value="">-- Error loading stores --</option>';
                        console.error('Failed to load stores:', data.message || 'Unknown error');
                    }
                } catch (error) {
                    storeSelect.innerHTML = '<option value="">-- Error loading stores --</option>';
                    console.error('Error fetching stores:', error);
                }
            }
            
            roleOptions.forEach(option => {
                option.addEventListener('click', function() {
                    roleOptions.forEach(opt => opt.classList.remove('active'));
                    this.classList.add('active');
                    
                    const role = this.dataset.role;
                    selectedRoleInput.value = role;
                    
                    memberForm.classList.remove('active');
                    managerForm.classList.remove('active');
                    
                    if (role === 'member') {
                        memberForm.classList.add('active');
                        setRequiredFields('member');
                    } else if (role === 'manajer') {
                        managerForm.classList.add('active');
                        setRequiredFields('manager');
                        loadStores();
                    }
                    
                    submitBtn.style.display = 'block';
                });
            });
            
            function setRequiredFields(type) {
                const memberFields = ['username', 'nama_lengkap', 'email', 'password', 'confirm_password'];
                const managerFields = ['username_mgr', 'nama_lengkap_mgr', 'email_mgr', 'password_mgr', 'confirm_password_mgr', 'store_id', 'no_telepon_mgr'];
                
                document.querySelectorAll('input, select, textarea').forEach(field => {
                    field.required = false;
                });
                
                if (type === 'member') {
                    memberFields.forEach(fieldId => {
                        const field = document.getElementById(fieldId);
                        if (field) field.required = true;
                    });
                } else if (type === 'manager') {
                    managerFields.forEach(fieldId => {
                        const field = document.getElementById(fieldId);
                        if (field) field.required = true;
                    });
                }
            }
            
            if (membershipCheckbox) {
                membershipCheckbox.addEventListener('change', function() {
                    toggleMembershipSection();
                });
            }
            
            function toggleMembershipSection() {
                if (membershipCheckbox && membershipCheckbox.checked) {
                    membershipSection.style.display = 'block';
                    membershipSection.classList.add('show');
                    document.getElementById('no_telepon').required = true;
                    document.getElementById('alamat').required = true;
                    
                    setTimeout(() => {
                        membershipSection.scrollIntoView({ 
                            behavior: 'smooth', 
                            block: 'nearest' 
                        });
                    }, 100);
                } else {
                    membershipSection.style.display = 'none';
                    membershipSection.classList.remove('show');
                    document.getElementById('no_telepon').required = false;
                    document.getElementById('alamat').required = false;
                }
            }
            
            const form = document.getElementById('registerForm');
            form.addEventListener('submit', function(e) {
                const role = selectedRoleInput.value;
                let password, confirmPassword;
                
                if (role === 'member') {
                    password = document.getElementById('password').value;
                    confirmPassword = document.getElementById('confirm_password').value;
                } else if (role === 'manajer') {
                    password = document.getElementById('password_mgr').value;
                    confirmPassword = document.getElementById('confirm_password_mgr').value;
                }
                
                if (password !== confirmPassword) {
                    e.preventDefault();
                    alert('Password dan konfirmasi password tidak cocok!');
                    return false;
                }
                
                if (password.length < 6) {
                    e.preventDefault();
                    alert('Password minimal 6 karakter!');
                    return false;
                }
                
                if (role === 'manajer') {
                    const storeId = document.getElementById('store_id').value;
                    if (!storeId) {
                        e.preventDefault();
                        alert('Store wajib dipilih untuk manajer!');
                        return false;
                    }
                }
                
                const btn = form.querySelector('button[type="submit"]');
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sedang memproses...';
            });
            
            function setupPasswordConfirmation(passwordId, confirmId) {
                const confirmField = document.getElementById(confirmId);
                if (confirmField) {
                    confirmField.addEventListener('input', function() {
                        const password = document.getElementById(passwordId).value;
                        const confirmPassword = this.value;
                        
                        if (confirmPassword.length > 0) {
                            if (password === confirmPassword) {
                                this.style.borderColor = '#28a745';
                                this.style.backgroundColor = '#d4edda';
                            } else {
                                this.style.borderColor = '#dc3545';
                                this.style.backgroundColor = '#f8d7da';
                            }
                        } else {
                            this.style.borderColor = '#e9ecef';
                            this.style.backgroundColor = '#f8f9fa';
                        }
                    });
                }
            }
            
            setupPasswordConfirmation('password', 'confirm_password');
            setupPasswordConfirmation('password_mgr', 'confirm_password_mgr');
            
            if (membershipCheckbox && membershipCheckbox.checked) {
                toggleMembershipSection();
            }
            
            const selectedRole = '<?= $_POST['role'] ?? '' ?>';
            if (selectedRole) {
                const roleElement = document.querySelector(`[data-role="${selectedRole}"]`);
                if (roleElement) {
                    roleElement.click();
                }
            }
        });
    </script>
</body>
</html>