<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutageSchedule extends Model
{
    protected $fillable = [
        'feeder_id',
        'created_by',
        'start_time',
        'end_time',
        'status',
        'reason',
    ];

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time'   => 'datetime',
        ];
    }

    public function feeder()
    {
        return $this->belongsTo(Feeder::class, 'feeder_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }
}
