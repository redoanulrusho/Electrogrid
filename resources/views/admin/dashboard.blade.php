@extends('layouts.app')

@section('title', 'ElectroGrid Console - Central Grid Telemetry')

@section('content')
<div class="container-fluid p-0">

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success border-0 p-3 mb-4 d-flex align-items-center gap-2"
             style="background-color: rgba(0,230,118,0.08); border: 1px solid rgba(0,230,118,0.3) !important; border-radius:6px;">
            <i class="bi bi-check-circle-fill text-success"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger border-0 p-3 mb-4 d-flex align-items-center gap-2"
             style="background-color: rgba(255,42,84,0.08); border: 1px solid rgba(255,42,84,0.3) !important; border-radius:6px;">
            <i class="bi bi-exclamation-octagon-fill text-danger"></i>
            <span>{{ session('error') }}</span>
            <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- HUD Neon Alert Banner (shown only when any feeder is in Outage) --}}
    @php $outageCount = $feeders->where('status','Outage')->count(); @endphp
    @if($outageCount > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-warning border-0 cyber-card card-warning d-flex align-items-center justify-content-between p-3" role="alert"
                 style="background-color: var(--electric-amber-glow); border: 1px solid rgba(255,159,28,0.4) !important;">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-2 rounded-circle d-flex align-items-center justify-content-center text-warning"
                         style="width:40px;height:40px;background-color:rgba(255,159,28,0.15);border:1px solid rgba(255,159,28,0.3);">
                        <i class="bi bi-radioactive fs-5"></i>
                    </div>
                    <div>
                        <strong class="text-white d-block tech-font" style="font-size:1.05rem;letter-spacing:0.5px;">
                            GRID OVERLOAD COMPENSATOR WARNING
                        </strong>
                        <span class="text-secondary" style="font-size:0.85rem;">
                            {{ $outageCount }} feeder(s) are currently offline. Force shedding active on affected sectors.
                        </span>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-cyber-primary tech-font px-3"
                            data-bs-toggle="modal" data-bs-target="#overrideModal">Override Control</button>
                    <button class="btn btn-sm btn-outline-slate text-white border-0" data-bs-dismiss="alert">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Telemetry Gauge Cards --}}
    <div class="row g-4 mb-4">
        {{-- Card 1: Feeders --}}
        <div class="col-12 col-md-6 col-xl-4">
            <div class="cyber-card p-4 h-100 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="tech-font text-secondary fw-semibold" style="font-size:0.78rem;letter-spacing:1.2px;">GRID DISTRIBUTION LINKS</span>
                        <h2 class="tech-font fw-bold mt-2 mb-1 text-white" style="font-size:2.5rem;text-shadow:0 0 10px var(--neon-teal-glow);">
                            {{ $stats['feeder_count'] }} <span class="fs-6 fw-normal text-dim">Feeders</span>
                        </h2>
                    </div>
                    <div class="p-3 rounded" style="background-color:rgba(0,245,255,0.08);border:1px solid var(--border-neon-teal-active);color:var(--neon-teal);">
                        <i class="bi bi-cpu-fill fs-4"></i>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-top" style="border-top-color:var(--border-neon-teal)!important;">
                    <div class="progress mb-2" style="height:4px;background-color:rgba(255,255,255,0.05);">
                        <div class="progress-bar bg-info" role="progressbar"
                             style="width:{{ $stats['feeder_count'] > 0 ? round(($stats['active_feeders']/$stats['feeder_count'])*100) : 0 }}%">
                        </div>
                    </div>
                    <div class="d-flex justify-content-between tech-font text-secondary" style="font-size:0.78rem;">
                        <span><i class="bi bi-circle-fill text-success me-1 fs-9"></i> {{ $stats['active_feeders'] }} Active Feeder Lines</span>
                        <span>{{ $stats['outage_feeders'] }} Downtime Sectors</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: MW Load --}}
        <div class="col-12 col-md-6 col-xl-4">
            <div class="cyber-card card-warning p-4 h-100 d-flex flex-column justify-content-between" style="border-color:rgba(255,159,28,0.15);">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="tech-font text-secondary fw-semibold" style="font-size:0.78rem;letter-spacing:1.2px;">ACTIVE LOAD DEMAND</span>
                        <h2 class="tech-font fw-bold mt-2 mb-1 text-warning" style="font-size:2.5rem;text-shadow:0 0 10px rgba(255,159,28,0.3);">
                            {{ number_format($stats['total_demand'], 1) }} <span class="fs-6 fw-normal text-dim">MW</span>
                        </h2>
                    </div>
                    <div class="p-3 rounded" style="background-color:rgba(255,159,28,0.08);border:1px solid rgba(255,159,28,0.3);color:var(--electric-amber);">
                        <i class="bi bi-lightning-charge-fill fs-4"></i>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-top" style="border-top-color:rgba(255,159,28,0.15)!important;">
                    <div class="progress mb-2" style="height:4px;background-color:rgba(255,255,255,0.05);">
                        <div class="progress-bar bg-warning" role="progressbar" style="width:{{ $stats['mw_deficit'] }}%"></div>
                    </div>
                    <div class="d-flex justify-content-between tech-font text-secondary" style="font-size:0.78rem;">
                        <span>System Max: {{ number_format($stats['total_capacity'], 1) }} MW</span>
                        <span class="text-warning">{{ $stats['mw_deficit'] }}% Load</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 3: Tickets --}}
        <div class="col-12 col-xl-4">
            <div class="cyber-card card-danger p-4 h-100 d-flex flex-column justify-content-between" style="border-color:rgba(255,42,84,0.15);">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="tech-font text-secondary fw-semibold" style="font-size:0.78rem;letter-spacing:1.2px;">OPEN CRITICAL TICKETS</span>
                        <h2 class="tech-font fw-bold mt-2 mb-1 text-danger" style="font-size:2.5rem;text-shadow:0 0 10px rgba(255,42,84,0.3);">
                            {{ $stats['ticket_count'] }} <span class="fs-6 fw-normal text-dim">Pending</span>
                        </h2>
                    </div>
                    <div class="p-3 rounded" style="background-color:rgba(255,42,84,0.08);border:1px solid rgba(255,42,84,0.3);color:var(--warning-crimson);">
                        <i class="bi bi-exclamation-octagon-fill fs-4"></i>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-top" style="border-top-color:rgba(255,42,84,0.15)!important;">
                    <div class="progress mb-2" style="height:4px;background-color:rgba(255,255,255,0.05);">
                        <div class="progress-bar bg-danger" role="progressbar"
                             style="width:{{ $stats['ticket_count'] > 0 ? min(100, $stats['open_tickets'] * 10) : 0 }}%"></div>
                    </div>
                    <div class="d-flex justify-content-between tech-font text-secondary" style="font-size:0.78rem;">
                        <span><i class="bi bi-shield-fill text-danger me-1"></i> {{ $stats['open_tickets'] }} Open Complaints</span>
                        <a href="{{ route('admin.tickets') }}" class="text-danger text-decoration-none">View Queue &rarr;</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ─── Feeder Node Cards ───────────────────────────────────────────────── --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="cyber-card">
                <div class="cyber-card-header">
                    <div>
                        <h5 class="tech-font text-white m-0 fw-bold">
                            <i class="bi bi-grid-3x3-gap-fill me-2 text-info"></i>Feeder Node Status Matrices
                        </h5>
                        <p class="text-secondary m-0 mt-1" style="font-size:0.75rem;">
                            Live grid network layout. Click actions on each card to control feeder status.
                        </p>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <div class="input-group input-group-sm" style="width:200px;">
                            <span class="input-group-text text-secondary" style="background:rgba(0,0,0,0.3);border:1px solid var(--border-neon-teal);">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" id="feeder-search" class="form-control text-white tech-font"
                                   placeholder="Search feeders..."
                                   style="background:rgba(0,0,0,0.3);border:1px solid var(--border-neon-teal);box-shadow:none;font-size:0.8rem;"
                                   oninput="filterFeeders(this.value)">
                        </div>
                        <a href="{{ route('admin.feeders.report') }}" class="btn btn-sm btn-cyber-outline tech-font">
                            <i class="bi bi-file-earmark-csv me-1"></i>Log Report
                        </a>
                    </div>
                </div>

                <div class="p-3">
                    <div class="row g-3" id="feeder-card-grid">
                        @forelse($feeders as $feeder)
                        <div class="col-12 col-md-6 col-xl-4 feeder-card-item"
                             data-feeder-search="{{ strtolower($feeder->substation_code . ' ' . $feeder->feeder_name . ' ' . $feeder->division) }}">
                            <div class="h-100 p-4 rounded position-relative"
                                 style="background:rgba(14,21,37,0.6);
                                        border-left: 3px solid {{ $feeder->status === 'Active' ? 'var(--grid-green)' : ($feeder->status === 'Outage' ? 'var(--warning-crimson)' : 'var(--electric-amber)') }};
                                        border-top:1px solid var(--border-neon-teal);
                                        border-right:1px solid var(--border-neon-teal);
                                        border-bottom:1px solid var(--border-neon-teal);
                                        border-radius:6px;transition:all 0.2s;">

                                {{-- Header: substation code + status dot --}}
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <code class="text-info fw-bold" style="font-size:1rem;font-family:monospace;">{{ $feeder->substation_code }}</code>
                                        @if($feeder->is_priority_zone)
                                            <span class="badge tech-font ms-2" style="background:rgba(255,42,84,0.1);border:1px solid rgba(255,42,84,0.3);color:var(--warning-crimson);font-size:0.6rem;">HIGH PRIORITY</span>
                                        @else
                                            <span class="badge tech-font ms-2" style="background:rgba(100,100,100,0.1);border:1px solid rgba(100,100,100,0.3);color:#768390;font-size:0.6rem;">STANDARD</span>
                                        @endif
                                    </div>
                                    <div class="d-flex flex-column align-items-end gap-1">
                                        {{-- Live status pill --}}
                                        <span class="badge tech-font d-flex align-items-center gap-1 px-2 py-1"
                                              style="font-size:0.65rem;
                                              @if($feeder->status === 'Active')
                                                  background:rgba(0,230,118,0.08);border:1px solid rgba(0,230,118,0.3);color:var(--grid-green);
                                              @elseif($feeder->status === 'Outage')
                                                  background:rgba(255,42,84,0.08);border:1px solid rgba(255,42,84,0.3);color:var(--warning-crimson);
                                              @else
                                                  background:rgba(255,159,28,0.08);border:1px solid rgba(255,159,28,0.3);color:var(--electric-amber);
                                              @endif">
                                            <span style="width:6px;height:6px;border-radius:50%;display:inline-block;background:currentColor;"></span>
                                            {{ strtoupper($feeder->status) }}
                                        </span>
                                        @php
                                            $risk = $riskByFeeder[$feeder->id] ?? null;
                                            $riskColor = ($risk['level'] ?? 'Low') === 'High'
                                                ? 'var(--warning-crimson)'
                                                : ((($risk['level'] ?? 'Low') === 'Medium') ? 'var(--electric-amber)' : 'var(--grid-green)');
                                        @endphp
                                        @if($risk)
                                        <span class="badge tech-font px-2 py-1"
                                              style="font-size:0.6rem;background:rgba(0,0,0,0.3);border:1px solid {{ $riskColor }};color:{{ $riskColor }};">
                                            RISK {{ strtoupper($risk['level']) }} · {{ $risk['score'] }}
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Feeder name + location --}}
                                <div class="fw-semibold text-white mb-1" style="font-size:0.92rem;">{{ $feeder->feeder_name }}</div>
                                <div class="text-secondary mb-3" style="font-size:0.75rem;">
                                    <i class="bi bi-geo-alt me-1"></i>{{ $feeder->division }} / {{ $feeder->district }} / {{ $feeder->upazila }}
                                </div>

                                {{-- Stats row --}}
                                <div class="row g-2 mb-3">
                                    <div class="col-4">
                                        <div class="p-2 rounded text-center" style="background:rgba(0,0,0,0.25);border:1px solid var(--border-neon-teal);">
                                            <div class="tech-font text-secondary" style="font-size:0.6rem;letter-spacing:0.5px;">CAPACITY</div>
                                            <div class="tech-font text-white fw-bold" style="font-size:0.9rem;">{{ number_format($feeder->max_capacity_mw, 1) }}<span class="text-secondary" style="font-size:0.6rem;"> MW</span></div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="p-2 rounded text-center" style="background:rgba(0,0,0,0.25);border:1px solid var(--border-neon-teal);">
                                            <div class="tech-font text-secondary" style="font-size:0.6rem;letter-spacing:0.5px;">DEMAND</div>
                                            <div class="tech-font fw-bold {{ $feeder->status === 'Active' ? 'text-info' : 'text-secondary' }}" style="font-size:0.9rem;">{{ number_format($feeder->current_demand_mw, 1) }}<span class="text-secondary" style="font-size:0.6rem;"> MW</span></div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="p-2 rounded text-center" style="background:rgba(0,0,0,0.25);border:1px solid var(--border-neon-teal);">
                                            <div class="tech-font text-secondary" style="font-size:0.6rem;letter-spacing:0.5px;">CONSUMERS</div>
                                            <div class="tech-font text-white fw-bold" style="font-size:0.9rem;">{{ $feeder->users->count() }}</div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Load bar --}}
                                @php
                                    $loadPct = $feeder->max_capacity_mw > 0
                                        ? round(($feeder->current_demand_mw / $feeder->max_capacity_mw) * 100)
                                        : 0;
                                    $barColor = $loadPct >= 90 ? 'var(--warning-crimson)' : ($loadPct >= 70 ? 'var(--electric-amber)' : 'var(--neon-teal)');
                                @endphp
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="tech-font text-secondary" style="font-size:0.62rem;">LOAD FACTOR</span>
                                        <span class="tech-font" style="font-size:0.62rem;color:{{ $barColor }};">{{ $loadPct }}%</span>
                                    </div>
                                    <div class="rounded" style="height:4px;background:rgba(255,255,255,0.05);">
                                        <div class="rounded" style="height:4px;width:{{ $loadPct }}%;background:{{ $barColor }};transition:width 0.5s;"></div>
                                    </div>
                                </div>

                                {{-- Action Buttons (inline — no dropdown, no overflow issue) --}}
                                <div class="d-flex gap-2 flex-wrap mt-auto">
                                    {{-- Telemetry --}}
                                    <button class="btn btn-sm btn-cyber-outline tech-font flex-grow-1"
                                            onclick="openTelemetry({{ $feeder->id }}, '{{ $feeder->substation_code }}')">
                                        <i class="bi bi-graph-up-arrow me-1 text-info"></i>Telemetry
                                    </button>

                                    {{-- Schedule Outage --}}
                                    <button class="btn btn-sm btn-cyber-outline tech-font flex-grow-1"
                                            onclick="openScheduleModal({{ $feeder->id }}, '{{ $feeder->substation_code }}')">
                                        <i class="bi bi-calendar-range me-1 text-warning"></i>Schedule
                                    </button>

                                    {{-- Provide Bill --}}
                                    <button class="btn btn-sm btn-cyber-outline tech-font flex-grow-1"
                                            onclick="openBillModal({{ $feeder->id }}, '{{ $feeder->substation_code }}', {{ json_encode($feeder->users) }})">
                                        <i class="bi bi-file-earmark-arrow-up me-1 text-success"></i>Provide Bill
                                    </button>
                                </div>

                                {{-- Status change buttons --}}
                                <div class="d-flex gap-2 flex-wrap mt-2">
                                    @if($feeder->status !== 'Active')
                                    <form method="POST" action="{{ route('admin.feeders.status', $feeder->id) }}" class="flex-grow-1">
                                        @csrf
                                        <input type="hidden" name="status" value="Active">
                                        <button type="submit" class="btn btn-sm w-100 tech-font"
                                                style="background:rgba(0,230,118,0.08);border:1px solid rgba(0,230,118,0.4);color:var(--grid-green);font-size:0.72rem;">
                                            <i class="bi bi-play-fill me-1"></i>Restore
                                        </button>
                                    </form>
                                    @endif

                                    @if($feeder->status !== 'Maintenance')
                                    <form method="POST" action="{{ route('admin.feeders.status', $feeder->id) }}" class="flex-grow-1">
                                        @csrf
                                        <input type="hidden" name="status" value="Maintenance">
                                        <button type="submit" class="btn btn-sm w-100 tech-font"
                                                style="background:rgba(255,159,28,0.08);border:1px solid rgba(255,159,28,0.4);color:var(--electric-amber);font-size:0.72rem;">
                                            <i class="bi bi-wrench me-1"></i>Maintenance
                                        </button>
                                    </form>
                                    @endif

                                    @if($feeder->status !== 'Outage')
                                    <form method="POST" action="{{ route('admin.feeders.status', $feeder->id) }}" class="flex-grow-1"
                                          onsubmit="return confirm('⚠️ Emergency Cutoff: Disconnect {{ $feeder->substation_code }} and notify {{ $feeder->users->count() }} consumers?')">
                                        @csrf
                                        <input type="hidden" name="status" value="Outage">
                                        <button type="submit" class="btn btn-sm w-100 tech-font"
                                                style="background:rgba(255,42,84,0.08);border:1px solid rgba(255,42,84,0.4);color:var(--warning-crimson);font-size:0.72rem;">
                                            <i class="bi bi-power me-1"></i>Cutoff
                                        </button>
                                    </form>
                                    @endif
                                </div>

                            </div>
                        </div>
                        @empty
                        <div class="col-12 text-center text-secondary py-5">
                            <i class="bi bi-cpu fs-2 d-block mb-2 text-info"></i>
                            No feeders found. Run <code>php artisan db:seed</code> to populate.
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ─── Grid Map ───────────────────────────────────────────────────────── --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="cyber-card">
                <div class="cyber-card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="tech-font text-white m-0 fw-bold">
                            <i class="bi bi-map me-2 text-info"></i>Grid Map
                        </h5>
                        <p class="text-secondary m-0 mt-1" style="font-size:0.75rem;">
                            Live feeder locations — marker color reflects current status. Updates in real time.
                        </p>
                    </div>
                    <button id="toggle-heatmap-btn" class="btn btn-sm btn-cyber-outline tech-font">
                        <i class="bi bi-fire me-1"></i>Show Complaints
                    </button>
                </div>
                <div id="grid-map" style="width:100%;height:480px;border-radius:0 0 8px 8px;"></div>
            </div>
        </div>
    </div>

