@extends('user.header')
@section('title', 'My Orders - Luxury Toys')

@section('content')
<div class="container py-5">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">My Orders</h3>
        <div>
             <form method="GET" action="{{ route('user.order') }}" class="d-flex align-items-center">
                <label for="status" class="form-label me-3 mb-0 fw-bold">Filter by Status:</label>
                <select name="status" id="status" class="form-select" style="width: auto;" onchange="this.form.submit()">
                    <option value="">All Orders ({{ $statusCounts['all'] }})</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>
                        Paid ({{ $statusCounts['paid'] }})
                    </option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                        Pending ({{ $statusCounts['pending'] }})
                    </option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>
                        Expired ({{ $statusCounts['expired'] }})
                    </option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                        Cancelled ({{ $statusCounts['cancelled'] }})
                    </option>
                </select>
            </form>
        </div>
    </div>
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Recent Orders</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Date</th>
                            <th>Order</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr class="align-middle">
                            <td class="ps-4">
                                <div class="fw-bold">{{ $order->created_at->format('d M Y') }}</div>
                                <small class="text-muted">{{ $order->order_number }}</small>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($order->orderCars->first() && $order->orderCars->first()->car)
                                    <img src="{{ asset('storage/' . $order->orderCars->first()->car->image) }}" 
                                         alt="{{ $order->orderCars->first()->car_name }}" 
                                         class="rounded d-none d-sm-block me-3" width="60" height="45" 
                                         style="object-fit: cover;">
                                    @else
                                    <div class="rounded bg-secondary d-none d-sm-block me-3" 
                                         style="width: 60px; height: 45px;"></div>
                                    @endif
                                    <span>
                                        {{ $order->orderCars->first()->car_name }}
                                        @if($order->orderCars->count() > 1)
                                        <small class="text-muted">+{{ $order->orderCars->count() - 1 }} more</small>
                                        @endif
                                    </span>
                                </div>
                            </td>
                            <td>
                                @if($order->status == 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($order->status == 'paid')
                                <span class="badge bg-success">Paid</span>
                                @elseif($order->status == 'processing')
                                <span class="badge bg-info">Processing</span>
                                @elseif($order->status == 'on_delivery')
                                <span class="badge bg-primary">On Delivery</span>
                                @elseif($order->status == 'delivered')
                                <span class="badge bg-success">Delivered</span>
                                @elseif($order->status == 'cancelled')
                                <span class="badge bg-danger">Cancelled</span>
                                @elseif($order->status == 'expired')
                                <span class="badge bg-secondary">Expired</span>
                                @else
                                <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                                @endif
                            </td>
                            <td>Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <a href="{{ route('user.order-detail', $order->id) }}" class="btn btn-sm btn-dark rounded-pill px-3">Details</a>
                                    @if($order->status == 'pending' || $order->status == 'reserved')
                                    <form action="{{ route('user.check-payment', $order->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3 ms-2">
                                            Check Status
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <p class="mb-0 text-muted">You haven't placed any orders yet.</p>
                                <a href="{{ route('user.catalog') }}" class="btn btn-dark rounded-pill mt-3">
                                    Browse Cars
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white py-3">
            {{ $orders->links() }}
        </div>
    </div>

    <div class="row g-4 mt-3">
        <!-- Delivery Information -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Delivery Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p class="mb-1 text-muted small">Standard Delivery</p>
                        <p class="mb-0">2-5 business days after payment confirmation</p>
                    </div>
                    <div class="mb-0">
                        <p class="mb-1 text-muted small">Premium Delivery</p>
                        <p class="mb-0">1-2 business days after payment confirmation</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Help  -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Need Help?</h5>
                </div>
                <div class="card-body">
                    <p>If you have any questions about your orders, please contact our customer service:</p>
                    <div class="d-flex align-items-center mt-3">
                        <i class="fas fa-phone me-2"></i>
                        <span>+62 21 1234 5678</span>
                    </div>
                    <div class="d-flex align-items-center mt-2">
                        <i class="fas fa-envelope me-2"></i>
                        <span>support@luxurytoys.com</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection