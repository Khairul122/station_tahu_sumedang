<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Email - Station Tahu Sumedang</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            max-width: 400px;
            width: 100%;
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
            border-radius: 15px 15px 0 0;
            padding: 2rem;
        }
        .card-header i {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        .email-info {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            text-align: center;
        }
        .code-input {
            font-size: 1.5rem;
            text-align: center;
            letter-spacing: 0.5rem;
            font-weight: bold;
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 1rem;
        }
        .code-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
            padding: 0.8rem;
            font-weight: 600;
        }
        .btn-outline-secondary {
            border-radius: 8px;
            margin-top: 1rem;
        }
        .countdown {
            text-align: center;
            margin-top: 0.5rem;
            color: #6c757d;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-envelope-open"></i>
                        <h4>Konfirmasi Email</h4>
                        <p class="mb-0">Masukkan kode dari email Anda</p>
                    </div>
                    <div class="card-body p-4">
                        <?php if (!empty($success)): ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>
                                <?= htmlspecialchars($success) ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <?= htmlspecialchars($error) ?>
                            </div>
                        <?php endif; ?>

                        <div class="email-info">
                            <small class="text-muted">Email dikirim ke:</small><br>
                            <strong><?= htmlspecialchars($email) ?></strong>
                        </div>

                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Kode Konfirmasi</label>
                                <input type="text" 
                                       class="form-control code-input" 
                                       name="kode_konfirmasi" 
                                       placeholder="000000" 
                                       maxlength="6" 
                                       pattern="[0-9]{6}"
                                       required
                                       autocomplete="off">
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-check me-2"></i>
                                Verifikasi Email
                            </button>
                        </form>

                        <button class="btn btn-outline-secondary w-100" id="btnResend" onclick="resendCode()">
                            <i class="fas fa-paper-plane me-2"></i>
                            Kirim Ulang Kode
                        </button>
                        <div class="countdown" id="countdown"></div>

                        <div class="text-center mt-3">
                            <a href="index.php?controller=auth&action=login" class="text-decoration-none">
                                <i class="fas fa-arrow-left me-2"></i>
                                Kembali ke Login
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let countdown = 60;
        let countdownInterval;

        document.addEventListener('DOMContentLoaded', function() {
            startCountdown();
            
            const codeInput = document.querySelector('.code-input');
            codeInput.addEventListener('input', function(e) {
                e.target.value = e.target.value.replace(/[^0-9]/g, '');
            });
        });

        function startCountdown() {
            const btnResend = document.getElementById('btnResend');
            const countdownEl = document.getElementById('countdown');
            
            btnResend.disabled = true;
            
            countdownInterval = setInterval(function() {
                countdown--;
                
                if (countdown > 0) {
                    countdownEl.textContent = `Kirim ulang dalam ${countdown} detik`;
                } else {
                    clearInterval(countdownInterval);
                    btnResend.disabled = false;
                    countdownEl.textContent = '';
                    countdown = 60;
                }
            }, 1000);
        }

        function resendCode() {
            const btnResend = document.getElementById('btnResend');
            const email = '<?= htmlspecialchars($email) ?>';
            
            btnResend.disabled = true;
            btnResend.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengirim...';
            
            fetch('index.php?controller=registrasi&action=resendKode', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'email=' + encodeURIComponent(email)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Kode baru telah dikirim ke email Anda');
                    startCountdown();
                } else {
                    alert(data.message);
                    btnResend.disabled = false;
                }
                
                btnResend.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Kirim Ulang Kode';
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan. Silakan coba lagi.');
                btnResend.disabled = false;
                btnResend.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Kirim Ulang Kode';
            });
        }
    </script>
</body>
</html>