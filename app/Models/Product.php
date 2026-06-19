<?php

namespace App\Models;

use App\Models\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Gloudemans\Shoppingcart\Contracts\Buyable;

class Product extends Model implements HasMedia, Buyable
{
    use HasFactory, SoftDeletes,InteractsWithMedia;
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
    'created_by',
    'updated_by',
    'deleted_by',
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
   
];

protected $casts = [
    'quantity' => 'double',
    'stock_level' => 'double', 
    'status' => 'boolean',
    'created_by' => 'integer',
    'updated_by' => 'integer',
    'deleted_by' => 'integer',
    'cost_price' => 'decimal:2',
    'selling_price' => 'decimal:2',
    'weight' => 'decimal:2',
    'is_featured' => 'boolean',
    'is_on_sale' => 'boolean',
    'is_new' => 'boolean',
    'is_online' => 'boolean',
    'tags' => 'array',
];


public function registerMediaConversions(?Media $media = null): void
{
        $this->addMediaConversion('thumb')
        ->format('jpg')
          ->width(300)
          ->height(300)
          ->sharpen(10);

        $this->addMediaConversion('uniform')
        ->format('jpg')
        ->width(300)
        ->height(300)
        ->sharpen(7)
        ->fit(Fit::Stretch, 300, 300)
        ->withResponsiveImages();
}
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
    public function getBuyableIdentifier($options = null) {
        return $this->id;
    }

    public function getBuyableDescription($options = null) {
        return $this->name;
    }

    public function getBuyablePrice($options = null) {
        return $this->cost_price;
    }
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

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    
    public function invoicedetails()
    {
        return $this->hasMany(InvoiceDetail::class, 'product_id', 'id');
    }

    public function scopeNewArrivals($query)
    {
        return $query->where('is_new', true);
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
