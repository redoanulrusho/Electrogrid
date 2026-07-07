@extends('layouts.app')

@section('title', 'ElectroGrid Console - Central Grid Telemetry')

@section('content')
<div class="container-fluid p-0">

    <!-- HUD Neon Alert Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-warning border-0 cyber-card card-warning d-flex align-items-center justify-content-between p-3" role="alert" style="background-color: var(--electric-amber-glow); border: 1px solid rgba(255, 159, 28, 0.4) !important;">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-2 rounded-circle d-flex align-items-center justify-content-center text-warning" style="width: 40px; height: 40px; background-color: rgba(255,159,28,0.15); border: 1px solid rgba(255,159,28,0.3);">
                        <i class="bi bi-radioactive fs-5"></i>
                    </div>
                    <div>
                        <strong class="text-white d-block tech-font" style="font-size: 1.05rem; letter-spacing: 0.5px;">GRID OVERLOAD COMPENSATOR WARNING</strong>
                        <span class="text-secondary" style="font-size: 0.85rem;">Substation 4 load levels are peaking near capacity boundaries. Force shedding automated sequence active on Residential sectors.</span>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-cyber-primary tech-font px-3" onclick="alert('Accessing shedding protocols...')">Override Control</button>
                    <button class="btn btn-sm btn-outline-slate text-white border-0" data-bs-dismiss="alert" aria-label="Close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Redesigned Telemetry Gauge Cards -->
    <div class="row g-4 mb-4">
        <!-- Card 1: Feeders -->
        <div class="col-12 col-md-6 col-xl-4">
            <div class="cyber-card p-4 h-100 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="tech-font text-secondary fw-semibold" style="font-size: 0.78rem; letter-spacing: 1.2px;">GRID DISTRIBUTION LINKS</span>
                        <h2 class="tech-font fw-bold mt-2 mb-1 text-white" style="font-size: 2.5rem; text-shadow: 0 0 10px var(--neon-teal-glow);">14 <span class="fs-6 fw-normal text-dim">Feeders</span></h2>
                    </div>
                    <div class="p-3 rounded" style="background-color: rgba(0, 245, 255, 0.08); border: 1px solid var(--border-neon-teal-active); color: var(--neon-teal);">
                        <i class="bi bi-cpu-fill fs-4"></i>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-top" style="border-top-color: var(--border-neon-teal) !important;">
                    <div class="progress mb-2" style="height: 4px; background-color: rgba(255,255,255,0.05);">
                        <div class="progress-bar bg-info" role="progressbar" style="width: 71.4%;" aria-valuenow="71.4" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex justify-content-between tech-font text-secondary" style="font-size: 0.78rem;">
                        <span><i class="bi bi-circle-fill text-success me-1 fs-9"></i> 10 Active Feeder Lines</span>
                        <span>4 Downtime Sectors</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: MW Deficit -->
        <div class="col-12 col-md-6 col-xl-4">
            <div class="cyber-card card-warning p-4 h-100 d-flex flex-column justify-content-between" style="border-color: rgba(255, 159, 28, 0.15);">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="tech-font text-secondary fw-semibold" style="font-size: 0.78rem; letter-spacing: 1.2px;">ACTIVE LOAD DEFICIT</span>
                        <h2 class="tech-font fw-bold mt-2 mb-1 text-warning" style="font-size: 2.5rem; text-shadow: 0 0 10px rgba(255, 159, 28, 0.3);">45.8 <span class="fs-6 fw-normal text-dim">MW</span></h2>
                    </div>
                    <div class="p-3 rounded" style="background-color: rgba(255, 159, 28, 0.08); border: 1px solid rgba(255, 159, 28, 0.3); color: var(--electric-amber);">
                        <i class="bi bi-lightning-charge-fill fs-4"></i>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-top" style="border-top-color: rgba(255, 159, 28, 0.15) !important;">
                    <div class="progress mb-2" style="height: 4px; background-color: rgba(255,255,255,0.05);">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: 76.3%;" aria-valuenow="76.3" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex justify-content-between tech-font text-secondary" style="font-size: 0.78rem;">
                        <span>System Target: 160.0 MW Max</span>
                        <span class="text-warning">Critical 76.3% Deficit</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Tickets -->
        <div class="col-12 col-xl-4">
            <div class="cyber-card card-danger p-4 h-100 d-flex flex-column justify-content-between" style="border-color: rgba(255, 42, 84, 0.15);">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="tech-font text-secondary fw-semibold" style="font-size: 0.78rem; letter-spacing: 1.2px;">OPEN CRITICAL TICKETS</span>
                        <h2 class="tech-font fw-bold mt-2 mb-1 text-danger" style="font-size: 2.5rem; text-shadow: 0 0 10px rgba(255, 42, 84, 0.3);">18 <span class="fs-6 fw-normal text-dim">Pending</span></h2>
                    </div>
                    <div class="p-3 rounded" style="background-color: rgba(255, 42, 84, 0.08); border: 1px solid rgba(255, 42, 84, 0.3); color: var(--warning-crimson);">
                        <i class="bi bi-exclamation-octagon-fill fs-4"></i>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-top" style="border-top-color: rgba(255, 42, 84, 0.15) !important;">
                    <div class="progress mb-2" style="height: 4px; background-color: rgba(255,255,255,0.05);">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: 30%;" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex justify-content-between tech-font text-secondary" style="font-size: 0.78rem;">
                        <span><i class="bi bi-shield-fill text-danger me-1"></i> 3 Industrial Class Outages</span>
                        <a href="#" class="text-danger text-decoration-none">View HUD Queue &rarr;</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- HUD Telemetry Layout Charts & Grid status -->
    <div class="row g-4 mb-4">
        <!-- Load Demand Oscilloscope Bar Chart mockup using CSS layout components -->
        <div class="col-12 col-xl-5">
            <div class="cyber-card p-4 h-100">
                <h6 class="tech-font text-white fw-bold mb-1"><i class="bi bi-graph-up me-2 text-info"></i>Grid Load Frequency Scanner</h6>
                <p class="text-secondary" style="font-size: 0.75rem;">Simulated live substation load cycle waves across operational sectors.</p>
                
                <div class="d-flex align-items-end justify-content-between mt-4 px-2" style="height: 150px; background-color: rgba(0,0,0,0.3); border: 1px solid var(--border-neon-teal); border-radius: 4px; padding-bottom: 10px;">
                    <div class="bg-info bg-opacity-25 border border-info w-100 mx-1 rounded-top" style="height: 40px;" title="00:00 Sector A load"></div>
                    <div class="bg-info bg-opacity-25 border border-info w-100 mx-1 rounded-top" style="height: 60px;" title="04:00 Sector B load"></div>
                    <div class="bg-info bg-opacity-50 border border-info w-100 mx-1 rounded-top" style="height: 95px;" title="08:00 Sector C load"></div>
                    <div class="bg-warning bg-opacity-75 border border-warning w-100 mx-1 rounded-top" style="height: 120px;" title="12:00 Sector D load"></div>
                    <div class="bg-danger border border-danger w-100 mx-1 rounded-top" style="height: 140px; box-shadow: 0 0 10px rgba(255, 42, 84, 0.4);" title="16:00 Sector E load (Critical)"></div>
                    <div class="bg-info bg-opacity-70 border border-info w-100 mx-1 rounded-top" style="height: 110px;" title="20:00 Sector F load"></div>
                    <div class="bg-info bg-opacity-50 border border-info w-100 mx-1 rounded-top" style="height: 75px;" title="24:00 Sector G load"></div>
                </div>

                <div class="d-flex justify-content-between tech-font text-secondary mt-2 px-1" style="font-size: 0.72rem;">
                    <span>00:00</span>
                    <span>08:00</span>
                    <span class="text-danger fw-bold">16:00 Peak Outage</span>
                    <span>20:00</span>
                    <span>24:00</span>
                </div>
            </div>
        </div>

        <!-- Node telemetry map -->
        <div class="col-12 col-xl-7">
            <div class="cyber-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="tech-font text-white fw-bold mb-1"><i class="bi bi-hdd-network-fill me-2 text-info"></i>Active Node Distribution Pipeline</h6>
                        <p class="text-secondary m-0" style="font-size: 0.75rem;">Status of mapped transformer and distribution nodes linked to sub-grid clusters.</p>
                    </div>
                    <span class="tech-font text-info border border-info border-opacity-25 px-2 py-0.5 rounded" style="font-size: 0.7rem;">Nodes Active: 4 / 4</span>
                </div>

                <div class="row g-3">
                    <div class="col-6">
                        <div class="p-3 border rounded" style="background-color: rgba(255, 255, 255, 0.01); border-color: rgba(0, 245, 255, 0.15) !important;">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="tech-font fw-semibold text-white" style="font-size: 0.82rem;">CLUSTER NODE A</span>
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 tech-font" style="font-size: 0.65rem;">ONLINE</span>
                            </div>
                            <span class="text-secondary" style="font-size: 0.7rem; font-family: monospace;">TRANSFORMER_NODE_98X</span>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="p-3 border rounded" style="background-color: rgba(255, 255, 255, 0.01); border-color: rgba(0, 245, 255, 0.15) !important;">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="tech-font fw-semibold text-white" style="font-size: 0.82rem;">CLUSTER NODE B</span>
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 tech-font" style="font-size: 0.65rem;">ONLINE</span>
                            </div>
                            <span class="text-secondary" style="font-size: 0.7rem; font-family: monospace;">TRANSFORMER_NODE_45Y</span>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="p-3 border rounded" style="background-color: rgba(255, 255, 255, 0.01); border-color: rgba(0, 245, 255, 0.15) !important;">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="tech-font fw-semibold text-white" style="font-size: 0.82rem;">CLUSTER NODE C</span>
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 tech-font" style="font-size: 0.65rem;">ONLINE</span>
                            </div>
                            <span class="text-secondary" style="font-size: 0.7rem; font-family: monospace;">TRANSFORMER_NODE_22Z</span>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="p-3 border rounded" style="background-color: rgba(255, 255, 255, 0.01); border-color: rgba(0, 245, 255, 0.15) !important;">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="tech-font fw-semibold text-white" style="font-size: 0.82rem;">CLUSTER NODE D</span>
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 tech-font" style="font-size: 0.65rem;">TRIPPED</span>
                            </div>
                            <span class="text-secondary" style="font-size: 0.7rem; font-family: monospace;">TRANSFORMER_NODE_11W</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Feeder Status Matrix Grid Table -->
    <div class="row">
        <div class="col-12">
            <div class="cyber-card">
                <!-- Card Header -->
                <div class="cyber-card-header">
                    <div>
                        <h5 class="tech-font text-white m-0 fw-bold"><i class="bi bi-grid-3x3-gap-fill me-2 text-info"></i>Feeder Node status matrices</h5>
                        <p class="text-secondary m-0 mt-1" style="font-size: 0.75rem;">Interactive grid network layout displaying live capacity metrics, load demands, and active relays.</p>
                    </div>
                    <div class="d-flex gap-2">
                        <div class="input-group input-group-sm border border-secondary border-opacity-25 rounded bg-black bg-opacity-30">
                            <span class="input-group-text bg-transparent border-0 text-secondary"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control bg-transparent border-0 text-white" placeholder="Scan feeder codes..." style="width: 170px; box-shadow: none;">
                        </div>
                        <button class="btn btn-sm btn-cyber-outline tech-font" onclick="alert('Exporting grid log protocols...')">
                            <i class="bi bi-file-earmark-pdf me-1"></i> LOG REPORT
                        </button>
                    </div>
                </div>

                <!-- Cyber Matrix Grid Table -->
                <div class="p-3">
                    <div class="table-responsive">
                        <table class="table cyber-table align-middle m-0">
                            <thead>
                                <tr>
                                    <th class="tech-font">Substation ID</th>
                                    <th class="tech-font">Distribution Feeder</th>
                                    <th class="tech-font">Zone Mapping Location</th>
                                    <th class="tech-font text-end">Cap Limit (MW)</th>
                                    <th class="tech-font text-end">Live Demand (MW)</th>
                                    <th class="tech-font text-center">Priority Level</th>
                                    <th class="tech-font text-center">Grid Relay Status</th>
                                    <th class="tech-font text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Feeder Row 1 -->
                                <tr>
                                    <td><code class="text-info fw-semibold" style="font-family: monospace;">SUB-DH-04A</code></td>
                                    <td>
                                        <div class="fw-semibold text-white">Gulshan Industrial Trunk 1</div>
                                        <small class="text-secondary" style="font-size: 0.75rem;">Linked: users_seq.128</small>
                                    </td>
                                    <td class="text-secondary" style="font-size: 0.85rem;">Dhaka // Dhaka // Gulshan // Gulshan 1</td>
                                    <td class="text-end fw-bold text-white">25.00</td>
                                    <td class="text-end fw-bold text-info">18.45</td>
                                    <td class="text-center">
                                        <span class="badge border bg-danger bg-opacity-10 text-danger border-danger border-opacity-25 tech-font" style="font-size: 0.7rem; letter-spacing: 0.5px;">Level 3: Industrial</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="telemetry-status telemetry-active">
                                            <span class="status-dot"></span> Active
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-cyber-outline py-1 px-2.5" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end border-secondary bg-black" style="border: 1px solid var(--border-neon-teal) !important;">
                                                <li><a class="dropdown-item tech-font" href="#"><i class="bi bi-graph-up-arrow me-2 text-info"></i>Live Telemetry</a></li>
                                                <li><a class="dropdown-item tech-font" href="#"><i class="bi bi-calendar-range me-2 text-warning"></i>Outage Schedule</a></li>
                                                <li><hr class="dropdown-divider border-secondary"></li>
                                                <li><a class="dropdown-item tech-font text-danger" href="#"><i class="bi bi-power me-2 text-danger"></i>Emergency Cutoff</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Feeder Row 2 -->
                                <tr>
                                    <td><code class="text-info fw-semibold" style="font-family: monospace;">SUB-DH-04B</code></td>
                                    <td>
                                        <div class="fw-semibold text-white">Banani Residential Grid</div>
                                        <small class="text-secondary" style="font-size: 0.75rem;">Linked: users_seq.94</small>
                                    </td>
                                    <td class="text-secondary" style="font-size: 0.85rem;">Dhaka // Dhaka // Banani // Block C</td>
                                    <td class="text-end fw-bold text-white">18.00</td>
                                    <td class="text-end fw-bold text-warning">0.00</td>
                                    <td class="text-center">
                                        <span class="badge border bg-secondary bg-opacity-10 text-secondary border-secondary border-opacity-25 tech-font" style="font-size: 0.7rem; letter-spacing: 0.5px;">Level 1: Residential</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="telemetry-status telemetry-shedding">
                                            <span class="status-dot"></span> Shedding
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-cyber-outline py-1 px-2.5" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end border-secondary bg-black" style="border: 1px solid var(--border-neon-teal) !important;">
                                                <li><a class="dropdown-item tech-font text-success" href="#"><i class="bi bi-play-fill me-2 text-success"></i>Restore Relay</a></li>
                                                <li><a class="dropdown-item tech-font" href="#"><i class="bi bi-graph-up-arrow me-2 text-info"></i>Live Telemetry</a></li>
                                                <li><hr class="dropdown-divider border-secondary"></li>
                                                <li><a class="dropdown-item tech-font text-danger" href="#"><i class="bi bi-power me-2 text-danger"></i>Emergency Cutoff</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Feeder Row 3 -->
                                <tr>
                                    <td><code class="text-info fw-semibold" style="font-family: monospace;">SUB-DH-04C</code></td>
                                    <td>
                                        <div class="fw-semibold text-white">Tejgaon Commercial Trunk 2</div>
                                        <small class="text-secondary" style="font-size: 0.75rem;">Linked: users_seq.310</small>
                                    </td>
                                    <td class="text-secondary" style="font-size: 0.85rem;">Dhaka // Dhaka // Tejgaon // Industrial Area</td>
                                    <td class="text-end fw-bold text-white">30.00</td>
                                    <td class="text-end fw-bold text-info">26.10</td>
                                    <td class="text-center">
                                        <span class="badge border bg-danger bg-opacity-10 text-danger border-danger border-opacity-25 tech-font" style="font-size: 0.7rem; letter-spacing: 0.5px;">Level 3: Commercial</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="telemetry-status telemetry-active">
                                            <span class="status-dot"></span> Active
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-cyber-outline py-1 px-2.5" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end border-secondary bg-black" style="border: 1px solid var(--border-neon-teal) !important;">
                                                <li><a class="dropdown-item tech-font" href="#"><i class="bi bi-graph-up-arrow me-2 text-info"></i>Live Telemetry</a></li>
                                                <li><a class="dropdown-item tech-font" href="#"><i class="bi bi-calendar-range me-2 text-warning"></i>Outage Schedule</a></li>
                                                <li><hr class="dropdown-divider border-secondary"></li>
                                                <li><a class="dropdown-item tech-font text-danger" href="#"><i class="bi bi-power me-2 text-danger"></i>Emergency Cutoff</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Feeder Row 4 -->
                                <tr>
                                    <td><code class="text-info fw-semibold" style="font-family: monospace;">SUB-DH-04D</code></td>
                                    <td>
                                        <div class="fw-semibold text-white">Badda Suburban Feeder</div>
                                        <small class="text-secondary" style="font-size: 0.75rem;">Linked: users_seq.71</small>
                                    </td>
                                    <td class="text-secondary" style="font-size: 0.85rem;">Dhaka // Dhaka // Badda // Progoti Sarani</td>
                                    <td class="text-end fw-bold text-white">12.00</td>
                                    <td class="text-end fw-bold text-dim">0.00</td>
                                    <td class="text-center">
                                        <span class="badge border bg-secondary bg-opacity-10 text-secondary border-secondary border-opacity-25 tech-font" style="font-size: 0.7rem; letter-spacing: 0.5px;">Level 1: Suburban</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="telemetry-status telemetry-down">
                                            <span class="status-dot"></span> Grid Down
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-cyber-outline py-1 px-2.5" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end border-secondary bg-black" style="border: 1px solid var(--border-neon-teal) !important;">
                                                <li><a class="dropdown-item tech-font text-info" href="#"><i class="bi bi-wrench-adjustable me-2 text-info"></i>Dispatch Crew</a></li>
                                                <li><a class="dropdown-item tech-font" href="#"><i class="bi bi-graph-up-arrow me-2 text-info"></i>Live Telemetry</a></li>
                                                <li><hr class="dropdown-divider border-secondary"></li>
                                                <li><a class="dropdown-item tech-font text-danger" href="#"><i class="bi bi-power me-2 text-danger"></i>Emergency Cutoff</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
