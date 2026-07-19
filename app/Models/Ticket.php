<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'user_id',
        'feeder_id',
        'subject',
        'description',
        'status',
        'admin_response',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function feeder()
    {
        return $this->belongsTo(Feeder::class, 'feeder_id');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'open'        => 'danger',
            'in_progress' => 'warning',
            'resolved'    => 'success',
            default       => 'secondary',
        };
    }
}
