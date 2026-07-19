<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feeder extends Model
{
    protected $fillable = [
        'feeder_name',
        'substation_code',
        'division',
        'district',
        'upazila',
        'max_capacity_mw',
        'current_demand_mw',
        'is_priority_zone',
        'status',
        'admin_id',
        'latitude',
        'longitude',
    ];

    protected function casts(): array
    {
        return [
            'is_priority_zone'   => 'boolean',
            'max_capacity_mw'    => 'float',
            'current_demand_mw'  => 'float',
        ];
    }

    /**
     * Consumers linked to this feeder.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'feeder_id');
    }

    /**
     * Admin responsible for this feeder.
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    /**
     * Outage schedules for this feeder.
     */
    public function outageSchedules()
    {
        return $this->hasMany(OutageSchedule::class, 'feeder_id');
    }

    /**
     * Tickets raised against this feeder.
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'feeder_id');
    }

    /**
     * Load percentage helper.
     */
    public function getLoadPercentAttribute(): float
    {
        if ($this->max_capacity_mw <= 0) return 0;
        return round(($this->current_demand_mw / $this->max_capacity_mw) * 100, 1);
    }

    /**
     * Stress index from 0..1 used by animated map overlays.
     */
    public function getStressIndexAttribute(): float
    {
        if ($this->status === 'Outage') {
            return 1.0;
        }

        if ($this->max_capacity_mw <= 0) {
            return 0;
        }

        $ratio = $this->current_demand_mw / $this->max_capacity_mw;
        return round(min(1, max(0, $ratio)), 3);
    }

    /**
     * Status badge color helper for blade views.
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'Active'      => 'success',
            'Outage'      => 'danger',
            'Maintenance' => 'warning',
            default       => 'secondary',
        };
    }

    /**
     * Telemetry CSS class for the status dot in the dashboard table.
     */
    public function getTelemetryClassAttribute(): string
    {
        return match($this->status) {
            'Active'      => 'telemetry-active',
            'Outage'      => 'telemetry-down',
            'Maintenance' => 'telemetry-shedding',
            default       => '',
        };
    }
}
