@extends('layouts.admin')

@section('content')
    <div class="page-inner">
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
                <div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header border-0">
                                <h5 class="modal-title">
                                    <span class="fw-mediumbold"> New</span>
                                    <span class="fw-light"> Property </span>
                                </h5>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="formAddProperty">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group form-group-default">
                                                <label>Property Name</label>
                                                <input id="addName" type="text" class="form-control"
                                                    placeholder="fill name" />
                                            </div>
                                        </div>
                                        <div class="col-md-6 pe-0">
                                            <div class="form-group form-group-default">
                                                <label>City</label>
                                                <input id="addCity" type="text" class="form-control"
                                                    placeholder="fill city" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-group-default">
                                                <label>Price</label>
                                                <input id="addPrice" type="number" class="form-control"
                                                    placeholder="fill price" />
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer border-0">
                                <button type="button" id="addRowButton" class="btn btn-primary">Add</button>
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="property-table" class="display table table-striped table-hover" style="opacity: 0;">
                        <thead>
                            <tr>
                                <th>Thumbnail</th>
                                <th>Name</th>
                                <th>City</th>
                                <th>Price</th>
                                <th>Owner</th>
                                <th style="width: 10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($properties as $item)
                                <tr>
                                    <td>
                                        <div class="avatar avatar-sm">
                                            @php
                                                // Handle potential different structure of pictures
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
                                    <td>{{ 'Rp ' . number_format($item->price, 0, ',', '.') }}</td>
                                    <td>
                                        @if($item->owner)
                                            <a href="{{ route('users.profile', $item->owner->id) }}">{{ $item->owner->name }}</a>
                                        @else
                                            <span class="text-muted">Unknown</span>
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

    <script>
        $(document).ready(function() {

            // 1. Inisialisasi DataTable dengan Buttons dan Animasi
            var table = $('#property-table').DataTable({
                "pageLength": 10,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i> Print',
                        className: 'btn btn-secondary btn-sm',
                        exportOptions: {
                            columns: [0, 1, 2, 3] // Name, City, Price, Owner
                        },
                        title: 'Property List Report'
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fa fa-file-excel"></i> Excel',
                        className: 'btn btn-success btn-sm',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        },
                        title: 'Property List Report'
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fa fa-file-pdf"></i> PDF',
                        className: 'btn btn-danger btn-sm',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        },
                        title: 'Property List Report'
                    }
                ],
                "initComplete": function(settings, json) {
                    $('#property-table').animate({ opacity: 1 }, 1000);
                    // Move buttons to the header
                    table.buttons().container().appendTo('.card-header .d-flex');
                    $('.dt-buttons').addClass('ms-2'); // Add margin
                    $('.dt-button').removeClass('dt-button'); // Remove default class
                }
            });

            // 2. SweetAlert untuk Save Edit
            $('.btn-save-edit').on('click', function() {
                var id = $(this).data('id');
                // Form submission logic will be handled normally via HTML form, 
                // but if keeping SweetAlert, it should trigger form submit
                $('#editModal' + id).modal('hide');
            });

            // 3. SweetAlert untuk Delete
            $('.btn-confirm-delete').on('click', function() {
                var id = $(this).data('id');
                // Logic untuk submit form delete (akan diimplementasikan di view)
            });
        });
    </script>
@endsection
