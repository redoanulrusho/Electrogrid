<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'ElectroGrid - Main Grid HUD')</title>
    
    <!-- Google Fonts: Rajdhani for futuristic technical layouts & Inter for body text -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Rajdhani:wght@500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Premium Raw Custom CSS: Cybernetic Grid Console Theme -->
    <style>
        :root {
            --bg-obsidian: #03060c;
            --bg-hud: rgba(9, 14, 26, 0.7);
            --card-glass: rgba(14, 21, 37, 0.65);
            --border-neon-teal: rgba(0, 245, 255, 0.15);
            --border-neon-teal-active: rgba(0, 245, 255, 0.55);
            --neon-teal: #00f5ff;
            --neon-teal-glow: rgba(0, 245, 255, 0.25);
            --electric-amber: #ff9f1c;
            --electric-amber-glow: rgba(255, 159, 28, 0.25);
            --warning-crimson: #ff2a54;
            --warning-crimson-glow: rgba(255, 42, 84, 0.25);
            --grid-green: #00e676;
            --grid-green-glow: rgba(0, 230, 118, 0.2);
            --text-hud: #c9d1d9;
            --text-dim: #768390;
            --transition-speed: 0.25s;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-obsidian);
            color: var(--text-hud);
            overflow-x: hidden;
            min-height: 100vh;
            background-image: 
                linear-gradient(rgba(0, 245, 255, 0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 245, 255, 0.02) 1px, transparent 1px);
            background-size: 30px 30px;
            position: relative;
        }

        /* Subtle scanline animation covering the whole viewport simulating high-tech monitoring displays */
        body::before {
            content: " ";
            display: block;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            background: linear-gradient(rgba(18, 16, 16, 0) 50%, rgba(0, 0, 0, 0.25) 50%), linear-gradient(90deg, rgba(255, 0, 0, 0.06), rgba(0, 255, 0, 0.02), rgba(0, 0, 255, 0.06));
            z-index: 99999;
            background-size: 100% 3px, 6px 100%;
            pointer-events: none;
        }

        /* Technical Titles / Headers Style */
        .tech-font {
            font-family: 'Rajdhani', sans-serif;
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }

        /* Webkit scrollbar customizations */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: var(--bg-obsidian);
        }
        ::-webkit-scrollbar-thumb {
            background: var(--border-neon-teal);
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--neon-teal);
            box-shadow: 0 0 10px var(--neon-teal-glow);
        }

        /* Sidebar Styling */
        #sidebar-hud {
            width: 260px;
            min-height: 100vh;
            background-color: var(--bg-hud);
            backdrop-filter: blur(10px);
            border-right: 1px solid var(--border-neon-teal);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: all var(--transition-speed) ease;
        }

        .sidebar-brand-hud {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid var(--border-neon-teal);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sidebar-brand-hud span {
            font-weight: 800;
            font-size: 1.5rem;
            color: #fff;
            text-shadow: 0 0 10px var(--neon-teal-glow);
        }

        .sidebar-brand-hud .brand-bolt-glow {
            color: var(--neon-teal);
            filter: drop-shadow(0 0 8px var(--neon-teal));
            animation: pulse-glow 2s infinite ease-in-out;
        }

        @keyframes pulse-glow {
            0%, 100% { opacity: 0.6; transform: scale(0.95); }
            50% { opacity: 1; transform: scale(1.05); }
        }

        .nav-menu-hud {
            padding: 1.25rem 0.75rem;
            list-style: none;
            margin: 0;
        }

        .nav-item-hud {
            margin-bottom: 0.75rem;
        }

        .nav-link-hud {
            display: flex;
            align-items: center;
            padding: 0.8rem 1.2rem;
            color: var(--text-dim);
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all var(--transition-speed) ease;
            border: 1px solid transparent;
            position: relative;
            background-color: rgba(0, 0, 0, 0.2);
        }

        .nav-link-hud i {
            font-size: 1.1rem;
            margin-right: 0.85rem;
            color: var(--text-dim);
            transition: color var(--transition-speed) ease;
        }

        .nav-link-hud:hover {
            color: var(--neon-teal);
            background-color: rgba(0, 245, 255, 0.05);
            border-color: var(--border-neon-teal);
            text-shadow: 0 0 5px var(--neon-teal-glow);
        }

        .nav-link-hud:hover i {
            color: var(--neon-teal);
        }

        .nav-link-hud.active {
            color: var(--neon-teal);
            background-color: rgba(0, 245, 255, 0.08);
            border-color: var(--border-neon-teal-active);
            box-shadow: 0 0 15px rgba(0, 245, 255, 0.1);
            text-shadow: 0 0 8px var(--neon-teal-glow);
        }

        .nav-link-hud.active i {
            color: var(--neon-teal);
            filter: drop-shadow(0 0 4px var(--neon-teal));
        }

        .nav-link-hud.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 25%;
            height: 50%;
            width: 3px;
            background-color: var(--neon-teal);
            box-shadow: 0 0 8px var(--neon-teal);
            border-radius: 4px;
        }

        /* Top Header HUD Navbar */
        #top-navbar-hud {
            height: 75px;
            background-color: var(--bg-hud);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-neon-teal);
            position: fixed;
            top: 0;
            left: 260px;
            right: 0;
            z-index: 999;
            transition: all var(--transition-speed) ease;
        }

        .navbar-content-hud {
            height: 100%;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Main Scaffold */
        #main-content-hud {
            margin-left: 260px;
            padding: 105px 2rem 2.5rem 2rem;
            min-height: 100vh;
            transition: all var(--transition-speed) ease;
        }

        /* Cyber Glass Cards with Clip Corners style borders */
        .cyber-card {
            background: var(--card-glass);
            border: 1px solid var(--border-neon-teal);
            border-radius: 8px;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.4);
            position: relative;
            overflow: hidden;
            transition: all var(--transition-speed) ease;
        }

        .cyber-card::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 10px;
            height: 10px;
            border-top: 2px solid var(--neon-teal);
            border-right: 2px solid var(--neon-teal);
            pointer-events: none;
            opacity: 0.5;
        }

        .cyber-card::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 10px;
            height: 10px;
            border-bottom: 2px solid var(--neon-teal);
            border-left: 2px solid var(--neon-teal);
            pointer-events: none;
            opacity: 0.5;
        }

        .cyber-card:hover {
            border-color: var(--border-neon-teal-active);
            box-shadow: 0 8px 30px rgba(0, 245, 255, 0.15);
        }

        .cyber-card.card-warning:hover {
            border-color: var(--electric-amber);
            box-shadow: 0 8px 30px rgba(255, 159, 28, 0.15);
        }

        .cyber-card.card-warning::after, .cyber-card.card-warning::before {
            border-color: var(--electric-amber);
        }

        .cyber-card.card-danger:hover {
            border-color: var(--warning-crimson);
            box-shadow: 0 8px 30px rgba(255, 42, 84, 0.15);
        }

        .cyber-card.card-danger::after, .cyber-card.card-danger::before {
            border-color: var(--warning-crimson);
        }

        .cyber-card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border-neon-teal);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(180deg, rgba(0, 245, 255, 0.03) 0%, rgba(0,0,0,0) 100%);
        }

        /* Cyberpunk Tables styling */
        .cyber-table {
            background-color: transparent;
            color: var(--text-hud);
            border-collapse: separate;
            border-spacing: 0 8px;
        }

        .cyber-table thead th {
            border: none;
            color: var(--neon-teal);
            text-shadow: 0 0 5px var(--neon-teal-glow);
            font-weight: 700;
            font-size: 0.8rem;
            letter-spacing: 1.5px;
            padding: 12px 18px;
            background-color: rgba(0, 245, 255, 0.03) !important;
            border-bottom: 1px solid var(--border-neon-teal);
        }

        .cyber-table tbody tr {
            background-color: rgba(14, 21, 37, 0.4);
            border: 1px solid var(--border-neon-teal);
            transition: all var(--transition-speed) ease;
        }

        .cyber-table tbody tr:hover {
            background-color: rgba(14, 21, 37, 0.85);
            box-shadow: 0 0 10px rgba(0, 245, 255, 0.05);
        }

        .cyber-table tbody td {
            border-top: 1px solid var(--border-neon-teal);
            border-bottom: 1px solid var(--border-neon-teal);
            padding: 14px 18px;
        }

        .cyber-table tbody tr td:first-child {
            border-left: 1px solid var(--border-neon-teal);
            border-top-left-radius: 4px;
            border-bottom-left-radius: 4px;
        }

        .cyber-table tbody tr td:last-child {
            border-right: 1px solid var(--border-neon-teal);
            border-top-right-radius: 4px;
            border-bottom-right-radius: 4px;
        }

        /* Interactive Telemetry Indicator status lights */
        .telemetry-status {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 700;
            font-size: 0.78rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            padding: 0.35rem 0.75rem;
            border-radius: 4px;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            position: relative;
        }

        /* Pulsing Glow Animation */
        .status-dot::after {
            content: '';
            position: absolute;
            top: -3px;
            left: -3px;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            animation: pulse-ring 1.8s cubic-bezier(0.215, 0.610, 0.355, 1) infinite;
        }

        @keyframes pulse-ring {
            0% { transform: scale(0.5); opacity: 1; }
            100% { transform: scale(1.6); opacity: 0; }
        }

        /* Active Teal Status */
        .telemetry-active {
            background-color: rgba(0, 230, 118, 0.08);
            color: var(--grid-green);
            border: 1px solid rgba(0, 230, 118, 0.25);
        }

        .telemetry-active .status-dot {
            background-color: var(--grid-green);
        }

        .telemetry-active .status-dot::after {
            background-color: var(--grid-green);
        }

        /* Shedding Amber Status */
        .telemetry-shedding {
            background-color: rgba(255, 159, 28, 0.08);
            color: var(--electric-amber);
            border: 1px solid rgba(255, 159, 28, 0.25);
        }

        .telemetry-shedding .status-dot {
            background-color: var(--electric-amber);
        }

        .telemetry-shedding .status-dot::after {
            background-color: var(--electric-amber);
        }

        /* Outage Crimson Status */
        .telemetry-down {
            background-color: rgba(255, 42, 84, 0.08);
            color: var(--warning-crimson);
            border: 1px solid rgba(255, 42, 84, 0.25);
        }

        .telemetry-down .status-dot {
            background-color: var(--warning-crimson);
        }

        .telemetry-down .status-dot::after {
            background-color: var(--warning-crimson);
        }

        /* Neon HUD Buttons */
        .btn-cyber-primary {
            background-color: rgba(0, 245, 255, 0.08);
            border: 1px solid var(--neon-teal);
            color: var(--neon-teal);
            font-weight: 700;
            font-size: 0.85rem;
            transition: all 0.2s ease;
        }

        .btn-cyber-primary:hover {
            background-color: var(--neon-teal);
            color: #000;
            box-shadow: 0 0 15px var(--neon-teal-glow);
        }

        .btn-cyber-outline {
            background-color: transparent;
            border: 1px solid var(--border-neon-teal);
            color: var(--text-hud);
            transition: all 0.2s ease;
        }

        .btn-cyber-outline:hover {
            border-color: var(--neon-teal);
            color: var(--neon-teal);
            background-color: rgba(0, 245, 255, 0.02);
            box-shadow: 0 0 10px rgba(0, 245, 255, 0.05);
        }

        /* Responsive */
        @media (max-width: 991.98px) {
            #sidebar-hud {
                left: -260px;
            }
            #sidebar-hud.active {
                left: 0;
            }
            #top-navbar-hud {
                left: 0;
            }
            #main-content-hud {
                margin-left: 0;
                padding: 95px 1rem 2rem 1rem;
            }
            .sidebar-toggle-btn {
                display: block !important;
            }
        }

        .sidebar-toggle-btn {
            display: none;
            background: none;
            border: none;
            color: var(--neon-teal);
            font-size: 1.5rem;
            margin-right: 1rem;
            cursor: pointer;
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Cyber HUD Sidebar -->
    <aside id="sidebar-hud">
        <div class="sidebar-brand-hud">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-lightning-charge-fill brand-bolt-glow fs-4"></i>
                <span class="tech-font">ELECTROGRID</span>
            </div>
        </div>
        
        <ul class="nav-menu-hud tech-font">
            <li class="nav-item-hud">
                <a href="/admin/dashboard" class="nav-link-hud active">
                    <i class="bi bi-cpu"></i>
                    <span>📊 HUD Dashboard</span>
                </a>
            </li>
            <li class="nav-item-hud">
                <a href="#" class="nav-link-hud">
                    <i class="bi bi-lightning-fill"></i>
                    <span>🔌 Feeder Capacity</span>
                </a>
            </li>
            <li class="nav-item-hud">
                <a href="#" class="nav-link-hud">
                    <i class="bi bi-calendar-range-fill"></i>
                    <span>📅 Grid Schedules</span>
                </a>
            </li>
            <li class="nav-item-hud">
                <a href="#" class="nav-link-hud">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span>🚨 Blackout Logs</span>
                </a>
            </li>
            <li class="nav-item-hud">
                <a href="#" class="nav-link-hud">
                    <i class="bi bi-envelope-exclamation-fill"></i>
                    <span>📥 User Tickets</span>
                </a>
            </li>
        </ul>

        <!-- Console status logs -->
        <div class="position-absolute bottom-0 start-0 w-100 p-3 border-top" style="border-top-color: var(--border-neon-teal) !important;">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="status-dot" style="width: 6px; height: 6px; background-color: var(--grid-green);"></span>
                    <small class="tech-font text-secondary" style="font-size: 0.7rem;">Grid Nodes Linked</small>
                </div>
                <small class="text-success tech-font" style="font-size: 0.7rem;">99.8% OK</small>
            </div>
            <div class="text-secondary tech-font" style="font-size: 0.65rem;">System: Oracle DB Connect</div>
        </div>
    </aside>

    <!-- Top HUD Header Navbar -->
    <header id="top-navbar-hud">
        <div class="navbar-content-hud">
            <div class="d-flex align-items-center">
                <button class="sidebar-toggle-btn" id="toggle-sidebar-hud">
                    <i class="bi bi-list"></i>
                </button>
                <div class="d-none d-md-flex flex-column">
                    <span class="tech-font fw-bold text-white m-0" style="font-size: 1.25rem; text-shadow: 0 0 5px var(--neon-teal-glow);">Grid Control HUD v1.4</span>
                    <span class="text-secondary" style="font-size: 0.72rem;">Zone Link: Dhaka Substation 4</span>
                </div>
            </div>

            <!-- Telemetry Metrics & Counters -->
            <div class="d-flex align-items-center gap-4">
                
                <!-- System Frequency Frequency HUD Telemetry -->
                <div class="d-none d-xl-flex flex-column text-end">
                    <span class="tech-font text-secondary" style="font-size: 0.68rem; letter-spacing: 0.5px;">System Frequency</span>
                    <span class="tech-font fw-bold text-white" id="live-frequency">50.00 Hz</span>
                </div>

                <!-- Session Access Indicator -->
                <span class="tech-font badge px-3 py-2 border" style="background-color: rgba(255, 159, 28, 0.05); color: var(--electric-amber); border-color: rgba(255, 159, 28, 0.3);">
                    <i class="bi bi-shield-lock-fill me-1"></i> Admin Operator
                </span>

                <!-- Uptime simulated ticking clock -->
                <div class="d-none d-sm-flex flex-column text-end">
                    <span class="tech-font text-secondary" style="font-size: 0.68rem; letter-spacing: 0.5px;">Grid Session Uptime</span>
                    <span class="tech-font text-white fw-bold" id="session-uptime">02:44:19</span>
                </div>

                <div class="border-start h-100 mx-1" style="border-left: 1px solid var(--border-neon-teal) !important; height: 35px !important;"></div>

                <!-- Custom Red HUD Sign Out Button -->
                <button class="btn btn-sm btn-outline-danger tech-font px-3 py-2 d-flex align-items-center gap-1" style="border-radius: 4px; border: 1px solid var(--warning-crimson); color: var(--warning-crimson); background-color: rgba(255, 42, 84, 0.05);" onclick="alert('Session Terminated.')">
                    <i class="bi bi-power"></i>
                    <span class="d-none d-sm-inline">Term. Session</span>
                </button>
            </div>
        </div>
    </header>

    <!-- Main Section -->
    <main id="main-content-hud">
        @yield('content')
    </main>

    <!-- Bootstrap 5 Bundle JS via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Interactive Clock & Frequency Telemetry Simulator script -->
    <script>
        // Toggling logic
        const toggleBtn = document.getElementById('toggle-sidebar-hud');
        const sidebar = document.getElementById('sidebar-hud');
        if (toggleBtn && sidebar) {
            toggleBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                sidebar.classList.toggle('active');
            });
            document.addEventListener('click', function(e) {
                if (window.innerWidth < 992) {
                    if (!sidebar.contains(e.target) && e.target !== toggleBtn) {
                        sidebar.classList.remove('active');
                    }
                }
            });
        }

        // Live Uptime Timer Ticker
        let seconds = 9859; // Simulated initial uptime in seconds (2h 44m 19s)
        setInterval(function() {
            seconds++;
            let hrs = Math.floor(seconds / 3600);
            let mins = Math.floor((seconds - (hrs * 3600)) / 60);
            let secs = seconds - (hrs * 3600) - (mins * 60);
            
            hrs = hrs < 10 ? '0' + hrs : hrs;
            mins = mins < 10 ? '0' + mins : mins;
            secs = secs < 10 ? '0' + secs : secs;
            
            const timerElement = document.getElementById('session-uptime');
            if (timerElement) timerElement.innerText = `${hrs}:${mins}:${secs}`;
        }, 1000);

        // Fluctuating Grid frequency simulator
        setInterval(function() {
            const freqVal = (49.95 + Math.random() * 0.1).toFixed(2);
            const freqElement = document.getElementById('live-frequency');
            if (freqElement) freqElement.innerText = freqVal + ' Hz';
        }, 1500);
    </script>
    @yield('scripts')
</body>
</html>
