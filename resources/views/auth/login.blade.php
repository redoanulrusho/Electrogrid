<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ElectroGrid Portal - Secure Gateway Login</title>
    
    <!-- Google Fonts: Rajdhani & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Rajdhani:wght@600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Custom CSS for Cyberpunk Gateway Portal -->
    <style>
        :root {
            --bg-obsidian: #03060c;
            --bg-slate: #0b0f19;
            --card-glass: rgba(11, 17, 30, 0.85);
            --border-neon-teal: rgba(0, 245, 255, 0.2);
            --neon-teal: #00f5ff;
            --neon-teal-glow: rgba(0, 245, 255, 0.35);
            --electric-amber: #ff9f1c;
            --electric-amber-glow: rgba(255, 159, 28, 0.3);
            --warning-crimson: #ff2a54;
            --text-hud: #c9d1d9;
            --text-dim: #768390;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-obsidian);
            color: var(--text-hud);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
            background-image: 
                linear-gradient(rgba(0, 245, 255, 0.015) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 245, 255, 0.015) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        /* Ambient neon orb behind the login form */
        .ambient-glow {
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, var(--neon-teal-glow) 0%, transparent 70%);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1;
            filter: blur(100px);
            pointer-events: none;
        }

        .login-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 480px;
        }

        .tech-font {
            font-family: 'Rajdhani', sans-serif;
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }

        .brand-header-hud {
            text-align: center;
            margin-bottom: 2rem;
        }

        .brand-icon-hud {
            font-size: 3rem;
            color: var(--neon-teal);
            filter: drop-shadow(0 0 12px var(--neon-teal));
            animation: pulse-bolt 3s infinite alternate;
        }

        @keyframes pulse-bolt {
            0% { transform: scale(0.95); filter: drop-shadow(0 0 8px var(--neon-teal-glow)); }
            100% { transform: scale(1.05); filter: drop-shadow(0 0 16px var(--neon-teal)); }
        }

        .cyber-login-card {
            background-color: var(--card-glass);
            border: 1px solid var(--border-neon-teal);
            border-radius: 8px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(12px);
            overflow: hidden;
            position: relative;
        }

        /* High-tech corners */
        .cyber-login-card::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 12px;
            height: 12px;
            border-top: 3px solid var(--neon-teal);
            border-right: 3px solid var(--neon-teal);
            pointer-events: none;
        }

        .cyber-login-card::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 12px;
            height: 12px;
            border-bottom: 3px solid var(--neon-teal);
            border-left: 3px solid var(--neon-teal);
            pointer-events: none;
        }

        .login-tabs-hud {
            display: flex;
            border-bottom: 1px solid var(--border-neon-teal);
            background-color: rgba(0, 0, 0, 0.3);
        }

        .login-tab-hud {
            flex: 1;
            padding: 1.15rem 0.5rem;
            text-align: center;
            cursor: pointer;
            font-weight: 700;
            font-size: 0.9rem;
            color: var(--text-dim);
            transition: all 0.25s ease;
            border-bottom: 2px solid transparent;
            user-select: none;
        }

        .login-tab-hud:hover {
            color: var(--neon-teal);
            background-color: rgba(0, 245, 255, 0.02);
        }

        .login-tab-hud.active {
            color: var(--neon-teal);
            border-bottom-color: var(--neon-teal);
            background-color: rgba(0, 245, 255, 0.05);
            text-shadow: 0 0 8px var(--neon-teal-glow);
        }

        .card-body-hud {
            padding: 2.5rem;
        }

        .form-label-hud {
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--text-dim);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 0.5rem;
        }

        .input-group-hud {
            position: relative;
            background-color: rgba(0,0,0,0.4);
            border: 1px solid var(--border-neon-teal);
            border-radius: 4px;
            display: flex;
            align-items: center;
            transition: all 0.2s ease;
        }

        .input-group-hud:focus-within {
            border-color: var(--neon-teal);
            box-shadow: 0 0 10px rgba(0, 245, 255, 0.15);
        }

        .input-icon-hud {
            padding: 0.75rem 1rem;
            color: var(--neon-teal);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
        }

        .form-control-hud {
            flex: 1;
            background: transparent;
            border: none;
            color: #fff;
            padding: 0.75rem 1rem 0.75rem 0;
            font-size: 0.95rem;
            box-shadow: none;
        }

        .form-control-hud:focus {
            outline: none;
            box-shadow: none;
        }

        .password-toggle-hud {
            cursor: pointer;
            padding: 0.75rem 1rem;
            color: var(--text-dim);
            transition: color 0.2s ease;
            user-select: none;
        }

        .password-toggle-hud:hover {
            color: var(--neon-teal);
        }

        /* Custom glowing action button */
        .btn-cyber-action {
            background: linear-gradient(135deg, rgba(0, 245, 255, 0.1) 0%, rgba(0, 245, 255, 0.05) 100%);
            border: 1px solid var(--neon-teal);
            color: var(--neon-teal);
            font-weight: 700;
            padding: 0.85rem;
            border-radius: 4px;
            width: 100%;
            transition: all 0.2s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.9rem;
            text-shadow: 0 0 5px var(--neon-teal-glow);
            box-shadow: 0 0 10px rgba(0, 245, 255, 0.05);
        }

        .btn-cyber-action:hover {
            background-color: var(--neon-teal);
            color: #000;
            box-shadow: 0 0 20px var(--neon-teal-glow);
            text-shadow: none;
        }

        .role-indicator-hud {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 1.2px;
            padding: 0.4rem 1rem;
            border-radius: 4px;
            text-transform: uppercase;
            background-color: rgba(0, 245, 255, 0.04);
            border: 1px solid var(--border-neon-teal);
            display: inline-block;
            margin-bottom: 1.5rem;
            text-shadow: 0 0 5px var(--neon-teal-glow);
        }
    </style>
</head>
<body>

    <div class="ambient-glow"></div>

    <div class="login-wrapper">
        
        <!-- Application Branding HUD Header -->
        <div class="brand-header-hud">
            <div class="brand-icon-hud mb-2">
                <i class="bi bi-lightning-charge-fill"></i>
            </div>
            <h2 class="tech-font fw-extrabold m-0 text-white" style="letter-spacing: 1.5px; font-size: 1.85rem; text-shadow: 0 0 8px var(--neon-teal-glow);">ELECTROGRID</h2>
            <p class="text-secondary mt-1" style="font-size: 0.82rem;">Central Grid Node Authorization Gateway</p>
        </div>

        <!-- Cyber Portal Card -->
        <div class="cyber-login-card">
            
            <!-- Dual-tab selection for High Privilege Admin vs General Consumer -->
            <div class="login-tabs-hud tech-font">
                <div class="login-tab-hud active" id="consumer-tab" onclick="switchRole('consumer')">
                    <i class="bi bi-person-workspace me-1"></i> Consumer Portal
                </div>
                <div class="login-tab-hud" id="admin-tab" onclick="switchRole('admin')">
                    <i class="bi bi-shield-lock me-1"></i> Grid Operator
                </div>
            </div>

            <div class="card-body-hud">
                
                <!-- Display Dynamic Status Role Label -->
                <div class="text-center">
                    <span class="role-indicator-hud text-info tech-font" id="role-badge">POWER CONSUMER VALIDATION</span>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger p-3 mb-4" style="border-left: 3px solid var(--warning-crimson) !important; font-size: 0.85rem; border-radius: 4px;">
                        <strong class="d-block mb-1">Access Authorization Failed:</strong>
                        <ul class="m-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success border-0 bg-success bg-opacity-10 text-success p-3 mb-4" style="border-left: 3px solid var(--grid-green) !important; font-size: 0.85rem; border-radius: 4px;">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    </div>
                @endif


                <!-- Unified Form Submitting to standard Laravel auth route -->
                <form action="{{ route('login') }}" method="POST" autocomplete="off">
                    @csrf
                    
                    <!-- Hidden input to track role selection mapping -->
                    <input type="hidden" name="role" id="login-role" value="consumer">

                    <!-- Username / Email Address Alphanumeric Field -->
                    <div class="mb-4">
                        <label for="login-identity" class="form-label form-label-hud" id="identity-label">Email or Meter Number</label>
                        <div class="input-group-hud">
                            <div class="input-icon-hud">
                                <i class="bi bi-person-fill" id="identity-icon"></i>
                            </div>
                            <input type="text" 
                                   name="identity" 
                                   id="login-identity" 
                                   class="form-control-hud" 
                                   placeholder="consumer@example.com or meter number"
                                   required 
                                   value="{{ old('identity') }}">
                        </div>
                        <div class="form-text text-secondary" style="font-size: 0.75rem;">
                            Verify distribution details mapped in your consumer billing file.
                        </div>
                    </div>

                    <!-- Password Masked Field -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label for="login-password" class="form-label form-label-hud m-0">Encrypted Pass-Key</label>
                            <a href="#" class="text-decoration-none text-secondary tech-font" style="font-size: 0.72rem;">Recovery Key?</a>
                        </div>
                        <div class="input-group-hud">
                            <div class="input-icon-hud">
                                <i class="bi bi-key-fill"></i>
                            </div>
                            <input type="password" 
                                   name="password" 
                                   id="login-password" 
                                   class="form-control-hud" 
                                   placeholder="••••••••"
                                   required>
                            <div class="password-toggle-hud" onclick="togglePasswordVisibility()">
                                <i class="bi bi-eye-fill" id="eye-icon"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Remember session configuration check -->
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div class="form-check m-0">
                            <input class="form-check-input bg-transparent border-secondary" type="checkbox" name="remember" id="remember-me" style="border-radius: 4px;">
                            <label class="form-check-label text-secondary" for="remember-me" style="font-size: 0.85rem; user-select: none;">
                                Persist Active Node Connection
                            </label>
                        </div>
                    </div>

                    <!-- Submit Login Button -->
                    <button type="submit" class="btn btn-cyber-action tech-font">
                        AUTHENTICATE CONNECTION
                    </button>

                    <!-- Switch to Registration Option -->
                    <div class="text-center mt-4" id="register-redirect">
                        <span class="text-secondary" style="font-size: 0.82rem;">Unregistered consumer terminal?</span> 
                        <a href="{{ route('register') }}" class="text-info text-decoration-none fw-semibold ms-1 tech-font" style="font-size: 0.82rem; text-shadow: 0 0 5px var(--neon-teal-glow);">Register Node <i class="bi bi-arrow-right-short"></i></a>
                    </div>
                </form>

            </div>
        </div>
        
        <!-- Footer trademark -->
        <div class="text-center mt-4 text-secondary tech-font" style="font-size: 0.7rem; letter-spacing: 0.5px;">
            SECURE LINK LOG // DEPT OF POWER // BANGLADESH POWER CORP.
        </div>
    </div>

    <!-- Script to toggle login layout states -->
    <script>
        function switchRole(role) {
            const consumerTab = document.getElementById('consumer-tab');
            const adminTab = document.getElementById('admin-tab');
            const roleBadge = document.getElementById('role-badge');
            const hiddenRoleInput = document.getElementById('login-role');
            const identityLabel = document.getElementById('identity-label');
            const identityInput = document.getElementById('login-identity');
            const identityIcon = document.getElementById('identity-icon');
            const registerRedirect = document.getElementById('register-redirect');

            // Reset tab styling classes
            consumerTab.classList.remove('active');
            adminTab.classList.remove('active');

            if (role === 'admin') {
                adminTab.classList.add('active');
                roleBadge.innerText = 'GRID OPERATOR SECURITY LOCK';
                roleBadge.style.color = 'var(--electric-amber)';
                roleBadge.style.borderColor = 'rgba(255, 159, 28, 0.3)';
                roleBadge.style.textShadow = '0 0 5px var(--electric-amber-glow)';
                hiddenRoleInput.value = 'admin';
                identityLabel.innerText = 'Operator Alphanumeric Username';
                identityInput.placeholder = 'e.g. operator_dh4';
                identityIcon.className = 'bi bi-shield-fill-exclamation';
                registerRedirect.style.display = 'none';
            } else {
                consumerTab.classList.add('active');
                roleBadge.innerText = 'POWER CONSUMER VALIDATION';
                roleBadge.style.color = 'var(--neon-teal)';
                roleBadge.style.borderColor = 'var(--border-neon-teal)';
                roleBadge.style.textShadow = '0 0 5px var(--neon-teal-glow)';
                hiddenRoleInput.value = 'consumer';
                identityLabel.innerText = 'Email or Meter Number';
                identityInput.placeholder = 'consumer@example.com or meter number';
                identityIcon.className = 'bi bi-person-fill';
                registerRedirect.style.display = 'block';
            }
        }

        // Toggle secure password view
        function togglePasswordVisibility() {
            const passwordField = document.getElementById('login-password');
            const eyeIcon = document.getElementById('eye-icon');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeIcon.className = 'bi bi-eye-slash-fill';
            } else {
                passwordField.type = 'password';
                eyeIcon.className = 'bi bi-eye-fill';
            }
        }
    </script>
</body>
</html>
