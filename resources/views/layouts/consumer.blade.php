<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ElectroGrid — Consumer Portal')</title>

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
        .consumer-nav {
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

        .brand-bolt-glow {
            color: var(--neon-teal);
            filter: drop-shadow(0 0 8px var(--neon-teal));
            animation: pulse-glow 2s infinite ease-in-out;
        }

        @keyframes pulse-glow {
            0%, 100% { opacity: 0.6; transform: scale(0.95); }
            50%       { opacity: 1;   transform: scale(1.05); }
        }

        .nav-link-consumer {
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

        .nav-link-consumer:hover {
            color: var(--neon-teal);
            background: rgba(0,245,255,0.05);
            border-color: var(--border-neon-teal);
        }

        .nav-link-consumer.active {
            color: var(--neon-teal);
            background: rgba(0,245,255,0.08);
            border-color: var(--border-neon-teal-active);
            text-shadow: 0 0 6px var(--neon-teal-glow);
        }

        /* ── Page wrapper ── */
        .page-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 2rem 3rem 2rem;
        }

        /* ── Cyber Cards ── */
        .cyber-card {
            background: var(--card-glass);
            border: 1px solid var(--border-neon-teal);
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.4);
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

        .cyber-card.card-danger { }
        .cyber-card.card-danger:hover  { border-color: var(--warning-crimson); box-shadow: 0 8px 30px rgba(255,42,84,0.15); }
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

        /* ── Notification items ── */
        .notification-item {
            background: rgba(0,245,255,0.03);
            border: 1px solid var(--border-neon-teal);
            border-radius: 6px;
            padding: 0.9rem 1.1rem;
            margin-bottom: 0.5rem;
            font-size: 0.88rem;
        }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: var(--bg-obsidian); }
        ::-webkit-scrollbar-thumb { background: var(--border-neon-teal); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--neon-teal); }

        @media (max-width: 768px) {
            .consumer-nav { padding: 0 1rem; }
            .page-content { padding: 1.25rem 1rem 2rem; }
            .nav-link-consumer span { display: none; }
        }
    </style>
    @yield('styles')
</head>
<body>

    {{-- ── Consumer Top Navbar ── --}}
    <nav class="consumer-nav">
        {{-- Brand + nav links --}}
        <div class="d-flex align-items-center gap-3">
            <i class="bi bi-lightning-charge-fill brand-bolt-glow fs-4"></i>
            <div class="d-flex flex-column">
                <span class="tech-font fw-bold text-white" style="font-size:1.2rem;text-shadow:0 0 8px var(--neon-teal-glow);line-height:1.1;">ELECTROGRID</span>
                <span class="text-secondary" style="font-size:0.65rem;letter-spacing:1px;">CONSUMER PORTAL</span>
            </div>

            <div class="d-none d-md-flex align-items-center gap-1 ms-3">
                <a href="{{ route('consumer.dashboard') }}"
                   class="nav-link-consumer {{ request()->routeIs('consumer.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i><span>Dashboard</span>
                </a>
                <a href="{{ route('consumer.bills') }}"
                   class="nav-link-consumer {{ request()->routeIs('consumer.bills') ? 'active' : '' }}">
                    <i class="bi bi-receipt"></i><span>Bills</span>
                </a>
                <a href="{{ route('consumer.tickets') }}"
                   class="nav-link-consumer {{ request()->routeIs('consumer.tickets*') ? 'active' : '' }}">
                    <i class="bi bi-ticket-perforated-fill"></i><span>Tickets</span>
                    @php $openCount = auth()->guard('web')->user()?->tickets()->where('status','open')->count() ?? 0; @endphp
                    @if($openCount > 0)
                        <span class="badge bg-warning text-dark" style="font-size:0.6rem;padding:2px 5px;">{{ $openCount }}</span>
                    @endif
                </a>
            </div>
        </div>

        {{-- Right section --}}
        <div class="d-flex align-items-center gap-2">
            {{-- Consumer class badge --}}
            <span class="tech-font badge px-2 py-1 border d-none d-sm-inline"
                  style="background:rgba(0,245,255,0.05);color:var(--neon-teal);border-color:rgba(0,245,255,0.3);font-size:0.68rem;">
                <i class="bi bi-person-circle me-1"></i>
                {{ strtoupper(auth()->guard('web')->user()?->consumer_class ?? 'Consumer') }}
            </span>

            {{-- Mobile nav dropdown --}}
            <div class="dropdown d-md-none">
                <button class="btn btn-cyber-outline btn-sm" data-bs-toggle="dropdown">
                    <i class="bi bi-list"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end" style="background:#0e1525;border:1px solid var(--border-neon-teal);">
                    <li><a class="dropdown-item tech-font" href="{{ route('consumer.dashboard') }}"><i class="bi bi-speedometer2 me-2 text-info"></i>Dashboard</a></li>
                    <li><a class="dropdown-item tech-font" href="{{ route('consumer.bills') }}"><i class="bi bi-receipt me-2 text-info"></i>Bills</a></li>
                    <li><a class="dropdown-item tech-font" href="{{ route('consumer.tickets') }}"><i class="bi bi-ticket-perforated-fill me-2 text-info"></i>Tickets</a></li>
                    <li><a class="dropdown-item tech-font" href="{{ route('consumer.tickets.create') }}"><i class="bi bi-plus-circle me-2 text-info"></i>New Complaint</a></li>
                </ul>
            </div>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm tech-font px-3 py-2 d-flex align-items-center gap-1"
                        style="border-radius:4px;border:1px solid var(--warning-crimson);color:var(--warning-crimson);background:rgba(255,42,84,0.05);"
                        onclick="return confirm('Disconnect session?')">
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
    @yield('scripts')
</body>
</html>
