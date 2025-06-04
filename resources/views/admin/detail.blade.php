<!-- resources/views/admin/detail.blade.php -->
@extends('admin.layout')
@section('title', 'Car Details - Admin Panel')
@section('header', 'Car Details')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-0">{{ $car->name }}</h1>
            <p class="text-muted">Added on {{ $car->created_at->format('F j, Y') }} | ID: #CAR-{{ str_pad($car->id, 4, '0', STR_PAD_LEFT) }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('cars.edit', $car->id) }}" class="btn btn-outline-dark">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
            <form action="{{ route('cars.destroy', $car->id) }}" method="POST" class="d-inline" 
                  onsubmit="return confirm('Are you sure you want to delete this car?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">
                    <i class="bi bi-trash me-1"></i> Delete
                </button>
            </form>
        </div>
    </div>
    
    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-0">
                    @if($car->image)
                        <img src="{{ asset('storage/' . $car->image) }}" alt="{{ $car->name }}" class="img-fluid rounded">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center rounded" style="height: 300px;">
                            <span class="text-muted">No Image Available</span>
                        </div>
                    @endif
                </div>
            </div>
        
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Overview</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                                <tr>
                                    <th class="ps-4 bg-light" style="width: 40%">Manufacturer</th>
                                    <td class="ps-4">{{ $car->manufacturer->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-4 bg-light">Year</th>
                                    <td class="ps-4">{{ $car->year }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-4 bg-light">Type</th>
                                    <td class="ps-4">{{ $car->cartype->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-4 bg-light">Color</th>
                                    <td class="ps-4">{{ $car->color ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-4 bg-light">Status</th>
                                    <td class="ps-4">
                                        <span class="badge bg-{{ $car->status == 'available' ? 'success' : 'warning' }}">
                                            {{ ucfirst($car->status) }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Pricing</h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Current Price</p>
                            <h2 class="text-primary fw-bold mb-0">Rp {{ number_format($car->price, 0, ',', '.') }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Technical Specifications</h5>
                </div>
                <div class="card-body p-0">
                    <div class="row g-0 text-center">
                        <div class="col-6 col-md-3 border-end border-bottom p-3">
                            <div class="text-muted small">Engine Size</div>
                            <div class="fw-bold">{{ $car->engine_size ? $car->engine_size . 'L' : 'N/A' }}</div>
                        </div>
                        <div class="col-6 col-md-3 border-bottom border-md-end p-3">
                            <div class="text-muted small">Transmission</div>
                            <div class="fw-bold">{{ ucfirst($car->transmission ?? 'N/A') }}</div>
                        </div>
                        <div class="col-6 col-md-3 border-end border-bottom p-3">
                            <div class="text-muted small">Fuel Type</div>
                            <div class="fw-bold">{{ ucfirst($car->fuel_type ?? 'N/A') }}</div>
                        </div>
                        <div class="col-6 col-md-3 border-bottom p-3">
                            <div class="text-muted small">Mileage</div>
                            <div class="fw-bold">{{ $car->mileage ? number_format($car->mileage) . ' km' : 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Description</h5>
                </div>
                <div class="card-body">
                    <p>{{ $car->description ?? 'No description available.' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="mt-4">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-dark">
            <i class="bi bi-arrow-left me-2"></i>Back to Dashboard
        </a>
    </div>
</div>
@endsection