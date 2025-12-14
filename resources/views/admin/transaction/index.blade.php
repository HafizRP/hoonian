@extends('layouts.admin')

@section('content')
    <div class="page-inner">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Transaction List</h4>
                    <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal" data-bs-target="#addRowModal">
                        <i class="fa fa-plus"></i>
                        New Transaction
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header border-0">
                                <h5 class="modal-title">
                                    <span class="fw-mediumbold"> New</span>
                                    <span class="fw-light"> Transaction </span>
                                </h5>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="formAddTransaction">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group form-group-default">
                                                <label>Property</label>
                                                <select class="form-control" id="addProperty">
                                                    @foreach($properties as $prop)
                                                        <option value="{{ $prop->id }}">{{ $prop->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 pe-0">
                                            <div class="form-group form-group-default">
                                                <label>Transaction Date</label>
                                                <input id="addDate" type="date" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-group-default">
                                                <label>Amount (Rp)</label>
                                                <input id="addAmount" type="number" class="form-control" placeholder="0" />
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer border-0">
                                <button type="button" id="addRowButton" class="btn btn-primary">Create</button>
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="transaction-table" class="display table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Transaction ID</th>
                                <th>Property</th>
                                <th>User</th>
                                <th>Total Price</th>
                                <th>Status</th>
                                {{-- <th style="width: 10%">Action</th> --}}
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
                                    <td>{{ 'Rp ' . number_format($trx->amount, 0, ',', '.') }}</td>
                                    <td>
                                        @php
                                            $statusClass = $trx->status == 'success' ? 'badge-success' : ($trx->status == 'pending' ? 'badge-warning' : 'badge-danger');
                                        @endphp
                                        <span class="badge {{ $statusClass }}">
                                            {{ strtoupper($trx->status) }}
                                        </span>
                                    </td>
                                    {{-- <td>
                                        <div class="form-button-action">
                                            <button type="button" class="btn btn-link btn-primary btn-lg"
                                                data-bs-toggle="modal" data-bs-target="#editTrx{{ $trx->id }}">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                        </div>
                                    </td> --}}
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

    <script>
        $(document).ready(function() {
            // Init DataTable
            var table = $('#transaction-table').DataTable({
                "pageLength": 10,
                "order": [[ 0, "desc" ]], // Newest first
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i> Print',
                        className: 'btn btn-secondary btn-sm',
                        exportOptions: { columns: [0, 1, 2, 3, 4, 5] },
                        title: 'Transaction Report'
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fa fa-file-excel"></i> Excel',
                        className: 'btn btn-success btn-sm',
                        exportOptions: { columns: [0, 1, 2, 3, 4, 5] },
                        title: 'Transaction Report'
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fa fa-file-pdf"></i> PDF',
                        className: 'btn btn-danger btn-sm',
                        exportOptions: { columns: [0, 1, 2, 3, 4, 5] },
                        title: 'Transaction Report'
                    }
                ],
                "initComplete": function(settings, json) {
                    // Fade in table
                    $('#transaction-table').css('opacity', '1');
                    
                    // Move buttons
                    table.buttons().container().appendTo('.card-header .d-flex');
                    $('.dt-buttons').addClass('ms-2');
                    $('.dt-button').removeClass('dt-button');
                }
            });
        });
    </script>
