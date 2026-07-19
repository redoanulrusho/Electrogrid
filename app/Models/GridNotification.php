<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GridNotification extends Model
{
    protected $table = 'grid_notifications';

    protected $fillable = [
        'user_id',
        'message',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function markAsRead(): void
    {
        $this->update(['read_at' => now()]);
    }
}
