@extends('admin.layout')
@section('title', 'Admin Edit Product')
@section('header', 'Edit Product')

@section('content')
<div class="container py-4">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('cars.update', $car->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-lg-6 pe-lg-4 border-end-lg">
                        <h4 class="mb-4">Vehicle Information</h4>

                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name', $car->name) }}" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="manufacturer_id" class="form-label fw-bold">Manufacturer</label>
                            <select class="form-select @error('manufacturer_id') is-invalid @enderror"
                                id="manufacturer_id" name="manufacturer_id" required>
                                <option value="" disabled>Select Manufacturer</option>
                                @foreach($manufacturers as $manufacturer)
                                <option value="{{ $manufacturer->id }}" {{ old('manufacturer_id', $car->manufacturer_id)
                                    == $manufacturer->id ? 'selected' : '' }}>
                                    {{ $manufacturer->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('manufacturer_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="year" class="form-label fw-bold">Year</label>
                            <input type="number" class="form-control @error('year') is-invalid @enderror" id="year"
                                name="year" value="{{ old('year', $car->year) }}" required>
                            @error('year')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="car_type_id" class="form-label fw-bold">Type</label>
                            <select class="form-select @error('car_type_id') is-invalid @enderror" id="car_type_id"
                                name="car_type_id" required>
                                <option value="" disabled>Select Type</option>
                                @foreach($carTypes as $carType)
                                <option value="{{ $carType->id }}" {{ old('car_type_id', $car->car_type_id) ==
                                    $carType->id ? 'selected' : '' }}>
                                    {{ $carType->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('car_type_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="color" class="form-label fw-bold">Color</label>
                            <input type="text" class="form-control @error('color') is-invalid @enderror" id="color"
                                name="color" value="{{ old('color', $car->color) }}" placeholder="Type or select color"
                                autocomplete="off" list="colorList" required>
                            <datalist id="colorList">
                                <option value="red">Red</option>
                                <option value="blue">Blue</option>
                                <option value="green">Green</option>
                                <option value="yellow">Yellow</option>
                                <option value="orange">Orange</option>
                                <option value="purple">Purple</option>
                                <option value="pink">Pink</option>
                                <option value="brown">Brown</option>
                                <option value="black">Black</option>
                                <option value="white">White</option>
                                <option value="gray">Gray</option>
                                <option value="silver">Silver</option>
                                <option value="dark blue">Dark Blue</option>
                                <option value="light blue">Light Blue</option>
                                <option value="navy blue">Navy Blue</option>
                                <option value="sky blue">Sky Blue</option>
                                <option value="royal blue">Royal Blue</option>
                                <option value="dark green">Dark Green</option>
                                <option value="light green">Light Green</option>
                                <option value="forest green">Forest Green</option>
                                <option value="dark red">Dark Red</option>
                                <option value="bright red">Bright Red</option>
                                <option value="maroon">Maroon</option>
                            </datalist>
                            @error('color')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status Field Added Here -->
                        <div class="mb-3">
                            <label for="status" class="form-label fw-bold">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status"
                                required>
                                <option value="" disabled>Select Status</option>
                                <option value="available" {{ old('status', $car->status) == 'available' ? 'selected' :
                                    '' }}>Available</option>
                                <option value="sold" {{ old('status', $car->status) == 'sold' ? 'selected' : '' }}>Sold
                                </option>
                                <option value="reserved" {{ old('status', $car->status) == 'reserved' ? 'selected' : ''
                                    }}>Reserved</option>
                                <option value="maintenance" {{ old('status', $car->status) == 'maintenance' ? 'selected'
                                    : '' }}>Maintenance</option>
                            </select>
                            @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label fw-bold">Main Image</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image"
                                name="image" accept="image/*">
                            @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="image_preview" class="mt-2">
                                @if($car->image)
                                <img src="{{ asset('storage/' . $car->image) }}" class="img-thumbnail"
                                    style="max-height: 150px;">
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 ps-lg-4">
                        <h4 class="mb-4">Technical Specifications</h4>

                        <div class="mb-3">
                            <label for="mileage" class="form-label fw-bold">Mileage (km)</label>
                            <input type="number" class="form-control @error('mileage') is-invalid @enderror"
                                id="mileage" name="mileage" value="{{ old('mileage', $car->mileage) }}" required>
                            @error('mileage')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="transmission" class="form-label fw-bold">Transmission</label>
                            <select class="form-select @error('transmission') is-invalid @enderror" id="transmission"
                                name="transmission" required>
                                <option value="" disabled>Select Transmission</option>
                                <option value="automatic" {{ old('transmission', $car->transmission) == 'automatic' ?
                                    'selected' : '' }}>Automatic</option>
                                <option value="manual" {{ old('transmission', $car->transmission) == 'manual' ?
                                    'selected' : '' }}>Manual</option>
                                <option value="pdk" {{ old('transmission', $car->transmission) == 'pdk' ? 'selected' :
                                    '' }}>PDK</option>
                                <option value="dct" {{ old('transmission', $car->transmission) == 'dct' ? 'selected' :
                                    '' }}>DCT</option>
                                <option value="tiptronic" {{ old('transmission', $car->transmission) == 'tiptronic' ?
                                    'selected' : '' }}>Tiptronic</option>
                                <option value="cvt" {{ old('transmission', $car->transmission) == 'cvt' ? 'selected' :
                                    '' }}>CVT</option>


                            </select>
                            @error('transmission')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="fuel_type" class="form-label fw-bold">Fuel Type</label>
                            <select class="form-select @error('fuel_type') is-invalid @enderror" id="fuel_type"
                                name="fuel_type" required>
                                <option value="" disabled>Select Fuel Type</option>
                                <option value="petrol" {{ old('fuel_type', $car->fuel_type) == 'petrol' ? 'selected' :
                                    '' }}>Petrol</option>
                                <option value="diesel" {{ old('fuel_type', $car->fuel_type) == 'diesel' ? 'selected' :
                                    '' }}>Diesel</option>
                                <option value="hybrid" {{ old('fuel_type', $car->fuel_type) == 'hybrid' ? 'selected' :
                                    '' }}>Hybrid</option>
                                <option value="electric" {{ old('fuel_type', $car->fuel_type) == 'electric' ? 'selected'
                                    : '' }}>Electric</option>
                            </select>
                            @error('fuel_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="engine_size" class="form-label fw-bold">Engine Size</label>
                            <input type="number" step="0.1"
                                class="form-control @error('engine_size') is-invalid @enderror" id="engine_size"
                                name="engine_size" value="{{ old('engine_size', $car->engine_size) }}" required>
                            @error('engine_size')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label fw-bold">Price (Rp)</label>
                            <input type="number" class="form-control @error('price') is-invalid @enderror" id="price"
                                name="price" value="{{ old('price', $car->price) }}" required>
                            @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                                name="description" rows="4"
                                required>{{ old('description', $car->description) }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 mt-2">
                        <hr>
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.dashboard') }}"
                                class="btn btn-outline-secondary rounded-pill px-4 py-2">Cancel</a>
                            <button type="submit" class="btn btn-dark rounded-pill px-5 py-2">Update Product</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('image').addEventListener('change', function(event) {
        if (event.target.files.length > 0) {
            let src = URL.createObjectURL(event.target.files[0]);
            document.querySelector('#image_preview img').src = src;
        }
    });
</script>
@endsection