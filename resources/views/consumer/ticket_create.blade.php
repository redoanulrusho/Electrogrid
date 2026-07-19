@extends('layouts.consumer')

@section('title', 'ElectroGrid — Submit Complaint')

@section('styles')
<style>
    .form-ctrl {
        background: rgba(0,0,0,0.3);
        border: 1px solid var(--border-neon-teal);
        color: #fff;
        border-radius: 4px;
        padding: 0.75rem 1rem;
        width: 100%;
        font-size: 0.92rem;
        transition: all 0.2s;
    }
    .form-ctrl:focus {
        outline: none;
        border-color: var(--neon-teal);
        box-shadow: 0 0 10px rgba(0,245,255,0.1);
        background: rgba(0,0,0,0.4);
        color: #fff;
    }
    .form-ctrl::placeholder { color: #4a5462; }
    .form-label-hud {
        font-size: 0.72rem;
        font-weight: 700;
        color: var(--text-dim);
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 0.45rem;
        display: block;
    }
</style>
@endsection

@section('content')
<div class="container-fluid p-0">

    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-xl-6">

            {{-- Back link --}}
            <a href="{{ route('consumer.tickets') }}" class="text-secondary text-decoration-none tech-font d-inline-flex align-items-center gap-1 mb-4" style="font-size:0.75rem;">
                <i class="bi bi-chevron-left"></i> Back to Tickets
            </a>

            <div class="cyber-card">
                <div class="cyber-card-header">
                    <div>
                        <h5 class="tech-font text-white fw-bold m-0">
                            <i class="bi bi-ticket-fill me-2 text-info"></i>Submit Complaint Ticket
                        </h5>
                        <p class="text-secondary m-0 mt-1" style="font-size:0.8rem;">
                            Report unscheduled outages, billing issues, or service complaints.
                        </p>
                    </div>
                </div>

                <div class="p-4">
                    @if($errors->any())
                    <div class="p-3 mb-4 rounded" style="background:rgba(255,42,84,0.08);border:1px solid rgba(255,42,84,0.3);">
                        @foreach($errors->all() as $error)
                            <div class="text-danger" style="font-size:0.83rem;"><i class="bi bi-x-circle me-1"></i>{{ $error }}</div>
                        @endforeach
                    </div>
                    @endif

                    <form method="POST" action="{{ route('consumer.tickets.store') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="subject" class="form-label-hud">Subject / Complaint Title</label>
                            <input type="text" id="subject" name="subject" class="form-ctrl"
                                   placeholder="e.g. Unscheduled power cut for 3 hours"
                                   value="{{ old('subject') }}" required>
                        </div>
                        <div class="mb-4">
                            <label for="description" class="form-label-hud">Detailed Description</label>
                            <textarea id="description" name="description" rows="5" class="form-ctrl"
                                      placeholder="Describe the issue in detail..." required>{{ old('description') }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-cyber-primary tech-font w-100 py-2">
                            <i class="bi bi-send-fill me-2"></i>Submit Ticket
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
