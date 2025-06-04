@extends('admin.layout')
@section('title', 'Order Details - Admin')
@section('header', 'Order Details')

@section('content')
<div class="container py-4">
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

    <!-- Order Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Order #{{ $order->order_number }}</h3>
            <p class="text-muted mb-0">{{ $order->created_at->format('F d, Y \a\t g:i A') }}</p>
        </div>
        <div class="d-flex align-items-center">
            <span class="badge {{ 
                $order->status == 'paid' ? 'bg-success' : 
                ($order->status == 'pending' ? 'bg-warning text-dark' : 
                ($order->status == 'cancelled' ? 'bg-danger' : 'bg-secondary')) 
            }} py-2 px-3 me-3 fs-6">
                {{ ucfirst($order->status) }}
            </span>
            
            <form action="{{ route('admin.check-payment', $order->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-clockwise me-1"></i> Check Status
                </button>
            </form>
        </div>
    </div>

    <!-- Customer Information -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Customer Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3 mb-md-0">
                    <h6 class="text-muted text-uppercase small">Customer Name</h6>
                    <p class="mb-0 fw-bold">{{ $order->user->name ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3 mb-md-0">
                    <h6 class="text-muted text-uppercase small">Email</h6>
                    <p class="mb-0">{{ $order->user->email ?? 'N/A' }}</p>
                </div>
                {{-- <div class="col-md-4">
                    <h6 class="text-muted text-uppercase small">Phone</h6>
                    <p class="mb-0">{{ $order->customer_phone ?? 'Not provided' }}</p>
                </div> --}}
            </div>
            <hr class="my-3">
            <div class="row">
                <div class="col-md-6 mb-3 mb-md-0">
                    <h6 class="text-muted text-uppercase small">Shipping Address</h6>
                    <p class="mb-0">{{ $order->shipping_address ?? 'Not provided' }}</p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted text-uppercase small">Payment Method</h6>
                    <p class="mb-0">{{ $order->payment_method ?? 'Midtrans Payment Gateway' }}</p>
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
                            <th class="text-end">Unit Price</th>
                            <th class="text-end pe-4">Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderCars as $item)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('storage/' . ($item->car->image ?? 'cars/default.jpg')) }}" 
                                         alt="{{ $item->car_name }}" class="rounded me-3" 
                                         width="80" height="60" style="object-fit: cover;">
                                    <div>
                                        <h6 class="fw-bold mb-1">{{ $item->car_name }}</h6>
                                        <p class="text-muted small mb-0">
                                            @if($item->car)
                                                {{ $item->car->color ?? 'N/A' }} | 
                                                {{ $item->car->year ?? 'N/A' }} | 
                                                {{ $item->car->transmission ?? 'Automatic' }}
                                            @else
                                                Product details not available
                                            @endif
                                        </p>
                                        @if($item->car)
                                            <small class="badge bg-{{ $item->car->status == 'sold' ? 'success' : ($item->car->status == 'reserved' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($item->car->status) }}
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-end fw-bold">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="text-end pe-4 fw-bold">Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Order Summary and Payment Info -->
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
                            <td class="text-end pe-0">Rp {{ number_format($order->total_price ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="ps-0 text-muted">Shipping Fee:</td>
                            <td class="text-end pe-0">Rp {{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="border-top">
                            <td class="ps-0 fw-bold pt-2">GRAND TOTAL:</td>
                            <td class="text-end pe-0 fw-bold pt-2 fs-5 text-primary">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Payment Status -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Payment Information</h5>
                </div>
                <div class="card-body">
                    @if($order->status == 'paid')
                        <div class="alert alert-success mb-3">
                            <i class="bi bi-check-circle-fill me-2"></i> 
                            <strong>Payment Completed</strong>
                            <br><small>Payment has been successfully processed.</small>
                        </div>
                    @elseif($order->status == 'pending')
                        <div class="alert alert-warning mb-3">
                            <i class="bi bi-clock-fill me-2"></i> 
                            <strong>Payment Pending</strong>
                            <br><small>Waiting for customer payment confirmation.</small>
                        </div>
                    @elseif($order->status == 'cancelled')
                        <div class="alert alert-danger mb-3">
                            <i class="bi bi-x-circle-fill me-2"></i> 
                            <strong>Order Cancelled</strong>
                            <br><small>This order has been cancelled.</small>
                        </div>
                    @else
                        <div class="alert alert-secondary mb-3">
                            <i class="bi bi-info-circle-fill me-2"></i> 
                            <strong>Status: {{ ucfirst($order->status) }}</strong>
                        </div>
                    @endif

                    @if($order->payment_url && $order->status == 'pending')
                        <div class="mb-3">
                            <label class="form-label small text-muted">Payment URL:</label>
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" value="{{ $order->payment_url }}" readonly>
                                <button class="btn btn-outline-secondary btn-sm" type="button" onclick="copyToClipboard('{{ $order->payment_url }}')">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                            </div>
                        </div>
                    @endif

                   
                </div>
            </div>
        </div>
    </div>
    
    <!-- Back Button -->
    <div class="mt-4">
        <a href="{{ route('admin.order') }}" class="btn btn-outline-dark">
            <i class="bi bi-arrow-left me-2"></i>Back to Orders
        </a>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Payment URL copied to clipboard!');
    });
}
</script>
@endsection