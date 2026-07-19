@extends('layouts.consumer')

@section('title', 'ElectroGrid — Consumer Portal')

@section('content')
<div class="container-fluid p-0">

    {{-- Notifications --}}
    @if($notifications->count() > 0)
        @foreach($notifications as $notif)
        <div class="notification-item mb-2 d-flex align-items-start gap-2">
            <i class="bi bi-bell-fill text-info mt-1" style="font-size:0.85rem;"></i>
            <span style="font-size:0.85rem;">{{ $notif->message }}</span>
            <small class="ms-auto text-secondary" style="white-space:nowrap;font-size:0.72rem;">{{ $notif->created_at->diffForHumans() }}</small>
        </div>
        @endforeach
    @endif

    {{-- Power status banner (top full-width) --}}
    <div class="row mb-4">
        <div class="col-12">
            @if($feeder)
                {{-- Power Outage Banner --}}
                <div id="power-banner-outage" class="cyber-card card-danger p-4 align-items-center gap-4"
                     style="display: {{ ($todayOutage || $feeder->status === 'Outage') ? 'flex' : 'none' }};">
                    <div style="width:16px;height:16px;border-radius:50%;background:var(--warning-crimson);box-shadow:0 0 12px rgba(255,42,84,0.7);flex-shrink:0;"></div>
                    <div class="flex-grow-1">
                        <strong class="tech-font text-danger fs-6">⚡ POWER OUTAGE IN EFFECT</strong>
                        <div class="d-flex gap-4 mt-2 flex-wrap" style="font-size:0.82rem;">
                            <div>
                                <div class="text-secondary" style="font-size:0.7rem;letter-spacing:0.5px;">START TIME</div>
                                <div class="text-white fw-bold">{{ $todayOutage ? \Carbon\Carbon::parse($todayOutage->start_time)->format('h:i A') : 'NOW' }}</div>
                            </div>
                            <div>
                                <div class="text-secondary" style="font-size:0.7rem;letter-spacing:0.5px;">EXPECTED RESTORE</div>
                                <div class="text-white fw-bold">{{ $todayOutage ? \Carbon\Carbon::parse($todayOutage->end_time)->format('h:i A') : 'TBD' }}</div>
                            </div>
                            @if($todayOutage && $todayOutage->reason)
                            <div>
                                <div class="text-secondary" style="font-size:0.7rem;letter-spacing:0.5px;">REASON</div>
                                <div class="text-white">{{ $todayOutage->reason }}</div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Normal Status Banner --}}
                <div id="power-banner-normal" class="cyber-card p-4 align-items-center gap-3"
                     style="display: {{ (!$todayOutage && $feeder->status !== 'Outage') ? 'flex' : 'none' }}; border-color:rgba(0,230,118,0.3);background:rgba(0,230,118,0.04);">
                    <div style="width:16px;height:16px;border-radius:50%;background:var(--grid-green);box-shadow:0 0 12px rgba(0,230,118,0.6);flex-shrink:0;"></div>
                    <div>
                        <strong class="tech-font text-success">✅ NO OUTAGE SCHEDULED TODAY</strong>
                        <p class="text-secondary m-0 mt-1" style="font-size:0.82rem;">
                            Your feeder ({{ $feeder->substation_code }}) status is currently
                            <strong id="banner-feeder-status" class="{{ $feeder->status === 'Active' ? 'text-success' : 'text-warning' }}">{{ $feeder->status }}</strong>.
                        </p>
                    </div>
                </div>
            @else
            <div class="cyber-card p-3 text-center text-secondary">
                <i class="bi bi-exclamation-circle me-2"></i>No feeder mapped to your account. Contact support.
            </div>
            @endif
        </div>
    </div>

    {{-- Main grid --}}
    <div class="row g-4">

        {{-- Left column --}}
        <div class="col-12 col-lg-8">

            {{-- Consumer identity card --}}
            <div class="cyber-card p-4 mb-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <p class="tech-font text-secondary m-0" style="font-size:0.7rem;letter-spacing:1px;">AUTHENTICATED CONSUMER NODE</p>
                        <h4 class="text-white fw-bold mb-1 mt-1">{{ $user->name }}</h4>
                        <div class="d-flex gap-3 flex-wrap" style="font-size:0.82rem;">
                            <span class="text-secondary"><i class="bi bi-person-fill me-1 text-info"></i>{{ $user->username }}</span>
                            <span class="text-secondary"><i class="bi bi-envelope-fill me-1 text-info"></i>{{ $user->email }}</span>
                            <span class="text-secondary"><i class="bi bi-upc-scan me-1 text-info"></i>{{ $user->meter_number }}</span>
                        </div>
                    </div>
                    <span class="tech-font badge px-3 py-2" style="background:rgba(0,245,255,0.06);border:1px solid var(--border-neon-teal);color:var(--neon-teal);font-size:0.72rem;">
                        {{ strtoupper($user->consumer_class) }}
                    </span>
                </div>
            </div>

            {{-- Upcoming outages --}}
            @if($upcomingOutages->count() > 0)
            <div class="cyber-card mb-4">
                <div class="cyber-card-header">
                    <h6 class="tech-font text-white fw-bold m-0">
                        <i class="bi bi-calendar-week-fill me-2 text-warning"></i>Upcoming Outages (Next 7 Days)
                    </h6>
                </div>
                <div class="p-3">
                    @foreach($upcomingOutages as $sched)
                    <div class="d-flex justify-content-between align-items-start p-3 mb-2 rounded"
                         style="background:rgba(255,159,28,0.04);border:1px solid rgba(255,159,28,0.15);">
                        <div>
                            <div class="text-white fw-semibold" style="font-size:0.9rem;">
                                {{ \Carbon\Carbon::parse($sched->start_time)->format('D, d M Y') }}
                            </div>
                            <div class="text-secondary" style="font-size:0.8rem;">
                                {{ \Carbon\Carbon::parse($sched->start_time)->format('h:i A') }}
                                — {{ \Carbon\Carbon::parse($sched->end_time)->format('h:i A') }}
                                ({{ \Carbon\Carbon::parse($sched->start_time)->diffInHours($sched->end_time) }}h)
                            </div>
                            @if($sched->reason)
                                <div class="text-secondary mt-1" style="font-size:0.78rem;">{{ $sched->reason }}</div>
                            @endif
                        </div>
                        <span class="badge tech-font" style="background:rgba(255,159,28,0.1);border:1px solid rgba(255,159,28,0.3);color:var(--electric-amber);font-size:0.65rem;">PLANNED</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Blackout risk prediction --}}
            {{-- Blackout risk prediction --}}
            @if($riskInsight)
            @php
                $riskColor = $riskInsight['level'] === 'High'
                    ? 'var(--warning-crimson)'
                    : ($riskInsight['level'] === 'Medium' ? 'var(--electric-amber)' : 'var(--grid-green)');
            @endphp
            <div class="cyber-card mb-4" style="border-color:rgba(0,245,255,0.28);">
                <div class="cyber-card-header">
                    <h6 class="tech-font text-white fw-bold m-0">
                        <i class="bi bi-cpu-fill me-2 text-info"></i>Blackout Prediction Engine
                    </h6>
                </div>
                <div class="p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-secondary" style="font-size:0.78rem;">Risk Level (<30 Low | 30-49 Med | >=50 High)</span>
                        <span id="risk-level-pill" class="badge tech-font" style="background:rgba(0,0,0,0.28);border:1px solid {{ $riskColor }};color:{{ $riskColor }};font-size:0.66rem;">{{ strtoupper($riskInsight['level']) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-secondary" style="font-size:0.78rem;">Live Risk Score (0-100)</span>
                        <span id="risk-score-value" class="tech-font fw-bold" style="color:{{ $riskColor }};">{{ $riskInsight['score'] }}</span>
                    </div>
                    <div class="progress mb-3" style="height:7px;background:rgba(255,255,255,0.05);">
                        <div id="risk-score-bar" class="progress-bar" style="width:{{ $riskInsight['score'] }}%;background:{{ $riskColor }};"></div>
                    </div>

                    <div class="row g-2" style="font-size:0.74rem;">
                        <div class="col-3">
                            <div class="p-2 rounded" style="background:rgba(0,0,0,0.22);border:1px solid var(--border-neon-teal);">
                                <div class="text-secondary">Cutoff Count</div>
                                <div class="text-white fw-semibold" id="risk-cutoff-count">{{ $riskInsight['cutoff_count'] }}</div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="p-2 rounded" style="background:rgba(0,0,0,0.22);border:1px solid var(--border-neon-teal);">
                                <div class="text-secondary">Load Ratio</div>
                                <div class="text-white fw-semibold" id="risk-load-ratio">{{ $riskInsight['load_ratio'] }}%</div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="p-2 rounded" style="background:rgba(0,0,0,0.22);border:1px solid var(--border-neon-teal);">
                                <div class="text-secondary">Confidence</div>
                                <div class="text-white fw-semibold" id="risk-confidence">{{ $riskInsight['confidence'] }}%</div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="p-2 rounded" style="background:rgba(0,0,0,0.22);border:1px solid var(--border-neon-teal);">
                                <div class="text-secondary">Open Tickets</div>
                                <div class="text-white fw-semibold" id="risk-open-tickets">{{ $riskInsight['open_tickets'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Outage history --}}
            @if($outageHistory->count() > 0)
            <div class="cyber-card mb-4">
                <div class="cyber-card-header">
                    <h6 class="tech-font text-white fw-bold m-0">
                        <i class="bi bi-clock-history me-2 text-secondary"></i>Outage History (Last 30 Days)
                    </h6>
                </div>
                <div class="p-3">
                    @foreach($outageHistory as $hist)
                    <div class="d-flex justify-content-between align-items-center p-2 mb-1 rounded"
                         style="background:rgba(0,0,0,0.2);border:1px solid var(--border-neon-teal);">
                        <div class="text-secondary" style="font-size:0.82rem;">
                            {{ \Carbon\Carbon::parse($hist->start_time)->format('d M') }} —
                            {{ \Carbon\Carbon::parse($hist->start_time)->format('h:i A') }} to
                            {{ \Carbon\Carbon::parse($hist->end_time)->format('h:i A') }}
                        </div>
                        <span class="badge tech-font" style="background:rgba(0,230,118,0.08);border:1px solid rgba(0,230,118,0.2);color:var(--grid-green);font-size:0.65rem;">COMPLETED</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        {{-- Right column --}}
        <div class="col-12 col-lg-4">

            {{-- Feeder info --}}
            @if($feeder)
            <div class="cyber-card p-4 mb-4">
                <h6 class="tech-font text-info fw-bold mb-3">
                    <i class="bi bi-cpu-fill me-2"></i>Linked Feeder Node
                </h6>
                <code class="text-info fw-bold d-block mb-1" style="font-size:1rem;">{{ $feeder->substation_code }}</code>
                <div class="text-white fw-semibold mb-2">{{ $feeder->feeder_name }}</div>
                <div class="text-secondary mb-3" style="font-size:0.8rem;">
                    {{ $feeder->division }} / {{ $feeder->district }} / {{ $feeder->upazila }}
                </div>
                <div class="d-flex justify-content-between mb-2" style="font-size:0.78rem;">
                    <span class="tech-font text-secondary">Max Capacity</span>
                    <span class="text-white">{{ $feeder->max_capacity_mw }} MW</span>
                </div>
                <div class="d-flex justify-content-between" style="font-size:0.78rem;">
                    <span class="tech-font text-secondary">Status</span>
                    <span id="feeder-status-card-badge" class="fw-bold {{ $feeder->status === 'Active' ? 'text-success' : ($feeder->status === 'Outage' ? 'text-danger' : 'text-warning') }}">
                        {{ $feeder->status }}
                    </span>
                </div>
            </div>
            @endif

            {{-- Unpaid bill --}}
            @if($latestBill)
            <div class="cyber-card p-4 mb-4" style="border-color:rgba(255,159,28,0.3);">
                <h6 class="tech-font text-warning fw-bold mb-3">
                    <i class="bi bi-receipt me-2"></i>Unpaid Bill Alert
                </h6>
                <div class="d-flex justify-content-between mb-2" style="font-size:0.85rem;">
                    <span class="text-secondary">Month</span>
                    <span class="text-white">{{ \Carbon\Carbon::parse($latestBill->month)->format('F Y') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2" style="font-size:0.85rem;">
                    <span class="text-secondary">Units</span>
                    <span class="text-white">{{ $latestBill->units_consumed }} kWh</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="tech-font text-secondary" style="font-size:0.72rem;">AMOUNT DUE</span>
                    <span class="text-warning fw-bold fs-5 tech-font">৳{{ number_format($latestBill->amount, 2) }}</span>
                </div>
                <div class="text-secondary" style="font-size:0.75rem;">Due: {{ \Carbon\Carbon::parse($latestBill->due_date)->format('d M Y') }}</div>
                <a href="{{ route('consumer.bills') }}" class="btn btn-cyber-primary tech-font w-100 mt-3" style="font-size:0.85rem;">
                    <i class="bi bi-eye me-1"></i>View All Bills
                </a>
            </div>
            @endif

            {{-- Quick actions --}}
            <div class="cyber-card p-4">
                <h6 class="tech-font text-white fw-bold mb-3">
                    <i class="bi bi-grid-3x3-gap me-2 text-info"></i>Quick Actions
                </h6>
                <div class="d-grid gap-2">
                    <a href="{{ route('consumer.tickets.create') }}" class="btn btn-cyber-primary tech-font">
                        <i class="bi bi-plus-circle me-2"></i>Submit Complaint
                    </a>
                    <a href="{{ route('consumer.bills') }}" class="btn btn-cyber-outline tech-font">
                        <i class="bi bi-receipt me-2"></i>Bill History
                    </a>
                    <a href="{{ route('consumer.tickets') }}" class="btn btn-cyber-outline tech-font">
                        <i class="bi bi-ticket me-2"></i>My Tickets
                        @if($openTickets > 0)
                            <span class="badge bg-warning text-dark ms-1">{{ $openTickets }}</span>
                        @endif
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<meta name="csrf-token" content="{{ csrf_token() }}">
{{-- Pusher JS + Laravel Echo via CDN for real-time updates --}}
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.3/dist/echo.iife.js"></script>
<script>
    const FEEDER_ID = @json($user->feeder_id);
    const USER_ID   = @json($user->id);

    window.Echo = new Echo({
        broadcaster:  'pusher',
        key:          @json($pusherKey),
        cluster:      @json($pusherCluster),
        forceTLS:     true,
        authEndpoint: '/broadcasting/auth',
        auth: {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            }
        }
    });

    function updateFeederStatusUI(status, isOutage) {
        const outageEl = document.getElementById('power-banner-outage');
        const normalEl = document.getElementById('power-banner-normal');
        if (outageEl) outageEl.style.display = isOutage ? 'flex' : 'none';
        if (normalEl) normalEl.style.display = isOutage ? 'none' : 'flex';

        const bannerStatus = document.getElementById('banner-feeder-status');
        if (bannerStatus) {
            bannerStatus.textContent = status;
            bannerStatus.className = status === 'Active' ? 'text-success' : 'text-warning';
        }

        const cardBadge = document.getElementById('feeder-status-card-badge');
        if (cardBadge) {
            cardBadge.textContent = status;
            cardBadge.className = 'fw-bold ' + (status === 'Active' ? 'text-success' : (status === 'Outage' ? 'text-danger' : 'text-warning'));
        }
    }

    function applyRiskUI(risk) {
        if (!risk) return;

        const score = Number(risk.score || 0);
        const level = String(risk.level || 'Low');
        const color = level === 'High'
            ? 'var(--warning-crimson)'
            : (level === 'Medium' ? 'var(--electric-amber)' : 'var(--grid-green)');

        const scoreValue = document.getElementById('risk-score-value');
        const scoreBar   = document.getElementById('risk-score-bar');
        const levelPill  = document.getElementById('risk-level-pill');

        if (scoreValue) {
            scoreValue.textContent = score;
            scoreValue.style.color = color;
        }

        if (scoreBar) {
            scoreBar.style.width = score + '%';
            scoreBar.style.background = color;
        }

        if (levelPill) {
            levelPill.textContent = level.toUpperCase();
            levelPill.style.borderColor = color;
            levelPill.style.color = color;
        }

        const cutoffCountEl = document.getElementById('risk-cutoff-count');
        if (cutoffCountEl && risk.cutoff_count !== undefined) {
            cutoffCountEl.textContent = risk.cutoff_count;
        }

        const loadRatioEl = document.getElementById('risk-load-ratio');
        if (loadRatioEl && risk.load_ratio !== undefined) {
            loadRatioEl.textContent = risk.load_ratio + '%';
        }

        const confEl = document.getElementById('risk-confidence');
        if (confEl && risk.confidence !== undefined) {
            confEl.textContent = risk.confidence + '%';
        }

        const openTicketsEl = document.getElementById('risk-open-tickets');
        if (openTicketsEl && risk.open_tickets !== undefined) {
            openTicketsEl.textContent = risk.open_tickets;
        }
    }

    if (FEEDER_ID) {
        Echo.channel('feeder.' + FEEDER_ID)
            .listen('.FeederStatusChanged', function (data) {
                updateFeederStatusUI(data.status, data.status === 'Outage');
            });

        // Fast background sync (updates every 2.5s automatically even if Pusher/DNS is offline)
        setInterval(function () {
            fetch('{{ route('consumer.feeder-status') }}', {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                if (data && data.status) {
                    updateFeederStatusUI(data.status, data.has_outage);
                    applyRiskUI(data.risk);
                }
            })
            .catch(() => {});
        }, 2500);
    }

    Echo.private('user.' + USER_ID + '.complaints')
        .listen('.ComplaintStatusChanged', function (data) {
            const card = document.querySelector('[data-ticket-id="' + data.complaint_id + '"]');
            if (!card) return;
            const badge = card.querySelector('.ticket-status-badge');
            if (!badge) return;
            const labels = { open: 'OPEN', in_progress: 'IN PROGRESS', resolved: 'RESOLVED' };
            const colors = {
                open:        'var(--warning-crimson)',
                in_progress: 'var(--electric-amber)',
                resolved:    'var(--grid-green)',
            };
            badge.textContent = labels[data.status] || data.status.toUpperCase();
            badge.style.color = colors[data.status] || '';
        });
</script>
@endsection
