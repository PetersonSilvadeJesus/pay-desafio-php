<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    protected $fillable = [
        'postal_code',
        'address_number',
        'address_complement',
        'user_id',
        'phone'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
