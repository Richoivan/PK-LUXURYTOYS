<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'user_id',
        'order_number',
        'total_price',
        'shipping_cost',
        'grand_total',
        'status',
        'shipping_address',
        'customer_name',
        'payment_url',
    ];
     public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderCars()
    {
        return $this->hasMany(OrderCar::class);
    }


}
