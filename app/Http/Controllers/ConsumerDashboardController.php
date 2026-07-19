<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\GridNotification;
use App\Models\OutageSchedule;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConsumerDashboardController extends Controller
{
    /**
     * Consumer dashboard — shows feeder status, outage schedule, notifications.
     */
    public function index()
    {
        $user   = Auth::guard('web')->user();
        $feeder = $user->feeder;

        // Today's outage schedule for this feeder (if any)
        $todayOutage = null;
        $upcomingOutages = collect();
        $outageHistory = collect();

        if ($feeder) {
            $todayOutage = OutageSchedule::where('feeder_id', $feeder->id)
                ->whereDate('start_time', today())
                ->whereIn('status', ['planned', 'active'])
                ->first();

            // Next 7 days upcoming outages
            $upcomingOutages = OutageSchedule::where('feeder_id', $feeder->id)
                ->where('start_time', '>', now())
                ->where('start_time', '<=', now()->addDays(7))
                ->where('status', 'planned')
                ->orderBy('start_time')
                ->get();

            // Past 30 days completed outages
            $outageHistory = OutageSchedule::where('feeder_id', $feeder->id)
                ->where('status', 'completed')
                ->where('end_time', '>=', now()->subDays(30))
                ->orderByDesc('end_time')
                ->get();
        }

        // Unread notifications
        $notifications = GridNotification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        // Mark them as read
        GridNotification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        // Latest unpaid bill
        $latestBill = Bill::where('user_id', $user->id)
            ->where('paid_status', 'unpaid')
            ->orderByDesc('month')
            ->first();

        // Open tickets
        $openTickets = Ticket::where('user_id', $user->id)
            ->whereIn('status', ['open', 'in_progress'])
            ->count();

        // Pusher config — read from config(), never env() in Blade
        $pusherKey     = config('broadcasting.connections.pusher.key');
        $pusherCluster = config('broadcasting.connections.pusher.options.cluster');

        return view('consumer.dashboard', compact(
            'user', 'feeder', 'todayOutage', 'upcomingOutages',
            'outageHistory', 'notifications', 'latestBill', 'openTickets',
            'pusherKey', 'pusherCluster'
        ));
    }

    /**
     * Return current status JSON for fast real-time polling fallback.
     */
    public function statusJson()
    {
        $user   = Auth::guard('web')->user();
        $feeder = $user?->feeder;

        if (!$feeder) {
            return response()->json(['status' => 'Unknown', 'has_outage' => false]);
        }

        $todayOutage = OutageSchedule::where('feeder_id', $feeder->id)
            ->whereDate('start_time', today())
            ->whereIn('status', ['planned', 'active'])
            ->first();

        return response()->json([
            'feeder_id'       => $feeder->id,
            'substation_code' => $feeder->substation_code,
            'status'          => $feeder->status,
            'has_outage'      => ($todayOutage || $feeder->status === 'Outage'),
        ]);
    }
}
