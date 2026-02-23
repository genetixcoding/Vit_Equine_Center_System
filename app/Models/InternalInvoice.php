<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InternalInvoice extends Model
{
    use HasFactory;
    protected $table = 'internal_invoices';
    protected $fillable = [
        'supplier_id',
        'finance_id',
        'paid',
    ];
    public function medinternalinvoices(): HasMany
    {
        return $this->hasMany(MedicalInvoice::class, 'internal_invoice_id');
    }
    public function supinternalinvoices(): HasMany
    {
        return $this->hasMany(SuppliesInvoice::class, 'internal_invoice_id');
    }
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
    public function finance(): BelongsTo
    {
        return $this->belongsTo(Financial::class, 'finance_id');
    }
}
