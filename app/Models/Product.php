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
        'barcode',
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

    //for generating barcode automatically for products
    public static function generateBarcode(string $name, int $id, $createdAt = null): string
    {
        $createdAt = $createdAt ?: now();
        $yearSuffix = $createdAt->format('y');

        $cleanName = trim(preg_replace('/[^A-Za-z0-9\s]/u', '', $name));
        $parts = preg_split('/\s+/', $cleanName, -1, PREG_SPLIT_NO_EMPTY);

        if (count($parts) >= 2) {
            $prefix = mb_substr($parts[0], 0, 1) . mb_substr($parts[1], 0, 1);
        } elseif (count($parts) === 1) {
            $prefix = mb_substr($parts[0], 0, 2);
        } else {
            $prefix = 'PR';
        }

        $prefix = strtoupper($prefix);

        return $prefix . $id . $yearSuffix;
    }

   




}
