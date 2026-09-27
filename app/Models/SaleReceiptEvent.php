<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleReceiptEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'operator_id',
        'event_type',
        'reason',
        'event_at',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'event_at' => 'datetime',
        'sale_id' => 'integer',
        'operator_id' => 'integer',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }
}