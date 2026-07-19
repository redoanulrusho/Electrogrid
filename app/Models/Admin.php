<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'admins';

    protected $fillable = [
        'username',
        'email',
        'password',
        'designation',
        'assigned_zone',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Admin manages multiple feeders.
     */
    public function feeders()
    {
        return $this->hasMany(Feeder::class, 'admin_id');
    }

    /**
     * Admin creates outage schedules.
     */
    public function outageSchedules()
    {
        return $this->hasMany(OutageSchedule::class, 'created_by');
    }
}
