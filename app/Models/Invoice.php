<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'invoice_number',
        'invoice_date',
        'invoice_duedate',
        'taxes_for_line_item',
        'invoice_subtotal',
        'invoice_total',
        'tax1_label',
        'tax1_value',
        'tax2_label',
        'tax2_value',
        'round_off',
        'discount_value',
        'discount_type',
        'paid_to_date',
        'balance_due',
        'client_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'invoice_date' => 'timestamp',
        'invoice_duedate' => 'timestamp',
        'taxes_for_line_item' => 'boolean',
        'invoice_subtotal' => 'decimal:2',
        'tax1_value' => 'decimal:2',
        'tax2_value' => 'decimal:2',
        'round_off' => 'decimal:2',
        'discount_value' => 'decimal:2',
        'invoice_total' => 'decimal:2',
        'paid_to_date' => 'decimal:2',
        'balance_due' => 'decimal:2',
        'client_id' => 'integer',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    // public function products(): BelongsToMany
    // {
    //     return $this->belongsToMany(Product::class);
    // }

    public function invoiceProducts(): HasMany
    {
        return $this->hasMany(InvoiceProduct::class);
    }

    public function receipts(): BelongsToMany
    {
        return $this->belongsToMany(Receipt::class)
            ->using(Distribution::class)
            ->as('distribution')
            ->withPivot('id', 'invoice_id', 'receipt_id', 'amount_assigned', 'balance_amount', 'applied_date')
            ->withTimestamps();
    }
}
