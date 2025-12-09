@extends('layouts.main')

@section('content')
    {{-- <div class="hero page-inner overlay" style="background-image: url('{{ asset('assets/images/hero_bg_1.jpg') }}')">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-lg-9 text-center">
                    <h1 class="heading pt-5" data-aos="fade-up">Properties</h1>

                    <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="200">
                        <ol class="breadcrumb text-center justify-content-center">
                            <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                            <li class="breadcrumb-item active text-white-50" aria-current="page">
                                Properties
                            </li>
                        </ol>
                    </nav>
                </div>

                <div class="text-white mb-5">
                    <form action="{{ route('properties.index') }}" method="GET"
                        class="row g-3 align-items-end form-search" data-aos="fade-up" data-aos-delay="200">

                        <div class="col-md-12">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control px-4" value="{{ request('name') }}"
                                placeholder="e.g. New York">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">City</label>
                            <input type="text" name="city" class="form-control" value="{{ request('city') }}"
                                placeholder="City">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Min Price</label>
                            <input type="text" name="min_price" class="form-control" value="{{ request('min_price') }}"
                                placeholder="e.g. 100000">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Max Price</label>
                            <input type="text" name="max_price" class="form-control" value="{{ request('max_price') }}"
                                placeholder="e.g. 500000">
                        </div>


                        <div class="col-md-3">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-control">
                                <option value=""></option>

                                @foreach ($properties_type as $item)
                                    <option value="{{ $item->id }}"
                                        {{ request('type') == $item->id ? 'selected' : '' }}> {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2 d-grid">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> --}}

    <div class="hero">
        <div class="hero-slide">
            <div class="img overlay" style="background-image: url({{ asset('assets/images/hero_bg_3.jpg') }}"></div>
            <div class="img overlay" style="background-image: url({{ asset('assets/images/hero_bg_2.jpg') }}"></div>
            <div class="img overlay" style="background-image: url({{ asset('assets/images/hero_bg_1.jpg') }}"></div>
        </div>

        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-lg-9 text-white">
                    <h1 class="heading text-center" data-aos="fade-up">
                        Easiest way to find your dream home
                    </h1>
                    <form action="{{ route('properties.index') }}" method="GET"
                        class="row g-3 align-items-end form-search" data-aos="fade-up" data-aos-delay="200">

                        <div class="col-md-12">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control px-4" value="{{ request('name') }}"
                                placeholder="e.g. New York">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">City</label>
                            <input type="text" name="city" class="form-control" value="{{ request('city') }}"
                                placeholder="City">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Min Price</label>
                            <input type="text" name="min_price" class="form-control" value="{{ request('min_price') }}"
                                placeholder="e.g. 100000">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Max Price</label>
                            <input type="text" name="max_price" class="form-control"
                                value="{{ request('max_price') }}" placeholder="e.g. 500000">
                        </div>


                        <div class="col-md-3">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-control">
                                <option value=""></option>

                                @foreach ($properties_type as $item)
                                    <option value="{{ $item->id }}"
                                        {{ request('type') == $item->id ? 'selected' : '' }}> {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2 d-grid">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="container">
            <div class="row mb-5 align-items-center">
                <div class="col-lg-6 text-center mx-auto">
                    <h2 class="font-weight-bold text-primary heading">
                        Featured Properties
                    </h2>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="property-slider-wrap">
                        <div class="property-slider">
                            @foreach ($featured as $item)
                                <div class="property-item">
                                    <a href="{{ route('properties.show', $item->id) }}" class="img">
                                        <img src="{{ asset($item->thumbnail) }}" alt="Image"
                                            class="img-fluid object-fit-cover" style="aspect-ratio: 4/3;" /> </a>

                                    <div class="property-content">
                                        <div class="price mb-2">
                                            <span>{{ 'Rp ' . number_format($item->price, 0, ',', '.') }}</span>
                                        </div>
                                        <div>
                                            <span class="d-block mb-2 text-black-50">{{ $item->name }}</span>
                                            <span class="city d-block mb-3">{{ $item->city }}</span>

                                            <div class="specs d-flex mb-4">
                                                <span class="d-block d-flex align-items-center me-3">
                                                    <span class="icon-bed me-2"></span>
                                                    <span class="caption">{{ $item->bedrooms }} beds</span>
                                                </span>
                                                <span class="d-block d-flex align-items-center">
                                                    <span class="icon-bath me-2"></span>
                                                    <span class="caption">{{ $item->bathrooms }} baths</span>
                                                </span>
                                            </div>

                                            <a href="{{ route('properties.show', $item->id) }}"
                                                class="btn btn-primary py-2 px-3">See
                                                details</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            {{-- <div class="property-item">
                                <a href="{{ route('properties.show', 1) }}" class="img">
                                    <img src="{{ asset('assets/images/img_2.jpg') }}" alt="Image" class="img-fluid" />
                                </a>

                                <div class="property-content">
                                    <div class="price mb-2"><span>$1,291,000</span></div>
                                    <div>
                                        <span class="d-block mb-2 text-black-50">5232 California Fake, Ave. 21BC</span>
                                        <span class="city d-block mb-3">California, USA</span>

                                        <div class="specs d-flex mb-4">
                                            <span class="d-block d-flex align-items-center me-3">
                                                <span class="icon-bed me-2"></span>
                                                <span class="caption">2 beds</span>
                                            </span>
                                            <span class="d-block d-flex align-items-center">
                                                <span class="icon-bath me-2"></span>
                                                <span class="caption">2 baths</span>
                                            </span>
                                        </div>

                                        <a href="{{ route('properties.show', 1) }}" class="btn btn-primary py-2 px-3">See
                                            details</a>
                                    </div>
                                </div>
                            </div>
                            <!-- .item -->

                            <div class="property-item">
                                <a href="{{ route('properties.show', 1) }}" class="img">
                                    <img src="{{ asset('assets/images/img_3.jpg') }}" alt="Image" class="img-fluid" />
                                </a>

                                <div class="property-content">
                                    <div class="price mb-2"><span>$1,291,000</span></div>
                                    <div>
                                        <span class="d-block mb-2 text-black-50">5232 California Fake, Ave. 21BC</span>
                                        <span class="city d-block mb-3">California, USA</span>

                                        <div class="specs d-flex mb-4">
                                            <span class="d-block d-flex align-items-center me-3">
                                                <span class="icon-bed me-2"></span>
                                                <span class="caption">2 beds</span>
                                            </span>
                                            <span class="d-block d-flex align-items-center">
                                                <span class="icon-bath me-2"></span>
                                                <span class="caption">2 baths</span>
                                            </span>
                                        </div>

                                        <a href="{{ route('properties.show', 1) }}" class="btn btn-primary py-2 px-3">See
                                            details</a>
                                    </div>
                                </div>
                            </div>
                            <!-- .item -->

                            <div class="property-item">
                                <a href="{{ route('properties.show', 1) }}" class="img">
                                    <img src="{{ asset('assets/images/img_4.jpg') }}" alt="Image" class="img-fluid" />
                                </a>

                                <div class="property-content">
                                    <div class="price mb-2"><span>$1,291,000</span></div>
                                    <div>
                                        <span class="d-block mb-2 text-black-50">5232 California Fake, Ave. 21BC</span>
                                        <span class="city d-block mb-3">California, USA</span>

                                        <div class="specs d-flex mb-4">
                                            <span class="d-block d-flex align-items-center me-3">
                                                <span class="icon-bed me-2"></span>
                                                <span class="caption">2 beds</span>
                                            </span>
                                            <span class="d-block d-flex align-items-center">
                                                <span class="icon-bath me-2"></span>
                                                <span class="caption">2 baths</span>
                                            </span>
                                        </div>

                                        <a href="{{ route('properties.show', 1) }}" class="btn btn-primary py-2 px-3">See
                                            details</a>
                                    </div>
                                </div>
                            </div>
                            <!-- .item -->

                            <div class="property-item">
                                <a href="{{ route('properties.show', 1) }}" class="img">
                                    <img src="{{ asset('assets/images/img_5.jpg') }}" alt="Image"
                                        class="img-fluid" />
                                </a>

                                <div class="property-content">
                                    <div class="price mb-2"><span>$1,291,000</span></div>
                                    <div>
                                        <span class="d-block mb-2 text-black-50">5232 California Fake, Ave. 21BC</span>
                                        <span class="city d-block mb-3">California, USA</span>

                                        <div class="specs d-flex mb-4">
                                            <span class="d-block d-flex align-items-center me-3">
                                                <span class="icon-bed me-2"></span>
                                                <span class="caption">2 beds</span>
                                            </span>
                                            <span class="d-block d-flex align-items-center">
                                                <span class="icon-bath me-2"></span>
                                                <span class="caption">2 baths</span>
                                            </span>
                                        </div>

                                        <a href="{{ route('properties.show', 1) }}" class="btn btn-primary py-2 px-3">See
                                            details</a>
                                    </div>
                                </div>
                            </div>
                            <!-- .item -->

                            <div class="property-item">
                                <a href="{{ route('properties.show', 1) }}" class="img">
                                    <img src="{{ asset('assets/images/img_6.jpg') }}" alt="Image"
                                        class="img-fluid" />
                                </a>

                                <div class="property-content">
                                    <div class="price mb-2"><span>$1,291,000</span></div>
                                    <div>
                                        <span class="d-block mb-2 text-black-50">5232 California Fake, Ave. 21BC</span>
                                        <span class="city d-block mb-3">California, USA</span>

                                        <div class="specs d-flex mb-4">
                                            <span class="d-block d-flex align-items-center me-3">
                                                <span class="icon-bed me-2"></span>
                                                <span class="caption">2 beds</span>
                                            </span>
                                            <span class="d-block d-flex align-items-center">
                                                <span class="icon-bath me-2"></span>
                                                <span class="caption">2 baths</span>
                                            </span>
                                        </div>

                                        <a href="{{ route('properties.show', 1) }}" class="btn btn-primary py-2 px-3">See
                                            details</a>
                                    </div>
                                </div>
                            </div>
                            <!-- .item -->

                            <div class="property-item">
                                <a href="{{ route('properties.show', 1) }}" class="img">
                                    <img src="{{ asset('assets/images/img_7.jpg') }}" alt="Image"
                                        class="img-fluid" />
                                </a>

                                <div class="property-content">
                                    <div class="price mb-2"><span>$1,291,000</span></div>
                                    <div>
                                        <span class="d-block mb-2 text-black-50">5232 California Fake, Ave. 21BC</span>
                                        <span class="city d-block mb-3">California, USA</span>

                                        <div class="specs d-flex mb-4">
                                            <span class="d-block d-flex align-items-center me-3">
                                                <span class="icon-bed me-2"></span>
                                                <span class="caption">2 beds</span>
                                            </span>
                                            <span class="d-block d-flex align-items-center">
                                                <span class="icon-bath me-2"></span>
                                                <span class="caption">2 baths</span>
                                            </span>
                                        </div>

                                        <a href="{{ route('properties.show', 1) }}" class="btn btn-primary py-2 px-3">See
                                            details</a>
                                    </div>
                                </div>
                            </div>
                            <!-- .item -->

                            <div class="property-item">
                                <a href="{{ route('properties.show', 1) }}" class="img">
                                    <img src="{{ asset('assets/images/img_8.jpg') }}" alt="Image"
                                        class="img-fluid" />
                                </a>

                                <div class="property-content">
                                    <div class="price mb-2"><span>$1,291,000</span></div>
                                    <div>
                                        <span class="d-block mb-2 text-black-50">5232 California Fake, Ave. 21BC</span>
                                        <span class="city d-block mb-3">California, USA</span>

                                        <div class="specs d-flex mb-4">
                                            <span class="d-block d-flex align-items-center me-3">
                                                <span class="icon-bed me-2"></span>
                                                <span class="caption">2 beds</span>
                                            </span>
                                            <span class="d-block d-flex align-items-center">
                                                <span class="icon-bath me-2"></span>
                                                <span class="caption">2 baths</span>
                                            </span>
                                        </div>

                                        <a href="{{ route('properties.show', 1) }}" class="btn btn-primary py-2 px-3">See
                                            details</a>
                                    </div>
                                </div>
                            </div>
                            <!-- .item -->

                            <div class="property-item">
                                <a href="{{ route('properties.show', 1) }}" class="img">
                                    <img src="{{ asset('assets/images/img_1.jpg') }}" alt="Image"
                                        class="img-fluid" />
                                </a>

                                <div class="property-content">
                                    <div class="price mb-2"><span>$1,291,000</span></div>
                                    <div>
                                        <span class="d-block mb-2 text-black-50">5232 California Fake, Ave. 21BC</span>
                                        <span class="city d-block mb-3">California, USA</span>

                                        <div class="specs d-flex mb-4">
                                            <span class="d-block d-flex align-items-center me-3">
                                                <span class="icon-bed me-2"></span>
                                                <span class="caption">2 beds</span>
                                            </span>
                                            <span class="d-block d-flex align-items-center">
                                                <span class="icon-bath me-2"></span>
                                                <span class="caption">2 baths</span>
                                            </span>
                                        </div>

                                        <a href="{{ route('properties.show', 1) }}" class="btn btn-primary py-2 px-3">See
                                            details</a>
                                    </div>
                                </div>
                            </div>
                            <!-- .item --> --}}
                        </div>

                        <div id="property-nav" class="controls" tabindex="0" aria-label="Carousel Navigation">
                            <span class="prev" data-controls="prev" aria-controls="property"
                                tabindex="-1">Prev</span>
                            <span class="next" data-controls="next" aria-controls="property"
                                tabindex="-1">Next</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="section section-properties">
        <div class="container">
            <div class="row">
                @foreach ($properties as $item)
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                        <div class="property-item mb-30">
                            <a href="{{ route('properties.show', $item->id) }}" class="img">
                                <img src="{{ asset($item->thumbnail) }}" alt="Image"
                                    class="img-fluid object-fit-cover" style="aspect-ratio: 4/3;" /> </a>

                            <div class="property-content">
                                <div class="price mb-2">
                                    <span>{{ 'Rp ' . number_format($item->price, 0, ',', '.') }}</span>
                                </div>
                                <div>
                                    <span class="d-block mb-2 text-black-50">{{ $item->name }}</span>
                                    <span class="city d-block mb-3">{{ $item->city }}</span>

                                    <div class="specs d-flex mb-4">
                                        <span class="d-block d-flex align-items-center me-3">
                                            <span class="icon-bed me-2"></span>
                                            <span class="caption">{{ $item->bedrooms }} beds</span>
                                        </span>
                                        <span class="d-block d-flex align-items-center">
                                            <span class="icon-bath me-2"></span>
                                            <span class="caption">{{ $item->bathrooms }} baths</span>
                                        </span>
                                    </div>

                                    <a href="{{ route('properties.show', $item->id) }}"
                                        class="btn btn-primary py-2 px-3">See
                                        details</a>
                                </div>
                            </div>
                        </div>
                        <!-- .item -->
                    </div>
                @endforeach
            </div>
            {{ $properties->links('vendor.pagination.custom') }}
        </div>
    </div>
@endsection
