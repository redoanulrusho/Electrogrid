<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'meter_number',
        'consumer_class',
        'feeder_id',
        'division',
        'district',
        'upazila',
        'area',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /**
     * Consumer belongs to a distribution feeder.
     */
    public function feeder()
    {
        return $this->belongsTo(Feeder::class, 'feeder_id');
    }

    /**
     * Consumer's electricity bills.
     */
    public function bills()
    {
        return $this->hasMany(Bill::class, 'user_id');
    }

    /**
     * Consumer's complaint tickets.
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'user_id');
    }

    /**
     * Consumer's in-app notifications.
     */
    public function gridNotifications()
    {
        return $this->hasMany(GridNotification::class, 'user_id');
    }

    /**
     * Count of unread notifications.
     */
    public function getUnreadNotificationsCountAttribute(): int
    {
        return $this->gridNotifications()->whereNull('read_at')->count();
    }
}
