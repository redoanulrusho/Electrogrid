<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'ElectroGrid - Grid Control')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Rajdhani:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --bg-obsidian: #080f24;
            --card-glass: rgba(14, 23, 42, 0.75);
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
            min-height: 100vh;
            overflow-x: hidden;
        }

        .tech-font {
            font-family: 'Rajdhani', sans-serif;
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }

        /* ── Top Navbar ── */
        .admin-nav {
            background: rgba(9, 14, 26, 0.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-neon-teal);
            padding: 0 2rem;
            height: 68px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .admin-nav .brand-bolt {
            color: var(--neon-teal);
            filter: drop-shadow(0 0 8px var(--neon-teal));
            animation: pulse-glow 2s infinite ease-in-out;
        }

        @keyframes pulse-glow {
            0%, 100% { opacity: 0.6; transform: scale(0.95); }
            50%       { opacity: 1;   transform: scale(1.05); }
        }

        .nav-link-admin {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.45rem 0.9rem;
            color: var(--text-dim);
            text-decoration: none;
            border-radius: 5px;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            border: 1px solid transparent;
            transition: all 0.2s ease;
        }

        .nav-link-admin:hover {
            color: var(--neon-teal);
            background: rgba(0,245,255,0.05);
            border-color: var(--border-neon-teal);
        }

        .nav-link-admin.active {
            color: var(--neon-teal);
            background: rgba(0,245,255,0.08);
            border-color: var(--border-neon-teal-active);
            text-shadow: 0 0 6px var(--neon-teal-glow);
        }

        /* ── Page wrapper ── */
        .page-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem 2rem 3rem 2rem;
        }

        /* ── Cyber Cards ── */
        .cyber-card {
            background: var(--card-glass);
            border: 1px solid var(--border-neon-teal);
            border-radius: 8px;
            box-shadow: 0 4px 25px rgba(0,0,0,0.4);
            position: relative;
            overflow: hidden;
            transition: all var(--transition-speed) ease;
        }

        .cyber-card::after {
            content: '';
            position: absolute;
            top: 0; right: 0;
            width: 10px; height: 10px;
            border-top: 2px solid var(--neon-teal);
            border-right: 2px solid var(--neon-teal);
            pointer-events: none;
            opacity: 0.5;
        }

        .cyber-card::before {
            content: '';
            position: absolute;
            bottom: 0; left: 0;
            width: 10px; height: 10px;
            border-bottom: 2px solid var(--neon-teal);
            border-left: 2px solid var(--neon-teal);
            pointer-events: none;
            opacity: 0.5;
        }

        .cyber-card:hover {
            border-color: var(--border-neon-teal-active);
            box-shadow: 0 8px 30px rgba(0,245,255,0.12);
        }

        .cyber-card.card-warning:hover  { border-color: var(--electric-amber); box-shadow: 0 8px 30px rgba(255,159,28,0.15); }
        .cyber-card.card-warning::after, .cyber-card.card-warning::before { border-color: var(--electric-amber); }
        .cyber-card.card-danger:hover   { border-color: var(--warning-crimson); box-shadow: 0 8px 30px rgba(255,42,84,0.15); }
        .cyber-card.card-danger::after, .cyber-card.card-danger::before { border-color: var(--warning-crimson); }

        .cyber-card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border-neon-teal);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(180deg, rgba(0,245,255,0.03) 0%, rgba(0,0,0,0) 100%);
        }

        /* ── Buttons ── */
        .btn-cyber-primary {
            background-color: rgba(0,245,255,0.08);
            border: 1px solid var(--neon-teal);
            color: var(--neon-teal);
            font-weight: 700;
            font-size: 0.85rem;
            transition: all 0.2s ease;
        }
        .btn-cyber-primary:hover { background-color: var(--neon-teal); color: #000; box-shadow: 0 0 15px var(--neon-teal-glow); }

        .btn-cyber-outline {
            background-color: transparent;
            border: 1px solid var(--border-neon-teal);
            color: var(--text-hud);
            transition: all 0.2s ease;
        }
        .btn-cyber-outline:hover { border-color: var(--neon-teal); color: var(--neon-teal); background-color: rgba(0,245,255,0.02); }

        /* ── Status ── */
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
            width: 8px; height: 8px;
            border-radius: 50%;
            position: relative;
        }

        .status-dot::after {
            content: '';
            position: absolute;
            top: -3px; left: -3px;
            width: 14px; height: 14px;
            border-radius: 50%;
            animation: pulse-ring 1.8s cubic-bezier(0.215,0.610,0.355,1) infinite;
        }

        @keyframes pulse-ring {
            0%   { transform: scale(0.5); opacity: 1; }
            100% { transform: scale(1.6); opacity: 0; }
        }

        .telemetry-active  { background: rgba(0,230,118,0.08); color: var(--grid-green); border: 1px solid rgba(0,230,118,0.25); }
        .telemetry-active  .status-dot, .telemetry-active  .status-dot::after { background-color: var(--grid-green); }
        .telemetry-shedding{ background: rgba(255,159,28,0.08); color: var(--electric-amber); border: 1px solid rgba(255,159,28,0.25); }
        .telemetry-shedding .status-dot, .telemetry-shedding .status-dot::after { background-color: var(--electric-amber); }
        .telemetry-down    { background: rgba(255,42,84,0.08); color: var(--warning-crimson); border: 1px solid rgba(255,42,84,0.25); }
        .telemetry-down    .status-dot, .telemetry-down    .status-dot::after { background-color: var(--warning-crimson); }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg-obsidian); }
        ::-webkit-scrollbar-thumb { background: var(--border-neon-teal); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--neon-teal); }

        @media (max-width: 768px) {
            .admin-nav { padding: 0 1rem; }
            .page-content { padding: 1.25rem 1rem 2rem; }
            .nav-link-admin span { display: none; }
        }
    </style>
    @yield('styles')
