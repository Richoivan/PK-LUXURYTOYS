<?php

namespace Database\Seeders;

use App\Models\CarType;
use Illuminate\Database\Seeder;

class CarTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CarType::create([
            'id' => 1,
            'name' => 'Convertible',
            'logo' => 'car-types/convertible.png',
        ]);

        CarType::create([
            'id' => 2,
            'name' => 'Coupe',
            'logo' => 'car-types/coupe.png',
        ]);

        CarType::create([
            'id' => 3,
            'name' => 'EV',
            'logo' => 'car-types/ev.png',
        ]);

        CarType::create([
            'id' => 4,
            'name' => 'Hatchback',
            'logo' => 'car-types/hatchback.png',
        ]);

        CarType::create([
            'id' => 5,
            'name' => 'Luxury',
            'logo' => 'car-types/luxury.png',
        ]);

        CarType::create([
            'id' => 6,
            'name' => 'Sedan',
            'logo' => 'car-types/sedan.png',
        ]);

        CarType::create([
            'id' => 7,
            'name' => 'Sports',
            'logo' => 'car-types/sports.png',
        ]);

        CarType::create([
            'id' => 8,
            'name' => 'Supercar',
            'logo' => 'car-types/supercar.png',
        ]);

        CarType::create([
            'id' => 9,
            'name' => 'SUV',
            'logo' => 'car-types/suv.png',
        ]);

        CarType::create([
            'id' => 10,
            'name' => 'Wagon',
            'logo' => 'car-types/wagon.png',
        ]);
    }
}
