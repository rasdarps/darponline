<?php

namespace App\Models;

use App\Models\CustomerAddress;
use App\Models\ShopOrder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Authenticatable
{
    use HasFactory, SoftDeletes, Notifiable, HasRoles, Auditable;

    protected $fillable = [
        'name',
        'mobile_no',
        'email',
        'password',
        'address',
        'first_name',
        'last_name',
        'username',
        'created_by',
        'updated_by',
        'deleted_by',
        'email_verified_at'

    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'status' => 'boolean',
        'email_verified_at' => 'datetime',

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

    // Business relationships
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function orders()
    {
        return $this->hasMany(ShopOrder::class, 'customer_id');
    }

    public function addresses()
    {
        return $this->hasMany(CustomerAddress::class);
    }

    public function defaultAddress()
    {
        return $this->hasOne(CustomerAddress::class)->where('is_default', true);
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function wishlist()
    {
        return $this->belongsToMany(Product::class, 'wishlists')
                    ->withTimestamps();
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function notifications()
    {
        return $this->hasMany(CustomerNotification::class);
    }

    public function coupons()
    {
        return $this->belongsToMany(Coupon::class, 'coupon_usages')
                    ->withPivot('order_id', 'discount_amount')
                    ->withTimestamps();
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getAvatarUrlAttribute()
    {
        return $this->avatar 
            ? asset('storage/' . $this->avatar)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->full_name);
    }

    // Scopes
    public function scopeOnlineCustomers($query)
    {
        return $query->where('is_online_customer', true);
    }

    public function scopeSubscribedToNewsletter($query)
    {
        return $query->where('newsletter_subscribed', true);
    }

    // Methods
    public function addLoyaltyPoints($points)
    {
        $this->increment('loyalty_points', $points);
    }

    public function redeemLoyaltyPoints($points)
    {
        if ($this->loyalty_points >= $points) {
            $this->decrement('loyalty_points', $points);
            return true;
        }
        return false;
    }

    public function updateOrderStats()
    {
        $this->total_orders = $this->orders()->count();
        $this->total_spent = $this->orders()->sum('total_amount');
        $this->save();
    }

}
