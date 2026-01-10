@extends('layouts.main')

@section('title', 'My Bidding List - Property')

@section('content')
    <div class="hero page-inner overlay" style="background-image: url('{{ asset('images/hero_bg_1.jpg') }}')">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-lg-9 text-center">
                    <h1 class="heading" data-aos="fade-up"><i class="fas fa-gavel me-3"></i> My Bidding List</h1>
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
            {{-- Summary Statistics Cards --}}
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-2">
                                <i class="fas fa-inbox fa-2x text-primary"></i>
                            </div>
                            <h3 class="mb-0">{{ $sellingBids->count() }}</h3>
                            <p class="text-muted mb-0 small">Offers Received</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-2">
                                <i class="fas fa-paper-plane fa-2x text-success"></i>
                            </div>
                            <h3 class="mb-0">{{ $buyingBids->count() }}</h3>
                            <p class="text-muted mb-0 small">Bids Placed</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-2">
                                <i class="fas fa-trophy fa-2x text-warning"></i>
                            </div>
                            <h3 class="mb-0">{{ $sellingBids->where('status', 'leading')->count() + $buyingBids->where('status', 'leading')->count() }}</h3>
                            <p class="text-muted mb-0 small">Leading Bids</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-2">
                                <i class="fas fa-check-circle fa-2x text-info"></i>
                            </div>
                            <h3 class="mb-0">{{ $sellingBids->where('status', 'accepted')->count() + $buyingBids->where('status', 'accepted')->count() }}</h3>
                            <p class="text-muted mb-0 small">Accepted</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabs Navigation --}}
            <ul class="nav nav-pills mb-4 justify-content-center fs-5" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pills-selling-tab" data-bs-toggle="pill" data-bs-target="#pills-selling" type="button" role="tab" aria-controls="pills-selling" aria-selected="true">
                        <i class="fas fa-home me-2"></i> Offers on My Properties
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-buying-tab" data-bs-toggle="pill" data-bs-target="#pills-buying" type="button" role="tab" aria-controls="pills-buying" aria-selected="false">
                        <i class="fas fa-shopping-cart me-2"></i> My Bids
                    </button>
                </li>
            </ul>

            {{-- Filter Form (Applies to both) --}}
            <form action="{{ route('bidding.list') }}" method="GET" class="mb-5 p-4 bg-light rounded shadow-sm">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <h5 class="text-primary mb-0"><i class="fas fa-filter me-2"></i> Filter Bids</h5>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold small">Property</label>
                        <select name="property_id" id="property_id" class="form-control form-select">
                            <option value="">All Properties</option>
                            @foreach ($properties as $property)
                                <option value="{{ $property->id }}" {{ request('property_id') == $property->id ? 'selected' : '' }}>
                                    {{ $property->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold small">Status</label>
                        <select name="status" id="status" class="form-control form-select">
                            <option value="">All Status</option>
                            <option value="leading" {{ request('status') == 'leading' ? 'selected' : '' }}>Leading</option>
                            <option value="outbid" {{ request('status') == 'outbid' ? 'selected' : '' }}>Outbid</option>
                            <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-2"></i> Apply Filter</button>
                    </div>
                </div>
            </form>

            <div class="tab-content" id="pills-tabContent">
                {{-- TAB 1: Selling (Incoming Bids) --}}
                <div class="tab-pane fade show active" id="pills-selling" role="tabpanel" aria-labelledby="pills-selling-tab">
                    
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
                                            <a href="{{ route('properties.show', $bid->property->id) }}" target="_blank" class="fw-bold text-dark">
                                                <i class="fas fa-external-link-alt me-1 small"></i> {{ $bid->property->name }}
                                            </a>
                                            <span class="d-block text-muted small"><i class="fas fa-map-marker-alt me-1"></i> {{ $bid->property->city }}</span>
                                        </td>
                                        <td>
                                            <div class="fw-bold"><i class="fas fa-user me-1"></i> {{ $bid->user->name }}</div>
                                            <small class="text-muted">{{ $bid->user->email }}</small>
                                        </td>
                                        <td class="fw-bold text-success">{{ 'Rp ' . number_format($bid->amount, 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            @if ($bid->status == 'leading')
                                                <span class="badge bg-success"><i class="fas fa-trophy me-1"></i> Leading</span>
                                            @elseif($bid->status == 'outbid')
                                                <span class="badge bg-warning text-dark"><i class="fas fa-exclamation-triangle me-1"></i> Outbid</span>
                                            @elseif($bid->status == 'accepted')
                                                <span class="badge bg-primary"><i class="fas fa-check-circle me-1"></i> Accepted</span>
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
                                                            <i class="fas fa-check me-1"></i> Accept
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('bidding.decline', $bid->id) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                            onclick="return confirm('Decline this offer?')">
                                                            <i class="fas fa-times me-1"></i> Decline
                                                        </button>
                                                    </form>
                                                </div>
                                            @else
                                                @if ($bid->invoice_number && !$bid->isPaid())
                                                    <button type="button" class="btn btn-outline-success btn-sm border-2 rounded-pill fw-bold" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#markPaidModal{{ $bid->id }}">
                                                        <i class="fas fa-check-circle me-1"></i> Mark Paid
                                                    </button>

                                                    <!-- Modal Mark Paid -->
                                                    <div class="modal fade" id="markPaidModal{{ $bid->id }}" tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header border-0 pb-0">
                                                                    <h5 class="modal-title fw-bold">Confirm Payment</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <form action="{{ route('invoices.ownerMarkPaid', $bid->id) }}" method="POST">
                                                                    @csrf
                                                                    <div class="modal-body text-start">
                                                                        <p class="text-muted small mb-3">
                                                                            Please confirm that you have received the payment for invoice <strong>{{ $bid->invoice_number }}</strong>.
                                                                        </p>
                                                                        <div class="mb-3">
                                                                            <label class="form-label small fw-bold text-uppercase text-muted">Amount Received</label>
                                                                            <div class="form-control-plaintext fw-bold fs-5">Rp {{ number_format($bid->total_amount, 0, ',', '.') }}</div>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label for="payment_method" class="form-label small fw-bold">Payment Method</label>
                                                                            <select name="payment_method" class="form-select" required>
                                                                                <option value="">Select Method...</option>
                                                                                <option value="Bank Transfer">Bank Transfer (BCA)</option>
                                                                                <option value="Cash">Cash</option>
                                                                                <option value="Other">Other</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer border-0 pt-0">
                                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                                        <button type="submit" class="btn btn-success fw-bold"><i class="fas fa-check me-2"></i>Confirm Payment</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @elseif ($bid->invoice_number && $bid->isPaid())
                                                    <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-3">
                                                        <i class="fas fa-check-double me-1"></i> Paid
                                                    </span>
                                                @elseif ($bid->status == 'accepted')
                                                    <a href="{{ route('invoices.ownerGenerate', $bid->id) }}" class="btn btn-primary btn-sm rounded-pill fw-bold">
                                                        <i class="fas fa-file-invoice-dollar me-1"></i> Generate Invoice
                                                    </a>
                                                @else
                                                     <span class="text-muted small">-</span>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
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
                                    <th scope="col" class="text-center">Action</th>
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
                                            <a href="{{ route('properties.show', $bid->property->id) }}" target="_blank" class="fw-bold text-dark">
                                                <i class="fas fa-external-link-alt me-1 small"></i> {{ $bid->property->name }}
                                            </a>
                                            <span class="d-block text-muted small"><i class="fas fa-map-marker-alt me-1"></i> {{ $bid->property->city }}</span>
                                        </td>
                                        <td>
                                            <div class="fw-bold"><i class="fas fa-user-tie me-1"></i> {{ $bid->property->owner->name ?? 'Unknown' }}</div>
                                            <small class="text-muted">Owner</small>
                                        </td>
                                        <td class="fw-bold text-success">{{ 'Rp ' . number_format($bid->amount, 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            @if ($bid->status == 'leading')
                                                <span class="badge bg-success"><i class="fas fa-trophy me-1"></i> Leading</span>
                                            @elseif($bid->status == 'outbid')
                                                <span class="badge bg-warning text-dark"><i class="fas fa-exclamation-triangle me-1"></i> Outbid</span>
                                            @elseif($bid->status == 'accepted')
                                                <span class="badge bg-primary"><i class="fas fa-check-circle me-1"></i> Accepted</span>
                                                @if($bid->invoice_number)
                                                    <div class="mt-1 small text-muted">{{ $bid->invoice_number }}</div>
                                                @endif
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($bid->status) }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center text-muted">
                                            <i class="fas fa-calendar-alt me-1"></i> {{ $bid->created_at->format('d M Y') }}
                                        </td>
                                        <td class="text-center">
                                            @if ($bid->status == 'accepted' && $bid->invoice_number)
                                                <a href="{{ route('invoices.download', $bid->id) }}" class="btn btn-primary btn-sm rounded-pill" target="_blank">
                                                    <i class="fas fa-file-invoice me-1"></i> Invoice
                                                </a>
                                            @else
                                                <span class="text-black-50 small">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <i class="fas fa-shopping-bag fa-3x text-muted mb-3 d-block"></i>
                                            <p class="text-black-50">You haven't placed any bids yet.</p>
                                            <a href="{{ route('properties.index') }}" class="btn btn-primary mt-2">
                                                <i class="fas fa-search me-2"></i> Browse Properties
                                            </a>
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
