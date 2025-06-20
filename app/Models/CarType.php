<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarType extends Model
{
    use HasFactory;
    //
    protected $fillable = [
        'id',
        'name',
        'description',
        'image',
    ];
    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
