<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExternalInvoice extends Model
{
    use HasFactory;
    protected $table = 'external_invoices';
    protected $fillable = [
        'stud_id',
        'paid',
    ];
    public function medexternalinvoices()
    {
        return $this->hasMany(MedicalInvoice::class, 'external_invoice_id');
    }
    public function supexternalinvoices()
    {
        return $this->hasMany(SuppliesInvoice::class, 'external_invoice_id');
    }
    public function stud()
    {
        return $this->belongsTo(Stud::class, 'stud_id');
    }
    public function finance()
    {
        return $this->belongsTo(Financial::class, 'finance_id');
    }
}
