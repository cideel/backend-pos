<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'total_price',
        'order_status',
        'payment_status',
        'order_date'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}