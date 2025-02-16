<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'gateway_order_id',
        'status',
        'billing_type',
        'bank_slip_url',
        'user_id',
        'encoded_image_pix',
        'payload_code_pix',
        'expiration_date_pix',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeByUser(Builder $query)
    {
        return $query->where('user_id', auth()->user()->id);
    }
}
