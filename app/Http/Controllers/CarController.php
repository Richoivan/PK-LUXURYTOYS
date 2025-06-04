<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\CarType;
use App\Models\Manufacturer;
use Illuminate\Http\Request;
use Storage;

class CarController extends Controller
{
    //
    public function detail()
    {
        return view('user.detail');
    }
    public function create()
    {
        $manufacturers = Manufacturer::all();
        $carTypes = CarType::all();

        return view('admin.add-car', compact('manufacturers', 'carTypes'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string|max:1000',
            'car_type_id' => 'required|exists:car_types,id',
            'manufacturer_id' => 'required|exists:manufacturers,id',
            'year' => 'required|integer|min:1886|max:2025',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'color' => 'required|string|max:50',
            'mileage' => 'required|numeric|min:0',
            'transmission' => 'required|string',
            'fuel_type' => 'required|string|in:petrol,diesel,electric,hybrid',
            'engine_size' => 'required|numeric|min:0',
        ]);

        $data = $request->all();
        $data['status'] = 'available';

        if ($request->hasFile('image')) {
            $imageName = $request->file('image')->store('cars', 'public');
            $data['image'] = $imageName;
        }

        Car::create($data);

        return redirect()->route('admin.dashboard')->with('success', 'Car created successfully.');
    }
    public function edit($id)
    {
        $car = Car::findOrFail($id);
        $manufacturers = Manufacturer::all();
        $carTypes = CarType::all();

        return view('admin.edit-car', compact('car', 'manufacturers', 'carTypes'));
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'description' => 'required|string|max:1000',
        'car_type_id' => 'required|exists:car_types,id',
        'manufacturer_id' => 'required|exists:manufacturers,id',
        'year' => 'required|integer|min:1886|max:2025',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'color' => 'required|string|max:50',
        'status' => 'required|string|in:available,sold,reserved,maintenance',
        'mileage' => 'required|numeric|min:0',
        'transmission' => 'required|string',
        'fuel_type' => 'required|string|in:petrol,diesel,electric,hybrid',
        'engine_size' => 'required|numeric|min:0',
    ]);

    $car = Car::findOrFail($id);
    $data = $request->all();

    
    if ($request->hasFile('image')) {
       
        if ($car->image && Storage::disk('public')->exists($car->image)) {
            Storage::disk('public')->delete($car->image);
        }
        
        $imageName = $request->file('image')->store('cars', 'public');
        $data['image'] = $imageName;
    }

    $car->update($data);

    return redirect()->route('admin.dashboard')->with('success', 'Car updated successfully.');
}
    public function destroy($id)
    {
        $car = Car::findOrFail($id);

        if ($car->status === 'sold') {
            return redirect()->route('admin.dashboard')->with('error', 'Cannot delete a sold car.');
        }
        
        if ($car->status === 'reserved') {
            return redirect()->route('admin.dashboard')->with('error', 'Cannot delete a reserved car.');
        }

        if ($car->image && Storage::disk('public')->exists($car->image)) {
            Storage::disk('public')->delete($car->image);
        }

        $car->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Car deleted successfully.');
    }
    public function show($id)
    {
        $car = Car::with(['manufacturer', 'cartype'])->findOrFail($id);
        return view('admin.detail', compact('car'));
    }
}
