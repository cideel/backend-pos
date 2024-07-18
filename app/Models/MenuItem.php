<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    protected $primaryKey = 'item_id'; // Pastikan primary key adalah 'item_id'
    protected $fillable = ['item_name', 'item_price', 'item_description', 'item_label', 'item_type', 'image_url'];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'item_id');
    }
}
