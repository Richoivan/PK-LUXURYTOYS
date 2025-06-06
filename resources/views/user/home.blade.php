@extends('user.header')  
@section('title', 'Home - Luxury Toys')  
@section('content') 
<!-- Brands Slider --> 
<section class="py-5 bg-light">     
    <div class="container">         
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

        <h2 class="display-5 fw-bold text-center mb-4">BEST BRANDS</h2>                  

        <!-- Brands Carousel -->         
        <div id="brandsCarousel" class="carousel slide" data-bs-ride="carousel">             
            <div class="carousel-inner">                 
                @php                     
                    $chunkedManufacturers = $manufacturers->chunk(5);                 
                @endphp                                  
                @foreach($chunkedManufacturers as $index => $manufacturerChunk)                 
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">                     
                        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 justify-content-center align-items-center g-3 g-md-4">                         
                            @foreach($manufacturerChunk as $manufacturer)                         
                                <div class="col">                             
                                    <div class="card brand-card h-100 border-0 shadow-sm">                                 
                                        <div class="card-body text-center p-3 p-md-4">                                     
                                            <img src="{{ asset('storage/brands/' . strtolower(str_replace(' ', '-', $manufacturer->name)) . '.png') }}"                                           alt="{{ $manufacturer->name }}" class="img-fluid brand-logo mb-2 mb-md-3"                                           style="max-height: 60px;"                                           onerror="this.src='{{ asset('storage/brands/default-brand.png') }}'">                                     
                                            <h5 class="card-title mb-0 fs-6 fs-md-5">{{ $manufacturer->name }}</h5>                                 
                                        </div>                             
                                    </div>                         
                                </div>                         
                            @endforeach                     
                        </div>                 
                    </div>                 
                @endforeach             
            </div>              
            @if($chunkedManufacturers->count() > 1)             
                <button class="carousel-control-prev" type="button" data-bs-target="#brandsCarousel" data-bs-slide="prev">                 
                    <span class="carousel-control-prev-icon bg-dark rounded-circle p-1" aria-hidden="true"></span>                 
                    <span class="visually-hidden">Previous</span>             
                </button>             
                <button class="carousel-control-next" type="button" data-bs-target="#brandsCarousel" data-bs-slide="next">                 
                    <span class="carousel-control-next-icon bg-dark rounded-circle p-1" aria-hidden="true"></span>                 
                    <span class="visually-hidden">Next</span>             
                </button>                    

                <div class="carousel-indicators position-static mt-3">                 
                    @foreach($chunkedManufacturers as $index => $chunk)                 
                        <button type="button" data-bs-target="#brandsCarousel" data-bs-slide-to="{{ $index }}"                          
                            class="{{ $index === 0 ? 'active' : '' }} bg-dark"                          
                            aria-current="{{ $index === 0 ? 'true' : 'false' }}"                          
                            aria-label="Slide {{ $index + 1 }}"></button>                 
                    @endforeach             
                </div>             
            @endif         
        </div>     
    </div> 
</section>  

<!-- Most Expensive Cars Section --> 
<section class="py-5">     
    <div class="container">         
        <h2 class="display-5 fw-bold mb-4">Our Most Expensive Models Yet</h2>          
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">             
            @forelse($expensiveVehicles as $car)             
                <div class="col">                 
                    <a href="{{ route('user.detail', $car->id) }}" class="text-decoration-none text-dark">                     
                        <div class="card h-100 border-0 shadow-sm hover-shadow">                         
                            <div class="position-relative">                             
                                @if($car->image)                                 
                                    <img src="{{ asset('storage/' . $car->image) }}" class="card-img-top"                                      alt="{{ $car->name }}" style="height: 200px; object-fit: cover;">                             
                                @else                                 
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">                                     
                                        <i class="bi bi-image text-muted fs-1"></i>                                 
                                    </div>                             
                                @endif                             
                                <span class="badge bg-dark position-absolute bottom-0 start-0 m-2">{{ $car->year }}</span>                         
                            </div>                          
                            <div class="card-body">                             
                                <h5 class="card-title fw-bold mb-3">{{ $car->name }}</h5>                                 
                                <div class="row g-0 text-center small mb-3">                                     
                                    <div class="col-6 border-end py-2">                                         
                                        <div class="text-muted">Mileage</div>                                         
                                        <div class="fw-bold">{{ number_format($car->mileage ?? 0) }} km</div>                                     
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
                <div class="col-12 text-center">                 
                    <p class="text-muted">No cars available at the moment.</p>             
                </div>             
            @endforelse         
        </div>     
    </div> 
</section>  

<!-- Car Types Section --> 
<section class="py-5 bg-light">     
    <div class="container">         
        <h2 class="display-5 fw-bold text-center mb-4">CAR TYPE</h2>          

        <!-- Car Types Carousel -->         
        <div id="carTypesCarousel" class="carousel slide" data-bs-ride="carousel">             
            <div class="carousel-inner">                 
                @php                     
                    $chunkedCarTypes = $carTypes->chunk(5);                 
                @endphp                                  
                @foreach($chunkedCarTypes as $index => $carTypeChunk)                 
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">                     
                        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 justify-content-center align-items-center g-3 g-md-4">                         
                            @foreach($carTypeChunk as $carType)                         
                                <div class="col">                             
                                    <div class="card brand-card h-100 border-0 shadow-sm">                                 
                                        <div class="card-body text-center p-3 p-md-4">                                     
                                            <img src="{{ asset('storage/car-types/' . strtolower(str_replace(' ', '-', $carType->name)) . '.png') }}"                                           alt="{{ $carType->name }}" class="img-fluid mb-2 mb-md-3"                                           style="max-height: 60px;"                                          onerror="this.src='{{ asset('storage/car-types/default-type.png') }}'">                                     
                                            <h5 class="card-title mb-0 fs-6 fs-md-5">{{ $carType->name }}</h5>                                 
                                        </div>                             
                                    </div>                         
                                </div>                         
                            @endforeach                     
                        </div>                 
                    </div>                 
                @endforeach             
            </div>              

            @if($chunkedCarTypes->count() > 1)             
                <button class="carousel-control-prev" type="button" data-bs-target="#carTypesCarousel" data-bs-slide="prev">                 
                    <span class="carousel-control-prev-icon bg-dark rounded-circle p-1" aria-hidden="true"></span>                 
                    <span class="visually-hidden">Previous</span>             
                </button>             
                <button class="carousel-control-next" type="button" data-bs-target="#carTypesCarousel" data-bs-slide="next">                 
                    <span class="carousel-control-next-icon bg-dark rounded-circle p-1" aria-hidden="true"></span>                 
                    <span class="visually-hidden">Next</span>             
                </button>                 
                <div class="carousel-indicators position-static mt-3">                 
                    @foreach($chunkedCarTypes as $index => $chunk)                 
                        <button type="button" data-bs-target="#carTypesCarousel" data-bs-slide-to="{{ $index }}"                          
                            class="{{ $index === 0 ? 'active' : '' }} bg-dark"                          
                            aria-current="{{ $index === 0 ? 'true' : 'false' }}"                          
                            aria-label="Slide {{ $index + 1 }}"></button>                 
                    @endforeach             
                </div>             
            @endif         
        </div>     
    </div> 
</section>  

<!-- More Vehicles Section --> 
<section class="py-5">     
    <div class="container">         
        <h2 class="display-5 fw-bold mb-4">MORE VEHICLES</h2>          
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">             
            @forelse($moreVehicles as $car)             
                <div class="col">                 
                    <a href="{{ route('user.detail', $car->id) }}" class="text-decoration-none text-dark">                     
                        <div class="card h-100 border-0 shadow-sm hover-shadow">                         
                            <div class="position-relative">                             
                                @if($car->image)                                 
                                    <img src="{{ asset('storage/' . $car->image) }}" class="card-img-top"                                      alt="{{ $car->name }}" style="height: 200px; object-fit: cover;">                             
                                @else                                 
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">                                     
                                        <i class="bi bi-image text-muted fs-1"></i>                                 
                                    </div>                             
                                @endif                             
                                <span class="badge bg-dark position-absolute bottom-0 start-0 m-2">{{ $car->year }}</span>                         
                            </div>                          
                            <div class="card-body">                             
                                <h5 class="card-title fw-bold mb-3">{{ $car->name }}</h5>                                 
                                <div class="row g-0 text-center small mb-3">                                     
                                    <div class="col-6 border-end py-2">                                         
                                        <div class="text-muted">Mileage</div>                                         
                                        <div class="fw-bold">{{ number_format($car->mileage ?? 0) }} km</div>                                     
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
                <div class="col-12 text-center">                 
                    <p class="text-muted">No more vehicles available.</p>             
                </div>             
            @endforelse         
        </div>          
        <div class="text-center mt-5">             
            <a href="{{route('user.catalog')}}" class="btn btn-outline-dark btn-lg px-5 rounded-pill">View All Vehicles</a>         
        </div>     
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
    }); 
</script> 
@endsection  

user/home.blade.php