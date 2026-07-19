<?php

namespace App\Console\Commands;

use App\Events\FeederStatusChanged;
use App\Models\Feeder;
use App\Models\GridNotification;
use App\Models\OutageSchedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ProcessOutageSchedules extends Command
{
    /**
     * The name and signature of the console command.
     * Run via: php artisan outage:process
     * Registered in scheduler: runs every minute.
     */
    protected $signature = 'outage:process';
    protected $description = 'Process rotational outage schedules — auto flip feeder status based on start/end times';

    public function handle(): void
    {
        $now = Carbon::now();

        // ── 1. Activate planned schedules whose start_time has passed ──────────
        $toActivate = OutageSchedule::where('status', 'planned')
            ->where('start_time', '<=', $now)
            ->where('end_time', '>', $now)
            ->with('feeder')
            ->get();

        foreach ($toActivate as $schedule) {
            $feeder = $schedule->feeder;
            if (!$feeder) continue;

            // Update feeder status to Outage
            $feeder->update(['status' => 'Outage', 'current_demand_mw' => 0]);

            // Update schedule status to active
            $schedule->update(['status' => 'active']);

            // Notify consumers via in-app notification
            $this->notifyConsumers(
                $feeder,
                "⚡ Load Shedding Started: Your feeder ({$feeder->substation_code}) is now offline as per scheduled rotation. Expected restore: " .
                Carbon::parse($schedule->end_time)->format('h:i A')
            );

            // Broadcast real-time feeder status change
            try {
                FeederStatusChanged::dispatch($feeder->fresh());
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning("Pusher broadcast failed: " . $e->getMessage());
            }

            $this->info("Activated outage for {$feeder->substation_code}");
        }

        // ── 2. Complete active schedules whose end_time has passed ────────────
        $toComplete = OutageSchedule::where('status', 'active')
            ->where('end_time', '<=', $now)
            ->with('feeder')
            ->get();

        foreach ($toComplete as $schedule) {
            $feeder = $schedule->feeder;
            if (!$feeder) continue;

            // Restore feeder to Active
            $feeder->update(['status' => 'Active']);

            // Mark schedule as completed
            $schedule->update(['status' => 'completed']);

            // Notify consumers of restoration
            $this->notifyConsumers(
                $feeder,
                "✅ Power Restored: Your feeder ({$feeder->substation_code} — {$feeder->feeder_name}) relay is back online."
            );

            // Broadcast real-time feeder status change
            try {
                FeederStatusChanged::dispatch($feeder->fresh());
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning("Pusher broadcast failed: " . $e->getMessage());
            }

            $this->info("Completed outage for {$feeder->substation_code}");
        }

        $this->info('Outage schedule processing done at ' . $now->toDateTimeString());
    }

    /**
     * Send in-app notifications to all consumers on a feeder.
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
