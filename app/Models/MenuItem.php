<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $primaryKey = 'item_id'; // Gunakan 'item_id' sebagai primary key

    protected $fillable = [
        'item_name',
        'item_price',
        'item_description',
        'item_label',
        'item_type',
        'image_url',
    ];
}
