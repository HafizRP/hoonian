@extends('layouts.admin')

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
                                    <i class="fas fa-receipt"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Total Transactions</p>
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
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Total Revenue</p>
                                    <h4 class="card-title">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</h4>
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
                                    <p class="card-category">Accepted</p>
                                    <h4 class="card-title">{{ $stats['success_count'] }}</h4>
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
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Leading</p>
                                    <h4 class="card-title">{{ $stats['pending_count'] }}</h4>
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
                                    <i class="fas fa-times-circle"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Outbid</p>
                                    <h4 class="card-title">{{ $stats['failed_count'] }}</h4>
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
                    <h4 class="card-title">Transaction List</h4>
                </div>
            </div>
            <div class="card-body">
                <!-- Advanced Filters -->
                <div class="card mb-3" style="background-color: #f8f9fa;">
                    <div class="card-body">
                        <form method="GET" action="{{ route('backoffice.transactions') }}" id="filterForm">
                            <div class="row align-items-end">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Date From</label>
                                        <input type="date" name="date_from" class="form-control" 
                                               value="{{ request('date_from') }}" />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Date To</label>
                                        <input type="date" name="date_to" class="form-control" 
                                               value="{{ request('date_to') }}" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Status</label>
                                        <select name="status" class="form-control">
                                            <option value="">All Status</option>
                                            <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                                            <option value="leading" {{ request('status') == 'leading' ? 'selected' : '' }}>Leading</option>
                                            <option value="outbid" {{ request('status') == 'outbid' ? 'selected' : '' }}>Outbid</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Property</label>
                                        <select name="property_id" class="form-control">
                                            <option value="">All Properties</option>
                                            @foreach($properties as $prop)
                                                <option value="{{ $prop->id }}" {{ request('property_id') == $prop->id ? 'selected' : '' }}>
                                                    {{ $prop->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-block w-100">
                                            <i class="fa fa-filter"></i> Apply Filters
                                        </button>
                                        <a href="{{ route('backoffice.transactions') }}" class="btn btn-secondary btn-block w-100 mt-2">
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
                    <table id="transaction-table" class="display table table-striped table-hover" style="opacity: 0;">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Transaction ID</th>
                                <th>Property</th>
                                <th>User</th>
                                <th>Total Price</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $trx)
                                <tr>
                                    <td>{{ $trx->created_at->format('d M Y') }}</td>
                                    <td>#TRX-{{ str_pad($trx->id, 5, '0', STR_PAD_LEFT) }}</td>
                                    <td>
                                        @if($trx->property)
                                            <a href="{{ route('properties.show', $trx->property->id) }}" target="_blank">{{ $trx->property->name }}</a>
                                        @else
                                            <span class="text-muted">Deleted Property</span>
                                        @endif
                                    </td>
                                    <td>{{ $trx->user->name ?? 'Unknown' }}</td>
                                    <td data-order="{{ $trx->amount }}">{{ 'Rp ' . number_format($trx->amount, 0, ',', '.') }}</td>
                                    <td>
                                        @php
                                            $statusClass = $trx->status == 'accepted' ? 'badge-success' : ($trx->status == 'leading' ? 'badge-warning' : 'badge-secondary');
                                            $statusLabel = $trx->status == 'accepted' ? 'ACCEPTED' : ($trx->status == 'leading' ? 'LEADING' : 'OUTBID');
                                        @endphp
                                        <span class="badge {{ $statusClass }}">
                                            {{ $statusLabel }}
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
            @if(request('status'))
                filterInfo += '_{{ request("status") }}';
            @endif
            @if(request('date_from') || request('date_to'))
                filterInfo += '_filtered';
            @endif

            // Initialize DataTable with enhanced configuration
            var table = $('#transaction-table').DataTable({
                "pageLength": 25,
                "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                "order": [[ 0, "desc" ]], // Sort by date descending (newest first)
                "responsive": true,
                "dom": 'Blfrtip',
                "buttons": [
                    {
                        extend: 'copy',
                        text: '<i class="fa fa-copy"></i> Copy',
                        className: 'btn btn-info btn-sm',
                        exportOptions: { 
                            columns: [0, 1, 2, 3, 4, 5],
                            format: {
                                body: function(data, row, column, node) {
                                    // Remove HTML tags for clean export
                                    return column === 2 ? $(data).text() : data;
                                }
                            }
                        },
                        title: 'Transaction Report - ' + dateStr
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fa fa-file-excel"></i> Excel',
                        className: 'btn btn-success btn-sm',
                        exportOptions: { 
                            columns: [0, 1, 2, 3, 4, 5],
                            format: {
                                body: function(data, row, column, node) {
                                    // Clean HTML for property column
                                    if (column === 2) {
                                        return $(data).text();
                                    }
                                    // Clean badge HTML for status column
                                    if (column === 5) {
                                        return $(data).text();
                                    }
                                    return data;
                                }
                            }
                        },
                        title: 'Transaction Report',
                        filename: 'transactions_' + dateStr + filterInfo
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fa fa-file-pdf"></i> PDF',
                        className: 'btn btn-danger btn-sm',
                        exportOptions: { 
                            columns: [0, 1, 2, 3, 4, 5],
                            format: {
                                body: function(data, row, column, node) {
                                    if (column === 2) {
                                        return $(data).text();
                                    }
                                    if (column === 5) {
                                        return $(data).text();
                                    }
                                    return data;
                                }
                            }
                        },
                        title: 'Transaction Report',
                        filename: 'transactions_' + dateStr + filterInfo,
                        orientation: 'landscape',
                        pageSize: 'A4',
                        customize: function(doc) {
                            doc.styles.title = {
                                fontSize: 16,
                                bold: true,
                                alignment: 'center',
                                margin: [0, 0, 0, 10]
                            };
                            doc.content[1].table.widths = ['12%', '15%', '23%', '20%', '15%', '15%'];
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i> Print',
                        className: 'btn btn-secondary btn-sm',
                        exportOptions: { 
                            columns: [0, 1, 2, 3, 4, 5],
                            format: {
                                body: function(data, row, column, node) {
                                    if (column === 2) {
                                        return $(data).text();
                                    }
                                    if (column === 5) {
                                        return $(data).text();
                                    }
                                    return data;
                                }
                            }
                        },
                        title: 'Transaction Report - ' + dateStr,
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
                    $('#transaction-table').animate({ opacity: 1 }, 600);
                    
                    // Move buttons to the header
                    table.buttons().container().appendTo('.card-header .d-flex');
                    $('.dt-buttons').addClass('ms-auto');
                    
                    // Remove default DataTables button class for better styling
                    $('.dt-button').removeClass('dt-button');
                }
            });

            // Add search placeholder
            $('div.dataTables_filter input').attr('placeholder', 'Search transactions...');
        });
    </script>
@endsection
