@extends('admin.layout')
@section('title', 'Admin Dashboard')
@section('header', 'Dashboard')

@section('content')
<div class="container py-4">
    @if(session('error'))
    <div class="alert alert-danger mt-3">
        {{ session('error') }}
    </div>
    @endif
    @if(session('success'))
    <div class="alert alert-success mt-3">
        {{ session('success') }}
    </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="w-100">
                            <h6 class="text-muted text-uppercase small">Total Sales</h6>
                            <h3 class="mt-2 mb-0 fw-bold"
                                style="font-size: clamp(0.95rem, 2vw, 1.5rem); word-break: break-word;">
                                <span class="d-inline-block" style="white-space: normal; line-height: 1.2;">
                                    Rp {{ number_format($totalSales, 0, ',', '.') }}
                                </span>
                            </h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-2 rounded text-primary flex-shrink-0">
                            <i class="bi bi-cash-stack fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-3 position-relative">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex flex-column h-100">

                    <!-- Header bar: Judul + Link -->
                    <div class="d-flex flex-wrap align-items-center mb-2">
                        <h6 class="text-muted text-uppercase small mb-0 me-2">Orders Summary</h6>
                        <a href="{{ route('admin.order') }}" class="small text-decoration-none">
                            View all <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>


                    <!-- Icon di pojok kanan atas -->
                    <div class="position-absolute top-0 end-0 p-2">
                        <div class="bg-success bg-opacity-10 p-2 rounded text-success">
                            <i class="bi bi-cart-check fs-4"></i>
                        </div>
                    </div>

                    <!-- Dua box kecil -->
                    <div class="mt-3">
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="bg-light rounded p-2 text-center">
                                    <div class="small text-muted">Total Transactions</div>
                                    <div class="fw-bold fs-6">{{ $totalPaidOrders }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light rounded p-2 text-center">
                                    <div class="small text-muted">Total Orders</div>
                                    <div class="fw-bold fs-6">{{ $totalOrders }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Spacer supaya card tetap stretch tinggi -->
                    <div class="flex-grow-1"></div>
                </div>
            </div>
        </div>


        <div class="col-sm-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted text-uppercase small">Total Cars</h6>
                            <h3 class="mt-2 mb-0 fw-bold">{{ $totalCars }}</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 p-2 rounded text-info">
                            <i class="bi bi-car-front fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted text-uppercase small">Available Cars</h6>
                            <h3 class="mt-2 mb-0 fw-bold">{{ $availableCars }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-2 rounded text-warning">
                            <i class="bi bi-check-circle fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Section -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Product Inventory ({{ $cars->total() }} items)</h5>
            <a href="{{ route('cars.create') }}" class="btn btn-dark rounded-pill px-4">
                <i class="bi bi-plus-lg me-1"></i> Add New Product
            </a>
        </div>

        <!-- Search Form -->
        <div class="card-body border-bottom">
            <form method="GET" action="{{ route('admin.dashboard') }}">
                <div class="row g-3">
                    <div class="col-md-8">
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" value="{{ $search }}"
                                placeholder="Search by name, color, or manufacturer...">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        @if($search)
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-lg me-1"></i> Clear Search
                        </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- Products Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Product</th>
                        <th>Type</th>
                        <th>Manufacturer</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cars as $car)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                @if($car->image)
                                <img src="{{ asset('storage/' . $car->image) }}" alt="{{ $car->name }}" class="rounded"
                                    width="60" height="40" style="object-fit: cover;">
                                @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                    style="width: 60px; height: 40px;">
                                    <i class="bi bi-image text-muted"></i>
                                </div>
                                @endif
                                <div class="ms-3">
                                    <h6 class="mb-0 fw-bold">{{ $car->name }}</h6>
                                    <small class="text-muted">{{ $car->year }} • {{ $car->color ?? 'N/A' }}</small>

                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge  text-dark">{{ $car->cartype->name ?? 'N/A' }}</span>
                        </td>
                        <td>{{ $car->manufacturer->name ?? 'N/A' }}</td>
                        <td>
                            <strong>Rp {{ number_format($car->price, 0, ',', '.') }}</strong>
                        </td>
                        <td>
                            <span
                                class="badge bg-{{ $car->status == 'available' ? 'success' : ($car->status == 'sold' ? 'danger' : 'warning') }}">
                                {{ ucfirst($car->status) }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group">
                                <a href="{{ route('cars.show', $car->id) }}" class="btn btn-sm btn-outline-secondary"
                                    title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('cars.edit', $car->id) }}" class="btn btn-sm btn-outline-dark"
                                    title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if ($car->status == 'available' || $car->status == 'maintenance')
                                <form action="{{ route('cars.destroy', $car->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete {{ $car->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <div class="text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                @if($search)
                                No cars found matching your search: "<strong>{{ $search }}</strong>"
                                <br>
                                <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Clear search</a>
                                @else
                                No cars available. <a href="{{ route('cars.create') }}" class="text-decoration-none">Add
                                    your first car</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($cars->hasPages())
        <div class="card-footer bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Showing {{ $cars->firstItem() }} to {{ $cars->lastItem() }} of {{ $cars->total() }} results
                </div>
                <nav aria-label="Product pagination">
                    {{ $cars->appends(request()->query())->links('pagination::bootstrap-4') }}
                </nav>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection