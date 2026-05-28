@extends('layouts.master')

@section('content-wrapper')
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            User Management
            <small>Administrative Profiles & Roles</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Users</li>
        </ol>
    </section>

    <section class="content">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> Success!</h4>
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="line-height: 30px;">System Users</h3>

                        <div class="box-tools pull-right" style="display: flex; gap: 10px; align-items: center; position: static;">
                            <div class="input-group input-group-sm" style="width: 200px; margin-top: 1px;">
                                <input type="text" id="userSearchInput" class="form-control pull-right" placeholder="Search name or email...">
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>

                            <button type="button" class="btn btn-sm btn-primary btn-flat" data-toggle="modal" data-target="#createUserModal">
                                <i class="fa fa-user-plus"></i> Add New User
                            </button>
                        </div>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover" id="usersTable">
                            <thead>
                                <tr>
                                    <th>Full Name</th>
                                    <th>Email Address</th>
                                    <th>System Role</th>
                                    <th>Status Tracking</th>
                                    <th>Created Date</th>
                                    <th style="width: 260px; text-align: right;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr class="user-row">
                                        <td class="search-name">{{ $user->name }}</td>
                                        <td class="search-email">{{ $user->email }}</td>
                                        <td>
                                            @if($user->role === 'admin')
                                                <span class="label label-danger">Administrator</span>
                                            @else
                                                <span class="label label-info">{{ ucfirst($user->role) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->status)
                                                <span class="label label-success"><i class="fa fa-circle"></i> Active</span>
                                            @else
                                                <span class="label label-default"><i class="fa fa-circle-o"></i> Deactivated</span>
                                            @endif
                                        </td>
                                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                                        <td style="text-align: right;">
                                            <div class="btn-group">
                                                @if($user->id !== auth()->id())
                                                    <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST" style="display: inline-block;">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-default btn-sm">
                                                            @if($user->status)
                                                                <i class="fa fa-ban text-yellow"></i> Deactivate
                                                            @else
                                                                <i class="fa fa-check text-green"></i> Activate
                                                            @endif
                                                        </button>
                                                    </form>
                                                @endif

                                                <button type="button" class="btn btn-default btn-sm edit-user-btn"
                                                        data-id="{{ $user->id }}"
                                                        data-name="{{ $user->name }}"
                                                        data-email="{{ $user->email }}"
                                                        data-role="{{ $user->role }}"
                                                        data-status="{{ $user->status ? '1' : '0' }}">
                                                    <i class="fa fa-edit text-blue"></i> Edit
                                                </button>

                                                @if($user->id !== auth()->id())
                                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to permanently delete this user account?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-default btn-sm">
                                                            <i class="fa fa-trash text-red"></i> Delete
                                                        </button>
                                                    </form>
                                                @else
                                                    <button type="button" class="btn btn-default btn-sm disabled" title="You cannot modify yourself">
                                                        <i class="fa fa-lock text-muted"></i> Active
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr id="emptyRow">
                                        <td colspan="6" class="text-center text-muted" style="padding: 20px;">No records discovered within the user index database context.</td>
                                    </tr>
                                @endforelse
                                <tr id="noSearchResultsRow" style="display: none;">
                                    <td colspan="6" class="text-center text-muted" style="padding: 20px;"><i class="fa fa-search"></i> No users match your search criteria.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>
        </div>
    </section>
    </div>

<div class="modal fade" id="createUserModal" tabindex="-1" role="dialog" aria-labelledby="createUserModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="createUserModalLabel">Create User Account</h4>
            </div>
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Enter Full Name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Enter Email Address" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Assigned System Role</label>
                        <select id="role" name="role" class="form-control" required>
                            <option value="admin">Administrator</option>
                            <option value="counselor">Counselor</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status">Initial Status</label>
                        <select id="status" name="status" class="form-control" required>
                            <option value="1">Active</option>
                            <option value="0">Deactivated</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="password">Password Key</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Enter Password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-flat">Save User Account</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="editUserModalLabel">Modify Profile Parameters</h4>
            </div>
            <form method="POST" id="editUserForm" action="">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_name">Full Name</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_email">Email Address</label>
                        <input type="email" name="email" id="edit_email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_role">Assigned System Role</label>
                        <select name="role" id="edit_role" class="form-control" required>
                            <option value="admin">Administrator</option>
                            <option value="counselor">Counselor</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_status">Operational State</label>
                        <select name="status" id="edit_status" class="form-control" required>
                            <option value="1">Active</option>
                            <option value="0">Deactivated</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_password">Update Password <small class="text-muted">(Leave blank to keep current configuration)</small></label>
                        <input type="password" name="password" id="edit_password" class="form-control" placeholder="••••••••">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-flat">Update Record</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- 1. Edit Modal Population Handler ---
        $('.edit-user-btn').on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var email = $(this).data('email');
            var role = $(this).data('role');
            var status = $(this).data('status');

            $('#editUserForm').attr('action', '/admin/users/' + id);

            $('#edit_name').val(name);
            $('#edit_email').val(email);
            $('#edit_role').val(role);
            $('#edit_status').val(status);

            $('#editUserModal').modal('show');
        });

        // --- 2. Client-Side Live Search Engine ---
        var searchInput = document.getElementById('userSearchInput');

        searchInput.addEventListener('input', function() {
            var filter = searchInput.value.toLowerCase().trim();
            var rows = document.querySelectorAll('.user-row');
            var visibleRowsCount = 0;

            rows.forEach(function(row) {
                var nameText = row.querySelector('.search-name').textContent.toLowerCase();
                var emailText = row.querySelector('.search-email').textContent.toLowerCase();

                // If filter text matches name or email column values
                if (nameText.includes(filter) || emailText.includes(filter)) {
                    row.style.display = '';
                    visibleRowsCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Toggle "No Search Results found" message visibility
            var noResultsRow = document.getElementById('noSearchResultsRow');
            if (rows.length > 0) {
                if (visibleRowsCount === 0) {
                    noResultsRow.style.display = '';
                } else {
                    noResultsRow.style.display = 'none';
                }
            }
        });
    });
</script>
@endsection
