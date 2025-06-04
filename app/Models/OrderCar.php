<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderCar extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'order_id',
        'car_id',
        'car_name',
        'quantity',
        'price',
        'total_price',
    ];
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
