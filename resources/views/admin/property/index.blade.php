@extends('layouts.admin')

@section('title', 'Properties Management - Hoonian Admin')

@section('content')
    <div class="page-inner">
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-primary bubble-shadow-small">
                                    <i class="fas fa-warehouse"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Total Properties</p>
                                    <h4 class="card-title">{{ $stats['total_count'] }}</h4>
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
                                    <i class="fas fa-dollar-sign"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Total Value</p>
                                    <h4 class="card-title">Rp {{ number_format($stats['total_value'], 0, ',', '.') }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-2">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-success bubble-shadow-small">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Available</p>
                                    <h4 class="card-title">{{ $stats['available_count'] }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-2">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                    <i class="fas fa-home"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Sold</p>
                                    <h4 class="card-title">{{ $stats['sold_count'] }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-2">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-warning bubble-shadow-small">
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Featured</p>
                                    <h4 class="card-title">{{ $stats['featured_count'] }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Card -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Property List</h4>
                    <a href="{{ route('backoffice.properties.create') }}" class="btn btn-primary btn-round ms-auto">
                        <i class="fa fa-plus"></i>
                        New Property
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Advanced Filters -->
                <div class="card mb-3" style="background-color: #f8f9fa;">
                    <div class="card-body">
                        <form method="GET" action="{{ route('backoffice.properties') }}" id="filterForm">
                            <div class="row align-items-end">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">City</label>
                                        <input type="text" name="city" class="form-control" 
                                               value="{{ request('city') }}" placeholder="e.g. Jakarta" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Min Price</label>
                                        <input type="number" name="min_price" class="form-control" 
                                               value="{{ request('min_price') }}" placeholder="0" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Max Price</label>
                                        <input type="number" name="max_price" class="form-control" 
                                               value="{{ request('max_price') }}" placeholder="999999999" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Owner</label>
                                        <select name="owner_id" class="form-control">
                                            <option value="">All Owners</option>
                                            @foreach($owners as $owner)
                                                <option value="{{ $owner->id }}" {{ request('owner_id') == $owner->id ? 'selected' : '' }}>
                                                    {{ $owner->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Type</label>
                                        <select name="property_type" class="form-control">
                                            <option value="">All Types</option>
                                            @foreach($types as $type)
                                                <option value="{{ $type->id }}" {{ request('property_type') == $type->id ? 'selected' : '' }}>
                                                    {{ $type->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-block w-100">
                                            <i class="fa fa-filter"></i> Apply
                                        </button>
                                        <a href="{{ route('backoffice.properties') }}" class="btn btn-secondary btn-block w-100 mt-2">
                                            <i class="fa fa-times"></i> Clear
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- DataTable -->
                <div class="table-responsive">
                    <table id="property-table" class="display table table-striped table-hover" style="opacity: 0;">
                        <thead>
                            <tr>
                                <th>Thumbnail</th>
                                <th>Name</th>
                                <th>City</th>
                                <th>Price</th>
                                <th>Owner</th>
                                <th>Status</th>
                                <th style="width: 10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($properties as $item)
                                <tr>
                                    <td>
                                        <div class="avatar avatar-sm">
                                            @php
                                                $pic = 'https://via.placeholder.com/50';
                                                if ($item->pictures && count($item->pictures) > 0) {
                                                    $pic = asset($item->pictures[0]); 
                                                }
                                            @endphp
                                            <img src="{{ $pic }}" alt="..." class="avatar-img rounded-circle">
                                        </div>
                                    </td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->city }}</td>
                                    <td data-order="{{ $item->price }}">{{ 'Rp ' . number_format($item->price, 0, ',', '.') }}</td>
                                    <td>
                                        @if($item->owner)
                                            <a href="{{ route('users.profile', $item->owner->id) }}">{{ $item->owner->name }}</a>
                                        @else
                                            <span class="text-muted">Unknown</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->status == '1')
                                            <span class="badge badge-success">Available</span>
                                        @else
                                            <span class="badge badge-secondary">Sold</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="form-button-action">
                                            <a href="{{ route('backoffice.properties.edit', $item->id) }}" class="btn btn-link btn-primary btn-lg">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-link btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $item->id }}">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>

                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1"
                                            role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-sm" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header border-0">
                                                        <h5 class="modal-title">Confirm Delete</h5>
                                                        <button type="button" class="close" data-bs-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <p>Are you sure you want to delete <br><b>{{ $item->name }}</b>?</p>
                                                    </div>
                                                    <div class="modal-footer border-0 justify-content-center">
                                                        <form action="{{ route('backoffice.properties.destroy', $item->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Yes, Delete!</button>
                                                        </form>
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">No</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>

    <script>
        $(document).ready(function() {
            // Get current date for filename
            var today = new Date();
            var dateStr = today.getFullYear() + '-' + 
                         String(today.getMonth() + 1).padStart(2, '0') + '-' + 
                         String(today.getDate()).padStart(2, '0');
            
            // Get filter info for filename
            var filterInfo = '';
            @if(request('city'))
                filterInfo += '_{{ request("city") }}';
            @endif
            @if(request('min_price') || request('max_price'))
                filterInfo += '_filtered';
            @endif

            // Initialize DataTable with enhanced configuration
            var table = $('#property-table').DataTable({
                "pageLength": 25,
                "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                "order": [[ 1, "asc" ]], // Sort by name ascending
                "responsive": true,
                "dom": 'Blfrtip',
                "buttons": [
                    {
                        extend: 'copy',
                        text: '<i class="fa fa-copy"></i> Copy',
                        className: 'btn btn-info btn-sm',
                        exportOptions: { 
                            columns: [1, 2, 3, 4, 5],
                            format: {
                                body: function(data, row, column, node) {
                                    // Remove HTML tags for clean export
                                    return $(data).text();
                                }
                            }
                        },
                        title: 'Property List - ' + dateStr
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fa fa-file-excel"></i> Excel',
                        className: 'btn btn-success btn-sm',
                        exportOptions: { 
                            columns: [1, 2, 3, 4, 5],
                            format: {
                                body: function(data, row, column, node) {
                                    return $(data).text();
                                }
                            }
                        },
                        title: 'Property List Report',
                        filename: 'properties_' + dateStr + filterInfo
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fa fa-file-pdf"></i> PDF',
                        className: 'btn btn-danger btn-sm',
                        exportOptions: { 
                            columns: [1, 2, 3, 4, 5],
                            format: {
                                body: function(data, row, column, node) {
                                    return $(data).text();
                                }
                            }
                        },
                        title: 'Property List Report',
                        filename: 'properties_' + dateStr + filterInfo,
                        orientation: 'landscape',
                        pageSize: 'A4',
                        customize: function(doc) {
                            doc.styles.title = {
                                fontSize: 16,
                                bold: true,
                                alignment: 'center',
                                margin: [0, 0, 0, 10]
                            };
                            doc.content[1].table.widths = ['20%', '15%', '20%', '20%', '15%'];
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i> Print',
                        className: 'btn btn-secondary btn-sm',
                        exportOptions: { 
                            columns: [1, 2, 3, 4, 5],
                            format: {
                                body: function(data, row, column, node) {
                                    return $(data).text();
                                }
                            }
                        },
                        title: 'Property List Report - ' + dateStr,
                        customize: function(win) {
                            $(win.document.body).css('font-size', '10pt');
                            $(win.document.body).find('table')
                                .addClass('compact')
                                .css('font-size', 'inherit');
                        }
                    },
                    {
                        extend: 'colvis',
                        text: '<i class="fa fa-columns"></i> Columns',
                        className: 'btn btn-primary btn-sm'
                    }
                ],
                "initComplete": function(settings, json) {
                    // Fade in table smoothly
                    $('#property-table').animate({ opacity: 1 }, 600);
                    
                    // Move buttons to the header
                    table.buttons().container().appendTo('.card-header .d-flex');
                    $('.dt-buttons').addClass('me-2');
                    
                    // Remove default DataTables button class for better styling
                    $('.dt-button').removeClass('dt-button');
                }
            });

            // Add search placeholder
            $('div.dataTables_filter input').attr('placeholder', 'Search properties...');
        });
    </script>
@endsection
