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
                                <a href="properties.html">Properties</a>
                            </li>
                            <li class="breadcrumb-item active text-white-50" aria-current="page">
                                {{ $property->city }}
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
                            <img src="{{ asset($property->thumbnail) }}" alt="Image" class="img-fluid" />
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

                    <div class="specs d-flex mb-4">
                        <span class="d-block d-flex align-items-center me-3">
                            <span class="icon-bed me-2"></span>
                            <span class="caption">{{ $property->bedrooms }} beds</span>
                        </span>
                        <span class="d-block d-flex align-items-center">
                            <span class="icon-bath me-2"></span>
                            <span class="caption">{{ $property->bathrooms }} baths</span>
                        </span>
                    </div>

                    <div class="d-block agent-box p-5">
                        <div class="img mb-4">
                            <img src="{{ asset($property->owner->profile_img) }}" alt="Image" class="img-fluid" />
                        </div>
                        <div class="text">
                            <h3 class="mb-0">{{ $property->owner->name }}</h3>
                            <div class="meta mb-3">Real Estate</div>
                            <p>
                                {{ $property->owner->bio }}
                            </p>
                            <ul class="list-unstyled social dark-hover d-flex">
                                <li class="me-1">
                                    <a href="{{ $property->owner->ig_url }}"><span class="icon-instagram"></span></a>
                                </li>
                                <li class="me-1">
                                    <a href="{{ $property->owner->x_url }}"><span class="icon-twitter"></span></a>
                                </li>
                                <li class="me-1">
                                    <a href="{{ $property->owner->tele_url }}"><span class="icon-telegram"></span></a>
                                </li>
                                <li class="me-1">
                                    <a href="{{ $property->owner->wa_url }}"><span class="icon-whatsapp"></span></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
