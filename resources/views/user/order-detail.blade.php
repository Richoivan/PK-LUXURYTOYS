@extends('user.header')
@section('title', 'Order Details - Luxury Toys')

@section('content')
<div class="container py-5">
    <!-- Alert Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-4">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">Order #{{ $order->order_number }}</h3>
        <div class="d-flex align-items-center">
            <span class="badge {{ $order->status == 'paid' ? 'bg-success' : ($order->status == 'pending' ? 'bg-warning text-dark' : 'bg-danger') }} py-2 px-3 me-3">
                {{ ucfirst($order->status) }}
            </span>
            @if($order->status != 'paid')
            <form action="{{ route('user.check-payment', $order->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">Check Payment Status</button>
            </form>
            @endif
        </div>
    </div>

    <!-- Order Information -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3 mb-md-0">
                    <h5 class="text-muted text-uppercase small">Order Date</h5>
                    <p class="mb-0 fw-bold">{{ $order->created_at->format('F d, Y') }}</p>
                </div>
                <div class="col-md-4 mb-3 mb-md-0">
                    <h5 class="text-muted text-uppercase small">Shipping Address</h5>
                    <p class="mb-0">{{ $order->shipping_address ?? 'Not provided' }}</p>
                </div>
                <div class="col-md-4">
                    <h5 class="text-muted text-uppercase small">Payment Method</h5>
                    <p class="mb-0">{{ $order->payment_method ?? 'Midtrans Payment' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Items -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Order Items</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Product</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end pe-4">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderCars as $item)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('storage/' . ($item->car->image ?? 'cars/default.jpg')) }}" alt="{{ $item->car_name }}" class="rounded me-3" width="100" height="70" style="object-fit: cover;">
                                    <div>
                                        <h6 class="fw-bold mb-1">{{ $item->car_name }}</h6>
                                        <p class="text-muted small mb-0">
                                            {{ $item->car->color ?? 'N/A' }} |
                                            {{ $item->car->year ?? 'N/A' }} |
                                            {{ $item->car->transmission ?? 'Automatic' }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-end pe-4 fw-bold">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Order Summary -->
    <div class="row">
        <div class="col-lg-6 mb-4 mb-lg-0">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <td class="ps-0 text-muted">Subtotal:</td>
                            <td class="text-end pe-0">Rp {{ number_format($order->total_price  ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="ps-0 text-muted">Shipping Fee:</td>
                            <td class="text-end pe-0">Rp {{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="ps-0 fw-bold border-top pt-2">TOTAL:</td>
                            <td class="text-end pe-0 fw-bold border-top pt-2 fs-5">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Payment Details -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Payment Details</h5>
                </div>
                <div class="card-body">
                  
                    @if($order->status == 'paid')
                    <div class="alert alert-success mb-0">
                        <i class="fas fa-check-circle me-2"></i> Payment has been successfully completed.
                    </div>
                    @elseif($order->status == 'pending')
                    <p class="mb-3">Complete your payment to continue with your order.</p>
                    <div class="d-grid gap-2">
                        @if($order->payment_url)
                        <a href="{{ $order->payment_url }}" class="btn btn-primary" target="_blank">
                            <i class="fas fa-credit-card me-2"></i> Pay Now
                        </a>
                        @endif
                        <form action="{{ route('user.check-payment', $order->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-dark w-100">
                                <i class="fas fa-sync-alt me-2"></i> Refresh Payment Status
                            </button>
                        </form>

                        <!-- Add Cancel Order button -->
                        <form action="{{ route('user.cancel-order', $order->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order? This action cannot be undone.');">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger w-100 mt-2">
                                <i class="fas fa-times-circle me-2"></i> Cancel Order
                            </button>
                        </form>
                    </div>
                    @else
                    <div class="alert alert-danger mb-0">
                        <i class="fas fa-exclamation-circle me-2"></i> Payment {{ strtolower($order->status) }}. Please contact support.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="mt-4">
        <a href="{{ route('user.order') }}" class="btn btn-outline-dark">
            <i class="fas fa-arrow-left me-2"></i>Back to My Orders
        </a>
    </div>
</div>
@endsection
