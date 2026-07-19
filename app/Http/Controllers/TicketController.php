<?php

namespace App\Http\Controllers;

use App\Events\ComplaintStatusChanged;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Consumer: list their tickets.
     */
    public function index()
    {
        $user    = Auth::guard('web')->user();
        $tickets = Ticket::where('user_id', $user->id)
                         ->with('feeder')
                         ->orderByDesc('created_at')
                         ->paginate(10);

        return view('consumer.tickets', compact('tickets', 'user'));
    }

    /**
     * Consumer: show ticket creation form.
     */
    public function create()
    {
        $user = Auth::guard('web')->user();
        return view('consumer.ticket_create', compact('user'));
    }

    /**
     * Consumer: store new ticket.
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject'     => 'required|string|max:200',
            'description' => 'required|string|max:2000',
        ]);

        $user = Auth::guard('web')->user();

        Ticket::create([
            'user_id'     => $user->id,
            'feeder_id'   => $user->feeder_id,
            'subject'     => $request->subject,
            'description' => $request->description,
            'status'      => 'open',
        ]);

        return redirect()->route('consumer.tickets')
                         ->with('success', 'Complaint ticket submitted. Admin will respond shortly.');
    }

    // ─── Admin: view all tickets ──────────────────────────────────────────────

    /**
     * Admin: list all open/in-progress tickets.
     */
    public function adminIndex()
    {
        $tickets = Ticket::with(['user', 'feeder'])
                         ->orderByRaw("FIELD(status, 'open', 'in_progress', 'resolved')")
                         ->orderByDesc('created_at')
                         ->paginate(20);

        return view('admin.tickets', compact('tickets'));
    }

    /**
     * Admin: resolve / respond to a ticket.
     */
    public function resolve(Request $request, int $id)
    {
        $request->validate([
            'admin_response' => 'required|string|max:2000',
            'status'         => 'required|in:in_progress,resolved',
        ]);

        $ticket = Ticket::findOrFail($id);
        $ticket->update([
            'admin_response' => $request->admin_response,
            'status'         => $request->status,
        ]);

        // Broadcast real-time status change to the consumer's private channel (safely caught if network/DNS error)
        try {
            ComplaintStatusChanged::dispatch($ticket->fresh());
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning("Pusher broadcast failed: " . $e->getMessage());
        }

        return back()->with('success', "Ticket #{$ticket->id} updated to {$request->status}.");
    }
}
