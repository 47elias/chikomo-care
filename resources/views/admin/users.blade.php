@extends('layouts.master')

@section('content-wrapper')
<div class="content-wrapper" style="background-color: #f8fafc !important;">

    <section class="content-header" style="padding: 24px 20px 15px 20px !important;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
            <div>
                <h1 style="font-size: 24px; font-weight: 900; color: #1e293b; margin: 0; letter-spacing: -0.5px;">User Administration</h1>
                <p style="font-size: 12px; color: #64748b; margin: 4px 0 0 0; font-weight: 500;">Manage system administrative profiles, update permissions, and delete user accounts.</p>
            </div>
            <button type="button" class="btn btn-primary btn-flat" data-toggle="modal" data-target="#createUserModal">
                <span class="glyphicon glyphicon-plus" style="margin-right: 6px; font-size: 11px;"></span> Add Administrative User
            </button>
        </div>
    </section>

    <section class="content" style="padding: 0 20px 20px 20px !important;">

        @if(session('success'))
            <div class="alert alert-success" style="background-color: #f0fdf4 !important; border: 1px solid #bbf7d0 !important; color: #166534 !important; border-radius: 12px !important; font-size: 13px; text-shadow: none !important; margin-bottom: 20px; box-shadow: none;">
                <span class="glyphicon glyphicon-ok-sign" style="margin-right: 6px;"></span> {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger" style="background-color: #fef2f2 !important; border: 1px solid #fee2e2 !important; color: #991b1b !important; border-radius: 12px !important; font-size: 13px; text-shadow: none !important; margin-bottom: 20px; box-shadow: none;">
                <ul style="padding-left: 15px; margin-bottom: 0; font-weight: 500;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="box" style="border: 1px solid #e2e8f0 !important; border-radius: 16px !important; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02) !important; background: #fff !important; overflow: hidden; border-top: none !important;">
            <div class="box-body table-responsive" style="padding: 0;">
                <table class="table table-hover" style="margin-bottom: 0;">
                    <thead>
                        <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                            <th style="padding: 14px 20px; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; font-weight: 700;">Full Name</th>
                            <th style="padding: 14px 20px; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; font-weight: 700;">Email Address</th>
                            <th style="padding: 14px 20px; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; font-weight: 700;">System Role</th>
                            <th style="padding: 14px 20px; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; font-weight: 700;">Created Date</th>
                            <th style="padding: 14px 20px; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; font-weight: 700; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr style="border-bottom: 1px solid #f1f5f9; transition: all 0.15s ease;">
                                <td style="padding: 14px 20px; font-size: 13px; font-weight: 700; color: #334155; vertical-align: middle;">{{ $user->name }}</td>
                                <td style="padding: 14px 20px; font-size: 13px; color: #475569; font-weight: 500; vertical-align: middle;">{{ $user->email }}</td>
                                <td style="padding: 14px 20px; font-size: 13px; vertical-align: middle;">
                                    <span class="label" style="background-color: {{ $user->role === 'admin' ? '#e0e7ff' : '#ecfdf5' }} !important; color: {{ $user->role === 'admin' ? '#4338ca' : '#047857' }} !important; font-size: 10px !important; font-weight: 700; text-transform: uppercase; padding: 4px 8px !important; border-radius: 6px !important;">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td style="padding: 14px 20px; font-size: 12px; color: #94a3b8; font-weight: 500; vertical-align: middle;">{{ $user->created_at->format('M d, Y') }}</td>
                                <td style="padding: 14px 20px; text-align: right; vertical-align: middle;">
                                    <div style="display: flex; justify-content: flex-end; gap: 8px;">
                                        <button type="button" class="btn btn-sm btn-default edit-user-btn"
                                                data-id="{{ $user->id }}"
                                                data-name="{{ $user->name }}"
                                                data-email="{{ $user->email }}"
                                                data-role="{{ $user->role }}"
                                                style="border-radius: 6px !important; border-color: #cbd5e1 !important; color: #475569 !important; background: #fff; padding: 5px 10px; font-weight: 600; font-size: 12px;">
                                            <span class="glyphicon glyphicon-pencil" style="font-size: 10px; margin-right: 2px;"></span> Edit
                                        </button>

                                        @if($user->id !== auth()->id())
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Are you completely sure you want to drop this user account? This choice is absolute.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-default" style="border-radius: 6px !important; border-color: #fee2e2 !important; color: #ef4444 !important; background: #fff; padding: 5px 10px; font-weight: 600; font-size: 12px;">
                                                    <span class="glyphicon glyphicon-trash" style="font-size: 10px; margin-right: 2px;"></span> Delete
                                                </button>
                                            </form>
                                        @else
                                            <span style="font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; padding: 6px 10px; letter-spacing: 0.5px;">Active</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 40px; color: #94a3b8; font-size: 13px; font-weight: 500; font-style: italic;">No administrative records discovered within the users context.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="createUserModal" tabindex="-1" role="dialog" aria-labelledby="createUserModalLabel">
    <div class="modal-dialog" role="document" style="width: 420px; margin-top: 10%;">
        <div class="modal-content" style="border-radius: 20px !important; border: none !important; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1) !important; overflow: hidden;">
            <div class="modal-header" style="border-bottom: 1px solid #f1f5f9 !important; padding: 20px 25px !important; background: #fff !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: 2px;"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="createUserModalLabel" style="font-weight: 900; color: #1e293b; font-size: 16px; letter-spacing: -0.5px;">Create Administrative User</h4>
            </div>
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <div class="modal-body" style="padding: 25px !important; background: #fff !important;">
                    <div class="form-group" style="margin-bottom: 18px;">
                        <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; display: block;">Full Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Musa Elias" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 18px;">
                        <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; display: block;">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="name@domain.com" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 18px;">
                        <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; display: block;">Assigned System Role</label>
                        <select name="role" class="form-control" required style="background-color: #f8fafc !important;">
                            <option value="admin">Administrator</option>
                            <option value="counselor">Counselor</option>
                        </select>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; display: block;">Password Key</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••••••" required>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #f1f5f9 !important; padding: 15px 25px !important; background: #f8fafc; display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 8px !important; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border-color: #cbd5e1; color: #64748b; height: 38px;">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-flat" style="margin: 0; height: 38px; line-height: 24px;">Save Account</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel">
    <div class="modal-dialog" role="document" style="width: 420px; margin-top: 10%;">
        <div class="modal-content" style="border-radius: 20px !important; border: none !important; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1) !important; overflow: hidden;">
            <div class="modal-header" style="border-bottom: 1px solid #f1f5f9 !important; padding: 20px 25px !important; background: #fff !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: 2px;"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="editUserModalLabel" style="font-weight: 900; color: #1e293b; font-size: 16px; letter-spacing: -0.5px;">Modify Structural Profile</h4>
            </div>
            <form method="POST" id="editUserForm" action="">
                @csrf
                @method('PUT')
                <div class="modal-body" style="padding: 25px !important; background: #fff !important;">
                    <div class="form-group" style="margin-bottom: 18px;">
                        <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; display: block;">Full Name</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 18px;">
                        <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; display: block;">Email Address</label>
                        <input type="email" name="email" id="edit_email" class="form-control" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 18px;">
                        <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; display: block;">Assigned System Role</label>
                        <select name="role" id="edit_role" class="form-control" required style="background-color: #f8fafc !important;">
                            <option value="admin">Model Admin</option>
                            <option value="counselor">Counselor</option>
                        </select>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; display: block;">Update Password <span style="text-transform: lowercase; color: #94a3b8; font-weight: 500;">(Leave blank to keep current)</span></label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••••••">
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #f1f5f9 !important; padding: 15px 25px !important; background: #f8fafc; display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 8px !important; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border-color: #cbd5e1; color: #64748b; height: 38px;">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-flat" style="margin: 0; height: 38px; line-height: 24px;">Update Record</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('.edit-user-btn').on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var email = $(this).data('email');
            var role = $(this).data('role');

            // Set up dynamic form update route mapping targeting your update endpoint
            $('#editUserForm').attr('action', '/admin/users/' + id);

            // Assign current variables straight into values
            $('#edit_name').val(name);
            $('#edit_email').val(email);
            $('#edit_role').val(role);

            // Pop modal forward cleanly
            $('#editUserModal').modal('show');
        });
    });
</script>

<style>
    /* Premium components matching clean modern guidelines layout */
    .form-control {
        height: 40px !important;
        background: #f8fafc !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 8px !important;
        box-shadow: none !important;
        font-size: 13px !important;
        color: #334155 !important;
        transition: all 0.15s ease;
    }
    .form-control:focus {
        border-color: #6366f1 !important;
        background: #fff !important;
    }
    .btn-flat {
        border-radius: 8px !important;
        font-size: 11px !important;
        font-weight: 700 !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        background-color: #4f46e5 !important;
        border-color: #4f46e5 !important;
        padding: 8px 16px;
        transition: all 0.15s ease;
    }
    .btn-flat:hover {
        background-color: #4338ca !important;
        border-color: #4338ca !important;
    }
    tr:hover {
        background-color: #f8fafc !important;
    }
</style>
@endsection
