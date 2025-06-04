<?php

namespace Database\Seeders;

use App\Models\Manufacturer;
use Illuminate\Database\Seeder;

class ManufacturerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Manufacturer::create([
            'id' => 1,
            'name' => 'Audi',
            'logo' => 'brands/audi.png',
        ]);

        Manufacturer::create([
            'id' => 2,
            'name' => 'Bentley',
            'logo' => 'brands/bentley.png',
        ]);

        Manufacturer::create([
            'id' => 3,
            'name' => 'BMW',
            'logo' => 'brands/bmw.png',
        ]);

        Manufacturer::create([
            'id' => 4,
            'name' => 'Bugatti',
            'logo' => 'brands/bugatti.png',
        ]);

        Manufacturer::create([
            'id' => 5,
            'name' => 'Ferrari',
            'logo' => 'brands/ferrari.png',
        ]);

        Manufacturer::create([
            'id' => 6,
            'name' => 'Lamborghini',
            'logo' => 'brands/lamborghini.png',
        ]);

        Manufacturer::create([
            'id' => 7,
            'name' => 'Maserati',
            'logo' => 'brands/maserati.png',
        ]);

        Manufacturer::create([
            'id' => 8,
            'name' => 'Mercedes',
            'logo' => 'brands/mercedes.png',
        ]);

        Manufacturer::create([
            'id' => 9,
            'name' => 'Porsche',
            'logo' => 'brands/porsche.png',
        ]);

        Manufacturer::create([
            'id' => 10,
            'name' => 'Rolls-Royce',
            'logo' => 'brands/rolls-royce.png',
        ]);

        Manufacturer::create([
            'id' => 11,
            'name' => 'Mclaren',
            'logo' => 'brands/mclaren.png',
        ]);
    }
}
