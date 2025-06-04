@extends('admin.layout')
@section('title', 'Admin View Order')
@section('header', 'Customer Orders')

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

    <!-- Filter Section -->
    <div class="row mb-4">
        <div class="col-md-6">
            <form method="GET" action="{{ route('admin.order') }}" class="d-flex align-items-center">
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
        <div class="col-md-6 text-md-end">
            @if(request('status'))
                <a href="{{ route('admin.order') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle me-1"></i>Clear Filter
                </a>
            @endif
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">
                @if(request('status'))
                    {{ ucfirst(request('status')) }} Orders ({{ $orders->total() }} total)
                @else
                    All Orders ({{ $orders->total() }} total)
                @endif
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Date</th>
                            <th>Order</th>
                            <th>Status</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr class="align-middle">
                            <td class="ps-4">
                                <div class="fw-bold">{{ $order->created_at->format('d M Y') }}</div>
                                <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                                <div><small class="text-muted">#{{ $order->order_number }}</small></div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($order->orderCars->first() && $order->orderCars->first()->car)
                                        <img src="{{ asset('storage/' . ($order->orderCars->first()->car->image ?? 'cars/default.jpg')) }}" 
                                             alt="{{ $order->orderCars->first()->car_name }}" 
                                             class="rounded d-none d-sm-block me-3" width="60" height="45" style="object-fit: cover;">
                                        <div>
                                            <span class="fw-bold">{{ $order->orderCars->first()->car_name }}</span>
                                            @if($order->orderCars->count() > 1)
                                                <small class="text-muted d-block">+{{ $order->orderCars->count() - 1 }} more items</small>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">No items</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ 
                                    $order->status == 'paid' ? 'bg-success' : 
                                    ($order->status == 'pending' ? 'bg-warning text-dark' : 
                                    ($order->status == 'cancelled' ? 'bg-danger' : 
                                    ($order->status == 'expired' ? 'bg-secondary' : 'bg-info'))) 
                                }} text-white">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>
                                <div>
                                    <div class="fw-bold">{{ $order->user->name ?? $order->customer_name ?? 'N/A' }}</div>
                                    <small class="text-muted">{{ $order->user->email ?? 'N/A' }}</small>
                                </div>
                            </td>
                            <td class="fw-bold">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <a href="{{ route('admin.order-detail', $order->id) }}" 
                                       class="btn btn-sm btn-dark rounded-pill px-3">
                                        Details
                                    </a>
                                    @if($order->status == 'pending')
                                        <form action="{{ route('admin.check-payment', $order->id) }}" method="POST" class="d-inline ms-1">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill px-3" 
                                                    title="Check Payment Status">
                                                <i class="bi bi-arrow-clockwise"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                    @if(request('status'))
                                        <h5>No {{ request('status') }} orders found</h5>
                                        <p>There are no orders with "{{ request('status') }}" status at the moment.</p>
                                        <a href="{{ route('admin.order') }}" class="btn btn-outline-primary">View All Orders</a>
                                    @else
                                        <h5>No orders found</h5>
                                        <p>No customer orders have been placed yet.</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
        <div class="card-footer bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} results
                </div>
                <nav aria-label="Product pagination">
                    {{ $orders->appends(request()->query())->links('pagination::bootstrap-4') }}
                </nav>
            </div>
        </div>
        @endif
    </div>

    <!-- Summary Cards -->
    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-success">
                        <i class="bi bi-check-circle fs-1"></i>
                    </div>
                    <h4 class="fw-bold">{{ $statusCounts['paid'] }}</h4>
                    <p class="text-muted mb-0">Paid Orders</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-warning">
                        <i class="bi bi-clock fs-1"></i>
                    </div>
                    <h4 class="fw-bold">{{ $statusCounts['pending'] }}</h4>
                    <p class="text-muted mb-0">Pending Orders</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-secondary">
                        <i class="bi bi-hourglass fs-1"></i>
                    </div>
                    <h4 class="fw-bold">{{ $statusCounts['expired'] }}</h4>
                    <p class="text-muted mb-0">Expired Orders</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-danger">
                        <i class="bi bi-x-circle fs-1"></i>
                    </div>
                    <h4 class="fw-bold">{{ $statusCounts['cancelled'] }}</h4>
                    <p class="text-muted mb-0">Cancelled Orders</p>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
  
    document.addEventListener('DOMContentLoaded', function() {
  
        const statusSelect = document.getElementById('status');
        if (statusSelect.value) {
            statusSelect.style.borderColor = '#0d6efd';
            statusSelect.style.borderWidth = '2px';
        }

        document.querySelectorAll('form[action*="check-payment"]').forEach(form => {
            form.addEventListener('submit', function() {
                const button = this.querySelector('button[type="submit"]');
                button.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
                button.disabled = true;
            });
        });
    });
</script>
@endsection
@endsection