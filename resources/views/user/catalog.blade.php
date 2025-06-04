@extends('user.header')

@section('title', 'All Cars - Luxury Toys')

@section('content')
<!-- Catalog Header -->
<section class="py-5 bg-light">
    <div class="container">
        <h1 class="display-4 fw-bold text-center mb-5">SEE ALL CARS</h1>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('user.catalog') }}" id="filterForm">
            <!-- Preserve search from header if exists -->
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            
            <div class="row mb-5">
                <!-- Brand Filter -->
                <div class="col-md-4 mb-3 mb-md-0">
                    <div class="dropdown">
                        <button class="btn btn-outline-dark dropdown-toggle w-100 py-3" type="button"
                            id="brandFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-car me-2"></i> 
                            @if(request('manufacturer'))
                                {{ $manufacturers->where('id', request('manufacturer'))->first()->name ?? 'Filter by Brand' }}
                            @else
                                Filter by Brand
                            @endif
                        </button>
                        <ul class="dropdown-menu w-100" aria-labelledby="brandFilterDropdown">
                            <li>
                                <a class="dropdown-item {{ !request('manufacturer') ? 'active' : '' }}" 
                                   href="{{ route('user.catalog', array_merge(request()->query(), ['manufacturer' => ''])) }}">
                                    All Brands
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            @foreach($manufacturers as $manufacturer)
                            <li>
                                <a class="dropdown-item {{ request('manufacturer') == $manufacturer->id ? 'active' : '' }}" 
                                   href="{{ route('user.catalog', array_merge(request()->query(), ['manufacturer' => $manufacturer->id])) }}">
                                    {{ $manufacturer->name }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                
                <!-- Type Filter -->
                <div class="col-md-4 mb-3 mb-md-0">
                    <div class="dropdown">
                        <button class="btn btn-outline-dark dropdown-toggle w-100 py-3" type="button"
                            id="typeFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-tags me-2"></i> 
                            @if(request('car_type'))
                                {{ $carTypes->where('id', request('car_type'))->first()->name ?? 'Filter by Type' }}
                            @else
                                Filter by Type
                            @endif
                        </button>
                        <ul class="dropdown-menu w-100" aria-labelledby="typeFilterDropdown">
                            <li>
                                <a class="dropdown-item {{ !request('car_type') ? 'active' : '' }}" 
                                   href="{{ route('user.catalog', array_merge(request()->query(), ['car_type' => ''])) }}">
                                    All Types
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            @foreach($carTypes as $carType)
                            <li>
                                <a class="dropdown-item {{ request('car_type') == $carType->id ? 'active' : '' }}" 
                                   href="{{ route('user.catalog', array_merge(request()->query(), ['car_type' => $carType->id])) }}">
                                    {{ $carType->name }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Mileage Filter  -->
                <div class="col-md-4">
                    <div class="dropdown">
                        <button class="btn btn-outline-dark dropdown-toggle w-100 py-3" type="button"
                            id="mileageFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-tachometer-alt me-2"></i> 
                            @if(request('mileage'))
                                @if(request('mileage') == 'new')
                                    New Cars
                                @elseif(request('mileage') == 'used')
                                    Used Cars
                                @else
                                    Filter by Condition
                                @endif
                            @else
                                Filter by Condition
                            @endif
                        </button>
                        <ul class="dropdown-menu w-100" aria-labelledby="mileageFilterDropdown">
                            <li>
                                <a class="dropdown-item {{ !request('mileage') ? 'active' : '' }}" 
                                   href="{{ route('user.catalog', array_merge(request()->query(), ['mileage' => ''])) }}">
                                    All Conditions
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item {{ request('mileage') == 'new' ? 'active' : '' }}" 
                                   href="{{ route('user.catalog', array_merge(request()->query(), ['mileage' => 'new'])) }}">
                                    <i class="fas fa-star me-2 text-success"></i>New Cars (0 km)
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ request('mileage') == 'used' ? 'active' : '' }}" 
                                   href="{{ route('user.catalog', array_merge(request()->query(), ['mileage' => 'used'])) }}">
                                    <i class="fas fa-car me-2 text-info"></i>Used Cars (> 0 km)
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Clear Filters -->
            @if(request('manufacturer') || request('car_type') || request('mileage'))
            <div class="row mb-3">
                <div class="col-12 text-center">
                    <a href="{{ route('user.catalog', request('search') ? ['search' => request('search')] : []) }}" 
                       class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Clear All Filters
                    </a>
                </div>
            </div>
            @endif
        </form>
    </div>
</section>

