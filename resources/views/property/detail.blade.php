@extends('layouts.main')

@section('content')
    <div class="hero page-inner overlay" style="background-image: url('{{ asset('assets/images/hero_bg_3.jpg') }}')}}'')">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-lg-9 text-center mt-5">
                    <h1 class="heading" data-aos="fade-up">
                        <i class="fas fa-map-marker-alt me-2"></i> {{ $property->city }}
                    </h1>

                    <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="200">
                        <ol class="breadcrumb text-center justify-content-center">
                            <li class="breadcrumb-item"><a href="{{ route('main') }}">Home</a></li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('properties.index') }}">Properties</a>
                            </li>
                            <li class="breadcrumb-item active text-white-50" aria-current="page">
                                {{ $property->name }}
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-7">
                    <div class="img-property-slide-wrap">
                        <div class="img-property-slide">
                            <img src="{{ asset('storage/' . $property->thumbnail) }}" alt="Image" class="img-fluid" />
                            @foreach($property->gallery as $gal)
                                <img src="{{ asset('storage/' . $gal->url) }}" alt="Image" class="img-fluid" />
                            @endforeach
                        </div>
                    </div>

                    {{-- Property Details Section --}}
                    <div class="my-5">
                        <h3 class="text-primary mb-4"><i class="fas fa-info-circle me-2"></i> Property Details</h3>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded">
                                    <small class="text-muted d-block mb-1">Property Type</small>
                                    <strong><i class="fas fa-building me-2 text-primary"></i> {{ $property->propertyType->name ?? 'N/A' }}</strong>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded">
                                    <small class="text-muted d-block mb-1">Status</small>
                                    <strong>
                                        @if($property->status == 1)
                                            <i class="fas fa-check-circle me-2 text-success"></i> Available
                                        @else
                                            <i class="fas fa-times-circle me-2 text-danger"></i> Sold
                                        @endif
                                    </strong>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded">
                                    <small class="text-muted d-block mb-1">Land Area</small>
                                    <strong><i class="fas fa-ruler-combined me-2 text-primary"></i> {{ $property->land_area }} m²</strong>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded">
                                    <small class="text-muted d-block mb-1">Building Area</small>
                                    <strong><i class="fas fa-home me-2 text-primary"></i> {{ $property->building_area }} m²</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Customer Reviews Section --}}
                    <div class="section sec-testimonials">
                        <div class="container">
                            <div class="row mb-5 align-items-center">
                                <div class="col-md-6">
                                    <h2 class="font-weight-bold heading text-primary mb-4 mb-md-0">
                                        <i class="fas fa-comments me-2"></i> Customer Says
                                    </h2>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <div id="testimonial-nav">
                                        <span class="prev" data-controls="prev">Prev</span>
                                        <span class="next" data-controls="next">Next</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4"></div>
                            </div>
                            <div class="testimonial-slider-wrap">
                                <div class="testimonial-slider">
                                    @forelse ($reviews as $item)
                                        <div class="item">
                                            <div class="testimonial">
                                                <img src=" {{ asset($item->customerReviews->profile_img) }}" alt="Image"
                                                    class="img-fluid rounded-circle w-25 mb-4" />
                                                <div class="rate">
                                                    @foreach (range(1, $item->rating) as $i)
                                                        <span class="icon-star text-warning"></span>
                                                    @endforeach
                                                </div>
                                                <h3 class="h5 text-primary mb-4">{{ $item->customerReviews->name }}</h3>
                                                <blockquote>
                                                    <p>{{ $item->description }}</p>
                                                </blockquote>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-5">
                                            <i class="fas fa-comment-slash fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">No reviews yet</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="property-content col-lg-4">
                    {{-- Price & Title --}}
                    <div class="mb-4">
                        <h1 class="heading text-success mb-2">
                            <i class="fas fa-tag me-2"></i>
                            <span>{{ 'Rp ' . number_format($property->price, 0, ',', '.') }}</span>
                        </h1>
                        <h2 class="heading text-primary">{{ $property->name }}</h2>
                        <p class="meta"><i class="fas fa-map-marker-alt me-2"></i> {{ $property->city }}</p>
                        <p class="meta"><i class="fas fa-location-dot me-2"></i> {{ $property->address }}</p>
                    </div>

                    {{-- Description --}}
                    <div class="mb-4">
                        <h4 class="h5 text-primary"><i class="fas fa-align-left me-2"></i> Description</h4>
                        <p class="text-black-50">
                            {{ $property->description }}
                        </p>
                    </div>

                    {{-- Specifications --}}
                    <div class="mb-4">
                        <h4 class="h5 text-primary mb-3"><i class="fas fa-list-check me-2"></i> Specifications</h4>
                        <div class="specs d-flex mb-4 gap-3 flex-wrap">
                            <span class="d-block d-flex align-items-center">
                                <span class="icon-bed me-2"></span>
                                <span class="caption">{{ $property->bedrooms }} beds</span>
                            </span>
                            <span class="d-block d-flex align-items-center">
                                <span class="icon-bath me-2"></span>
                                <span class="caption">{{ $property->bathrooms }} baths</span>
                            </span>
                            <span class="d-block d-flex align-items-center">
                                <i class="flaticon-blueprint me-2"></i>
                                <span class="caption">{{ $property->land_area }} m² Land</span>
                            </span>
                             <span class="d-block d-flex align-items-center">
                                <i class="flaticon-house me-2"></i>
                                <span class="caption">{{ $property->building_area }} m² Build</span>
                            </span>
                             <span class="d-block d-flex align-items-center">
                                <i class="flaticon-building me-2"></i>
                                <span class="caption">{{ $property->floors }} Floors</span>
                            </span>
                        </div>
                    </div>

                    {{-- Location Map --}}
                    @if($property->maps_url)
                    <div class="mb-4">
                        <h4 class="h5 text-primary"><i class="fas fa-map-location-dot me-2"></i> Location</h4>
                        <div class="ratio ratio-16x9">
                             <iframe src="{{ $property->maps_url }}" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                    @endif

                    {{-- Bid Form --}}
                    <div class="mb-4">
                        <form action="{{ route('bidding.create') }}" method="POST">
                            @csrf
                            <input type="hidden" name="property_id" value="{{ $property->id }}">

                            <button type="submit" class="btn btn-primary w-100 py-3" @disabled($property->status != 1 || $userHasBid)>
                                <i class="fas fa-gavel me-2"></i> 
                                @if($property->status != 1)
                                    Property Sold
                                @elseif($userHasBid)
                                    You Already Placed a Bid
                                @else
                                    Place Your Bid
                                @endif
                            </button>
                        </form>
                        
                        @if($userHasBid)
                        <div class="alert alert-info mt-3 mb-0">
                            <i class="fas fa-info-circle me-2"></i> You have already placed a bid on this property. Check <a href="{{ route('bidding.list') }}" class="alert-link">My Bidding</a> page for status.
                        </div>
                        @endif
                    </div>

                    {{-- Owner Info --}}
                    <div class="d-block agent-box p-4 bg-light rounded">
                        <h4 class="h5 text-primary mb-3">
                            <i class="fas fa-user me-2"></i> Property Owner
                        </h4>
                        <div class="text-center mb-3">
                            <img src="{{ $property->owner->profile_img ? asset($property->owner->profile_img) : 'https://ui-avatars.com/api/?name='.urlencode($property->owner->name) }}" 
                                 alt="Image" 
                                 class="img-fluid rounded-circle" 
                                 style="width: 100px; height: 100px; object-fit: cover;" />
                        </div>
                        <div class="text-center">
                            <h3 class="mb-1">{{ $property->owner->name }}</h3>
                            <div class="meta mb-3 text-muted small">Property Owner</div>
                            <p class="small text-black-50">
                                {{ $property->owner->bio ?? 'No bio available.' }}
                            </p>
                            
                            @if($property->owner->ig_url || $property->owner->x_url || $property->owner->wa_url)
                            <div class="mt-3">
                                <small class="text-muted d-block mb-2">Connect with owner:</small>
                                <ul class="list-unstyled social dark-hover d-flex justify-content-center gap-2">
                                    @if($property->owner->ig_url)
                                    <li>
                                        <a href="{{ $property->owner->ig_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <span class="icon-instagram"></span>
                                        </a>
                                    </li>
                                    @endif
                                    @if($property->owner->x_url)
                                    <li>
                                        <a href="{{ $property->owner->x_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <span class="icon-twitter"></span>
                                        </a>
                                    </li>
                                    @endif
                                    @if($property->owner->wa_url)
                                    <li>
                                        <a href="{{ $property->owner->wa_url }}" target="_blank" class="btn btn-sm btn-outline-success">
                                            <span class="icon-whatsapp"></span>
                                        </a>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