</head>
<body>

    {{-- ── Admin Top Navbar ── --}}
    <nav class="admin-nav">
        {{-- Brand --}}
        <div class="d-flex align-items-center gap-3">
            <i class="bi bi-lightning-charge-fill brand-bolt fs-4"></i>
            <div class="d-flex flex-column">
                <span class="tech-font fw-bold text-white" style="font-size:1.2rem;text-shadow:0 0 8px var(--neon-teal-glow);line-height:1.1;">ELECTROGRID</span>
                <span class="text-secondary" style="font-size:0.65rem;letter-spacing:1px;">GRID CONTROL HUD</span>
            </div>

            {{-- Nav links --}}
            <div class="d-none d-md-flex align-items-center gap-1 ms-3">
                <a href="{{ route('admin.dashboard') }}"
                   class="nav-link-admin {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-cpu"></i><span>Dashboard</span>
                </a>
                <a href="{{ route('admin.feeders.report') }}"
                   class="nav-link-admin {{ request()->routeIs('admin.feeders.report') ? 'active' : '' }}">
                    <i class="bi bi-lightning-fill"></i><span>Feeders</span>
                </a>
                <a href="{{ route('admin.tickets') }}"
                   class="nav-link-admin {{ request()->routeIs('admin.tickets') ? 'active' : '' }}">
                    <i class="bi bi-envelope-exclamation-fill"></i><span>Tickets</span>
                </a>
            </div>
        </div>

        {{-- Right section --}}
        <div class="d-flex align-items-center gap-3">
            {{-- Live frequency --}}
            <div class="d-none d-xl-flex flex-column text-end">
                <span class="tech-font text-secondary" style="font-size:0.62rem;letter-spacing:0.5px;">SYSTEM FREQ</span>
                <span class="tech-font fw-bold text-white" id="live-frequency" style="font-size:0.9rem;">50.00 Hz</span>
            </div>

            {{-- Uptime --}}
            <div class="d-none d-lg-flex flex-column text-end">
                <span class="tech-font text-secondary" style="font-size:0.62rem;letter-spacing:0.5px;">UPTIME</span>
                <span class="tech-font fw-bold text-white" id="session-uptime" style="font-size:0.9rem;">00:00:00</span>
            </div>

            {{-- Admin badge --}}
            <span class="tech-font badge px-2 py-1 border d-none d-sm-inline"
                  style="background:rgba(255,159,28,0.05);color:var(--electric-amber);border-color:rgba(255,159,28,0.3);font-size:0.68rem;">
                <i class="bi bi-shield-lock-fill me-1"></i>ADMIN
            </span>

            {{-- Mobile nav toggle --}}
            <div class="dropdown d-md-none">
                <button class="btn btn-cyber-outline btn-sm" data-bs-toggle="dropdown">
                    <i class="bi bi-list"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end" style="background:#0e1525;border:1px solid var(--border-neon-teal);">
                    <li><a class="dropdown-item tech-font" href="{{ route('admin.dashboard') }}"><i class="bi bi-cpu me-2 text-info"></i>Dashboard</a></li>
                    <li><a class="dropdown-item tech-font" href="{{ route('admin.feeders.report') }}"><i class="bi bi-lightning-fill me-2 text-info"></i>Feeders</a></li>
                    <li><a class="dropdown-item tech-font" href="{{ route('admin.tickets') }}"><i class="bi bi-envelope-exclamation-fill me-2 text-info"></i>Tickets</a></li>
                </ul>
            </div>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm tech-font px-3 py-2 d-flex align-items-center gap-1"
                        style="border-radius:4px;border:1px solid var(--warning-crimson);color:var(--warning-crimson);background:rgba(255,42,84,0.05);"
                        onclick="return confirm('Terminate Grid Session?')">
                    <i class="bi bi-power"></i>
                    <span class="d-none d-sm-inline">Logout</span>
                </button>
            </form>
        </div>
    </nav>

    {{-- ── Page Content ── --}}
    <div class="page-content">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Uptime counter
        let seconds = 0;
        setInterval(function () {
            seconds++;
            const h = String(Math.floor(seconds / 3600)).padStart(2, '0');
            const m = String(Math.floor((seconds % 3600) / 60)).padStart(2, '0');
            const s = String(seconds % 60).padStart(2, '0');
            const el = document.getElementById('session-uptime');
            if (el) el.textContent = h + ':' + m + ':' + s;
        }, 1000);

        // Frequency sim
        setInterval(function () {
            const el = document.getElementById('live-frequency');
            if (el) el.textContent = (49.95 + Math.random() * 0.1).toFixed(2) + ' Hz';
        }, 1500);
    </script>
    @yield('scripts')
</body>
</html>
