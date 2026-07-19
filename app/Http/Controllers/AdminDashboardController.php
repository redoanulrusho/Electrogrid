<?php

namespace App\Http\Controllers;

use App\Models\Feeder;
use App\Models\Ticket;
use App\Models\User;
use App\Services\FeederInsightService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    /**
     * Main admin dashboard — passes live DB stats, feeders, and map config to the view.
     */
    public function index(FeederInsightService $insights)
    {
        $feeders = Feeder::with('users')->withCount('tickets')->orderBy('substation_code')->get();

        $stats = [
            'feeder_count'    => $feeders->count(),
            'active_feeders'  => $feeders->where('status', 'Active')->count(),
            'outage_feeders'  => $feeders->where('status', 'Outage')->count(),
            'consumer_count'  => User::count(),
            'ticket_count'    => Ticket::where('status', '!=', 'resolved')->count(),
            'open_tickets'    => Ticket::where('status', 'open')->count(),
            'total_capacity'  => $feeders->sum('max_capacity_mw'),
            'total_demand'    => $feeders->sum('current_demand_mw'),
        ];

        // MW deficit = demand compared to max capacity
        $stats['mw_deficit'] = round(
            ($stats['total_capacity'] > 0
                ? ($stats['total_demand'] / $stats['total_capacity']) * 100
                : 0),
            1
        );

        // Map config — all read from config(), never env() in Blade
        $mapboxToken   = config('services.mapbox.token');
        $pusherKey     = config('broadcasting.connections.pusher.key');
        $pusherCluster = config('broadcasting.connections.pusher.options.cluster');

        $riskByFeeder = $feeders->mapWithKeys(function (Feeder $feeder) use ($insights) {
            return [$feeder->id => $insights->predictBlackoutRisk($feeder)];
        });

        return view('admin.dashboard', compact(
            'feeders', 'stats',
            'mapboxToken', 'pusherKey', 'pusherCluster', 'riskByFeeder'
        ));
    }

    /**
     * Return feeder locations with complaint counts as JSON for the Mapbox heatmap layer.
     * Only returns feeders that have lat/lng set.
     */
    public function complaintsGeoJson()
    {
        $data = Feeder::withCount('tickets')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get(['id', 'feeder_name', 'substation_code', 'latitude', 'longitude', 'tickets_count']);

        return response()->json($data);
    }
}
