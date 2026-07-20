<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    protected $fillable = [
        'user_id',
        'month',
        'units_consumed',
        'rate_applied',
        'amount',
        'paid_status',
        'due_date',
        'document_path',
    ];

    protected function casts(): array
    {
        return [
            'month'          => 'date',
            'due_date'       => 'date',
            'units_consumed' => 'float',
            'rate_applied'   => 'float',
            'amount'         => 'float',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Calculate amount based on consumer class rate formula.
     * Residential: BDT 6.00/unit
     * Commercial:  BDT 9.50/unit
     * Industrial:  Tiered — 0-500: 9.00 | 500-1000: 10.50 | >1000: 12.00
     */
    public static function calculateAmount(float $units, string $consumerClass): array
    {
        if ($consumerClass === 'Residential') {
            $rate = 6.00;
            $amount = $units * $rate;
        } elseif ($consumerClass === 'Commercial') {
            $rate = 9.50;
            $amount = $units * $rate;
        } else {
            // Industrial tiered
            if ($units <= 500) {
                $rate = 9.00;
                $amount = $units * $rate;
            } elseif ($units <= 1000) {
                $rate = 10.50;
                $amount = $units * $rate;
            } else {
                $rate = 12.00;
                $amount = $units * $rate;
            }
        }

        return ['rate' => $rate, 'amount' => round($amount, 2)];
    }
}
