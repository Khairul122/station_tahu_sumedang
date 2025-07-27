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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px 0;
        }
        
        .register-container {
            background: white;
            border-radius: 25px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
            overflow: hidden;
            margin-top: 2rem;
            margin-bottom: 2rem;
            border: 1px solid rgba(255,255,255,0.2);
        }
        
        .register-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2.5rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .register-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="1.5" fill="rgba(255,255,255,0.1)"/><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
            animation: float 25s linear infinite;
            z-index: 1;
        }
        
        @keyframes float {
            0% { transform: translate(0, 0) rotate(0deg); }
            100% { transform: translate(-50px, -50px) rotate(360deg); }
        }
        
        .register-header .content {
            position: relative;
            z-index: 2;
        }
        
        .register-header h3 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .register-header p {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 0;
        }
        
        .register-body {
            padding: 2.5rem;
        }
        
        .form-step {
            display: none;
        }
        
        .form-step.active {
            display: block;
            animation: slideIn 0.5s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
        }
        
        .step {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 10px;
            font-weight: bold;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .step.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transform: scale(1.1);
        }
        
        .step.completed {
            background: #28a745;
            color: white;
        }
        
        .step:not(:last-child)::after {
            content: '';
            position: absolute;
            right: -30px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 2px;
            background: #e9ecef;
            transition: all 0.3s ease;
        }
        
        .step.completed:not(:last-child)::after {
            background: #28a745;
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
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            background: white;
            transform: translateY(-1px);
        }
        
        .form-control.is-valid {
            border-color: #28a745;
            background: rgba(40, 167, 69, 0.1);
        }
        
        .form-control.is-invalid {
            border-color: #dc3545;
            background: rgba(220, 53, 69, 0.1);
        }
        
        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }
        
        .form-check-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-next, .btn-register {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 15px;
            padding: 1rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-prev {
            background: #6c757d;
            border: none;
            border-radius: 15px;
            padding: 1rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-next::before, .btn-register::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-next:hover::before, .btn-register:hover::before {
            left: 100%;
        }
        
        .btn-next:hover, .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }
        
        .btn-prev:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(108, 117, 125, 0.3);
        }
        
        .alert {
            border-radius: 15px;
            margin-bottom: 1.5rem;
            border: none;
            padding: 1rem 1.5rem;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #dc3545 0%, #e74c3c 100%);
            color: white;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #28a745 0%, #2ecc71 100%);
            color: white;
        }
        
        .alert-info {
            background: linear-gradient(135deg, #17a2b8 0%, #3498db 100%);
            color: white;
        }
        
        .membership-benefits {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border: 2px solid #ffc107;
        }
        
        .membership-benefits h6 {
            color: #856404;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .membership-benefits ul {
            margin-bottom: 0;
            color: #856404;
        }
        
        .membership-benefits li {
            margin-bottom: 0.5rem;
        }
        
        .verification-info {
            background: linear-gradient(135deg, #d1ecf1 0%, #b8daff 100%);
            border-radius: 15px;
            padding: 1.5rem;
            margin-top: 1rem;
            border: 2px solid #17a2b8;
        }
        
        .verification-info h6 {
            color: #0c5460;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .verification-info p {
            color: #0c5460;
            margin-bottom: 0.5rem;
        }
        
        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid #e9ecef;
        }
        
        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .login-link a:hover {
            color: #764ba2;
            text-decoration: underline;
        }
        
        .password-strength {
            margin-top: 0.5rem;
        }
        
        .password-strength .strength-bar {
            height: 4px;
            border-radius: 2px;
            background: #e9ecef;
            margin-bottom: 0.5rem;
        }
        
        .password-strength .strength-fill {
            height: 100%;
            border-radius: 2px;
            transition: all 0.3s ease;
            width: 0;
        }
        
        .password-requirements {
            font-size: 0.875rem;
            color: #6c757d;
        }
        
        .requirement {
            display: flex;
            align-items: center;
            margin-bottom: 0.25rem;
        }
        
        .requirement i {
            margin-right: 0.5rem;
            width: 12px;
        }
        
        .requirement.met {
            color: #28a745;
        }
        
        @media (max-width: 768px) {
            .register-header {
                padding: 2rem 1.5rem;
            }
            
            .register-header h3 {
                font-size: 1.8rem;
            }
            
            .register-body {
                padding: 2rem 1.5rem;
            }
            
            .step-indicator {
                margin-bottom: 1.5rem;
            }
            
            .step {
                width: 35px;
                height: 35px;
                margin: 0 5px;
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
                        <div class="content">
                            <h3><i class="fas fa-user-plus me-2"></i>Bergabung dengan Kami</h3>
                            <p>Daftar sekarang dan nikmati berbagai keuntungan eksklusif</p>
                        </div>
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
                            </div>
                            <div class="verification-info">
                                <h6><i class="fas fa-envelope me-2"></i>Langkah Selanjutnya</h6>
                                <p><i class="fas fa-info-circle me-2"></i>Cek email Anda untuk kode verifikasi</p>
                                <p><i class="fas fa-clock me-2"></i>Kode berlaku selama 24 jam</p>
                                <p><i class="fas fa-shield-alt me-2"></i>Akun akan aktif setelah verifikasi</p>
                            </div>
                        <?php else: ?>
                            
                            <div class="step-indicator">
                                <div class="step active" id="step1">1</div>
                                <div class="step" id="step2">2</div>
                                <div class="step" id="step3">3</div>
                            </div>
                            
                            <form method="POST" id="registerForm">
                                <!-- Step 1: Basic Info -->
                                <div class="form-step active" id="step-1">
                                    <h5 class="mb-4 text-center">Informasi Dasar</h5>
                                    
                                    <div class="mb-3">
                                        <label for="username" class="form-label">
                                            <i class="fas fa-user me-2"></i>Username
                                        </label>
                                        <input type="text" class="form-control" id="username" name="username" 
                                               value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" 
                                               placeholder="Masukkan username" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="nama_lengkap" class="form-label">
                                            <i class="fas fa-id-card me-2"></i>Nama Lengkap
                                        </label>
                                        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" 
                                               value="<?= htmlspecialchars($_POST['nama_lengkap'] ?? '') ?>" 
                                               placeholder="Masukkan nama lengkap" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="email" class="form-label">
                                            <i class="fas fa-envelope me-2"></i>Email
                                        </label>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" 
                                               placeholder="contoh@email.com" required>
                                        <div class="invalid-feedback"></div>
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Email akan digunakan untuk verifikasi akun
                                        </small>
                                    </div>
                                    
                                    <div class="d-grid">
                                        <button type="button" class="btn btn-next" onclick="nextStep(1)">
                                            Selanjutnya <i class="fas fa-arrow-right ms-2"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Step 2: Password -->
                                <div class="form-step" id="step-2">
                                    <h5 class="mb-4 text-center">Keamanan Akun</h5>
                                    
                                    <div class="mb-3">
                                        <label for="password" class="form-label">
                                            <i class="fas fa-lock me-2"></i>Password
                                        </label>
                                        <input type="password" class="form-control" id="password" name="password" 
                                               placeholder="Masukkan password" required>
                                        <div class="password-strength">
                                            <div class="strength-bar">
                                                <div class="strength-fill" id="strengthFill"></div>
                                            </div>
                                            <div class="password-requirements">
                                                <div class="requirement" id="req-length">
                                                    <i class="fas fa-times"></i>
                                                    Minimal 6 karakter
                                                </div>
                                                <div class="requirement" id="req-letter">
                                                    <i class="fas fa-times"></i>
                                                    Mengandung huruf
                                                </div>
                                                <div class="requirement" id="req-number">
                                                    <i class="fas fa-times"></i>
                                                    Mengandung angka
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="confirm_password" class="form-label">
                                            <i class="fas fa-lock me-2"></i>Konfirmasi Password
                                        </label>
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                                               placeholder="Ulangi password" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    
                                    <div class="d-grid gap-2">
                                        <button type="button" class="btn btn-next" onclick="nextStep(2)">
                                            Selanjutnya <i class="fas fa-arrow-right ms-2"></i>
                                        </button>
                                        <button type="button" class="btn btn-prev" onclick="prevStep(2)">
                                            <i class="fas fa-arrow-left me-2"></i> Kembali
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Step 3: Membership -->
                                <div class="form-step" id="step-3">
                                    <h5 class="mb-4 text-center">Pilihan Membership</h5>
                                    
                                    <div class="membership-benefits">
                                        <h6><i class="fas fa-crown me-2"></i>Keuntungan Membership</h6>
                                        <ul class="list-unstyled">
                                            <li><i class="fas fa-check text-success me-2"></i>Dapatkan poin setiap pembelian</li>
                                            <li><i class="fas fa-check text-success me-2"></i>Diskon eksklusif member</li>
                                            <li><i class="fas fa-check text-success me-2"></i>Upgrade otomatis ke tier lebih tinggi</li>
                                            <li><i class="fas fa-check text-success me-2"></i>Prioritas layanan customer service</li>
                                        </ul>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="as_membership" name="as_membership" 
                                                   <?= isset($_POST['as_membership']) ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="as_membership">
                                                <i class="fas fa-star text-warning me-1"></i>
                                                <strong>Ya, daftar sebagai Membership</strong>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div id="membershipFields" style="display: none;">
                                        <div class="mb-3">
                                            <label for="no_telepon" class="form-label">
                                                <i class="fas fa-phone me-2"></i>No Telepon
                                            </label>
                                            <input type="text" class="form-control" id="no_telepon" name="no_telepon" 
                                                   value="<?= htmlspecialchars($_POST['no_telepon'] ?? '') ?>"
                                                   placeholder="08xxxxxxxxxx">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="alamat" class="form-label">
                                                <i class="fas fa-map-marker-alt me-2"></i>Alamat
                                            </label>
                                            <textarea class="form-control" id="alamat" name="alamat" rows="3" 
                                                      placeholder="Masukkan alamat lengkap"><?= htmlspecialchars($_POST['alamat'] ?? '') ?></textarea>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Info:</strong> Setelah registrasi, Anda akan menerima email verifikasi. 
                                        Silakan cek email dan masukkan kode verifikasi untuk mengaktifkan akun.
                                    </div>
                                    
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-register">
                                            <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                                        </button>
                                        <button type="button" class="btn btn-prev" onclick="prevStep(3)">
                                            <i class="fas fa-arrow-left me-2"></i> Kembali
                                        </button>
                                    </div>
                                </div>
                            </form>
                        <?php endif; ?>
                        
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
        let currentStep = 1;
        
        document.addEventListener('DOMContentLoaded', function() {
            const membershipCheckbox = document.getElementById('as_membership');
            const membershipFields = document.getElementById('membershipFields');
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('confirm_password');
            
            // Membership toggle
            membershipCheckbox?.addEventListener('change', function() {
                if (this.checked) {
                    membershipFields.style.display = 'block';
                    document.getElementById('no_telepon').required = true;
                    document.getElementById('alamat').required = true;
                } else {
                    membershipFields.style.display = 'none';
                    document.getElementById('no_telepon').required = false;
                    document.getElementById('alamat').required = false;
                }
            });
            
            // Initialize membership fields if checked
            if (membershipCheckbox?.checked) {
                membershipCheckbox.dispatchEvent(new Event('change'));
            }
            
            // Password strength checker
            passwordInput?.addEventListener('input', checkPasswordStrength);
            confirmPasswordInput?.addEventListener('input', checkPasswordMatch);
            
            // Form validation
            const form = document.getElementById('registerForm');
            form?.addEventListener('submit', function(e) {
                if (!validateForm()) {
                    e.preventDefault();
                    return false;
                }
                
                const btn = form.querySelector('button[type="submit"]');
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sedang memproses...';
            });
        });
        
        function nextStep(step) {
            if (validateStep(step)) {
                document.getElementById(`step-${step}`).classList.remove('active');
                document.getElementById(`step-${step + 1}`).classList.add('active');
                
                document.getElementById(`step${step}`).classList.remove('active');
                document.getElementById(`step${step}`).classList.add('completed');
                document.getElementById(`step${step + 1}`).classList.add('active');
                
                currentStep = step + 1;
            }
        }
        
        function prevStep(step) {
            document.getElementById(`step-${step}`).classList.remove('active');
            document.getElementById(`step-${step - 1}`).classList.add('active');
            
            document.getElementById(`step${step}`).classList.remove('active');
            document.getElementById(`step${step - 1}`).classList.remove('completed');
            document.getElementById(`step${step - 1}`).classList.add('active');
            
            currentStep = step - 1;
        }
        
        function validateStep(step) {
            let isValid = true;
            
            if (step === 1) {
                const username = document.getElementById('username');
                const namaLengkap = document.getElementById('nama_lengkap');
                const email = document.getElementById('email');
                
                if (!username.value.trim() || username.value.length < 3) {
                    showFieldError(username, 'Username minimal 3 karakter');
                    isValid = false;
                } else {
                    showFieldSuccess(username);
                }
                
                if (!namaLengkap.value.trim()) {
                    showFieldError(namaLengkap, 'Nama lengkap wajib diisi');
                    isValid = false;
                } else {
                    showFieldSuccess(namaLengkap);
                }
                
                if (!email.value.trim() || !isValidEmail(email.value)) {
                    showFieldError(email, 'Email tidak valid');
                    isValid = false;
                } else {
                    showFieldSuccess(email);
                }
            }
            
            if (step === 2) {
                const password = document.getElementById('password');
                const confirmPassword = document.getElementById('confirm_password');
                
                if (!password.value || password.value.length < 6) {
                    showFieldError(password, 'Password minimal 6 karakter');
                    isValid = false;
                } else {
                    showFieldSuccess(password);
                }
                
                if (password.value !== confirmPassword.value) {
                    showFieldError(confirmPassword, 'Password tidak cocok');
                    isValid = false;
                } else {
                    showFieldSuccess(confirmPassword);
                }
            }
            
            return isValid;
        }
        
        function validateForm() {
            const membershipCheckbox = document.getElementById('as_membership');
            
            if (membershipCheckbox.checked) {
                const noTelepon = document.getElementById('no_telepon');
                const alamat = document.getElementById('alamat');
                
                if (!noTelepon.value.trim()) {
                    showFieldError(noTelepon, 'No telepon wajib diisi untuk membership');
                    return false;
                }
                
                if (!alamat.value.trim()) {
                    showFieldError(alamat, 'Alamat wajib diisi untuk membership');
                    return false;
                }
            }
            
            return validateStep(1) && validateStep(2);
        }
        
        function showFieldError(field, message) {
            field.classList.add('is-invalid');
            field.classList.remove('is-valid');
            const feedback = field.nextElementSibling;
            if (feedback && feedback.classList.contains('invalid-feedback')) {
                feedback.textContent = message;
            }
        }
        
        function showFieldSuccess(field) {
            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
        }
        
        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
        
        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthFill = document.getElementById('strengthFill');
            
            let strength = 0;
            const requirements = {
                'req-length': password.length >= 6,
                'req-letter': /[a-zA-Z]/.test(password),
                'req-number': /[0-9]/.test(password)
            };
            
            Object.keys(requirements).forEach(req => {
                const element = document.getElementById(req);
                if (requirements[req]) {
                    element.classList.add('met');
                    element.querySelector('i').className = 'fas fa-check';
                    strength++;
                } else {
                    element.classList.remove('met');
                    element.querySelector('i').className = 'fas fa-times';
                }
            });
            
            const percentage = (strength / 3) * 100;
            strengthFill.style.width = percentage + '%';
            
            if (percentage < 33) {
                strengthFill.style.background = '#dc3545';
            } else if (percentage < 67) {
                strengthFill.style.background = '#ffc107';
            } else {
                strengthFill.style.background = '#28a745';
            }
        }
        
        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const confirmField = document.getElementById('confirm_password');
            
            if (confirmPassword.length > 0) {
                if (password === confirmPassword) {
                    showFieldSuccess(confirmField);
                } else {
                    showFieldError(confirmField, 'Password tidak cocok');
                }
            } else {
                confirmField.classList.remove('is-invalid', 'is-valid');
            }
        }
        
        // Auto-check username availability
        let usernameTimeout;
        document.getElementById('username')?.addEventListener('input', function() {
            clearTimeout(usernameTimeout);
            const username = this.value.trim();
            
            if (username.length >= 3) {
                usernameTimeout = setTimeout(() => {
                    checkUsernameAvailability(username);
                }, 500);
            }
        });
        
        function checkUsernameAvailability(username) {
            // This would typically make an AJAX call to check username
            // For now, just validate format
            const usernameField = document.getElementById('username');
            
            if (username.length >= 3 && /^[a-zA-Z0-9_]+$/.test(username)) {
                showFieldSuccess(usernameField);
            } else {
                showFieldError(usernameField, 'Username harus 3+ karakter, hanya huruf, angka, dan underscore');
            }
        }
        
        // Auto-check email format
        document.getElementById('email')?.addEventListener('input', function() {
            const email = this.value.trim();
            const emailField = this;
            
            if (email.length > 0) {
                if (isValidEmail(email)) {
                    showFieldSuccess(emailField);
                } else {
                    showFieldError(emailField, 'Format email tidak valid');
                }
            } else {
                emailField.classList.remove('is-invalid', 'is-valid');
            }
        });
        
        // Phone number formatting
        document.getElementById('no_telepon')?.addEventListener('input', function() {
            let value = this.value.replace(/[^0-9]/g, '');
            
            if (value.startsWith('62')) {
                value = '0' + value.substring(2);
            }
            
            if (value.startsWith('8')) {
                value = '0' + value;
            }
            
            this.value = value;
            
            if (value.length >= 10 && value.length <= 15) {
                showFieldSuccess(this);
            } else if (value.length > 0) {
                showFieldError(this, 'Nomor telepon tidak valid');
            } else {
                this.classList.remove('is-invalid', 'is-valid');
            }
        });
        
        // Enhanced form submission
        document.getElementById('registerForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!validateForm()) {
                return false;
            }
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses registrasi...';
            
            // Show loading state
            const loadingAlert = document.createElement('div');
            loadingAlert.className = 'alert alert-info';
            loadingAlert.innerHTML = '<i class="fas fa-info-circle me-2"></i>Sedang memproses registrasi dan mengirim email verifikasi...';
            this.parentNode.insertBefore(loadingAlert, this);
            
            // Submit form
            this.submit();
        });
        
        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.target.matches('textarea')) {
                e.preventDefault();
                
                if (currentStep < 3) {
                    nextStep(currentStep);
                } else {
                    document.getElementById('registerForm').dispatchEvent(new Event('submit'));
                }
            }
        });
        
        // Auto-focus first field
        setTimeout(() => {
            const firstField = document.querySelector('.form-step.active .form-control');
            if (firstField) {
                firstField.focus();
            }
        }, 100);
        
        // Smooth scroll on step change
        function smoothScrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
        
        // Add smooth scroll to next/prev step functions
        const originalNextStep = nextStep;
        const originalPrevStep = prevStep;
        
        nextStep = function(step) {
            originalNextStep(step);
            smoothScrollToTop();
        };
        
        prevStep = function(step) {
            originalPrevStep(step);
            smoothScrollToTop();
        };
    </script>
</body>
</html>