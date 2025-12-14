@extends('layouts.admin')

@section('content')
    <div class="page-inner">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Users List</h4>
                    <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal" data-bs-target="#addRowModal">
                        <i class="fa fa-plus"></i>
                        Add Row
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header border-0">
                                <h5 class="modal-title"><span class="fw-mediumbold">New</span> Row</h5>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{ route('backoffice.users.store') }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group form-group-default">
                                                <label>Name</label>
                                                <input name="name" type="text" class="form-control" placeholder="fill name" required />
                                            </div>
                                        </div>
                                        <div class="col-md-6 pe-0">
                                            <div class="form-group form-group-default">
                                                <label>Email</label>
                                                <input name="email" type="email" class="form-control" placeholder="fill email" required />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-group-default">
                                                <label>Password</label>
                                                <input name="password" type="password" class="form-control" placeholder="password" required />
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group form-group-default">
                                                <label>Role</label>
                                                <select class="form-control" name="role">
                                                    <option value="1">ADMIN</option>
                                                    <option value="3" selected>USER</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer border-0">
                                    <button type="submit" id="addRowButton" class="btn btn-primary">Add</button>
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="add-row" class="display table table-striped table-hover" style="opacity: 0;">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Role</th>
                                <th>Joined Date</th>
                                <th style="width: 10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                <img src="{{ $user->profile_img ? asset($user->profile_img) : 'https://via.placeholder.com/50' }}" alt="..." class="avatar-img rounded-circle">
                                            </div>
                                            <div>
                                                <h6 class="fw-bold mb-0">{{ $user->name }}</h6>
                                                <small class="text-muted">{{ $user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $user->role == 1 || $user->role == 'admin' ? 'danger' : 'secondary' }}">
                                            {{ $user->roleData->name ?? 'User' }}
                                        </span>
                                    </td>
                                    <td>{{ $user->created_at->format('d M Y') }}</td>
                                    <td>
                                        <div class="form-button-action">
                                            <button type="button" class="btn btn-link btn-primary btn-lg"
                                                data-bs-toggle="modal" data-bs-target="#editModal{{ $user->id }}">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-link btn-danger"
                                                data-bs-toggle="modal" data-bs-target="#deleteModal{{ $user->id }}">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>

                                        <!-- Edit Modal -->
                                        <div class="modal fade" id="editModal{{ $user->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header border-0">
                                                        <h5 class="modal-title">Edit User: <b>{{ $user->name }}</b></h5>
                                                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('backoffice.users.update', $user->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <div class="form-group form-group-default">
                                                                        <label>Name</label>
                                                                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required />
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group form-group-default">
                                                                        <label>Email</label>
                                                                        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required />
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group form-group-default">
                                                                        <label>Role</label>
                                                                        <select class="form-control" name="role">
                                                                            <option value="1" {{ $user->role == 1 ? 'selected' : '' }}>Admin</option>
                                                                            <option value="2" {{ $user->role == 2 ? 'selected' : '' }}>Agent</option>
                                                                            <option value="3" {{ $user->role == 3 ? 'selected' : '' }}>User</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-0">
                                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-sm" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header border-0">
                                                        <h5 class="modal-title">Confirm Delete</h5>
                                                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <p>Are you sure you want to delete <br><b>{{ $user->name }}</b>?</p>
                                                    </div>
                                                    <div class="modal-footer border-0 justify-content-center">
                                                        <form action="{{ route('backoffice.users.destroy', $user->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Yes, Delete it!</button>
                                                        </form>
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
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
        jQuery(document).ready(function($) {
            // 1. Init Datatable
            var table = $('#add-row').DataTable({
                "pageLength": 5,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i> Print Report',
                        className: 'btn btn-secondary btn-sm',
                        exportOptions: {
                            columns: [0, 1, 2] // Index columns to export (User, Role, Joined)
                        },
                        title: 'User List Report'
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fa fa-file-excel"></i> Export Excel',
                        className: 'btn btn-success btn-sm',
                        exportOptions: {
                            columns: [0, 1, 2]
                        },
                        title: 'User List Report'
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fa fa-file-pdf"></i> Export PDF',
                        className: 'btn btn-danger btn-sm',
                        exportOptions: {
                            columns: [0, 1, 2]
                        },
                        title: 'User List Report'
                    }
                ],
                "initComplete": function(settings, json) {
                    $('#add-row').animate({ opacity: 1 }, 1000);
                    // Move buttons to the header
                    table.buttons().container().appendTo('.card-header .d-flex');
                    $('.dt-buttons').addClass('ms-2'); // Add margin
                    $('.dt-button').removeClass('dt-button'); // Remove default class
                }
            });

            // 2. Aksi Confirm Delete (SweetAlert)
            $('.btn-confirm-delete').click(function() {
                var userId = $(this).data('id');
                var userName = $(this).data('name');

                // Tutup modal dulu baru munculkan SweetAlert
                $('#deleteModal' + userId).modal('hide');

                swal({
                    title: "Terhapus!",
                    text: "User " + userName + " telah berhasil dihapus.",
                    icon: "success",
                    buttons: {
                        confirm: {
                            className: 'btn btn-success'
                        }
                    }
                });
            });

            // 3. Aksi Save Edit (SweetAlert)
            $('.btn-save-edit').click(function() {
                var userId = $(this).data('id');
                $('#editModal' + userId).modal('hide');

                swal({
                    title: "Berhasil!",
                    text: "Data user telah diperbarui.",
                    icon: "success",
                    buttons: {
                        confirm: {
                            className: 'btn btn-success'
                        }
                    }
                });
            });

            // 4. Tombol Add Row
            $('#addRowButton').click(function() {
                var name = $('#addName').val();
                if (name !== "") {
                    $('#addRowModal').modal('hide');
                    swal("Berhasil!", "User " + name + " ditambahkan.", "success");
                }
            });
        });
    </script>
@endsection
