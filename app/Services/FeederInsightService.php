<?php

namespace App\Services;

use App\Models\Feeder;
use App\Models\OutageSchedule;
use App\Models\Ticket;

class FeederInsightService
{
    /**
     * Predict blackout risk for a feeder based on cutoff/maintenance instances.
     * Risk starts at 0. Each cutoff / maintenance adds +10 to risk score.
     * Thresholds: < 30 = Low | 30 - 49 = Medium | >= 50 = High
     */
    public function predictBlackoutRisk(Feeder $feeder): array
    {
        // Count cutoffs/outages logged for this feeder in the last 7 days (1 week)
        $cutoffCount7Days = OutageSchedule::where('feeder_id', $feeder->id)
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        // Include current non-active status if not already captured
        if (in_array($feeder->status, ['Outage', 'Maintenance']) && $cutoffCount7Days === 0) {
            $cutoffCount7Days = 1;
        }

        // Each cutoff in the 7-day window adds +10 to the risk score (starts at 0)
        // Score remains elevated after restoration and gradually expires after 1 week.
        $totalScore = (int) min(100, max(0, $cutoffCount7Days * 10));

        // Thresholds: < 30 = Low | 30 - 49 = Medium | >= 50 = High
        $level = match (true) {
            $totalScore >= 50 => 'High',
            $totalScore >= 30 => 'Medium',
            default           => 'Low',
        };

        $openTickets = Ticket::where('feeder_id', $feeder->id)
            ->whereIn('status', ['open', 'in_progress'])
            ->count();

        $loadRatio = $feeder->max_capacity_mw > 0
            ? min(1, max(0, $feeder->current_demand_mw / $feeder->max_capacity_mw))
            : 0;

        $confidence = (int) round(min(98, 80 + min($cutoffCount7Days * 2, 15)));

        return [
            'score'         => $totalScore,
            'level'         => $level,
            'confidence'    => $confidence,
            'cutoff_count'  => $cutoffCount7Days,
            'load_ratio'    => round($loadRatio * 100, 1),
            'open_tickets'  => $openTickets,
        ];
    }
}
