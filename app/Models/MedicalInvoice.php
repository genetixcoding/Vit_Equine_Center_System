<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalInvoice extends Model
{
    use HasFactory;
    protected $table = 'medical_invoices';
    protected $fillable = [
        'internal_invoice_id',
        'external_invoice_id',
        'item',
        'qty',
        'unit',
        'price',
    ] ;
    public function internalinvoice()
    {
        return $this->belongsTo(InternalInvoice::class, 'internal_invoice_id');
    }
    public function externalinvoice()
    {
        return $this->belongsTo(ExternalInvoice::class, 'external_invoice_id');
    }
}
