<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class OrderProduct extends Model
{
    use HasFactory,HasApiTokens;
    protected $fillable = [
        'orderId',
        'productId',
        'quantity',
        'price'
    ];
    public function product()
    {
        return $this->belongsTo(Product::class, 'productId');
    }
}
