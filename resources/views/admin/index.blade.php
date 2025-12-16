@extends('layouts.admin')

@section('content')
    <div class="page-inner">
        <!-- Header Section -->
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Dashboard Overview</h3>
                <h6 class="op-7 mb-2">Welcome back, {{ Auth::user()->name }}! Here is your business summary.</h6>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
                <a href="{{ route('backoffice.transactions') }}" class="btn btn-label-info btn-round me-2">
                    <i class="fa fa-chart-line me-1"></i> View Reports
                </a>
                <a href="{{ route('backoffice.properties.create') }}" class="btn btn-primary btn-round">
                    <i class="fa fa-plus me-1"></i> Add Property
                </a>
            </div>
        </div>

        <!-- Statistics Cards Row 1 - Main Metrics -->
        <div class="row mb-3">
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-primary bubble-shadow-small">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Total Users</p>
                                    <h4 class="card-title">{{ $totalUsers }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-info bubble-shadow-small">
                                    <i class="fas fa-warehouse"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Total Properties</p>
                                    <h4 class="card-title">{{ $totalProperties }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-success bubble-shadow-small">
                                    <i class="fas fa-receipt"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Total Transactions</p>
                                    <h4 class="card-title">{{ $totalTransactions }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-warning bubble-shadow-small">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Total Revenue</p>
                                    <h4 class="card-title">Rp {{ number_format($totalRevenue / 1000000, 1) }}M</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards Row 2 - Detailed Metrics -->
        <div class="row mb-3">
            <div class="col-sm-6 col-md-2">
                <div class="card card-stats card-round">
                    <div class="card-body p-3">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-danger bubble-shadow-small">
                                    <i class="fas fa-user-shield"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Admins</p>
                                    <h4 class="card-title">{{ $adminCount }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-2">
                <div class="card card-stats card-round">
                    <div class="card-body p-3">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-warning bubble-shadow-small">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Agents</p>
                                    <h4 class="card-title">{{ $agentCount }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-2">
                <div class="card card-stats card-round">
                    <div class="card-body p-3">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-success bubble-shadow-small">
                                    <i class="fas fa-home"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Available</p>
                                    <h4 class="card-title">{{ $availableProperties }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-2">
                <div class="card card-stats card-round">
                    <div class="card-body p-3">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Sold</p>
                                    <h4 class="card-title">{{ $soldProperties }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-2">
                <div class="card card-stats card-round">
                    <div class="card-body p-3">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-success bubble-shadow-small">
                                    <i class="fas fa-handshake"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Accepted</p>
                                    <h4 class="card-title">{{ $acceptedTransactions }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-2">
                <div class="card card-stats card-round">
                    <div class="card-body p-3">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-warning bubble-shadow-small">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Leading</p>
                                    <h4 class="card-title">{{ $leadingTransactions }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Latest Properties Section -->
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="card card-round">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <div class="card-title">Latest Properties</div>
                            <a href="{{ route('backoffice.properties') }}" class="btn btn-primary btn-sm btn-round ms-auto">
                                View All <i class="fa fa-chevron-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @forelse($latestProperties as $p)
                            <div class="col-md-4 mb-3">
                                <div class="card card-post card-round shadow-sm border h-100">
                                    <div class="card-body">
                                        <div class="info-post">
                                            <p class="username fw-bold mb-0 text-truncate">{{ $p->name }}</p>
                                            <p class="small text-muted mb-2">
                                                <i class="fas fa-map-marker-alt text-danger me-1"></i>{{ $p->city }}
                                            </p>
                                        </div>
                                        <div class="separator-solid my-2"></div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h3 class="card-title fw-bold text-primary mb-0" style="font-size: 1.1rem">
                                                Rp {{ number_format($p->price, 0, ',', '.') }}
                                            </h3>
                                            <span class="badge {{ $p->status == '1' ? 'badge-success' : 'badge-secondary' }}">
                                                {{ $p->status == '1' ? 'Available' : 'Sold' }}
                                            </span>
                                        </div>
                                        <div class="mt-3">
                                            <a href="{{ route('properties.show', $p->id) }}" class="btn btn-outline-primary btn-sm btn-round" target="_blank">
                                                <i class="fa fa-eye me-1"></i> Details
                                            </a>
                                            <a href="{{ route('backoffice.properties.edit', $p->id) }}" class="btn btn-outline-secondary btn-sm btn-round">
                                                <i class="fa fa-edit me-1"></i> Edit
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="col-12 text-center py-4">
                                <p class="text-muted">No properties yet</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Row: New Customers & Recent Transactions -->
        <div class="row">
            <div class="col-md-4">
                <div class="card card-round">
                    <div class="card-header">
                        <div class="card-title">New Customers</div>
                    </div>
                    <div class="card-body">
                        <div class="card-list py-2">
                            @forelse ($newCustomers as $c)
                                <div class="item-list d-flex align-items-center mb-3">
                                    <div class="avatar">
                                        <img src="{{ $c->profile_img ? asset($c->profile_img) : 'https://via.placeholder.com/50' }}" alt="..." class="avatar-img rounded-circle" />
                                    </div>
                                    <div class="info-user ms-3">
                                        <div class="username fw-bold">{{ $c->name }}</div>
                                        <div class="status small text-muted">{{ $c->email }}</div>
                                    </div>
                                    <div class="ms-auto">
                                        <span class="badge badge-secondary">{{ $c->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted text-center">No new customers</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card card-round">
                    <div class="card-header d-flex align-items-center">
                        <div class="card-title">Recent Transactions</div>
                        <a href="{{ route('backoffice.transactions') }}" class="btn btn-primary btn-sm btn-round ms-auto">
                            View All <i class="fa fa-chevron-right ms-1"></i>
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="dashboard-table" class="table align-items-center mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">Payment Details</th>
                                        <th scope="col" class="text-end">Date</th>
                                        <th scope="col" class="text-end">Amount</th>
                                        <th scope="col" class="text-end">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentTransactions as $t)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm me-2">
                                                         <img src="{{ $t->user->profile_img ? asset($t->user->profile_img) : 'https://via.placeholder.com/30' }}" class="avatar-img rounded-circle"/>
                                                    </div>
                                                    <span class="fw-bold">{{ $t->user->name }}</span>
                                                </div>
                                                <small class="text-muted d-block mt-1">Property: {{ $t->property->name ?? 'Deleted' }}</small>
                                            </td>
                                            <td class="text-end">{{ $t->created_at->format('M d, Y') }}</td>
                                            <td class="text-end fw-bold">Rp {{ number_format($t->amount, 0, ',', '.') }}</td>
                                            <td class="text-end">
                                                @php
                                                    $statusClass = $t->status == 'accepted' ? 'success' : ($t->status == 'leading' ? 'warning' : 'secondary');
                                                @endphp
                                                <span class="badge badge-{{ $statusClass }}">
                                                    {{ ucfirst($t->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">No transactions yet</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Initialize DataTable for transactions
            $('#dashboard-table').DataTable({
                "pageLength": 5,
                "searching": false,
                "lengthChange": false,
                "info": false,
                "ordering": false,
                "language": {
                    "paginate": { 
                        "next": '<i class="fa fa-chevron-right"></i>', 
                        "previous": '<i class="fa fa-chevron-left"></i>' 
                    }
                }
            });
        });
    </script>
@endsection
