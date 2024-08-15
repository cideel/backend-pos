<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $primaryKey = 'customer_id';
    protected $fillable = ['name', 'phone_number', 'email', 'is_logged_in', 'points'];
    
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
