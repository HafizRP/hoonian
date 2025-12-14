@extends('layouts.admin')

@section('content')
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Dashboard Overview</h3>
                <h6 class="op-7 mb-2">Welcome back, Admin! Here is your business summary.</h6>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
                <a href="#" class="btn btn-label-info btn-round me-2">Generate Report</a>
                <button class="btn btn-primary btn-round" id="btn-add">
                    <i class="fa fa-plus me-1"></i> Add Customer
                </button>
            </div>
        </div>

        <div class="row">
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
                                    <p class="card-category">Total Visitors</p>
                                    <h4 class="card-title">{{ $totalUsers }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card card-round">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <div class="card-title">Latest Properties</div>
                            <a href="#" class="btn btn-primary btn-link ms-auto">View All <i class="fa fa-chevron-right ms-1"></i></a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($latestProperties as $p)
                            <div class="col-md-4 mb-3">
                                <div class="card card-post card-round shadow-sm border">
                                    <div class="card-body">
                                        <div class="info-post">
                                            <p class="username fw-bold mb-0 text-truncate">{{ $p->name }}</p>
                                            <p class="small text-muted mb-2"><i class="fas fa-map-marker-alt text-danger me-1"></i>{{ $p->city }}</p>
                                        </div>
                                        <div class="separator-solid my-2"></div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h3 class="card-title fw-bold text-primary" style="font-size: 1.1rem">
                                                Rp {{ number_format($p->price, 0, ',', '.') }}
                                            </h3>
                                            <span class="badge badge-info">{{ $p->property_type }}</span>
                                        </div>
                                        <div class="mt-3">
                                            <a href="{{ route('properties.show', $p->id) }}" class="btn btn-outline-primary btn-sm btn-round btn-detail-prop" target="_blank">Details</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card card-round">
                    <div class="card-header">
                        <div class="card-title">New Customers</div>
                    </div>
                    <div class="card-body">
                        <div class="card-list py-2">
                            @foreach ($newCustomers as $c)
                                <div class="item-list d-flex align-items-center mb-3">
                                    <div class="avatar">
                                        <img src="{{ $c->profile_img ? asset($c->profile_img) : 'https://via.placeholder.com/50' }}" alt="..." class="avatar-img rounded-circle" />
                                    </div>
                                    <div class="info-user ms-3">
                                        <div class="username fw-bold">{{ $c->name }}</div>
                                        <div class="status small text-muted">{{ $c->email }}</div>
                                    </div>
                                    <div class="ms-auto">
                                        <button class="btn btn-icon btn-link btn-primary btn-email" data-name="{{ $c->name }}">
                                            <i class="far fa-envelope"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card card-round">
                    <div class="card-header d-flex align-items-center">
                        <div class="card-title">Transaction History</div>
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
                                    @foreach ($recentTransactions as $t)
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
                                                <span class="badge badge-{{ $t->status == 'accepted' ? 'success' : ($t->status == 'rejected' ? 'danger' : 'warning') }}">
                                                    {{ $t->status }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
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
            // 1. Inisialisasi DataTable
            $('#dashboard-table').DataTable({
                "pageLength": 5,
                "searching": false,
                "lengthChange": false,
                "info": false,
                "language": {
                    "paginate": { "next": '<i class="fa fa-chevron-right"></i>', "previous": '<i class="fa fa-chevron-left"></i>' }
                }
            });

            // 2. Alert Add Customer
            $('#btn-add').on('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'New Customer',
                    text: "Open the registration form?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Open',
                    customClass: { confirmButton: 'btn btn-primary', cancelButton: 'btn btn-danger' },
                    buttonsStyling: false
                });
            });

            // 3. Email & Ban Alerts (Delegated)
            $(document).on('click', '.btn-email', function() {
                Swal.fire('Info', `Prepare email for ${$(this).data('name')}?`, 'info');
            });

            $(document).on('click', '.btn-ban', function() {
                Swal.fire({
                    title: `Block ${$(this).data('name')}?`,
                    text: "Action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Block It!',
                    confirmButtonColor: '#d33'
                });
            });

            // 4. Toast Verify Payment
            $(document).on('click', '.btn-check-pay', function() {
                Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                }).fire({ icon: 'success', title: 'Payment verified' });
            });

            // 5. Property Detail Alert
            $(document).on('click', '.btn-detail-prop', function() {
                Swal.fire('Property Detail', `Showing details for ${$(this).data('name')}`, 'info');
            });

            // 6. Wishlist Toggle
            $(document).on('click', '.btn-wishlist', function() {
                const icon = $(this).find('i');
                icon.toggleClass('far fas text-danger');
                if(icon.hasClass('fas')) {
                    Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 1500 })
                    .fire({ icon: 'success', title: 'Added to favorites' });
                }
            });
        });
    </script>
@endsection
