<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FundDisbursal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'given_by_user_id',
        'amount',
        'disbursal_date',
        'purpose',
        'payment_method',
        'reference_no',
        'notes',
        'approved_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'disbursal_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function givenBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'given_by_user_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
