<?php

namespace App\Http\Controllers;

use App\Events\FeederStatusChanged;
use App\Models\Feeder;
use App\Models\GridNotification;
use App\Models\OutageSchedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeederController extends Controller
{
    /**
     * Change feeder status: Active | Outage | Maintenance
     * Called via POST from dashboard action buttons.
     */
    public function updateStatus(Request $request, int $id)
    {
        $request->validate([
            'status' => 'required|in:Active,Outage,Maintenance',
        ]);

        $feeder = Feeder::findOrFail($id);
        $oldStatus = $feeder->status;
        $feeder->update(['status' => $request->status]);

        // If changed to Outage or Maintenance — record cutoff event for 7-day memory rule & notify consumers
        if (in_array($request->status, ['Outage', 'Maintenance']) && !in_array($oldStatus, ['Outage', 'Maintenance'])) {
            $adminId = Auth::guard('admin')->id() ?? $feeder->admin_id ?? 1;

            OutageSchedule::create([
                'feeder_id'  => $feeder->id,
                'created_by' => $adminId,
                'start_time' => now(),
                'end_time'   => now()->addHours(2),
                'reason'     => "Emergency {$request->status} by Grid Operator",
                'status'     => 'active',
            ]);

            $this->notifyConsumers(
                $feeder,
                "⚡ Emergency {$request->status}: Your feeder ({$feeder->substation_code} — {$feeder->feeder_name}) has been force-disconnected by the grid operator."
            );
        }

        // If restored to Active — mark active outage schedules as completed & notify consumers
        if ($request->status === 'Active' && $oldStatus !== 'Active') {
            OutageSchedule::where('feeder_id', $feeder->id)
                ->where('status', 'active')
                ->update([
                    'status'   => 'completed',
                    'end_time' => now(),
                ]);

            $this->notifyConsumers(
                $feeder,
                "✅ Power Restored: Your feeder ({$feeder->substation_code} — {$feeder->feeder_name}) relay has been restored to Active."
            );
        }

        // Broadcast real-time status change to Pusher (safely caught if offline / cURL error)
        try {
            FeederStatusChanged::dispatch($feeder->fresh());
        } catch (\Throwable $e) {
            // Log or safely ignore network/cURL broadcast failures so DB update succeeds
            \Illuminate\Support\Facades\Log::warning("Pusher broadcast failed: " . $e->getMessage());
        }

        return back()->with('success', "Feeder {$feeder->substation_code} status updated to {$request->status}.");
    }

    /**
     * Return telemetry data as JSON for the Live Telemetry modal.
     */
    public function telemetry(int $id)
    {
        $feeder = Feeder::withCount('users')->findOrFail($id);
        $loadPercent = $feeder->max_capacity_mw > 0
            ? round(($feeder->current_demand_mw / $feeder->max_capacity_mw) * 100, 1)
            : 0;

        return response()->json([
            'id'               => $feeder->id,
            'feeder_name'      => $feeder->feeder_name,
            'substation_code'  => $feeder->substation_code,
            'max_capacity_mw'  => $feeder->max_capacity_mw,
            'current_demand_mw'=> $feeder->current_demand_mw,
            'load_percent'     => $loadPercent,
            'status'           => $feeder->status,
            'consumers'        => $feeder->users_count,
            'location'         => "{$feeder->division} / {$feeder->district} / {$feeder->upazila}",
        ]);
    }

    /**
     * Schedule an outage for a feeder.
     */
    public function scheduleOutage(Request $request, int $id)
    {
        $request->validate([
            'start_time' => 'required|date|after:now',
            'end_time'   => 'required|date|after:start_time',
            'reason'     => 'nullable|string|max:500',
        ]);

        $feeder = Feeder::findOrFail($id);
        $admin  = Auth::guard('admin')->user();

        $schedule = OutageSchedule::create([
            'feeder_id'  => $feeder->id,
            'created_by' => $admin->id,
            'start_time' => $request->start_time,
            'end_time'   => $request->end_time,
            'reason'     => $request->reason,
            'status'     => 'planned',
        ]);

        // Notify all consumers on this feeder about the planned outage
        $start = \Carbon\Carbon::parse($request->start_time)->format('d M Y, h:i A');
        $end   = \Carbon\Carbon::parse($request->end_time)->format('h:i A');
        $this->notifyConsumers(
            $feeder,
            "📅 Planned Outage: Your feeder ({$feeder->substation_code}) will be offline from {$start} to {$end}. Reason: " . ($request->reason ?? 'Scheduled maintenance.')
        );

        return back()->with('success', "Outage scheduled for {$feeder->substation_code} from {$start}.");
    }

    /**
     * Generate and download a CSV log report of all feeder statuses.
     */
    public function report()
    {
        $feeders = Feeder::withCount('users')->orderBy('substation_code')->get();
        $timestamp = now()->format('Y-m-d_His');

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"electrogrid_feeder_log_{$timestamp}.csv\"",
        ];

        $callback = function () use ($feeders, $timestamp) {
            $handle = fopen('php://output', 'w');

            // Report header
            fputcsv($handle, ["ElectroGrid Feeder Status Log — Generated: {$timestamp}"]);
            fputcsv($handle, []);
            fputcsv($handle, [
                'Substation Code', 'Feeder Name', 'Division', 'District', 'Upazila',
                'Max Capacity (MW)', 'Current Demand (MW)', 'Load %',
                'Priority Zone', 'Status', 'Linked Consumers',
            ]);

            foreach ($feeders as $feeder) {
                $loadPct = $feeder->max_capacity_mw > 0
                    ? round(($feeder->current_demand_mw / $feeder->max_capacity_mw) * 100, 1)
                    : 0;

                fputcsv($handle, [
                    $feeder->substation_code,
                    $feeder->feeder_name,
                    $feeder->division,
                    $feeder->district,
                    $feeder->upazila,
                    $feeder->max_capacity_mw,
                    $feeder->current_demand_mw,
                    $loadPct . '%',
                    $feeder->is_priority_zone ? 'Yes' : 'No',
                    $feeder->status,
                    $feeder->users_count,
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Notify all consumers on a feeder with an in-app notification.
     */
    private function notifyConsumers(Feeder $feeder, string $message): void
    {
        $users = User::where('feeder_id', $feeder->id)->get(['id']);
        $now   = now();

        $notifications = $users->map(fn($u) => [
            'user_id'    => $u->id,
            'message'    => $message,
            'created_at' => $now,
            'updated_at' => $now,
        ])->toArray();

        if (!empty($notifications)) {
            GridNotification::insert($notifications);
        }
    }
}
