<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\CarType;
use App\Models\Manufacturer;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class HomepageController extends Controller
{
 
    public function home()
    {
        $expensiveVehicles = Car::with(['manufacturer', 'carType'])
            ->where('status', 'available')
            ->orderBy('price', 'desc')
            ->take(4)
            ->get();

        $moreVehicles = Car::with(['manufacturer', 'carType'])
            ->where('status', 'available')->inRandomOrder()
            ->take(4)
            ->get();

        $manufacturers = Manufacturer::all();
        $carTypes = CarType::all();
        return view('user.home', compact('expensiveVehicles', 'moreVehicles', 'manufacturers', 'carTypes'));
    }

    public function catalog(Request $request)
    {
        $carQuery = Car::with(['manufacturer', 'carType'])->where('status', 'available')
            ->where('status', 'available');
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $carQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhereHas('manufacturer', function ($manufacturer) use ($search) {
                        $manufacturer->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('carType', function ($carType) use ($search) {
                        $carType->where('name', 'like', '%' . $search . '%');
                    });
            });
        }
        if ($request->has('manufacturer') && $request->manufacturer) {
            $carQuery->where('manufacturer_id', $request->manufacturer);
        }

        if ($request->has('car_type') && $request->car_type) {
            $carQuery->where('car_type_id', $request->car_type);
        }
        if ($request->has('mileage')) {
            if ($request->mileage == 'new') {
                $carQuery->where('mileage', '=', 0);
            } else if ($request->mileage == 'used') {
                $carQuery->where('mileage', '>', 0);
            }
        }

        $cars = $carQuery->orderBy('created_at', 'desc')->paginate(8);
        $manufacturers = Manufacturer::all();
        $carTypes = CarType::all();
        return view('user.catalog', compact('cars', 'manufacturers', 'carTypes'));
    }

    public function detail($id)
    {
        $car = Car::with(['manufacturer', 'carType'])->findOrFail($id);
        $isInWishlist = false;
        $wishlistItem = null;


        $wishlistItem = Wishlist::where('user_id', auth()->id())
            ->where('car_id', $car->id)
            ->first();
        $isInWishlist = !is_null($wishlistItem);


        return view('user.detail', compact('car', 'isInWishlist', 'wishlistItem'));
    }

}
