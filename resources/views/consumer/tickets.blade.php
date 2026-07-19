@extends('layouts.consumer')

@section('title', 'ElectroGrid — My Tickets')

@section('content')
<div class="container-fluid p-0">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h4 class="tech-font text-white fw-bold mb-1">🎟 My Complaint Tickets</h4>
            <p class="text-secondary m-0" style="font-size:0.82rem;">Submit and track complaints about outages or service issues.</p>
        </div>
        <a href="{{ route('consumer.tickets.create') }}" class="btn btn-cyber-primary tech-font px-4">
            <i class="bi bi-plus-circle me-1"></i> New Ticket
        </a>
    </div>

    @if(session('success'))
        <div class="alert p-3 mb-4 d-flex align-items-center gap-2"
             style="background:rgba(0,230,118,0.08);border:1px solid rgba(0,230,118,0.3);border-radius:6px;color:var(--grid-green);">
            <i class="bi bi-check-circle-fill"></i>{{ session('success') }}
        </div>
    @endif

    @forelse($tickets as $ticket)
    <div class="mb-3 p-4 rounded
         @if($ticket->status === 'open') border-start border-danger
         @elseif($ticket->status === 'in_progress') border-start border-warning
         @else border-start border-success @endif"
         data-ticket-id="{{ $ticket->id }}"
         style="background:rgba(14,21,37,0.5);border:1px solid var(--border-neon-teal);border-left-width:3px!important;transition:all 0.2s;">

        <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
                <span class="text-secondary tech-font me-2" style="font-size:0.7rem;">#{{ $ticket->id }}</span>
                <strong class="text-white" style="font-size:0.95rem;">{{ $ticket->subject }}</strong>
            </div>
            <span class="badge tech-font ticket-status-badge" style="font-size:0.65rem;
                @if($ticket->status === 'open') background:rgba(255,42,84,0.1);border:1px solid rgba(255,42,84,0.3);color:var(--warning-crimson);
                @elseif($ticket->status === 'in_progress') background:rgba(255,159,28,0.1);border:1px solid rgba(255,159,28,0.3);color:var(--electric-amber);
                @else background:rgba(0,230,118,0.1);border:1px solid rgba(0,230,118,0.3);color:var(--grid-green);
                @endif">
                {{ strtoupper(str_replace('_', ' ', $ticket->status)) }}
            </span>
        </div>

        <p class="text-secondary m-0 mb-2" style="font-size:0.82rem;">{{ $ticket->description }}</p>

        <div class="d-flex justify-content-between align-items-center">
            <small class="text-secondary" style="font-size:0.72rem;">
                <i class="bi bi-clock me-1"></i>{{ $ticket->created_at->diffForHumans() }}
                @if($ticket->feeder) · <code class="text-info">{{ $ticket->feeder->substation_code }}</code> @endif
            </small>
        </div>

        @if($ticket->admin_response)
        <div class="mt-3 p-3 rounded" style="background:rgba(0,245,255,0.04);border:1px solid var(--border-neon-teal);">
            <small class="tech-font text-info" style="font-size:0.68rem;">ADMIN RESPONSE:</small>
            <p class="text-white m-0 mt-1" style="font-size:0.82rem;">{{ $ticket->admin_response }}</p>
        </div>
        @endif
    </div>
    @empty
    <div class="cyber-card p-5 text-center text-secondary">
        <i class="bi bi-ticket fs-2 mb-2 d-block text-info"></i>
        No tickets yet. <a href="{{ route('consumer.tickets.create') }}" class="text-info">Submit your first complaint</a>.
    </div>
    @endforelse

    <div class="mt-3">{{ $tickets->links() }}</div>

</div>
@endsection
