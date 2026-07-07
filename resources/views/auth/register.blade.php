<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ElectroGrid Consumer Onboarding Wizard</title>
    
    <!-- Google Fonts: Rajdhani & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Rajdhani:wght@600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Custom CSS for Registration Onboarding Wizard -->
    <style>
        :root {
            --bg-obsidian: #03060c;
            --bg-slate: #0b0f19;
            --card-glass: rgba(11, 17, 30, 0.9);
            --border-neon-teal: rgba(0, 245, 255, 0.2);
            --border-neon-teal-active: rgba(0, 245, 255, 0.6);
            --neon-teal: #00f5ff;
            --neon-teal-glow: rgba(0, 245, 255, 0.25);
            --electric-amber: #ff9f1c;
            --electric-amber-glow: rgba(255, 159, 28, 0.2);
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
            padding: 2rem 1.5rem;
            position: relative;
            overflow-x: hidden;
            background-image: 
                linear-gradient(rgba(0, 245, 255, 0.015) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 245, 255, 0.015) 1px, transparent 1px);
            background-size: 35px 35px;
        }

        .ambient-glow {
            position: absolute;
            width: 450px;
            height: 450px;
            background: radial-gradient(circle, var(--electric-amber-glow) 0%, transparent 70%);
            bottom: -50px;
            right: -100px;
            z-index: 1;
            filter: blur(120px);
            pointer-events: none;
        }

        .wizard-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 750px;
        }

        .tech-font {
            font-family: 'Rajdhani', sans-serif;
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }

        .glass-wizard-card {
            background-color: var(--card-glass);
            border: 1px solid var(--border-neon-teal);
            border-radius: 8px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(15px);
            overflow: hidden;
            position: relative;
        }

        /* High-tech HUD Brackets */
        .glass-wizard-card::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 15px;
            height: 15px;
            border-top: 3px solid var(--neon-teal);
            border-right: 3px solid var(--neon-teal);
            pointer-events: none;
        }

        .glass-wizard-card::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 15px;
            height: 15px;
            border-bottom: 3px solid var(--neon-teal);
            border-left: 3px solid var(--neon-teal);
            pointer-events: none;
        }

        /* Step Progress Header Bar */
        .wizard-header-hud {
            background-color: rgba(0, 0, 0, 0.4);
            border-bottom: 1px solid var(--border-neon-teal);
            padding: 1.75rem 2.25rem;
        }

        .step-progress-container-hud {
            display: flex;
            justify-content: space-between;
            position: relative;
            margin-top: 1rem;
        }

        .step-progress-container-hud::before {
            content: '';
            position: absolute;
            top: 15px;
            left: 0;
            right: 0;
            height: 2px;
            background-color: rgba(255, 255, 255, 0.05);
            z-index: 1;
        }

        .step-progress-bar-hud-filled {
            position: absolute;
            top: 15px;
            left: 0;
            height: 2px;
            background-color: var(--neon-teal);
            box-shadow: 0 0 10px var(--neon-teal-glow);
            z-index: 2;
            width: 0%;
            transition: width 0.35s ease;
        }

        .step-indicator-hud {
            position: relative;
            z-index: 3;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: var(--bg-obsidian);
            border: 2px solid rgba(255,255,255,0.1);
            color: var(--text-dim);
            font-weight: 700;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .step-indicator-hud.active {
            border-color: var(--neon-teal);
            color: var(--neon-teal);
            box-shadow: 0 0 12px var(--neon-teal-glow);
        }

        .step-indicator-hud.completed {
            background-color: var(--neon-teal);
            border-color: var(--neon-teal);
            color: #000;
            box-shadow: 0 0 10px var(--neon-teal-glow);
        }

        .step-title-hud-text {
            position: absolute;
            top: 40px;
            left: 50%;
            transform: translateX(-50%);
            white-space: nowrap;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: var(--text-dim);
            transition: color 0.3s ease;
        }

        .step-indicator-hud.active .step-title-hud-text {
            color: #fff;
            text-shadow: 0 0 5px var(--neon-teal-glow);
        }

        .step-indicator-hud.completed .step-title-hud-text {
            color: var(--neon-teal);
        }

        /* Form Wizard Steps Wrapper */
        .wizard-body-hud {
            padding: 2.5rem;
        }

        .wizard-step-section-hud {
            display: none;
        }

        .wizard-step-section-hud.active {
            display: block;
            animation: fadeIn 0.4s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Form Element Custom Stylings */
        .form-label-hud {
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--text-dim);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 0.45rem;
        }

        .form-control-hud, .form-select-hud {
            background-color: rgba(0,0,0,0.3);
            border: 1px solid var(--border-neon-teal);
            color: #fff;
            padding: 0.75rem 1rem;
            border-radius: 4px;
            font-size: 0.92rem;
            transition: all 0.2s ease;
        }

        .form-control-hud:focus, .form-select-hud:focus {
            background-color: rgba(0,0,0,0.4);
            border-color: var(--neon-teal);
            color: #fff;
            box-shadow: 0 0 10px rgba(0, 245, 255, 0.15);
            outline: none;
        }

        .form-control-hud::placeholder {
            color: #4a5462;
        }

        .form-control-hud.is-invalid {
            border-color: var(--warning-crimson) !important;
            box-shadow: 0 0 10px rgba(255, 42, 84, 0.15) !important;
        }

        /* Action Nav Buttons */
        .wizard-footer-hud {
            padding: 1.5rem 2.5rem 2.25rem 2.5rem;
            border-top: 1px solid var(--border-neon-teal);
            display: flex;
            justify-content: space-between;
            background-color: rgba(0,0,0,0.2);
        }

        .btn-cyber-prev {
            border: 1px solid var(--border-neon-teal);
            background-color: transparent;
            color: var(--text-hud);
            font-weight: 700;
            padding: 0.65rem 1.5rem;
            border-radius: 4px;
            transition: all 0.2s ease;
        }

        .btn-cyber-prev:hover {
            border-color: var(--neon-teal);
            color: var(--neon-teal);
            background-color: rgba(0, 245, 255, 0.02);
            box-shadow: 0 0 10px rgba(0, 245, 255, 0.05);
        }

        .btn-cyber-next, .btn-cyber-submit {
            background: linear-gradient(135deg, rgba(0, 245, 255, 0.1) 0%, rgba(0, 245, 255, 0.05) 100%);
            border: 1px solid var(--neon-teal);
            color: var(--neon-teal);
            font-weight: 700;
            padding: 0.65rem 1.75rem;
            border-radius: 4px;
            transition: all 0.2s ease;
            text-shadow: 0 0 5px var(--neon-teal-glow);
        }

        .btn-cyber-next:hover, .btn-cyber-submit:hover {
            background-color: var(--neon-teal);
            color: #000;
            box-shadow: 0 0 15px var(--neon-teal-glow);
            text-shadow: none;
        }
    </style>
</head>
<body>

    <div class="ambient-glow"></div>

    <div class="wizard-wrapper">

        <!-- Header -->
        <div class="text-center mb-4">
            <h2 class="tech-font fw-extrabold m-0 text-white" style="letter-spacing: 1.5px; text-shadow: 0 0 8px var(--neon-teal-glow);">⚡ ELECTROGRID ONBOARDING</h2>
            <p class="text-secondary mt-1" style="font-size: 0.82rem;">Establish Consumer Meter to Distribution Feeder Relays</p>
        </div>

        <!-- Master Wizard Card -->
        <div class="glass-wizard-card">
            
            <!-- Step Indicators -->
            <div class="wizard-header-hud">
                <div class="step-progress-container-hud">
                    <div class="step-progress-bar-hud-filled" id="progress-bar"></div>
                    
                    <div class="step-indicator-hud active" id="indicator-1">
                        1
                        <span class="step-title-hud-text tech-font">1. Credentials</span>
                    </div>
                    <div class="step-indicator-hud" id="indicator-2">
                        2
                        <span class="step-title-hud-text tech-font">2. Node Ingestion</span>
                    </div>
                    <div class="step-indicator-hud" id="indicator-3">
                        3
                        <span class="step-title-hud-text tech-font">3. Geography</span>
                    </div>
                </div>
            </div>

            <!-- Error Alerts -->
            @if ($errors->any())
                <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger p-3 mx-4 mt-4" style="border-left: 3px solid var(--warning-crimson) !important; font-size: 0.85rem; border-radius: 4px;">
                    <strong class="d-block mb-1 text-white tech-font">Configuration Faults Detected:</strong>
                    <ul class="m-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Main Form -->
            <form action="{{ route('register') }}" method="POST" id="registration-wizard-form" autocomplete="off">
                @csrf

                <div class="wizard-body-hud">

                    <!-- STEP 1: Account Details -->
                    <div class="wizard-step-section-hud active" id="step-section-1">
                        <h6 class="tech-font text-white fw-bold mb-1"><i class="bi bi-person-badge-fill text-info me-2"></i>Account & Terminal Credentials</h6>
                        <p class="text-secondary mb-4" style="font-size: 0.75rem;">Configure account credentials to access status telemetry portals.</p>
                        
                        <div class="row g-3">
                            <!-- Full Name -->
                            <div class="col-12 col-md-6">
                                <label for="reg-name" class="form-label form-label-hud">Full Legal Name</label>
                                <input type="text" 
                                       name="name" 
                                       id="reg-name" 
                                       class="form-control form-control-hud w-100" 
                                       placeholder="e.g. Jahidul Islam" 
                                       required
                                       value="{{ old('name') }}">
                            </div>

                            <!-- Username -->
                            <div class="col-12 col-md-6">
                                <label for="reg-username" class="form-label form-label-hud">Unique Username</label>
                                <input type="text" 
                                       name="username" 
                                       id="reg-username" 
                                       class="form-control form-control-hud w-100" 
                                       placeholder="e.g. jahidul_99" 
                                       required
                                       value="{{ old('username') }}">
                            </div>

                            <!-- Email Address -->
                            <div class="col-12 col-md-6">
                                <label for="reg-email" class="form-label form-label-hud">Contact Email Address</label>
                                <input type="email" 
                                       name="email" 
                                       id="reg-email" 
                                       class="form-control form-control-hud w-100" 
                                       placeholder="e.g. jahidul@gmail.com" 
                                       required
                                       value="{{ old('email') }}">
                            </div>

                            <!-- Phone Number -->
                            <div class="col-12 col-md-6">
                                <label for="reg-phone" class="form-label form-label-hud">Mobile Phone Number</label>
                                <input type="text" 
                                       name="phone" 
                                       id="reg-phone" 
                                       class="form-control form-control-hud w-100" 
                                       placeholder="e.g. +88017XXXXXXXX" 
                                       required
                                       value="{{ old('phone') }}">
                            </div>

                            <!-- Passwords -->
                            <div class="col-12 col-md-6">
                                <label for="reg-password" class="form-label form-label-hud">Account Password</label>
                                <input type="password" 
                                       name="password" 
                                       id="reg-password" 
                                       class="form-control form-control-hud w-100" 
                                       placeholder="Min 8 characters" 
                                       required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="reg-password-confirm" class="form-label form-label-hud">Confirm Password</label>
                                <input type="password" 
                                       name="password_confirmation" 
                                       id="reg-password-confirm" 
                                       class="form-control form-control-hud w-100" 
                                       placeholder="Re-type password" 
                                       required>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 2: Meter Node & Service Class Ingestion -->
                    <div class="wizard-step-section-hud" id="step-section-2">
                        <h6 class="tech-font text-white fw-bold mb-1"><i class="bi bi-lightning-charge-fill text-info me-2"></i>Physical Utility Node Linkage</h6>
                        <p class="text-secondary mb-4" style="font-size: 0.75rem;">Map your electrical meter configuration to active substation feeder grids.</p>
                        
                        <div class="row g-3">
                            <!-- Meter Number -->
                            <div class="col-12 col-md-6">
                                <label for="reg-meter" class="form-label form-label-hud">Unique Meter Number</label>
                                <input type="text" 
                                       name="meter_number" 
                                       id="reg-meter" 
                                       class="form-control form-control-hud w-100" 
                                       placeholder="e.g. MTR-998845" 
                                       required
                                       value="{{ old('meter_number') }}">
                            </div>

                            <!-- Consumer Class Selection -->
                            <div class="col-12 col-md-6">
                                <label for="reg-class" class="form-label form-label-hud">Distribution Class</label>
                                <select name="consumer_class" id="reg-class" class="form-select form-select-hud w-100" required>
                                    <option value="" disabled selected>-- SELECT CLASS TYPE --</option>
                                    <option value="Residential" {{ old('consumer_class') == 'Residential' ? 'selected' : '' }}>Residential Class (Low Priority)</option>
                                    <option value="Commercial" {{ old('consumer_class') == 'Commercial' ? 'selected' : '' }}>Commercial Class (Medium Priority)</option>
                                    <option value="Industrial" {{ old('consumer_class') == 'Industrial' ? 'selected' : '' }}>Industrial Class (High Priority / Hospitals)</option>
                                </select>
                            </div>

                            <!-- Mapped Feeder Dropdown -->
                            <div class="col-12">
                                <label for="reg-feeder" class="form-label form-label-hud">Substation Grid Feeder Relay</label>
                                <select name="feeder_id" id="reg-feeder" class="form-select form-select-hud w-100" required>
                                    <option value="" disabled selected>-- SECTOR NODES DETECTED --</option>
                                    <option value="1" {{ old('feeder_id') == '1' ? 'selected' : '' }}>SUB-DH-04A // Gulshan Industrial Trunk 1</option>
                                    <option value="2" {{ old('feeder_id') == '2' ? 'selected' : '' }}>SUB-DH-04B // Banani Residential Grid</option>
                                    <option value="3" {{ old('feeder_id') == '3' ? 'selected' : '' }}>SUB-DH-04C // Tejgaon Commercial Trunk 2</option>
                                    <option value="4" {{ old('feeder_id') == '4' ? 'selected' : '' }}>SUB-DH-04D // Badda Suburban Feeder</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 3: Hierarchical Geography Mapping -->
                    <div class="wizard-step-section-hud" id="step-section-3">
                        <h6 class="tech-font text-white fw-bold mb-1"><i class="bi bi-geo-alt-fill text-info me-2"></i>Infrastructure Geolocation Mapping</h6>
                        <p class="text-secondary mb-4" style="font-size: 0.75rem;">Confirm physical service coordinates sorted hierarchically across region rows.</p>
                        
                        <!-- Row 1: Division & District -->
                        <div class="row g-3 mb-3">
                            <div class="col-12 col-md-6">
                                <label for="reg-division" class="form-label form-label-hud">Division</label>
                                <input type="text" 
                                       name="division" 
                                       id="reg-division" 
                                       class="form-control form-control-hud w-100" 
                                       placeholder="e.g. Dhaka" 
                                       required
                                       value="{{ old('division') }}">
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="reg-district" class="form-label form-label-hud">District</label>
                                <input type="text" 
                                       name="district" 
                                       id="reg-district" 
                                       class="form-control form-control-hud w-100" 
                                       placeholder="e.g. Dhaka" 
                                       required
                                       value="{{ old('district') }}">
                            </div>
                        </div>

                        <!-- Row 2: Upazila & Area -->
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="reg-upazila" class="form-label form-label-hud">Upazila</label>
                                <input type="text" 
                                       name="upazila" 
                                       id="reg-upazila" 
                                       class="form-control form-control-hud w-100" 
                                       placeholder="e.g. Gulshan" 
                                       required
                                       value="{{ old('upazila') }}">
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="reg-area" class="form-label form-label-hud">Area / Street Address</label>
                                <input type="text" 
                                       name="area" 
                                       id="reg-area" 
                                       class="form-control form-control-hud w-100" 
                                       placeholder="e.g. Road 11, Gulshan 2" 
                                       required
                                       value="{{ old('area') }}">
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Wizard Navigation Footer -->
                <div class="wizard-footer-hud">
                    <button type="button" class="btn btn-cyber-prev tech-font" id="btn-prev" onclick="changeStep(-1)" style="visibility: hidden;">
                        <i class="bi bi-chevron-left me-1"></i> Back
                    </button>
                    
                    <button type="button" class="btn btn-cyber-next tech-font" id="btn-next" onclick="changeStep(1)">
                        Next <i class="bi bi-chevron-right ms-1"></i>
                    </button>
                    
                    <button type="submit" class="btn btn-cyber-submit tech-font" id="btn-submit" style="display: none;">
                        SUBMIT MAPPING CODE
                    </button>
                </div>
            </form>
        </div>

        <div class="text-center mt-3 text-secondary tech-font" style="font-size: 0.8rem;">
            Authorized terminal user? <a href="{{ route('login') }}" class="text-info text-decoration-none fw-semibold">Load Session Login &rarr;</a>
        </div>
    </div>

    <!-- Wizard Javascript Logic -->
    <script>
        let currentStep = 1;
        const totalSteps = 3;

        function changeStep(direction) {
            if (direction === 1) {
                const activeFields = document.querySelectorAll(`#step-section-${currentStep} [required]`);
                let isValid = true;
                activeFields.forEach(field => {
                    if (!field.value) {
                        field.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });

                if (!isValid) {
                    alert('Configuration parameter values incomplete.');
                    return;
                }
            }

            const currentSection = document.getElementById(`step-section-${currentStep}`);
            currentStep += direction;
            const nextSection = document.getElementById(`step-section-${currentStep}`);

            currentSection.classList.remove('active');
            nextSection.classList.add('active');

            updateIndicators();
        }

        function updateIndicators() {
            for (let i = 1; i <= totalSteps; i++) {
                const indicator = document.getElementById(`indicator-${i}`);
                indicator.classList.remove('active', 'completed');
                
                if (i < currentStep) {
                    indicator.classList.add('completed');
                } else if (i === currentStep) {
                    indicator.classList.add('active');
                }
            }

            const progressBar = document.getElementById('progress-bar');
            const percent = ((currentStep - 1) / (totalSteps - 1)) * 100;
            progressBar.style.width = percent + '%';

            const btnPrev = document.getElementById('btn-prev');
            const btnNext = document.getElementById('btn-next');
            const btnSubmit = document.getElementById('btn-submit');

            if (currentStep === 1) {
                btnPrev.style.visibility = 'hidden';
            } else {
                btnPrev.style.visibility = 'visible';
            }

            if (currentStep === totalSteps) {
                btnNext.style.display = 'none';
                btnSubmit.style.display = 'block';
            } else {
                btnNext.style.display = 'block';
                btnSubmit.style.display = 'none';
            }
        }
    </script>
</body>
</html>
