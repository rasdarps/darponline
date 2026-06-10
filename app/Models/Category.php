<?php

namespace App\Models;

use App\Models\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Category extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;
    use Auditable;

    protected $fillable = [
        'name',
        'slug',
        'status',
        'description',
        'created_by',
        'updated_by',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'is_active',
        'show_on_homepage',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'show_on_homepage' => 'boolean',
        'sort_order' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
    ];


    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
              ->width(368)
              ->height(232)
              ->sharpen(10);
    }

    // Boot method to auto-generate slug

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
        
        static::updating(function ($category) {
            if ($category->isDirty('name')) {
                $category->slug = Str::slug($category->name);
            }
        });
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

    // Products in this category
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function getProductCountAttribute()
    {
        return $this->products()->where('is_online', true)->count();
    }

     // Scopes
     public function scopeActive($query)
     {
         return $query->where('is_active', true);
     }
 
     public function scopeParentCategories($query)
     {
         return $query->whereNull('parent_id');
     }
 
     public function scopeHomepage($query)
     {
         return $query->where('show_on_homepage', true)
                      ->where('is_active', true)
                      ->orderBy('sort_order');
     }

     public function scopeOrdered($query)
     {
         return $query->orderBy('sort_order')->orderBy('name');
     }
 
   
}
