<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseEditAudit extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'purchase_no',
        'edited_by',
        'edited_at',
        'reason',
        'before_total',
        'after_total',
    ];

    protected $casts = [
        'edited_at' => 'datetime',
        'before_total' => 'decimal:2',
        'after_total' => 'decimal:2',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'edited_by');
    }

    public function lines()
    {
        return $this->hasMany(PurchaseEditAuditLine::class);
    }
}
