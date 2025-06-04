<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
{
    //
    use HasFactory;
    //
    protected $fillable = [
        'id',
        'name',
        'logo',
        
    ];
    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
