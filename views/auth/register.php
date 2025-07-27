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
        
        .membership-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border: 2px dashed #dee2e6;
            transition: all 0.3s ease;
        }
        
        .membership-section.show {
            border-color: #ff6b6b;
            background: linear-gradient(135deg, #fff5f5 0%, #ffe6e6 100%);
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
                        <p>Daftar sekarang dan nikmati berbagai keuntungan</p>
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
                        
                        <form method="POST" id="registerForm">
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
                            
                            <button type="submit" class="btn btn-register">
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
            const membershipCheckbox = document.getElementById('as_membership');
            const membershipSection = document.getElementById('membershipSection');
            const noTeleponInput = document.getElementById('no_telepon');
            const alamatInput = document.getElementById('alamat');
            
            function toggleMembershipSection() {
                if (membershipCheckbox.checked) {
                    membershipSection.style.display = 'block';
                    membershipSection.classList.add('show');
                    noTeleponInput.required = true;
                    alamatInput.required = true;
                    
                    setTimeout(() => {
                        membershipSection.scrollIntoView({ 
                            behavior: 'smooth', 
                            block: 'nearest' 
                        });
                    }, 100);
                } else {
                    membershipSection.style.display = 'none';
                    membershipSection.classList.remove('show');
                    noTeleponInput.required = false;
                    alamatInput.required = false;
                }
            }
            
            membershipCheckbox.addEventListener('change', toggleMembershipSection);
            
            if (membershipCheckbox.checked) {
                toggleMembershipSection();
            }
            
            const form = document.getElementById('registerForm');
            form.addEventListener('submit', function(e) {
                const password = document.getElementById('password').value;
                const confirmPassword = document.getElementById('confirm_password').value;
                
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
                
                const btn = form.querySelector('button[type="submit"]');
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sedang memproses...';
            });
            
            document.getElementById('confirm_password').addEventListener('input', function() {
                const password = document.getElementById('password').value;
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
        });
    </script>
</body>
</html>