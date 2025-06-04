@extends('user.header')
@section('title', 'Wishlist - Luxury Toys')

@section('content')
<div class="container py-5">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($wishlists->count() > 0)
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h4 class="mb-0 fw-bold">Your Wishlist ({{ $wishlists->count() }})</h4>
        </div>
        <div class="card-body p-0">
            @foreach($wishlists as $wishlist)
            <!-- Wishlist Item -->
            <div class="{{ !$loop->last ? 'border-bottom' : '' }} p-3 p-md-4">
                <div class="row align-items-center">
                    <!-- Product Image & Info -->
                    <div class="col-md-6 mb-3 mb-md-0">
                        <div class="d-flex align-items-center">
                            @if($wishlist->car->image)
                                <img src="{{ asset('storage/' . $wishlist->car->image) }}" 
                                     alt="{{ $wishlist->car->name }}" class="rounded me-3" 
                                     width="120" height="80" style="object-fit: cover;">
                            @else
                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                     style="width: 120px; height: 80px;">
                                    <i class="bi bi-image text-muted"></i>
                                </div>
                            @endif
                            <div>
                                <h5 class="mb-1">{{ $wishlist->car->name }}</h5>
                                <p class="text-muted mb-0 small">{{ $wishlist->car->color }} | {{ $wishlist->car->year }}</p>
                                <small class="text-muted">{{ $wishlist->car->manufacturer->name ?? 'Unknown Brand' }}</small>
                            </div>
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="col-md-3 mb-3 mb-md-0 text-center text-md-end">
                        <h5 class="fw-bold mb-0">Rp {{ number_format($wishlist->car->price, 0, ',', '.') }}</h5>
                        
                    </div>
                    
                    <!-- Actions -->
                    <div class="col-md-3 text-center text-md-end">
                        <div class="d-flex justify-content-end gap-2">
                           

                            <!-- Remove from Wishlist -->
                            <form action="{{ route('wishlist.destroy', $wishlist->id) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Remove {{ $wishlist->car->name }} from wishlist?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger rounded-circle p-2" title="Remove item">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="card-footer bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('user.home') }}" class="text-decoration-none">
                    <i class="bi bi-arrow-left me-1"></i> Continue Shopping
                </a>
                <a href="{{ route('user.catalog') }}" class="btn btn-outline-dark rounded-pill px-4">
                    Explore More
                </a>
            </div>
        </div>
    </div>
    @else
    <!-- Empty Wishlist -->
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="bi bi-heart text-muted" style="font-size: 3rem;"></i>
            <h5 class="mt-3 mb-2">Your wishlist is empty</h5>
            <p class="text-muted mb-4">Browse our collection and add your favorite cars to your wishlist.</p>
            <a href="{{ route('user.catalog') }}" class="btn btn-dark rounded-pill px-4 py-2">
                Explore Collection
            </a>
        </div>
    </div>
    @endif
</div>
@endsection