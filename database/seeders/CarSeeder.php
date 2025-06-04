<?php

namespace Database\Seeders;

use App\Models\Car;
use Illuminate\Database\Seeder;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Car::create([
            'id' => 1,
            'name' => 'Audi RS 7',
            'description' => 'The Audi RS 7 is a high-performance luxury sportback that blends aggressive styling with refined comfort and cutting-edge technology.',
            'image' => 'cars/audi-rs7.jpg',
            'manufacturer_id' => 1,
            'car_type_id' => 7, // Sports
            'price' => 50000,
            'year' => 2023,
            'status' => 'available',
            'color' => 'black',
            'mileage' => 0,
            'transmission' => 'automatic',
            'fuel_type' => 'petrol',
            'engine_size' => '4.0L',
        ]);

        Car::create([
            'id' => 2,
            'name' => 'Bentley Continental',
            'description' => 'The Bentley Continental combines British craftsmanship with powerful performance in a luxurious grand touring coupe.',
            'image' => 'cars/bentley-continental.jpg',
            'manufacturer_id' => 2,
            'car_type_id' => 5, // Luxury
            'price' => 80000,
            'year' => 2023,
            'status' => 'available',
            'color' => 'navy',
            'mileage' => 0,
            'transmission' => 'automatic',
            'fuel_type' => 'petrol',
            'engine_size' => '6.0L',
        ]);

        Car::create([
            'id' => 3,
            'name' => 'BMW M8',
            'description' => 'The BMW M8 is a luxury performance coupe that offers exhilarating speed, precise handling, and state-of-the-art features.',
            'image' => 'cars/bmw-m8.jpg',
            'manufacturer_id' => 3,
            'car_type_id' => 2, // Coupe
            'price' => 70000,
            'year' => 2023,
            'status' => 'available',
            'color' => 'red',
            'mileage' => 0,
            'transmission' => 'automatic',
            'fuel_type' => 'petrol',
            'engine_size' => '4.4L',
        ]);

        Car::create([
            'id' => 4,
            'name' => 'Ferrari Roma',
            'description' => 'The Ferrari Roma is a sleek and stylish grand tourer that embodies timeless Italian elegance and dynamic performance.',
            'image' => 'cars/ferrari-roma.jpg',
            'manufacturer_id' => 5,
            'car_type_id' => 8, // Supercar
            'price' => 120000,
            'year' => 2023,
            'status' => 'available',
            'color' => 'silver',
            'mileage' => 0,
            'transmission' => 'automatic',
            'fuel_type' => 'petrol',
            'engine_size' => '3.9L',
        ]);

        Car::create([
            'id' => 5,
            'name' => 'Lamborghini Huracan',
            'description' => 'The Lamborghini Huracan is an exotic supercar with a bold design, roaring V10 engine, and exceptional driving dynamics.',
            'image' => 'cars/lamborghini-huracan.jpg',
            'manufacturer_id' => 6,
            'car_type_id' => 8, // Supercar
            'price' => 200000,
            'year' => 2023,
            'status' => 'available',
            'color' => 'yellow',
            'mileage' => 0,
            'transmission' => 'automatic',
            'fuel_type' => 'petrol',
            'engine_size' => '5.2L',
        ]);

        Car::create([
            'id' => 6,
            'name' => 'McLaren 570S',
            'description' => 'The McLaren 570S is a lightweight sports car designed for road and track, delivering thrilling speed with precision engineering.',
            'image' => 'cars/mclaren-570s.jpg',
            'manufacturer_id' => 11, 
            'car_type_id' => 8, // Supercar
            'price' => 180000,
            'year' => 2023,
            'status' => 'available',
            'color' => 'orange',
            'mileage' => 0,
            'transmission' => 'automatic',
            'fuel_type' => 'petrol',
            'engine_size' => '3.8L',
        ]);

        Car::create([
            'id' => 7,
            'name' => 'Mercedes AMG GT',
            'description' => 'The Mercedes AMG GT is a luxury sports car offering aggressive performance, premium comfort, and iconic AMG design.',
            'image' => 'cars/mercedes-amg-gt.png',
            'manufacturer_id' => 8,
            'car_type_id' => 7, // Sports
            'price' => 90000,
            'year' => 2023,
            'status' => 'available',
            'color' => 'gray',
            'mileage' => 0,
            'transmission' => 'automatic',
            'fuel_type' => 'petrol',
            'engine_size' => '4.0L',
        ]);

        Car::create([
            'id' => 8,
            'name' => 'Porsche 911',
            'description' => 'The Porsche 911 is a legendary sports car known for its iconic design, exceptional balance, and everyday usability.',
            'image' => 'cars/porsche-911.png',
            'manufacturer_id' => 9,
            'car_type_id' => 7, // Sports
            'price' => 100000,
            'year' => 2023,
            'status' => 'available',
            'color' => 'white',
            'mileage' => 0,
            'transmission' => 'automatic',
            'fuel_type' => 'petrol',
            'engine_size' => '3.0L',
        ]);
    }
}
