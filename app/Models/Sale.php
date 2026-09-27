<?php

namespace App\Models;

use App\Models\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use HasFactory, SoftDeletes;
    use Auditable;
    
    protected $fillable = [
        'sale_no',
        'date', 
        'description',
        'delivery_code',
        'printed_at',
        'printed_by',
        'delivered_at',
        'delivered_by',
        'cancelled_at',
        'cancelled_by',
        'cancellation_reason',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'date' => 'date',
        'printed_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'printed_by' => 'integer',
        'delivered_by' => 'integer',
        'cancelled_by' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
    ];

    // User relationships
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function cancelledBy()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    public function printedBy()
    {
        return $this->belongsTo(User::class, 'printed_by');
    }

    public function deliveredBy()
    {
        return $this->belongsTo(User::class, 'delivered_by');
    }

    public function receiptEvents()
    {
        return $this->hasMany(SaleReceiptEvent::class, 'sale_id', 'id');
    }

    // Existing relationships
    public function payment()
    {
        return $this->hasOne(Payment::class, 'sale_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

     public function sale_details(){
        return $this->hasMany(SaleDetail::class,'sale_id','id');
    }
}

