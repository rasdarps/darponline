<?php

namespace App\Models;

use App\Models\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    use Auditable;
    // protected $guarded = [];
    protected $fillable = [
    'name',
    'unit_id',
    'category_id',
    'quantity',
    'stock_level',
    'created_by',
    'updated_by',
    'deleted_by',
];

protected $casts = [
    'quantity' => 'double',
    'stock_level' => 'double', 
    'status' => 'boolean',
    'created_by' => 'integer',
    'updated_by' => 'integer',
    'deleted_by' => 'integer',
];

    /*public function supplier(){
        return $this->belongsTo(Supplier::class,'supplier_id','id');
    }*/
 
     public function unit(){
        return $this->belongsTo(Unit::class,'unit_id','id');
    }

     public function category(){
        return $this->belongsTo(Category::class,'category_id','id');
    }


    public function purchasedetails()
    {
        return $this->hasMany(PurchaseDetail::class);
    }

    public function invoicedetails()
    {
        return $this->hasMany(InvoiceDetail::class);
    }

    // User relationships - NOW PROPERLY LINKED
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

   




}
