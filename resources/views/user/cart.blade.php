@extends('user.header')
@section('title', 'Shopping Cart - Luxury Toys')

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

    <div class="row">
        <div class="col-lg-8 mb-4 mb-lg-0">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h4 class="mb-0 fw-bold">Your Cart ({{ $carts->count() }})</h4>
                </div>
                <div class="card-body p-0">
                    @forelse($carts as $cart)
                    <!-- Cart Item -->
                    <div class="{{ !$loop->last ? 'border-bottom' : '' }} p-3 p-md-4">
                        <div class="row align-items-center">
                            <!-- Product Image & Info -->
                            <div class="col-md-6 mb-3 mb-md-0">
                                <div class="d-flex align-items-center">
                                    @if($cart->car->image)
                                        <img src="{{ asset('storage/' . $cart->car->image) }}"
                                             alt="{{ $cart->car->name }}" class="rounded me-3" width="120" height="80"
                                             style="object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                             style="width: 120px; height: 80px;">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h5 class="mb-1">{{ $cart->car->name }}</h5>
                                        <p class="text-muted mb-0 small">{{ $cart->car->color }} | {{ $cart->car->year }}</p>
                                        <small class="text-muted">{{ $cart->car->manufacturer->name ?? 'Unknown Brand' }}</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Quantity Controls -->
                            <div class="col-md-2 mb-3 mb-md-0 text-center">
                                <form action="{{ route('cart.update', $cart->id) }}" method="POST" class="quantity-form">
                                    @csrf
                                    @method('PUT')
                                    <div class="d-flex align-items-center justify-content-center">
                                        <button type="button" class="btn btn-sm btn-outline-dark minus-btn">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                        <input type="number" name="quantity" class="form-control form-control-sm mx-2 text-center quantity-input" 
                                               value="{{ $cart->quantity }}" min="1" max="10" style="width: 45px;">
                                        <button type="button" class="btn btn-sm btn-outline-dark plus-btn">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Price -->
                            <div class="col-md-3 mb-3 mb-md-0 text-center text-md-end">
                                <h5 class="fw-bold mb-0">Rp {{ number_format($cart->car->price * $cart->quantity, 0, ',', '.') }}</h5>
                                <small class="text-muted">{{ $cart->quantity }} x Rp {{ number_format($cart->car->price, 0, ',', '.') }}</small>
                            </div>

                            <!-- Remove Button -->
                            <div class="col-md-1 text-end">
                                <form action="{{ route('cart.destroy', $cart->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Remove {{ $cart->car->name }} from cart?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn rounded-circle p-2" title="Remove item">
                                        <i class="fa fa-trash fa-2x text-danger"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <!-- Empty Cart -->
                    <div class="text-center py-5">
                        <i class="bi bi-cart text-muted" style="font-size: 3rem;"></i>
                        <h5 class="mt-3 mb-2">Your cart is empty</h5>
                        <p class="text-muted mb-4">Browse our collection and add your favorite cars to your cart.</p>
                        <a href="{{ route('user.catalog') }}" class="btn btn-dark rounded-pill px-4 py-2">
                            Explore Collection
                        </a>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Order Summary & Checkout Form -->
        @if($carts->count() > 0)
        <div class="col-lg-4 d-flex flex-column">
            <div class="mt-auto">
                <!-- Checkout Form -->
                <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
                    @csrf
                    
                    <!-- Shipping Address Card -->
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold">Shipping Address</h5>
                        </div>
                        <div class="card-body">
                            
                            
                            <div class="mb-3">
                                <label for="address" class="form-label">Shipping Address</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" 
                                          name="address" 
                                          rows="3" 
                                          placeholder="Enter your complete shipping address" 
                                          required>{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                           
                        </div>
                    </div>

                    <!-- Order Summary Card -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h4 class="mb-0 fw-bold">Order Summary</h4>
                        </div>
                        <div class="card-body">
                            <!-- Cart Items Summary -->
                            <div class="mb-3">
                                <small class="text-muted">Items in cart:</small>
                                @foreach($carts as $cart)
                                <div class="d-flex justify-content-between align-items-center py-1">
                                    <small>{{ $cart->car->name }} (x{{ $cart->quantity }})</small>
                                    <small>Rp {{ number_format($cart->car->price * $cart->quantity, 0, ',', '.') }}</small>
                                </div>
                                @endforeach
                            </div>
                            
                            <hr>
                            
                            <!-- Price Breakdown -->
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Subtotal</span>
                                <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Shipping Cost</span>
                                <span>Rp {{ number_format(1000000, 0, ',', '.') }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="fw-bold">Grand Total</span>
                                <span class="fw-bold fs-5 text-primary">Rp {{ number_format($total + 1000000, 0, ',', '.') }}</span>
                            </div>

                            <!-- Checkout Button -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-dark py-3 rounded-pill fw-bold" id="checkoutBtn">
                                    <i class="fas fa-credit-card me-2"></i>
                                    Proceed to Payment
                                </button>
                            </div>

                            <div class="text-center mt-3">
                                <a href="{{ route('user.catalog') }}" class="text-decoration-none">
                                    <i class="fa fa-arrow-left me-1"></i> Continue Shopping
                                </a>
                            </div>
                            
                            <div class="text-center mt-2">
                                <small class="text-muted">
                                    <i class="fas fa-lock me-1"></i>
                                    Secure checkout powered by Midtrans
                                </small>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        document.querySelectorAll('.plus-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const input = this.parentNode.querySelector('.quantity-input');
                const currentValue = parseInt(input.value);
                if (currentValue < 10) {
                    input.value = currentValue + 1;
                     Auto-submit form
                    this.closest('.quantity-form').submit();
                }
            });
        });

        document.querySelectorAll('.minus-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const input = this.parentNode.querySelector('.quantity-input');
                const currentValue = parseInt(input.value);
                if (currentValue > 1) {
                    input.value = currentValue - 1;
                     Auto-submit form
                    this.closest('.quantity-form').submit();
                }
            });
        });

      
        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('change', function() {
                const value = parseInt(this.value);
                if (value >= 1 && value <= 10) {
                    this.closest('.quantity-form').submit();
                } else {
                    this.value = this.defaultValue;  Reset to original value
                }
            });
        });

        document.getElementById('checkoutForm').addEventListener('submit', function(e) {
            const checkoutBtn = document.getElementById('checkoutBtn');
            
       
            checkoutBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Processing...';
            checkoutBtn.disabled = true;
            
      
            const requiredFields = ['customer_name', 'address', 'phone', 'email'];
            let isValid = true;
            
            requiredFields.forEach(fieldName => {
                const field = document.getElementById(fieldName);
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                } else {
                    field.classList.remove('is-invalid');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                checkoutBtn.innerHTML = '<i class="fas fa-credit-card me-2"></i>Proceed to Payment';
                checkoutBtn.disabled = false;
                alert('Please fill in all required fields.');
            }
        });

      
        ['customer_name', 'address', 'phone', 'email'].forEach(fieldName => {
            document.getElementById(fieldName).addEventListener('input', function() {
                if (this.value.trim()) {
                    this.classList.remove('is-invalid');
                }
            });
        });
    });
</script>
@endsection