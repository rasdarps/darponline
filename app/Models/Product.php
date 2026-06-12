<?php

namespace App\Models;

use App\Models\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    use Auditable;
    // protected $guarded = [];
    protected $fillable = [
    'name',
    'slug',
    'unit_id',
    'category_id',
    'quantity',
    'stock_level',
    'barcode',
    'description',
    'video_url',
    'price',
    'weight',
    'length',
    'width',
    'height',
    'brand_id',
    'unit_id',
    'min_order_quantity',
    'max_order_quantity',
    'is_featured',
    'is_on_sale',
    'is_new',
    'is_online',
    'tags',
    'seo_title',
    'seo_description',
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
    'cost_price' => 'decimal:2',
    'weight' => 'decimal:2',
    'is_featured' => 'boolean',
    'is_on_sale' => 'boolean',
    'is_new' => 'boolean',
    'is_online' => 'boolean',
    'tags' => 'array',
];

    /*public function supplier(){
        return $this->belongsTo(Supplier::class,'supplier_id','id');
    }*/

       // Boot method to auto-generate slug and SKU
       protected static function boot()
       {
           parent::boot();
           
           static::creating(function ($product) {
               if (empty($product->slug)) {
                   $product->slug = Str::slug($product->name);
               }
               if (empty($product->sku)) {
                   $product->sku = self::generateSKU($product->name);
               }
           });
           
           static::updating(function ($product) {
               if ($product->isDirty('name')) {
                   $product->slug = Str::slug($product->name);
               }
           });
       }

    // unique SKU generator
    public static function generateSKU($name)
    {
        $prefix = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $name), 0, 3));
        $random = strtoupper(Str::random(5));
        $sku = $prefix . '-' . $random;
        
        // Ensure uniqueness
        while (self::where('sku', $sku)->exists()) {
            $random = strtoupper(Str::random(5));
            $sku = $prefix . '-' . $random;
        }
        
        return $sku;
    }
 
     public function unit(){
        return $this->belongsTo(Unit::class);
    }

     public function category(){
        return $this->belongsTo(Category::class);
    }


    public function purchasedetails()
    {
        return $this->hasMany(PurchaseDetail::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    
    public function invoicedetails()
    {
        return $this->hasMany(InvoiceDetail::class);
    }

    public function scopeNewArrivals($query)
    {
        return $query->where('is_new', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('quantity', '>', 0);
    }

    public function scopeActive($query)
    {
        return $query->where('is_online', true);
    }

    // User relationships - NOW PROPERLY LINKED
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeOnSale($query)
    {
        return $query->where('is_on_sale', true);
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
