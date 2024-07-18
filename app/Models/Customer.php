<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Model
{
    use HasFactory, HasApiTokens;
    protected $primaryKey = 'customer_id';
    protected $fillable = ['name', 'phone_number', 'email', 'is_logged_in', 'points'];
    
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
