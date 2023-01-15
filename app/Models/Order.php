<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Order extends Model
{
    use HasFactory,HasApiTokens;
    protected $fillable = [
        'customerId',
        'address',
        'totalPrice',
    ];
    public function orderProduct()
    {
        return $this->belongsTo(Order::class, 'orderId');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customerId');
    }
}

