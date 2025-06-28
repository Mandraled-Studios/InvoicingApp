<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Receipt extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'receipt_number',
        'payment_amount',
        'assignment',
        'assigned_amount',
        'receipt_date',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'payment_amount' => 'decimal:2',
        'assignment' => 'array',
        'assigned_amount' => 'decimal:2',
        'receipt_date' => 'timestamp',
    ];

    public function invoices(): BelongsToMany
    {
        return $this->belongsToMany(Invoice::class)
            ->using(Distribution::class)
            ->as('distribution')
            ->withPivot('id', 'invoice_id', 'receipt_id', 'amount_assigned', 'balance_amount', 'applied_date')
            ->withTimestamps();
    }
}
