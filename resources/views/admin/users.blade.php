@extends('layouts.master')

@section('content-wrapper')
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            User Management
            <small>Administrative Profiles &amp; Roles</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Users</li>
        </ol>
    </section>

    <section class="content">

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible modern-alert">
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
                <div class="box box-primary dashboard-box">
                    <div class="box-header with-border users-box-header">
                        <h3 class="box-title">System Users</h3>

                        <div class="box-tools pull-right users-toolbar">
                            <div class="input-group input-group-sm search-input-group">
                                <input type="text" id="userSearchInput" class="form-control" placeholder="Search name or email...">
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>

                            <button type="button" class="btn btn-sm btn-primary btn-flat action-btn" data-toggle="modal" data-target="#createUserModal">
                                <i class="fa fa-user-plus"></i> Add New User
                            </button>
                        </div>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover modern-table" id="usersTable">
                            <thead>
                                <tr class="table-head-row">
                                    <th>Full Name</th>
                                    <th>Email Address</th>
                                    <th>System Role</th>
                                    <th>Status Tracking</th>
                                    <th>Created Date</th>
                                    <th class="actions-col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr class="user-row">
                                        <td class="search-name name-cell">{{ $user->name }}</td>
                                        <td class="search-email email-cell">{{ $user->email }}</td>
                                        <td>
                                            @if($user->role === 'admin')
                                                <span class="label label-danger role-badge">Administrator</span>
                                            @else
                                                <span class="label label-info role-badge">{{ ucfirst($user->role) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->status)
                                                <span class="label label-success role-badge"><i class="fa fa-circle"></i> Active</span>
                                            @else
                                                <span class="label label-default role-badge"><i class="fa fa-circle-o"></i> Deactivated</span>
                                            @endif
                                        </td>
                                        <td class="date-cell">{{ $user->created_at->format('M d, Y') }}</td>
                                        <td class="actions-col">
                                            <div class="btn-group">
                                                @if($user->id !== auth()->id())
                                                    <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST" class="inline-form">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-default btn-sm table-action-btn">
                                                            @if($user->status)
                                                                <i class="fa fa-ban text-yellow"></i> Deactivate
                                                            @else
                                                                <i class="fa fa-check text-green"></i> Activate
                                                            @endif
                                                        </button>
                                                    </form>
                                                @endif

                                                <button type="button" class="btn btn-default btn-sm table-action-btn edit-user-btn"
                                                        data-id="{{ $user->id }}"
                                                        data-name="{{ $user->name }}"
                                                        data-email="{{ $user->email }}"
                                                        data-role="{{ $user->role }}"
                                                        data-status="{{ $user->status ? '1' : '0' }}">
                                                    <i class="fa fa-edit text-blue"></i> Edit
                                                </button>

                                                @if($user->id !== auth()->id())
                                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline-form" onsubmit="return confirm('Are you sure you want to permanently delete this user account?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-default btn-sm table-action-btn">
                                                            <i class="fa fa-trash text-red"></i> Delete
                                                        </button>
                                                    </form>
                                                @else
                                                    <button type="button" class="btn btn-default btn-sm table-action-btn disabled" title="You cannot modify yourself">
                                                        <i class="fa fa-lock text-muted"></i> Active
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr id="emptyRow">
                                        <td colspan="6" class="text-center empty-state">No records discovered within the user index database context.</td>
                                    </tr>
                                @endforelse
                                <tr id="noSearchResultsRow" style="display: none;">
                                    <td colspan="6" class="text-center empty-state"><i class="fa fa-search"></i> No users match your search criteria.</td>
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
        <div class="modal-content modern-modal">
            <div class="modal-header bg-primary">
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
                    <button type="submit" class="btn btn-primary btn-flat action-btn">Save User Account</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content modern-modal">
            <div class="modal-header bg-blue">
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
                    <button type="submit" class="btn btn-primary btn-flat action-btn">Update Record</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .modern-alert {
        border-radius: 8px;
        border: none;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    }

    .dashboard-box {
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
    }

    .users-box-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        padding: 16px 18px;
    }

    .users-box-header .box-title {
        line-height: 30px;
        font-weight: 600;
    }

    .users-toolbar {
        display: flex;
        gap: 10px;
        align-items: center;
        position: static;
    }

    .search-input-group {
        width: 220px;
        margin-top: 1px;
    }

    .action-btn {
        border-radius: 6px;
        font-weight: 600;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .action-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
    }

    /* Table */
    .modern-table {
        margin-bottom: 0;
    }

    .table-head-row th {
        background: #f9fafb;
        padding: 12px 10px;
        font-size: 11.5px;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #64748b;
        font-weight: 700;
        border-bottom: 2px solid #eef1f4 !important;
    }

    .modern-table td {
        padding: 12px 10px;
        vertical-align: middle;
    }

    .modern-table tr.user-row {
        transition: background 0.15s ease;
    }

    .modern-table tr.user-row:hover {
        background: #f9fafb;
    }

    .name-cell {
        font-weight: 600;
        color: #1e293b;
    }

    .email-cell {
        color: #64748b;
        font-size: 13px;
    }

    .date-cell {
        color: #94a3b8;
        font-size: 12.5px;
        font-family: 'SFMono-Regular', Consolas, monospace;
    }

    .role-badge {
        font-weight: 600;
        padding: 0.35em 0.9em;
        border-radius: 20px;
        letter-spacing: 0.2px;
    }

    .actions-col {
        width: 260px;
        text-align: right;
    }

    .inline-form {
        display: inline-block;
    }

    .table-action-btn {
        border-radius: 4px;
        font-weight: 500;
        transition: background 0.15s ease;
    }

    .table-action-btn:hover {
        background: #f1f5f9;
    }

    .empty-state {
        padding: 24px;
        color: #94a3b8;
    }

    /* Modals */
    .modern-modal {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.2);
    }

    @media (max-width: 767px) {
        .users-box-header {
            flex-direction: column;
            align-items: stretch;
        }
        .users-toolbar {
            margin-top: 10px;
            flex-direction: column;
        }
        .search-input-group {
            width: 100%;
        }
    }
</style>

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

                if (nameText.includes(filter) || emailText.includes(filter)) {
                    row.style.display = '';
                    visibleRowsCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            var noResultsRow = document.getElementById('noSearchResultsRow');
            if (rows.length > 0) {
                noResultsRow.style.display = visibleRowsCount === 0 ? '' : 'none';
            }
        });
    });
</script>
@endsection