<!-- Cars Section -->
<section class="py-5">
    <div class="container">
        <!-- Search Results Info -->
        @if(request('search') || request('manufacturer') || request('car_type') || request('mileage'))
            <div class="mb-4">
                <h3 class="h5">
                    @if(request('search'))
                        Search results for "<strong>{{ request('search') }}</strong>"
                    @else
                        Filtered results
                    @endif
                    @if(request('manufacturer'))
                        • Brand: <strong>{{ $manufacturers->where('id', request('manufacturer'))->first()->name ?? 'Unknown' }}</strong>
                    @endif
                    @if(request('car_type'))
                        • Type: <strong>{{ $carTypes->where('id', request('car_type'))->first()->name ?? 'Unknown' }}</strong>
                    @endif
                    @if(request('mileage'))
                        • Condition: <strong>{{ request('mileage') == 'new' ? 'New Cars' : 'Used Cars' }}</strong>
                    @endif
                    <span class="text-muted">({{ $cars->total() }} cars found)</span>
                </h3>
            </div>
        @endif

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
            @forelse($cars as $car)
            <div class="col">
                <a href="{{ route('user.detail', $car->id) }}" class="text-decoration-none text-dark">
                    <div class="card h-100 border-0 shadow-sm hover-shadow">
                        <div class="position-relative">
                            @if($car->image)
                                <img src="{{ asset('storage/' . $car->image) }}" class="card-img-top"
                                     alt="{{ $car->name }}" style="height: 200px; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <i class="bi bi-image text-muted fs-1"></i>
                                </div>
                            @endif
                            <span class="badge bg-dark position-absolute bottom-0 start-0 m-2">{{ $car->year }}</span>

                            @if($car->mileage == 0)
                                <span class="badge bg-success position-absolute top-0 end-0 m-2">NEW</span>
                            @else
                                <span class="badge bg-info position-absolute top-0 end-0 m-2">USED</span>
                            @endif
                        </div>

                        <div class="card-body">
                            <h5 class="card-title fw-bold mb-3">{{ $car->name }}</h5>
                            
                            <!-- Brand and Type Info -->
                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="fas fa-industry me-1"></i>{{ $car->manufacturer->name ?? 'Unknown' }} • 
                                    <i class="fas fa-car me-1"></i>{{ $car->cartype->name ?? 'Unknown' }}
                                </small>
                            </div>

                            <div class="row g-0 text-center small mb-3">
                                <div class="col-6 border-end py-2">
                                    <div class="text-muted">Mileage</div>
                                    <div class="fw-bold">
                                        {{ number_format($car->mileage ?? 0) }} km
                                        
                                    </div>
                                </div>
                                <div class="col-6 py-2">
                                    <div class="text-muted">Transmission</div>
                                    <div class="fw-bold">{{ ucfirst($car->transmission ?? 'N/A') }}</div>
                                </div>
                                <div class="col-6 border-end border-top py-2">
                                    <div class="text-muted">Color</div>
                                    <div class="fw-bold">{{ $car->color ?? 'N/A' }}</div>
                                </div>
                                <div class="col-6 border-top py-2">
                                    <div class="text-muted">Engine</div>
                                    <div class="fw-bold">{{ $car->engine_size ? $car->engine_size . 'L' : 'N/A' }}</div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="fs-4 fw-bold text-muted">Rp{{ number_format($car->price, 0, ',', '.') }}</span>
                                </div>
                              
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <i class="fas fa-car fs-1 text-muted mb-3"></i>
                <h4 class="text-muted">No cars found</h4>
                @if(request('search') || request('manufacturer') || request('car_type') || request('mileage'))
                    <p class="text-muted">Try adjusting your search criteria or filters</p>
                    <a href="{{ route('user.catalog') }}" class="btn btn-outline-primary">View All Cars</a>
                @else
                    <p class="text-muted">No cars are currently available</p>
                @endif
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($cars->hasPages())
        <div class="d-flex justify-content-center mt-5">
            <nav aria-label="Car catalog pagination">
                {{ $cars->appends(request()->query())->links('pagination::bootstrap-4') }}
            </nav>
        </div>
        @endif
    </div>
</section>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
      
        const cards = document.querySelectorAll('.hover-shadow');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.classList.add('shadow');
                this.style.transform = 'translateY(-5px)';
                this.style.transition = 'transform 0.3s ease, box-shadow 0.3s ease';
            });
            card.addEventListener('mouseleave', function() {
                this.classList.remove('shadow');
                this.style.transform = 'translateY(0)';
            });
        });


        document.querySelectorAll('.dropdown-item').forEach(item => {
            item.addEventListener('click', function(e) {
                
                const dropdown = this.closest('.dropdown');
                const button = dropdown.querySelector('.dropdown-toggle');
                
             
                button.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Loading...';
            });
        });
    });
</script>
@endsection