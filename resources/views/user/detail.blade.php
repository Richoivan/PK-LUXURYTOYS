@extends('user.header')

@section('title', 'Car Detail - Luxury Toys')

@section('content')
 @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 mb-4 mb-lg-0">
                <!-- Image -->
                <div class="position-relative mb-4">
                    @if($car->image)
                    <img src="{{ asset('storage/' . $car->image) }}" class="img-fluid rounded shadow-sm"
                        alt="{{ $car->name }}" style="width: 100%; height: 300px; object-fit: cover;">
                    @else
                    <div class="bg-light rounded shadow-sm d-flex align-items-center justify-content-center"
                        style="height: 400px;">
                        <i class="bi bi-image text-muted" style="font-size: 4rem;"></i>
                    </div>
                    @endif
                    <span class="badge bg-dark position-absolute bottom-0 start-0 m-3 p-2 fs-6">{{ $car->year }}</span>
                </div>

                <!-- Description -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h4 class="card-title mb-3">Description</h4>
                        <p class="text-muted">
                            {{ $car->description ?? 'No description available for this vehicle.' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Details -->
            <div class="col-lg-7">
                <!-- Car name & brand -->
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-2">
                        @if($car->manufacturer)
                        <img src="{{ asset('storage/' . $car->manufacturer->logo) }}"
                            alt="{{ $car->manufacturer->name }}" style="height: 50px;" class="me-2">
                        <span class="text-muted">{{ $car->manufacturer->name }}</span>
                        @endif
                    </div>
                    <h1 class="display-5 fw-bold mb-1">{{ $car->name }}</h1>
                    <p class="text-muted fs-5">{{ $car->year }}</p>
                </div>

                <!-- Car specifications -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-0">
                        <div class="row g-0 text-center">
                            <div class="col-6 col-md-4 border-end border-bottom p-3">
                                <div class="text-muted small">Mileage</div>
                                <div class="fw-bold">{{ number_format($car->mileage ?? 0) }} km</div>
                            </div>
                            <div class="col-6 col-md-4 border-end border-bottom p-3">
                                <div class="text-muted small">Transmission</div>
                                <div class="fw-bold">{{ ucfirst($car->transmission ?? 'N/A') }}</div>
                            </div>
                            <div class="col-6 col-md-4 border-bottom p-3">
                                <div class="text-muted small">Color</div>
                                <div class="fw-bold">{{ $car->color ?? 'N/A' }}</div>
                            </div>
                            <div class="col-6 col-md-4 border-end p-3">
                                <div class="text-muted small">Type</div>
                                <div class="fw-bold">{{ $car->cartype->name ?? 'N/A' }}</div>
                            </div>
                            <div class="col-6 col-md-4 border-end p-3">
                                <div class="text-muted small">Fuel</div>
                                <div class="fw-bold">{{ ucfirst($car->fuel_type ?? 'N/A') }}</div>
                            </div>
                            <div class="col-6 col-md-4 p-3">
                                <div class="text-muted small">Engine</div>
                                <div class="fw-bold">{{ $car->engine_size ? $car->engine_size . 'L' : 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Price -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h4 class="card-title mb-3">Price</h4>
                        <div class="d-flex align-items-center">
                            <span class="fs-2 fw-bold text-muted">Rp{{ number_format($car->price, 0, ',', '.')
                                }}</span>
                        </div>
                    </div>
                </div>

                @if($car->status == 'available')
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h4 class="card-title mb-3">Actions</h4>
                        <div class="d-flex gap-2">
                            <!-- Add to Cart Form -->
                            <form action="{{ route('cart.store') }}" method="POST" class="flex-grow-1">
                                @csrf
                                <input type="hidden" name="car_id" value="{{ $car->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill">
                                    <i class="fas fa-shopping-cart me-2"></i> Add to Cart
                                </button>
                            </form>

                            @if($isInWishlist)
                                <!-- Remove from Wishlist -->
                                <form action="{{ route('wishlist.destroy', $wishlistItem->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-lg rounded-pill px-3" title="Remove from Wishlist">
                                        <i class="fas fa-heart"></i>
                                    </button>
                                </form>
                            @else
                                <!-- Add to Wishlist -->
                                <form action="{{ route('wishlist.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="car_id" value="{{ $car->id }}">
                                    <button type="submit" class="btn btn-outline-danger btn-lg rounded-pill px-3" title="Add to Wishlist">
                                        <i class="far fa-heart"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                        
                        <!-- Wishlist Status Text -->
                        @if($isInWishlist)
                            <div class="mt-2">
                                <small class="text-danger">
                                    <i class="fas fa-heart me-1"></i>Already in your wishlist
                                </small>
                            </div>
                        @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection