<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'price',
        'description',
        'car_type_id',
        'manufacturer_id',
        'year',
        'image',
        'status',
        'color',
        'mileage',
        'transmission',
        'fuel_type',
        'engine_size',

    ];
    public function cart()
    {
        return $this->hasMany(Cart::class);
    }
    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }
    public function carType()
    {
        return $this->belongsTo(CarType::class);
    }
    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class);
    }
}
