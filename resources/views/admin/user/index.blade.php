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
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Total Users</p>
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
                                <div class="icon-big text-center icon-danger bubble-shadow-small">
                                    <i class="fas fa-user-shield"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Admins</p>
                                    <h4 class="card-title">{{ $stats['admin_count'] }}</h4>
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
                                    <i class="fas fa-user-tie"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Agents</p>
                                    <h4 class="card-title">{{ $stats['agent_count'] }}</h4>
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
                                <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Regular Users</p>
                                    <h4 class="card-title">{{ $stats['user_count'] }}</h4>
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
                    <h4 class="card-title">Users List</h4>
                    <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal" data-bs-target="#addRowModal">
                        <i class="fa fa-plus"></i>
                        Add User
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Add User Modal -->
                <div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header border-0">
                                <h5 class="modal-title"><span class="fw-mediumbold">New</span> User</h5>
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
                                                    <option value="2">AGENT</option>
                                                    <option value="3" selected>USER</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer border-0">
                                    <button type="submit" class="btn btn-primary">Add</button>
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Advanced Filters -->
                <div class="card mb-3" style="background-color: #f8f9fa;">
                    <div class="card-body">
                        <form method="GET" action="{{ route('backoffice.users') }}" id="filterForm">
                            <div class="row align-items-end">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Search</label>
                                        <input type="text" name="search" class="form-control" 
                                               value="{{ request('search') }}" placeholder="Search by name or email..." />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Role</label>
                                        <select name="role" class="form-control">
                                            <option value="">All Roles</option>
                                            <option value="1" {{ request('role') == '1' ? 'selected' : '' }}>Admin</option>
                                            <option value="2" {{ request('role') == '2' ? 'selected' : '' }}>Agent</option>
                                            <option value="3" {{ request('role') == '3' ? 'selected' : '' }}>User</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-block w-100">
                                            <i class="fa fa-filter"></i> Apply Filters
                                        </button>
                                        <a href="{{ route('backoffice.users') }}" class="btn btn-secondary btn-block w-100 mt-2">
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
                                        @php
                                            $roleClass = $user->role == 1 ? 'badge-danger' : ($user->role == 2 ? 'badge-warning' : 'badge-secondary');
                                        @endphp
                                        <span class="badge {{ $roleClass }}">
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
            @if(request('role'))
                filterInfo += '_role{{ request("role") }}';
            @endif
            @if(request('search'))
                filterInfo += '_filtered';
            @endif

            // Initialize DataTable with enhanced configuration
            var table = $('#add-row').DataTable({
                "pageLength": 25,
                "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                "order": [[ 2, "desc" ]], // Sort by joined date descending (newest first)
                "responsive": true,
                "dom": 'Blfrtip',
                "buttons": [
                    {
                        extend: 'copy',
                        text: '<i class="fa fa-copy"></i> Copy',
                        className: 'btn btn-info btn-sm',
                        exportOptions: { 
                            columns: [0, 1, 2],
                            format: {
                                body: function(data, row, column, node) {
                                    // Remove HTML tags for clean export
                                    return $(data).text();
                                }
                            }
                        },
                        title: 'User List - ' + dateStr
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fa fa-file-excel"></i> Excel',
                        className: 'btn btn-success btn-sm',
                        exportOptions: { 
                            columns: [0, 1, 2],
                            format: {
                                body: function(data, row, column, node) {
                                    return $(data).text();
                                }
                            }
                        },
                        title: 'User List Report',
                        filename: 'users_' + dateStr + filterInfo
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fa fa-file-pdf"></i> PDF',
                        className: 'btn btn-danger btn-sm',
                        exportOptions: { 
                            columns: [0, 1, 2],
                            format: {
                                body: function(data, row, column, node) {
                                    return $(data).text();
                                }
                            }
                        },
                        title: 'User List Report',
                        filename: 'users_' + dateStr + filterInfo,
                        orientation: 'portrait',
                        pageSize: 'A4',
                        customize: function(doc) {
                            doc.styles.title = {
                                fontSize: 16,
                                bold: true,
                                alignment: 'center',
                                margin: [0, 0, 0, 10]
                            };
                            doc.content[1].table.widths = ['50%', '25%', '25%'];
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i> Print',
                        className: 'btn btn-secondary btn-sm',
                        exportOptions: { 
                            columns: [0, 1, 2],
                            format: {
                                body: function(data, row, column, node) {
                                    return $(data).text();
                                }
                            }
                        },
                        title: 'User List Report - ' + dateStr,
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
                    $('#add-row').animate({ opacity: 1 }, 600);
                    
                    // Move buttons to the header
                    table.buttons().container().appendTo('.card-header .d-flex');
                    $('.dt-buttons').addClass('me-2');
                    
                    // Remove default DataTables button class for better styling
                    $('.dt-button').removeClass('dt-button');
                }
            });

            // Add search placeholder
            $('div.dataTables_filter input').attr('placeholder', 'Search users...');
        });
    </script>
@endsection
