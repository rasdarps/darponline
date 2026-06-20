<?php

namespace App\Models;

use App\Models\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;
    use Auditable;
    
    protected $fillable = [
        'invoice_no',
        'date', 
        'description',
        'sales_source',
        'cancelled_at',
        'cancelled_by',
        'cancellation_reason',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'date' => 'date',
        'cancelled_at' => 'datetime',
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

    // Existing relationships
    public function payment()
    {
        return $this->hasOne(Payment::class, 'invoice_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

     public function invoice_details(){
        return $this->hasMany(InvoiceDetail::class,'invoice_id','id');
    }
}