{{-- ─── Live Telemetry Modal ──────────────────────────────────────────────── --}}
<div class="modal fade" id="telemetryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="background:#0e1525;border:1px solid rgba(0,245,255,0.4)!important;box-shadow:0 0 40px rgba(0,0,0,0.8);">
            <div class="modal-header" style="border-bottom:1px solid rgba(0,245,255,0.2);background:#0a1020;">
                <h5 class="modal-title tech-font" style="color:#00f5ff;font-size:1rem;letter-spacing:1px;">
                    <i class="bi bi-graph-up-arrow me-2" style="color:#00f5ff;"></i>
                    LIVE TELEMETRY — <span id="tm-code" style="color:#ff9f1c;"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="telemetry-body" style="background:#0e1525;">
                <div class="text-center py-3" style="color:#768390;"><i class="bi bi-arrow-repeat fs-2 spin-icon"></i><br>Loading telemetry...</div>
            </div>
        </div>
    </div>
</div>

{{-- ─── Outage Schedule Modal ─────────────────────────────────────────────── --}}
<div class="modal fade" id="scheduleModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="background:#0e1525;border:1px solid rgba(0,245,255,0.4)!important;box-shadow:0 0 40px rgba(0,0,0,0.8);">
            <div class="modal-header" style="border-bottom:1px solid rgba(0,245,255,0.2);background:#0a1020;">
                <h5 class="modal-title tech-font" style="color:#00f5ff;font-size:1rem;letter-spacing:1px;">
                    <i class="bi bi-calendar-range me-2" style="color:#ff9f1c;"></i>SCHEDULE OUTAGE — <span id="sched-code" style="color:#ff9f1c;"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="scheduleForm" method="POST">
                @csrf
                <div class="modal-body p-4" style="background:#0e1525;">
                    <div class="mb-4">
                        <label class="form-label tech-font" style="font-size:0.72rem;color:#00f5ff;letter-spacing:1px;">START TIME</label>
                        <input type="datetime-local" name="start_time" required
                               style="display:block;width:100%;background:#1a2332;border:1px solid rgba(0,245,255,0.5);border-radius:4px;color:#ffffff;padding:0.65rem 0.9rem;font-size:0.92rem;outline:none;colorscheme:dark;"
                               onfocus="this.style.borderColor='#00f5ff';this.style.boxShadow='0 0 8px rgba(0,245,255,0.2)';"
                               onblur="this.style.borderColor='rgba(0,245,255,0.5)';this.style.boxShadow='none';">
                    </div>
                    <div class="mb-4">
                        <label class="form-label tech-font" style="font-size:0.72rem;color:#00f5ff;letter-spacing:1px;">END TIME</label>
                        <input type="datetime-local" name="end_time" required
                               style="display:block;width:100%;background:#1a2332;border:1px solid rgba(0,245,255,0.5);border-radius:4px;color:#ffffff;padding:0.65rem 0.9rem;font-size:0.92rem;outline:none;colorscheme:dark;"
                               onfocus="this.style.borderColor='#00f5ff';this.style.boxShadow='0 0 8px rgba(0,245,255,0.2)';"
                               onblur="this.style.borderColor='rgba(0,245,255,0.5)';this.style.boxShadow='none';">
                    </div>
                    <div class="mb-2">
                        <label class="form-label tech-font" style="font-size:0.72rem;color:#00f5ff;letter-spacing:1px;">REASON <span style="color:#768390;">(OPTIONAL)</span></label>
                        <textarea name="reason" rows="3"
                                  placeholder="e.g. Scheduled rotational load-shedding"
                                  style="display:block;width:100%;background:#1a2332;border:1px solid rgba(0,245,255,0.5);border-radius:4px;color:#e6edf3;padding:0.65rem 0.9rem;font-size:0.9rem;outline:none;resize:vertical;"
                                  onfocus="this.style.borderColor='#00f5ff';this.style.boxShadow='0 0 8px rgba(0,245,255,0.2)';"
                                  onblur="this.style.borderColor='rgba(0,245,255,0.5)';this.style.boxShadow='none';"></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid rgba(0,245,255,0.2);background:#0a1020;">
                    <button type="button" data-bs-dismiss="modal"
                            style="background:transparent;border:1px solid rgba(0,245,255,0.4);color:#c9d1d9;padding:0.5rem 1.2rem;border-radius:4px;font-family:'Rajdhani',sans-serif;text-transform:uppercase;font-weight:700;cursor:pointer;transition:all 0.2s;"
                            onmouseover="this.style.borderColor='#00f5ff';this.style.color='#00f5ff';"
                            onmouseout="this.style.borderColor='rgba(0,245,255,0.4)';this.style.color='#c9d1d9';">Cancel</button>
                    <button type="submit"
                            style="background:rgba(0,245,255,0.12);border:1px solid #00f5ff;color:#00f5ff;padding:0.5rem 1.4rem;border-radius:4px;font-family:'Rajdhani',sans-serif;text-transform:uppercase;font-weight:700;cursor:pointer;transition:all 0.2s;"
                            onmouseover="this.style.background='#00f5ff';this.style.color='#000';"
                            onmouseout="this.style.background='rgba(0,245,255,0.12)';this.style.color='#00f5ff';">
                        <i class="bi bi-calendar-check me-1"></i> Confirm Schedule
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ─── Override Control Modal ────────────────────────────────────────────── --}}
<div class="modal fade" id="overrideModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0" style="background:#0e1525;border:1px solid rgba(255,159,28,0.5)!important;box-shadow:0 0 40px rgba(0,0,0,0.8);">
            <div class="modal-header" style="border-bottom:1px solid rgba(255,159,28,0.25);background:#0a1020;">
                <h5 class="modal-title tech-font" style="color:#ff9f1c;font-size:1rem;letter-spacing:1px;">
                    <i class="bi bi-radioactive me-2"></i>GRID OVERRIDE CONTROL PANEL
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" style="background:#0e1525;">
                <p style="color:#c9d1d9;font-size:0.85rem;margin-bottom:1rem;">
                    The following feeders are currently in a non-active state. Restore or force-cut individual feeders below.
                </p>
                @foreach($feeders->where('status','!=','Active') as $feeder)
                <div class="d-flex align-items-center justify-content-between p-3 mb-2 rounded"
                     style="background:#131d2e;border:1px solid rgba(255,159,28,0.25);">
                    <div>
                        <code style="color:#ff9f1c;font-weight:700;">{{ $feeder->substation_code }}</code>
                        <span style="color:#c9d1d9;margin-left:0.75rem;font-size:0.88rem;">{{ $feeder->feeder_name }}</span>
                        <span style="margin-left:0.5rem;font-size:0.75rem;color:{{ $feeder->status === 'Outage' ? '#ff2a54' : '#ff9f1c' }};font-family:'Rajdhani',sans-serif;">[ {{ strtoupper($feeder->status) }} ]</span>
                    </div>
                    <div class="d-flex gap-2">
                        <form method="POST" action="{{ route('admin.feeders.status', $feeder->id) }}">
                            @csrf <input type="hidden" name="status" value="Active">
                            <button type="submit" data-bs-dismiss="modal"
                                    style="background:rgba(0,230,118,0.12);border:1px solid rgba(0,230,118,0.6);color:#00e676;padding:0.35rem 1rem;border-radius:4px;font-family:'Rajdhani',sans-serif;text-transform:uppercase;font-weight:700;cursor:pointer;font-size:0.82rem;"
                                    onmouseover="this.style.background='#00e676';this.style.color='#000';"
                                    onmouseout="this.style.background='rgba(0,230,118,0.12)';this.style.color='#00e676';">Restore</button>
                        </form>
                    </div>
                </div>
                @endforeach
                @if($feeders->where('status','!=','Active')->isEmpty())
                    <p style="color:#00e676;text-align:center;font-family:'Rajdhani',sans-serif;text-transform:uppercase;">All feeders are currently Active.</p>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Modal: Provide Bill (Upload PDF / PNG / JPG File) --}}
