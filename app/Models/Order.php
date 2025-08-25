<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'address',
        'phone',
        'total_price',
        'status',
        'payment_method',
        'momo_order_id',
        'momo_request_id'
    ];

    // Quan hệ: 1 đơn hàng thuộc về 1 user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Quan hệ: 1 đơn hàng có nhiều order items
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
