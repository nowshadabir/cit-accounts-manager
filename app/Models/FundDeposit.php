<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FundDeposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'contributor_name',
        'amount',
        'deposit_date',
        'payment_method',
        'reference_no',
        'description',
        'document_path',
        'document_url',
        'document_filename',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'deposit_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
