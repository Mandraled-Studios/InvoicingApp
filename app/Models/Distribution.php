<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distribution extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'invoice_id',
        'receipt_id',
        'amount_assigned',
        'balance_amount',
        'applied_date',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'invoice_id' => 'integer',
        'receipt_id' => 'integer',
        'amount_assigned' => 'decimal:2',
        'balance_amount' => 'decimal:2',
        'applied_date' => 'timestamp',
    ];
}
