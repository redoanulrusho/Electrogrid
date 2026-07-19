@extends('layouts.app')

@section('title', 'ElectroGrid — Ticket Queue Management')

@section('content')
<div class="container-fluid p-0">

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert p-3 mb-4 d-flex align-items-center gap-2"
             style="background:rgba(0,230,118,0.08);border:1px solid rgba(0,230,118,0.3)!important;border-radius:6px;color:#00e676;">
            <i class="bi bi-check-circle-fill"></i><span>{{ session('success') }}</span>
            <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="cyber-card">
        <div class="cyber-card-header">
            <div>
                <h5 class="tech-font text-white m-0 fw-bold">
                    <i class="bi bi-envelope-exclamation-fill me-2 text-warning"></i>Consumer Complaint Ticket Queue
                </h5>
                <p class="text-secondary m-0 mt-1" style="font-size:0.75rem;">
                    Open and in-progress tickets from registered consumers. Respond and resolve below.
                </p>
            </div>
            <span class="tech-font badge px-3 py-2" style="background:rgba(255,42,84,0.08);border:1px solid rgba(255,42,84,0.3);color:#ff2a54;font-size:0.72rem;">
                {{ $tickets->total() }} Total Tickets
            </span>
        </div>

        <div class="p-3">
            @forelse($tickets as $ticket)
            <div class="p-4 mb-3 rounded"
                 style="background:#0e1525;
                        border:1px solid rgba(0,245,255,0.12);
                        @if($ticket->status === 'open') border-left:3px solid #ff2a54!important;
                        @elseif($ticket->status === 'in_progress') border-left:3px solid #ff9f1c!important;
                        @else border-left:3px solid #00e676!important; @endif">

                {{-- Ticket Header --}}
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <span class="tech-font me-2" style="font-size:0.7rem;color:#768390;">#{{ $ticket->id }}</span>
                        <strong style="color:#e6edf3;font-size:1rem;">{{ $ticket->subject }}</strong>
                    </div>
                    <div class="d-flex gap-2 align-items-center flex-shrink-0 ms-3">
                        @if($ticket->feeder)
                            <code style="color:#00f5ff;font-size:0.72rem;background:rgba(0,245,255,0.06);padding:0.2rem 0.5rem;border-radius:3px;border:1px solid rgba(0,245,255,0.15);">{{ $ticket->feeder->substation_code }}</code>
                        @endif
                        <span class="badge tech-font" style="font-size:0.65rem;
                            @if($ticket->status === 'open') background:rgba(255,42,84,0.1);border:1px solid rgba(255,42,84,0.4);color:#ff2a54;
                            @elseif($ticket->status === 'in_progress') background:rgba(255,159,28,0.1);border:1px solid rgba(255,159,28,0.4);color:#ff9f1c;
                            @else background:rgba(0,230,118,0.1);border:1px solid rgba(0,230,118,0.4);color:#00e676;
                            @endif">
                            {{ strtoupper(str_replace('_',' ',$ticket->status)) }}
                        </span>
                    </div>
                </div>

                {{-- Description --}}
                <p style="color:#c9d1d9;font-size:0.88rem;margin-bottom:0.75rem;line-height:1.6;">{{ $ticket->description }}</p>

                {{-- Meta info --}}
                <div class="d-flex gap-4 mb-3" style="font-size:0.78rem;">
                    <span style="color:#9ba8b5;">
                        <i class="bi bi-person-fill me-1" style="color:#00f5ff;"></i>
                        <strong style="color:#c9d1d9;">{{ $ticket->user->name }}</strong>
                        <span style="color:#768390;"> ({{ $ticket->user->meter_number }})</span>
                    </span>
                    <span style="color:#768390;">
                        <i class="bi bi-clock me-1"></i>{{ $ticket->created_at->format('d M Y, h:i A') }}
                    </span>
                </div>

                {{-- Previous admin response --}}
                @if($ticket->admin_response)
                <div class="p-3 mb-3 rounded" style="background:#131d2e;border:1px solid rgba(0,245,255,0.2);">
                    <div class="tech-font mb-2" style="font-size:0.68rem;color:#00f5ff;letter-spacing:1px;">PREVIOUS RESPONSE:</div>
                    <p style="color:#e6edf3;font-size:0.88rem;margin:0;line-height:1.6;">{{ $ticket->admin_response }}</p>
                </div>
                @endif

                {{-- Respond form (only for non-resolved tickets) --}}
                @if($ticket->status !== 'resolved')
                <form method="POST" action="{{ route('admin.tickets.resolve', $ticket->id) }}" class="row g-2 align-items-end">
                    @csrf
                    <div class="col-12 col-md-7">
                        <label class="form-label tech-font" style="font-size:0.68rem;color:#00f5ff;letter-spacing:1px;margin-bottom:0.35rem;">ADMIN RESPONSE</label>
                        <input type="text" name="admin_response"
                               placeholder="Type your response to the consumer..."
                               required
                               style="display:block;width:100%;background:#1a2332;border:1px solid rgba(0,245,255,0.4);border-radius:4px;color:#e6edf3;padding:0.55rem 0.85rem;font-size:0.88rem;outline:none;"
                               onfocus="this.style.borderColor='#00f5ff';this.style.boxShadow='0 0 6px rgba(0,245,255,0.15)';"
                               onblur="this.style.borderColor='rgba(0,245,255,0.4)';this.style.boxShadow='none';">
                    </div>
                    <div class="col-auto">
                        <label class="form-label tech-font" style="font-size:0.68rem;color:#00f5ff;letter-spacing:1px;margin-bottom:0.35rem;">SET STATUS</label>
                        <select name="status"
                                style="background:#1a2332;border:1px solid rgba(0,245,255,0.4);border-radius:4px;color:#e6edf3;padding:0.55rem 0.85rem;font-size:0.85rem;outline:none;cursor:pointer;"
                                onfocus="this.style.borderColor='#00f5ff';"
                                onblur="this.style.borderColor='rgba(0,245,255,0.4)';">
                            <option value="in_progress" style="background:#0e1525;">In Progress</option>
                            <option value="resolved" style="background:#0e1525;">Resolved</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit"
                                style="background:rgba(0,245,255,0.1);border:1px solid #00f5ff;color:#00f5ff;padding:0.55rem 1.2rem;border-radius:4px;font-family:'Rajdhani',sans-serif;text-transform:uppercase;font-weight:700;cursor:pointer;font-size:0.88rem;transition:all 0.2s;"
                                onmouseover="this.style.background='#00f5ff';this.style.color='#000';"
                                onmouseout="this.style.background='rgba(0,245,255,0.1)';this.style.color='#00f5ff';">
                            <i class="bi bi-send me-1"></i> Respond
                        </button>
                    </div>
                </form>
                @else
                <div style="font-size:0.78rem;color:#00e676;font-family:'Rajdhani',sans-serif;text-transform:uppercase;letter-spacing:0.5px;">
                    <i class="bi bi-check2-circle me-1"></i> Ticket resolved
                </div>
                @endif
            </div>
            @empty
            <div class="text-center py-5" style="color:#768390;">
                <i class="bi bi-inbox fs-2 mb-2 d-block"></i>
                No tickets found.
            </div>
            @endforelse

            <div class="mt-3">{{ $tickets->links() }}</div>
        </div>
    </div>
</div>
@endsection
