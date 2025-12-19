@extends('layouts.main')

@section('title', 'Post a Property')

@section('content')
    <div class="hero page-inner overlay" style="background-image: url('{{ asset('images/hero_bg_1.jpg') }}')">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-lg-9 text-center">
                    <h1 class="heading" data-aos="fade-up">Post a Property</h1>
                    <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="200">
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a href="{{ route('main') }}">Home</a></li>
                            <li class="breadcrumb-item active text-white-50" aria-current="page">Post Property</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <form action="{{ route('properties.store') }}" method="POST" enctype="multipart/form-data"
                        class="p-5 bg-white border rounded">
                        @csrf
                        <input type="hidden" name="owner_id" value="{{ Auth::id() }}">

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row mb-3">
                            <div class="col-12">
                                <h3 class="h4 text-black mb-4">Property Details</h3>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="name" class="form-label">Property Title</label>
                                <input type="text" name="name" id="name"
                                    class="form-control @error('name') is-invalid @enderror" required
                                    value="{{ old('name') }}" placeholder="e.g. Modern Villa in Bali">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="property_type" class="form-label">Type</label>
                                <select name="property_type" id="property_type"
                                    class="form-control form-select @error('property_type') is-invalid @enderror" required>
                                    <option value="">Select Type</option>
                                    @foreach ($properties_type as $type)
                                        <option value="{{ $type->id }}" {{ old('property_type') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('property_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">Price (IDR)</label>
                                <input type="number" name="price" id="price"
                                    class="form-control @error('price') is-invalid @enderror" required
                                    value="{{ old('price') }}" placeholder="e.g. 500000000">
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <h3 class="h4 text-black mb-4">Location</h3>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">City</label>
                                <input type="text" name="city" id="city"
                                    class="form-control @error('city') is-invalid @enderror" required
                                    value="{{ old('city') }}" placeholder="e.g. Jakarta Selatan">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="maps_url" class="form-label">Google Maps URL</label>
                                <input type="url" name="maps_url" id="maps_url"
                                    class="form-control @error('maps_url') is-invalid @enderror"
                                    value="{{ old('maps_url') }}" placeholder="https://maps.google.com/...">
                                @error('maps_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 mb-3">
                                <label for="address" class="form-label">Full Address</label>
                                <textarea name="address" id="address" cols="30" rows="3"
                                    class="form-control @error('address') is-invalid @enderror"
                                    required>{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <h3 class="h4 text-black mb-4">Specification</h3>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="bedrooms" class="form-label">Bedrooms</label>
                                <input type="number" name="bedrooms" id="bedrooms"
                                    class="form-control @error('bedrooms') is-invalid @enderror" required min="0"
                                    value="{{ old('bedrooms') }}">
                                @error('bedrooms')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="bathrooms" class="form-label">Bathrooms</label>
                                <input type="number" name="bathrooms" id="bathrooms"
                                    class="form-control @error('bathrooms') is-invalid @enderror" required min="0"
                                    value="{{ old('bathrooms') }}">
                                @error('bathrooms')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="floors" class="form-label">Floors</label>
                                <input type="number" name="floors" id="floors"
                                    class="form-control @error('floors') is-invalid @enderror" required min="1"
                                    value="{{ old('floors') }}">
                                @error('floors')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label for="land_area" class="form-label">Land Area (m²)</label>
                                <input type="number" name="land_area" id="land_area"
                                    class="form-control @error('land_area') is-invalid @enderror" required step="0.01"
                                    value="{{ old('land_area') }}">
                                @error('land_area')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="building_area" class="form-label">Building Area (m²)</label>
                                <input type="number" name="building_area" id="building_area"
                                    class="form-control @error('building_area') is-invalid @enderror" required step="0.01"
                                    value="{{ old('building_area') }}">
                                @error('building_area')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" id="description" cols="30" rows="5"
                                    class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <h3 class="h4 text-black mb-4">Images</h3>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="thumbnail" class="form-label">Main Thumbnail</label>
                                <input type="file" name="thumbnail" id="thumbnail"
                                    class="form-control @error('thumbnail') is-invalid @enderror" required accept="image/*">
                                @error('thumbnail')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="images" class="form-label">Gallery Images (Multiple)</label>
                                <input type="file" name="images[]" id="images"
                                    class="form-control @error('images') is-invalid @enderror" multiple accept="image/*">
                                <div id="preview-gallery" class="d-flex flex-wrap mt-3 gap-2"></div>
                                @error('images')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary w-100 py-3">Submit Property</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('images').addEventListener('change', function (event) {
            const previewContainer = document.getElementById('preview-gallery');
            previewContainer.innerHTML = ''; // Clear previous previews

            const files = event.target.files;
            if (files) {
                Array.from(files).forEach(file => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.className = 'img-thumbnail rounded';
                            img.style.width = '100px';
                            img.style.height = '100px';
                            img.style.objectFit = 'cover';
                            previewContainer.appendChild(img);
                        }
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
    </script>
@endsection