<div class="modal fade" id="billUploadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content cyber-card" style="background:#0e1525;border:1px solid var(--border-neon-teal);">
            <div class="cyber-card-header d-flex justify-content-between align-items-center p-3" style="border-bottom:1px solid rgba(0,245,255,0.2);">
                <h6 class="tech-font text-white m-0 fw-bold">
                    <i class="bi bi-file-earmark-arrow-up me-2 text-success"></i>Upload Billing Document (<span id="bill-feeder-code">--</span>)
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="bill-upload-form" method="POST" action="" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-3">
                    <div class="mb-3">
                        <label class="form-label text-secondary tech-font" style="font-size:0.78rem;">Target Consumer Node</label>
                        <select class="form-select bg-dark text-white border-secondary tech-font" name="user_id" id="bill-user-select" required>
                            <option value="all">🌐 All Consumers on this Feeder</option>
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label text-secondary tech-font" style="font-size:0.78rem;">Billing Month</label>
                            <input type="date" class="form-control bg-dark text-white border-secondary tech-font" name="month" value="{{ date('Y-m-01') }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-secondary tech-font" style="font-size:0.78rem;">Units Consumed (kWh)</label>
                            <input type="number" step="0.01" class="form-control bg-dark text-white border-secondary tech-font" name="units_consumed" placeholder="e.g. 250" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary tech-font" style="font-size:0.78rem;">Due Date</label>
                        <input type="date" class="form-control bg-dark text-white border-secondary tech-font" name="due_date" value="{{ date('Y-m-15', strtotime('+1 month')) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary tech-font" style="font-size:0.78rem;">Bill Document File (.pdf, .png, .jpg)</label>
                        <input type="file" class="form-control bg-dark text-white border-secondary tech-font" name="bill_file" accept=".pdf,.png,.jpg,.jpeg" required>
                        <div class="form-text text-secondary" style="font-size:0.7rem;">Supported formats: PDF document or PNG/JPG image scan (max 10MB).</div>
                    </div>
                </div>
                <div class="modal-footer p-3" style="border-top:1px solid rgba(0,245,255,0.2);">
                    <button type="button" class="btn btn-outline-secondary tech-font btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info text-dark fw-bold tech-font btn-sm">
                        <i class="bi bi-cloud-upload me-1"></i>Publish & Attach Bill
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<link href='https://api.mapbox.com/mapbox-gl-js/v3.3.0/mapbox-gl.css' rel='stylesheet'>
<style>
    @keyframes spin { to { transform: rotate(360deg); } }
    .spin-icon { display: inline-block; animation: spin 1s linear infinite; }
    select option { background: #0b0f19; color: #c9d1d9; }
    input[type="datetime-local"]::-webkit-calendar-picker-indicator {
        filter: invert(0.8) sepia(1) saturate(3) hue-rotate(155deg) brightness(1.2);
        cursor: pointer;
    }
    .modal-content { border-radius: 8px !important; overflow: hidden; }
    /* Mapbox popup dark theme */
    .mapboxgl-popup-content {
        background: #0e1525 !important;
        border: 1px solid rgba(0,245,255,0.3) !important;
        border-radius: 6px !important;
        padding: 0 !important;
    }
    .mapboxgl-popup-tip { display: none; }
</style>
<script src='https://api.mapbox.com/mapbox-gl-js/v3.3.0/mapbox-gl.js'></script>
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.3/dist/echo.iife.js"></script>
<script>
    // ── Client-side feeder card search ─────────────────────────────────────
    function filterFeeders(query) {
        const cards = document.querySelectorAll('.feeder-card-item');
        const q = query.toLowerCase();
        cards.forEach(card => {
            const text = card.getAttribute('data-feeder-search') || '';
            card.style.display = text.includes(q) ? '' : 'none';
        });
    }

    // ── Live Telemetry Modal ──────────────────────────────────────────
    function openTelemetry(feederId, code) {
        document.getElementById('tm-code').textContent = code;
        document.getElementById('telemetry-body').innerHTML =
            '<div class="text-center text-secondary py-3"><i class="bi bi-arrow-repeat fs-2 spin-icon"></i><br>Loading telemetry...</div>';

        const modal = new bootstrap.Modal(document.getElementById('telemetryModal'));
        modal.show();

        fetch(`/admin/feeders/${feederId}/telemetry`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            const loadColor = data.load_percent >= 90 ? 'danger' : data.load_percent >= 70 ? 'warning' : 'info';
            document.getElementById('telemetry-body').innerHTML = `
                <div class="row g-3">
                    <div class="col-6">
                        <div class="p-3 rounded" style="background:rgba(0,0,0,0.3);border:1px solid var(--border-neon-teal);">
                            <div class="tech-font text-secondary" style="font-size:0.7rem;">MAX CAPACITY</div>
                            <div class="tech-font fw-bold text-white fs-4">${data.max_capacity_mw} MW</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded" style="background:rgba(0,0,0,0.3);border:1px solid var(--border-neon-teal);">
                            <div class="tech-font text-secondary" style="font-size:0.7rem;">LIVE DEMAND</div>
                            <div class="tech-font fw-bold text-info fs-4">${data.current_demand_mw} MW</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded" style="background:rgba(0,0,0,0.3);border:1px solid var(--border-neon-teal);">
                            <div class="tech-font text-secondary" style="font-size:0.7rem;">CONSUMERS</div>
                            <div class="tech-font fw-bold text-white fs-4">${data.consumers}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded" style="background:rgba(0,0,0,0.3);border:1px solid var(--border-neon-teal);">
                            <div class="tech-font text-secondary" style="font-size:0.7rem;">STATUS</div>
                            <div class="tech-font fw-bold text-${loadColor} fs-5">${data.status}</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="tech-font text-secondary mb-1" style="font-size:0.72rem;">LOAD: ${data.load_percent}%</div>
                        <div class="progress" style="height:8px;background:rgba(255,255,255,0.05);">
                            <div class="progress-bar bg-${loadColor}" style="width:${data.load_percent}%"></div>
                        </div>
                    </div>
                    <div class="col-12 text-secondary tech-font" style="font-size:0.72rem;">
                        <i class="bi bi-geo-alt me-1"></i>${data.location}
                    </div>
                </div>
            `;
        })
        .catch(() => {
            document.getElementById('telemetry-body').innerHTML =
                '<div class="text-danger text-center py-3">Failed to load telemetry data.</div>';
        });
    }

    // ── Outage Schedule Modal ────────────────────────────────────────
    function openScheduleModal(feederId, code) {
        document.getElementById('sched-code').textContent = code;
        document.getElementById('scheduleForm').action = `/admin/feeders/${feederId}/schedule-outage`;
        new bootstrap.Modal(document.getElementById('scheduleModal')).show();
    }

    // ── Provide Bill Upload Modal ──────────────────────────────────────
    function openBillModal(feederId, code, users) {
        document.getElementById('bill-feeder-code').textContent = code;
        document.getElementById('bill-upload-form').action = `/admin/feeders/${feederId}/upload-bill`;

        const userSelect = document.getElementById('bill-user-select');
        userSelect.innerHTML = '<option value="all">🌐 All Consumers on this Feeder</option>';

        if (Array.isArray(users)) {
            users.forEach(u => {
                userSelect.innerHTML += `<option value="${u.id}">👤 ${u.name} (${u.meter_number || 'Meter N/A'})</option>`;
            });
        }

        new bootstrap.Modal(document.getElementById('billUploadModal')).show();
    }

    // ── Mapbox Grid Map ────────────────────────────────────────────
    mapboxgl.accessToken = @json($mapboxToken);

    const FEEDERS        = @json($feeders);
    const RISK_BY_FEEDER = @json($riskByFeeder);
    const statusColors   = { Active: '#00e676', Outage: '#ff2a54', Maintenance: '#ff9f1c' };
    const feederMarkers  = {}; // keyed by feeder id for live updates

    function getFeederRiskScore(feederId) {
        const r = RISK_BY_FEEDER[feederId];
        return r ? Number(r.score || 0) : 0;
    }

    function syncStressPointsMap() {
        if (!map.getSource('stress-points')) return;
        const features = FEEDERS
            .filter(f => f.latitude && f.longitude)
            .map(f => ({
                type: 'Feature',
                properties: {
                    feeder_id: f.id,
                    status: f.status,
                    status_color: statusColors[f.status] || '#00e676',
                    risk_score: getFeederRiskScore(f.id),
                },
                geometry: {
                    type: 'Point',
                    coordinates: [parseFloat(f.longitude), parseFloat(f.latitude)],
                }
            }));
        map.getSource('stress-points').setData({ type: 'FeatureCollection', features: features });
    }

    const map = new mapboxgl.Map({
        container: 'grid-map',
        style:     'mapbox://styles/mapbox/dark-v11',
        center:    [89.5403, 22.8456], // Khulna region center
        zoom:      9
    });
    map.addControl(new mapboxgl.NavigationControl(), 'top-right');

    map.on('load', function () {
        // ─ Plot a marker for every feeder that has coordinates ──────────
        FEEDERS.forEach(function (f) {
            if (!f.latitude || !f.longitude) return;
            const color = statusColors[f.status] || '#aaaaaa';
            const risk = getFeederRiskScore(f.id);

            const el = document.createElement('div');
            el.style.cssText = [
                'width:16px', 'height:16px', 'border-radius:50%',
                'background:' + color,
                'border:2px solid rgba(255,255,255,0.25)',
                'box-shadow:0 0 12px ' + color,
                'cursor:pointer',
                'transition:box-shadow 0.3s'
            ].join(';');

            const popup = new mapboxgl.Popup({ offset: 22, closeButton: false })
                .setHTML([
                    '<div style="font-family:\"Rajdhani\",sans-serif;background:#0e1525;',
                    'color:#c9d1d9;padding:12px 14px;border-radius:6px;min-width:190px;">',
                    '<div style="color:#00f5ff;font-weight:700;font-size:1rem;margin-bottom:4px;">' + f.substation_code + '</div>',
                    '<div style="font-size:0.88rem;margin-bottom:4px;">' + f.feeder_name + '</div>',
                    '<div style="font-size:0.78rem;color:#768390;">Demand: <span style="color:#c9d1d9;">' + f.current_demand_mw + ' MW</span></div>',
                    '<div style="font-size:0.78rem;margin-top:2px;">Status: <span style="color:' + color + ';font-weight:700;">' + f.status + '</span></div>',
                    '<div style="font-size:0.78rem;margin-top:2px;color:#ff9f1c;">Risk Score: <strong style="color:#fff;">' + risk + '</strong> / 100</div>',
                    '</div>'
                ].join(''));

            const marker = new mapboxgl.Marker({ element: el })
                .setLngLat([parseFloat(f.longitude), parseFloat(f.latitude)])
                .setPopup(popup)
                .addTo(map);

            feederMarkers[f.id] = { el, marker };
        });

        // ─ Complaint heatmap source + layer (hidden until toggled) ───
        fetch('/admin/map/complaints-geojson')
            .then(r => r.json())
            .then(function (feeders) {
                const features = feeders
                    .filter(f => f.latitude && f.longitude)
                    .map(f => ({
                        type: 'Feature',
                        properties: { count: f.tickets_count || 0 },
                        geometry: { type: 'Point', coordinates: [parseFloat(f.longitude), parseFloat(f.latitude)] }
                    }));

                map.addSource('complaints', {
                    type: 'geojson',
                    data: { type: 'FeatureCollection', features }
                });

                map.addLayer({
                    id:     'complaints-heat',
                    type:   'heatmap',
                    source: 'complaints',
                    layout: { visibility: 'none' },
                    paint: {
                        'heatmap-weight':   ['interpolate', ['linear'], ['get', 'count'], 0, 0, 10, 1],
                        'heatmap-color': [
                            'interpolate', ['linear'], ['heatmap-density'],
                            0,   'rgba(0,245,255,0)',
                            0.4, 'rgba(0,245,255,0.4)',
                            0.7, 'rgba(255,159,28,0.7)',
                            1,   'rgba(255,42,84,0.9)'
                        ],
                        'heatmap-radius':   30,
                        'heatmap-opacity':  0.8,
                    }
                });
            });

        // ─ Stress pulse source + layers ───────────────────────────
        // Main core circle depends on STATUS (Active green, Maintenance yellow, Cutoff red)
        // Sub-circle outer ring depends on RISK SCORE (Medium bigger, High large)
        const stressFeatures = FEEDERS
            .filter(f => f.latitude && f.longitude)
            .map(f => ({
                type: 'Feature',
                properties: {
                    feeder_id: f.id,
                    status: f.status,
                    status_color: statusColors[f.status] || '#00e676',
                    risk_score: getFeederRiskScore(f.id),
                },
                geometry: {
                    type: 'Point',
                    coordinates: [parseFloat(f.longitude), parseFloat(f.latitude)],
                }
            }));

        map.addSource('stress-points', {
            type: 'geojson',
            data: { type: 'FeatureCollection', features: stressFeatures }
        });

        // Main circle (depends on status: Active green, Maintenance yellow, Cutoff red)
        map.addLayer({
            id: 'stress-core',
            type: 'circle',
            source: 'stress-points',
            paint: {
                'circle-color': ['get', 'status_color'],
                'circle-opacity': 0.65,
                'circle-radius': 10
            }
        });

        // Sub-circle around main circle (Feature 3: depends on Risk Score — Medium bigger, High large)
        map.addLayer({
            id: 'stress-pulse',
            type: 'circle',
            source: 'stress-points',
            paint: {
                'circle-color': [
                    'interpolate', ['linear'], ['get', 'risk_score'],
                    0,   '#00e676', // Low (<30)
                    30,  '#ff9f1c', // Medium (30-49)
                    50,  '#ff2a54'  // High (>=50)
                ],
                'circle-opacity': [
                    'interpolate', ['linear'], ['get', 'risk_score'],
                    0,  0.12,
                    30, 0.35,
                    50, 0.60
                ],
                'circle-radius': [
                    'interpolate', ['linear'], ['get', 'risk_score'],
                    0,  14,
                    30, 34, // Medium risk = medium bigger sub-circle
                    50, 68  // High risk = large sub-circle
                ],
                'circle-blur': 0.45
            }
        });

        // Animate sub-circle pulse radius based on risk score
        let pulseTick = 0;
        const animateStressPulse = () => {
            if (!map.getLayer('stress-pulse')) return;
            pulseTick += 0.07;
            const wave = 8 + Math.sin(pulseTick) * 7;

            map.setPaintProperty('stress-pulse', 'circle-radius', [
                'interpolate', ['linear'], ['get', 'risk_score'],
                0,  14 + (wave * 0.3),
                30, 32 + (wave * 0.8), // Medium risk = medium bigger pulsing ring
                50, 62 + (wave * 2.8)  // High risk = large pulsing ring!
            ]);

            requestAnimationFrame(animateStressPulse);
        };

        requestAnimationFrame(animateStressPulse);
    });

    // ── Heatmap toggle button ──────────────────────────────────────
    document.getElementById('toggle-heatmap-btn').addEventListener('click', function () {
        if (!map.getLayer('complaints-heat')) return;
        const isHidden = map.getLayoutProperty('complaints-heat', 'visibility') === 'none';
        map.setLayoutProperty('complaints-heat', 'visibility', isHidden ? 'visible' : 'none');
        this.innerHTML = isHidden
            ? '<i class="bi bi-eye-slash me-1"></i>Hide Complaints'
            : '<i class="bi bi-fire me-1"></i>Show Complaints';
    });

    // ── Laravel Echo: live marker color update on FeederStatusChanged ─────
    window.AdminEcho = new Echo({
        broadcaster: 'pusher',
        key:         @json($pusherKey),
        cluster:     @json($pusherCluster),
        forceTLS:    true,
    });

    FEEDERS.forEach(function (f) {
        AdminEcho.channel('feeder.' + f.id)
            .listen('.FeederStatusChanged', function (data) {
                const entry = feederMarkers[data.feeder_id];
                if (entry) {
                    const newColor = statusColors[data.status] || '#aaaaaa';
                    entry.el.style.background = newColor;
                    entry.el.style.boxShadow  = '0 0 12px ' + newColor;
                }

                // Update feeder record & increase risk score dynamically on cutoff
                const item = FEEDERS.find(x => Number(x.id) === Number(data.feeder_id));
                if (item) {
                    item.status = data.status;
                }

                if (!RISK_BY_FEEDER[data.feeder_id]) {
                    RISK_BY_FEEDER[data.feeder_id] = { score: 0, level: 'Low' };
                }
                const currentScore = Number(RISK_BY_FEEDER[data.feeder_id].score || 0);
                if (data.status === 'Outage' || data.status === 'Maintenance') {
                    const newScore = Math.min(100, currentScore + 10);
                    RISK_BY_FEEDER[data.feeder_id].score = newScore;
                    RISK_BY_FEEDER[data.feeder_id].level = newScore >= 50 ? 'High' : (newScore >= 30 ? 'Medium' : 'Low');
                }

                syncStressPointsMap();
            });
    });
</script>
@endsection
