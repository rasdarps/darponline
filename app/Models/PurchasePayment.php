<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Auditable;

class PurchasePayment extends Model
{
    use HasFactory;
    use Auditable;

    protected $guarded = [];

     public function supplier(){
        return $this->belongsTo(Supplier::class,'supplier_id','id');
    } 

     public function purchase(){
        return $this->belongsTo(Purchase::class,'purchase_id','id');
    }
}
