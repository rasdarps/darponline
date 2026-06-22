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
    'barcode',
    'created_by',
    'updated_by',
    'deleted_by',
    'price',
    'unit_id',
    'cost_price',
    'selling_price',
   
];

protected $casts = [
    'quantity' => 'double',
    'stock_level' => 'double', 
    'created_by' => 'integer',
    'updated_by' => 'integer',
    'deleted_by' => 'integer',
    'cost_price' => 'decimal:2',
    'selling_price' => 'decimal:2',
];
    /*public function supplier(){
        return $this->belongsTo(Supplier::class,'supplier_id','id');
    }*/

     public function unit(){
        return $this->belongsTo(Unit::class);
    }

     public function category(){
        return $this->belongsTo(Category::class);
    }


    public function purchasedetails()
    {
        return $this->hasMany(PurchaseDetail::class, 'product_id', 'id');
    }

    public function invoicedetails()
    {
        return $this->hasMany(SaleDetail::class, 'product_id', 'id');
    }

    public function scopeInStock($query)
    {
        return $query->where('quantity', '>', 0);
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('quantity', '<=', 'stock_level')
            ->where('quantity', '>', 0);
    }
    
    public function scopeOutOfStock($query)
    {
        return $query->where('quantity', '<=', 0);
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

     public function productPrice()
    {
        return $this->hasMany(ProductPrice::class, 'product_id', 'id');
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

    /**
     * Check if product is in stock
     */
    public function isInStock()
    {
        return $this->quantity > 0;
    }


      /**
     * Check if product is low on stock
     */
    public function isLowStock()
    {
        return $this->quantity <= $this->stock_level && $this->quantity > 0;
    }
    /**
     * Check if product is out of stock
     */
    public function isOutOfStock()
    {
        return $this->quantity <= 0;
    }

/**
     * Get stock status label
     */
    public function getStockStatusAttribute()
    {
        if ($this->isOutOfStock()) {
            return 'Out of Stock';
        } elseif ($this->isLowStock()) {
            return 'Low Stock';
        }
        return 'In Stock';
    }
    

    /**
     * Get stock status badge class
     */
    public function getStockBadgeClassAttribute()
    {
        if ($this->isOutOfStock()) {
            return 'danger';
        } elseif ($this->isLowStock()) {
            return 'warning';
        }
        return 'success';
    }
}

