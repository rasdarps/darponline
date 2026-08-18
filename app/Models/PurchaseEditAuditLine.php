<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseEditAuditLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_edit_audit_id',
        'purchase_detail_id',
        'product_id',
        'product_name_snapshot',
        'quantity_from',
        'quantity_to',
        'unit_cost_from',
        'unit_cost_to',
        'line_amount_from',
        'line_amount_to',
        'quantity_delta',
        'amount_delta',
    ];

    protected $casts = [
        'quantity_from' => 'double',
        'quantity_to' => 'double',
        'quantity_delta' => 'double',
        'unit_cost_from' => 'decimal:2',
        'unit_cost_to' => 'decimal:2',
        'line_amount_from' => 'decimal:2',
        'line_amount_to' => 'decimal:2',
        'amount_delta' => 'decimal:2',
    ];

    public function audit()
    {
        return $this->belongsTo(PurchaseEditAudit::class, 'purchase_edit_audit_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
