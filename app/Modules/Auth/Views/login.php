<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aksara Coffee - Login Sistem</title>
    <!-- Google Fonts: Outfit -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #005813;
            --primary-light: #00871d;
            --accent: #10b981;
            --dark-bg: #0f172a;
            --glass-card: rgba(30, 41, 59, 0.65);
            --border: rgba(255, 255, 255, 0.08);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background-color: var(--dark-bg);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
            position: relative;
        }

        /* Ambient Glowing Background Elements */
        .ambient-glow {
            position: absolute;
            width: 350px;
            height: 350px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(0, 88, 19, 0.45) 0%, rgba(16, 185, 129, 0.05) 70%, rgba(0, 0, 0, 0) 100%);
            filter: blur(50px);
            z-index: 1;
            animation: float 8s ease-in-out infinite alternate;
        }
        .glow-1 {
            top: -10%;
            left: -10%;
        }
        .glow-2 {
            bottom: -15%;
            right: -10%;
            animation-delay: -4s;
        }

        @keyframes float {
            0% { transform: translateY(0) scale(1); }
            100% { transform: translateY(30px) scale(1.1); }
        }

        /* Glassmorphism Card Container */
        .login-container {
            width: 100%;
            max-width: 440px;
            margin: 1.5rem;
            background: var(--glass-card);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 2.5rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.45);
            z-index: 10;
            position: relative;
            transform: translateY(0);
            transition: all 0.3s;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-logo {
            font-size: 2.2rem;
            font-weight: 800;
            letter-spacing: -1px;
            color: #fff;
            margin-bottom: 0.4rem;
        }

        .login-logo span {
            color: var(--accent);
        }

        .login-subtitle {
            font-size: 0.88rem;
            color: var(--text-muted);
            font-weight: 400;
        }

        /* Error Message Alert Banner */
        .alert-error {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #f87171;
            padding: 0.8rem 1rem;
            border-radius: 12px;
            font-size: 0.85rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            animation: shake 0.4s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-6px); }
            75% { transform: translateX(6px); }
        }

        /* Form Inputs Styling */
        .input-group {
            position: relative;
            margin-bottom: 1.4rem;
        }

        .input-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 1.05rem;
            transition: color 0.3s;
        }

        .form-input {
            width: 100%;
            background: rgba(15, 23, 42, 0.4);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 0.85rem 1rem 0.85rem 2.8rem;
            color: #fff;
            font-size: 0.95rem;
            outline: none;
            transition: all 0.3s;
        }

        .form-input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15);
            background: rgba(15, 23, 42, 0.6);
        }

        .form-input:focus + .input-icon {
            color: var(--accent);
        }

        /* Premium Login Button */
        .btn-login {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            width: 100%;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            border: none;
            outline: none;
            color: #fff;
            padding: 0.95rem;
            border-radius: 12px;
            font-size: 0.98rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-top: 2rem;
            box-shadow: 0 4px 15px rgba(0, 88, 19, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 88, 19, 0.45);
            background: linear-gradient(135deg, var(--primary-light) 0%, #00b026 100%);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        /* Demo Accounts Instruction Box */
        .demo-box {
            margin-top: 2.2rem;
            border-top: 1px dashed rgba(255, 255, 255, 0.12);
            padding-top: 1.4rem;
        }

        .demo-title {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-muted);
            text-align: center;
            margin-bottom: 0.8rem;
        }

        .demo-badges {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .demo-badge {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            padding: 0.4rem 0.8rem;
            font-size: 0.72rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .demo-badge:hover {
            background: rgba(16, 185, 129, 0.08);
            border-color: rgba(16, 185, 129, 0.2);
        }

        .demo-role {
            font-weight: 700;
            text-transform: uppercase;
        }
        
        .demo-role.kasir { color: #f59e0b; }
        .demo-role.admin { color: #3b82f6; }
        .demo-role.owner { color: #ec4899; }

        .demo-creds {
            color: var(--text-muted);
            font-family: monospace;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 1.5rem !important;
                margin: 1rem !important;
                border-radius: 18px !important;
            }
            .login-logo {
                font-size: 1.8rem !important;
            }
            .btn-login {
                padding: 0.8rem !important;
                margin-top: 1.5rem !important;
            }
            .demo-box {
                margin-top: 1.8rem !important;
                padding-top: 1rem !important;
            }
            .login-subtitle {
                font-size: 0.8rem !important;
            }
        }
    </style>
</head>
<body>

    <!-- Radial glowing backgrounds -->
    <div class="ambient-glow glow-1"></div>
    <div class="ambient-glow glow-2"></div>

    <div class="login-container">
        
        <div class="login-header">
            <h1 class="login-logo"><span>Aksara Coffe</span></h1>
            <p class="login-subtitle">Silakan masuk untuk mengakses KDS dan POS Kasir</p>
        </div>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <span><?= session()->getFlashdata('error') ?></span>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('login') ?>" method="POST">
            
            <div class="input-group">
                <label for="username" class="input-label">Username</label>
                <div class="input-wrapper">
                    <input type="text" id="username" name="username" class="form-input" placeholder="Masukkan username Anda..." required autocomplete="off">
                    <i class="fas fa-user input-icon"></i>
                </div>
            </div>

            <div class="input-group" style="margin-bottom: 1rem;">
                <label for="password" class="input-label">Password</label>
                <div class="input-wrapper">
                    <input type="password" id="password" name="password" class="form-input" placeholder="Masukkan password Anda..." required>
                    <i class="fas fa-lock input-icon"></i>
                </div>
            </div>

            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Masuk Sekarang
            </button>

        </form>

        <!-- Demo Accounts Hint Helper -->
        <div class="demo-box">
            <h4 class="demo-title"><i class="fas fa-info-circle"></i> Akun Demo Cepat (Klik untuk isi)</h4>
            <div class="demo-badges">
                <div class="demo-badge" onclick="fillCreds('kasir', 'kasir123')">
                    <span class="demo-role kasir">Cashier / Kasir</span>
                    <span class="demo-creds">kasir / kasir123</span>
                </div>
                <div class="demo-badge" onclick="fillCreds('admin', 'admin123')">
                    <span class="demo-role admin">Admin Toko</span>
                    <span class="demo-creds">admin / admin123</span>
                </div>
                <div class="demo-badge" onclick="fillCreds('owner', 'owner123')">
                    <span class="demo-role owner">Owner / Pemilik</span>
                    <span class="demo-creds">owner / owner123</span>
                </div>
            </div>
        </div>

    </div>

    <script>
        function fillCreds(username, password) {
            document.getElementById('username').value = username;
            document.getElementById('password').value = password;
            // Add scale tap effect on form inputs
            const inputs = document.querySelectorAll('.form-input');
            inputs.forEach(inp => {
                inp.style.transform = 'scale(1.02)';
                setTimeout(() => inp.style.transform = 'scale(1)', 150);
            });
        }
    </script>
</body>
</html>
