@extends('layouts.main')

@section('content')
    <div class="hero page-inner overlay" style="background-image: url('{{ asset('assets/images/hero_bg_3.jpg') }}')}}')">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-lg-9 text-center mt-5">
                    <h1 class="heading" data-aos="fade-up">
                        {{ $property->city }}
                    </h1>

                    <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="200">
                        <ol class="breadcrumb text-center justify-content-center">
                            <li class="breadcrumb-item"><a href="index.html">Home</a></li>
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

                    <div class="section sec-testimonials">
                        <div class="container">
                            <div class="row mb-5 align-items-center">
                                <div class="col-md-6">
                                    <h2 class="font-weight-bold heading text-primary mb-4 mb-md-0">
                                        Customer Says
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
                                    @foreach ($reviews as $item)
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
                                                {{-- <p class="text-black-50">Designer, Co-founder</p> --}}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="property-content col-lg-4">
                    <h1 class="heading text-success">
                        <span>{{ 'Rp ' . number_format($property->price, 0, ',', '.') }}</span>

                    </h1>
                    <h2 class="heading text-primary">{{ $property->name }}</h2>
                    <p class="meta">{{ $property->city }}</p>
                    <p class="meta">{{ $property->address }}</p>
                    <p class="text-black-50">
                        {{ $property->description }}
                    </p>

                    <div class="specs d-flex mb-4 gap-4 flex-wrap">
                        <span class="d-block d-flex align-items-center me-3">
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

                    @if($property->maps_url)
                    <div class="mb-4">
                        <h4 class="h5 text-primary">Location</h4>
                        <div class="ratio ratio-16x9">
                             <iframe src="{{ $property->maps_url }}" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                    @endif

                    <form action="{{ route('bidding.create') }}" method="POST">
                        @csrf
                        <input type="hidden" name="property_id" value="{{ $property->id }}">

                        <button type="submit" class="btn btn-primary" @disabled($property->status != 1)>
                            Add Offer
                        </button>
                    </form>

                    <div class="d-block agent-box p-5">
                        <div class="img mb-4">
                            <img src="{{ $property->owner->profile_img ? asset($property->owner->profile_img) : 'https://ui-avatars.com/api/?name='.urlencode($property->owner->name) }}" alt="Image" class="img-fluid rounded-circle" style="width: 100px; height: 100px; object-fit: cover;" />
                        </div>
                        <div class="text">
                            <h3 class="mb-0">{{ $property->owner->name }}</h3>
                            <div class="meta mb-3">Owner</div>
                            <p>
                                {{ $property->owner->bio ?? 'No bio available.' }}
                            </p>
                            <ul class="list-unstyled social dark-hover d-flex">
                                @if($property->owner->ig_url)
                                <li class="me-1">
                                    <a href="{{ $property->owner->ig_url }}" target="_blank"><span class="icon-instagram"></span></a>
                                </li>
                                @endif
                                @if($property->owner->x_url)
                                <li class="me-1">
                                    <a href="{{ $property->owner->x_url }}" target="_blank"><span class="icon-twitter"></span></a>
                                </li>
                                @endif
                                @if($property->owner->tele_url)
                                <li class="me-1">
                                    <a href="{{ $property->owner->tele_url }}" target="_blank"><span class="icon-telegram"></span></a>
                                </li>
                                @endif
                                @if($property->owner->wa_url)
                                <li class="me-1">
                                    <a href="{{ $property->owner->wa_url }}" target="_blank"><span class="icon-whatsapp"></span></a>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
