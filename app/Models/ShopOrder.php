<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number', 'customer_id', 'billing_address_id', 'shipping_address_id',
        'status', 'payment_status', 'shipping_status', 'payment_method',
        'payment_transaction_id', 'shipping_method', 'subtotal', 'discount_amount',
        'coupon_code', 'shipping_cost', 'tax_amount', 'total_amount',
        'notes', 'admin_notes', 'paid_at', 'shipped_at', 'delivered_at', 'cancelled_at'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];


       // Status constants
       const STATUS_PENDING = 'pending';
       const STATUS_PROCESSING = 'processing';
       const STATUS_CONFIRMED = 'confirmed';
       const STATUS_SHIPPED = 'shipped';
       const STATUS_DELIVERED = 'delivered';
       const STATUS_CANCELLED = 'cancelled';
       const STATUS_REFUNDED = 'refunded';
       const STATUS_FAILED = 'failed';
       const STATUS_PAID = 'paid';

       const PAYMENT_PENDING = 'pending';
       const PAYMENT_PAID = 'paid';
       const PAYMENT_FAILED = 'failed';
       const PAYMENT_REFUNDED = 'refunded';

        // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function billingAddress()
    {
        return $this->belongsTo(CustomerAddress::class, 'billing_address_id');
    }

    public function shippingAddress()
    {
        return $this->belongsTo(CustomerAddress::class, 'shipping_address_id');
    }

    public function items()
    {
        return $this->hasMany(ShopOrderItem::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', self::STATUS_PROCESSING);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_DELIVERED);
    }

    // Methods
    public function canBeCancelled()
    {
        return in_array($this->status, [
            self::STATUS_PENDING,
            self::STATUS_PROCESSING
        ]);
    }

    public function canBeRefunded()
    {
        return $this->payment_status === self::PAYMENT_PAID && 
               in_array($this->status, [self::STATUS_DELIVERED, self::STATUS_SHIPPED]);
    }

    public function updateStatus($status)
    {
        $this->status = $status;
        
        switch ($status) {
            case self::STATUS_PAID:
                $this->paid_at = now();
                $this->payment_status = self::PAYMENT_PAID;
                break;
            case self::STATUS_SHIPPED:
                $this->shipped_at = now();
                $this->shipping_status = 'shipped';
                break;
            case self::STATUS_DELIVERED:
                $this->delivered_at = now();
                $this->shipping_status = 'delivered';
                break;
            case self::STATUS_CANCELLED:
                $this->cancelled_at = now();
                break;
        }
        
        $this->save();
        
        // Dispatch notification
        // event(new OrderStatusChanged($this));
    }
}
