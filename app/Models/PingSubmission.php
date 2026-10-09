<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PingSubmission extends Model
{
    protected $fillable = [
        'phone',
        'first_name',
        'last_name',
        'zip_code',
        'traffic_source',
        'status',
        'bid_amount',
        'bid_id',
        'transfer_number',
        'reject_reason',
        'response_payload',
    ];

    protected function casts(): array
    {
        return [
            'bid_amount' => 'decimal:2',
            'response_payload' => 'array',
        ];
    }

    public function getCustomerNameAttribute(): string
    {
        return trim(($this->first_name ?? '').' '.($this->last_name ?? '')) ?: '—';
    }
}
