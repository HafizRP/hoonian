@extends('layouts.main')

@section('title', 'My Bidding List - Property')

@section('content')
    <div class="hero page-inner overlay" style="background-image: url('{{ asset('images/hero_bg_1.jpg') }}')">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-lg-9 text-center">
                    <h1 class="heading" data-aos="fade-up">My Bidding List</h1>
                    <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="200">
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a href="{{ route('main') }}">Home</a></li>
                            <li class="breadcrumb-item active text-white-50" aria-current="page">Bidding</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="container">
            {{-- Tabs Navigation --}}
            <ul class="nav nav-pills mb-4 justify-content-center fs-5" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pills-selling-tab" data-bs-toggle="pill" data-bs-target="#pills-selling" type="button" role="tab" aria-controls="pills-selling" aria-selected="true">
                        <i class="icon-home me-2"></i> Offers on My Properties
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-buying-tab" data-bs-toggle="pill" data-bs-target="#pills-buying" type="button" role="tab" aria-controls="pills-buying" aria-selected="false">
                        <i class="icon-shopping-cart me-2"></i> My Bids
                    </button>
                </li>
            </ul>

            {{-- Filter Form (Applies to both) --}}
            <form action="{{ route('bidding.list') }}" method="GET" class="mb-5 p-4 bg-light rounded">
                <div class="row g-3">
                    <div class="col-md-3">
                        <h5 class="mt-2 text-primary">Filter Bids</h5>
                    </div>
                    <div class="col-md-3">
                        <select name="property_id" id="property_id" class="form-control form-select">
                            <option value="">All Properties</option>
                            @foreach ($properties as $property)
                                <option value="{{ $property->id }}" {{ request('property_id') == $property->id ? 'selected' : '' }}>
                                    {{ $property->title }} ({{ $property->name }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="status" id="status" class="form-control form-select">
                            <option value="">All Status</option>
                            <option value="leading" {{ request('status') == 'leading' ? 'selected' : '' }}>Leading</option>
                            <option value="outbid" {{ request('status') == 'outbid' ? 'selected' : '' }}>Outbid</option>
                            <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">Apply Filter</button>
                    </div>
                </div>
            </form>

            <div class="tab-content" id="pills-tabContent">
                {{-- TAB 1: Selling (Incoming Bids) --}}
                <div class="tab-pane fade show active" id="pills-selling" role="tabpanel" aria-labelledby="pills-selling-tab">
                    
                    {{-- Filter Form Removed from here --}}

                    <div class="table-responsive custom-table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr class="text-uppercase text-black">
                                    <th scope="col">Property</th>
                                    <th scope="col">Property Name</th>
                                    <th scope="col">Bidder Name</th>
                                    <th scope="col">Bid Amount</th>
                                    <th scope="col" class="text-center">Status</th>
                                    <th scope="col" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sellingBids as $bid)
                                    <tr data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 10 }}">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset($bid->property->thumbnail) }}" alt="Image"
                                                    class="img-fluid rounded me-3"
                                                    style="width: 80px; height: 60px; object-fit: cover;">
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('properties.show', $bid->property->id) }}" target="_blank" class="fw-bold text-dark">{{ $bid->property->name }}</a>
                                            <span class="d-block text-muted small">{{ $bid->property->city }}</span>
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $bid->user->name }}</div>
                                            <small class="text-muted">{{ $bid->user->email }}</small>
                                        </td>
                                        <td>{{ 'Rp ' . number_format($bid->amount, 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            @if ($bid->status == 'leading')
                                                <span class="badge bg-success">Leading</span>
                                            @elseif($bid->status == 'outbid')
                                                <span class="badge bg-warning text-dark">Outbid</span>
                                            @elseif($bid->status == 'accepted')
                                                <span class="badge bg-primary">Accepted</span>
                                            @else
                                                <span class="badge bg-danger">{{ ucfirst($bid->status) }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($bid->status == 'leading' || $bid->status == 'pending')
                                                <div class="d-flex gap-2 justify-content-center">
                                                    <form action="{{ route('bidding.accept', $bid->id) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-success btn-sm"
                                                            onclick="return confirm('Accept this offer?')">
                                                            Accept
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('bidding.decline', $bid->id) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                            onclick="return confirm('Decline this offer?')">
                                                            Decline
                                                        </button>
                                                    </form>
                                                </div>
                                            @else
                                                <span class="text-muted small">Completed</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <i class="icon-inbox fs-1 text-muted mb-3"></i>
                                            <p class="text-black-50">No incoming offers on your properties yet.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- TAB 2: Buying (Outbound Bids) --}}
                <div class="tab-pane fade" id="pills-buying" role="tabpanel" aria-labelledby="pills-buying-tab">
                    <div class="table-responsive custom-table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr class="text-uppercase text-black">
                                    <th scope="col">Property</th>
                                    <th scope="col">Property Name</th>
                                    <th scope="col">Owner Name</th>
                                    <th scope="col">Bid Amount</th>
                                    <th scope="col" class="text-center">Status</th>
                                    <th scope="col" class="text-center">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($buyingBids as $bid)
                                    <tr data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 10 }}">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset($bid->property->thumbnail) }}" alt="Image"
                                                    class="img-fluid rounded me-3"
                                                    style="width: 80px; height: 60px; object-fit: cover;">
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('properties.show', $bid->property->id) }}" target="_blank" class="fw-bold text-dark">{{ $bid->property->name }}</a>
                                            <span class="d-block text-muted small">{{ $bid->property->city }}</span>
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $bid->property->owner->name ?? 'Unknown' }}</div>
                                            <small class="text-muted">Owner</small>
                                        </td>
                                        <td>{{ 'Rp ' . number_format($bid->amount, 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            @if ($bid->status == 'leading')
                                                <span class="badge bg-success">Leading</span>
                                            @elseif($bid->status == 'outbid')
                                                <span class="badge bg-warning text-dark">Outbid</span>
                                            @elseif($bid->status == 'accepted')
                                                <span class="badge bg-primary">Accepted</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($bid->status) }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center text-muted">
                                            {{ $bid->created_at->format('d M Y') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <i class="icon-shopping-bag fs-1 text-muted mb-3"></i>
                                            <p class="text-black-50">You haven't placed any bids yet.</p>
                                            <a href="{{ route('properties.index') }}" class="btn btn-primary mt-2">Browse Properties</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
