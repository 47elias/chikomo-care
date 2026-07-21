@extends('layouts.master')

@section('content-wrapper')
<div class="content-wrapper">
    {{-- Header Section --}}
    <section class="content-header">
        <h1>
            Account Settings
            <small>Manage your system profile information</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Settings</li>
        </ol>
    </section>

    <section class="content">
        {{-- Session Feedback Alerts --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible modern-alert">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> Success!</h4>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible modern-alert">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-ban"></i> Error!</h4>
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible modern-alert">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-ban"></i> Validation Error!</h4>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row">
            {{-- Left Column: User Profile Snapshot Card --}}
            <div class="col-md-4">
                <div class="box box-primary profile-card">
                    <div class="box-body box-profile">
                        <div class="profile-avatar-wrap">
                            <img class="profile-user-img img-responsive img-circle"
                                 src="{{ asset('dist/img/avatar5.png') }}"
                                 alt="User Profile Picture">
                        </div>

                        <h3 class="profile-username">
                            {{ Auth::user()->name }}
                        </h3>

                        <p class="profile-role">
                            <span class="label bg-navy role-pill">{{ str_replace('_', ' ', Auth::user()->role) }}</span>
                        </p>

                        <ul class="list-group list-group-unbordered profile-info-list">
                            <li class="list-group-item">
                                <b>Email Address</b>
                                <span class="pull-right info-value">{{ Auth::user()->email }}</span>
                            </li>

                            {{-- Conditional Counselor Sidebar Fields --}}
                            @if(Auth::user()->role === 'counselor' && Auth::user()->counselor)
                                <li class="list-group-item">
                                    <b>License Registration</b>
                                    <span class="pull-right label label-default info-pill">
                                        {{ Auth::user()->counselor->license_number }}
                                    </span>
                                </li>
                                <li class="list-group-item">
                                    <b>Operational Status</b>
                                    <span class="pull-right">
                                        @if(strtolower(Auth::user()->counselor->status) === 'busy')
                                            <span class="label label-warning">BUSY</span>
                                        @elseif(strtolower(Auth::user()->counselor->status) === 'on_leave')
                                            <span class="label label-danger">ON LEAVE</span>
                                        @else
                                            <span class="label label-success">ACTIVE</span>
                                        @endif
                                    </span>
                                </li>
                            @endif

                            <li class="list-group-item info-list-last">
                                <b>Member Since</b>
                                <span class="pull-right info-muted">{{ Auth::user()->created_at->format('M Y') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Right Column: Dynamic Form Panel --}}
            <div class="col-md-8">
                <div class="nav-tabs-custom settings-tabs">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#profile-settings" data-toggle="tab" class="settings-tab-link">
                                <i class="fa fa-sliders text-blue"></i> Edit Profile Information
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content settings-tab-content">
                        <div class="active tab-pane" id="profile-settings">
                            {{-- Form routes back to a universal profile management endpoint --}}
                            <form class="form-horizontal" action="{{ route('profile.update') }}" method="POST">
                                @csrf
                                @method('PUT')

                                {{-- SECTION 1: Standard Core User Accounts Details (All Roles) --}}
                                <h4 class="settings-section-heading">
                                    <i class="fa fa-user text-muted"></i> Account Details <small>(Base Information - Read Only)</small>
                                </h4>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Display Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="name" class="form-control readonly-input" id="inputName" placeholder="Your full name" value="{{ old('name', Auth::user()->name) }}" readonly required>
                                        <p class="help-block small text-muted field-note"><i class="fa fa-lock"></i> Account name modification disabled.</p>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Email Address</label>
                                    <div class="col-sm-9">
                                        <input type="email" name="email" class="form-control readonly-input" id="inputEmail" placeholder="yourname@domain.com" value="{{ old('email', Auth::user()->email) }}" readonly required>
                                        <p class="help-block small text-muted field-note"><i class="fa fa-lock"></i> Account system email identity disabled.</p>
                                    </div>
                                </div>


                                {{-- SECTION 2: Extra Professional Metadata Fields (Only Loaded for Active Counselors) --}}
                                @if(Auth::user()->role === 'counselor' && Auth::user()->counselor)
                                    <h4 class="settings-section-heading settings-section-spaced">
                                        <i class="fa fa-graduation-cap text-muted"></i> Professional Metadata <small>(Counselor Directory Extensions)</small>
                                    </h4>

                                    <div class="form-group">
                                        <label for="inputSpecialization" class="col-sm-3 control-label">Specialization Field</label>
                                        <div class="col-sm-9">
                                            <select name="specialization" id="inputSpecialization" class="form-control" required>
                                                <option value="Trauma & Grief" {{ old('specialization', Auth::user()->counselor->specialization) === 'Trauma & Grief' ? 'selected' : '' }}>Trauma & Grief</option>
                                                <option value="Anxiety & Depression" {{ old('specialization', Auth::user()->counselor->specialization) === 'Anxiety & Depression' ? 'selected' : '' }}>Anxiety & Depression</option>
                                                <option value="Academic Counseling" {{ old('specialization', Auth::user()->counselor->specialization) === 'Academic Counseling' ? 'selected' : '' }}>Academic Counseling</option>
                                                <option value="Substance Abuse" {{ old('specialization', Auth::user()->counselor->specialization) === 'Substance Abuse' ? 'selected' : '' }}>Substance Abuse</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputLicense" class="col-sm-3 control-label">Medical License No.</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control readonly-input" id="inputLicense" value="{{ Auth::user()->counselor->license_number }}" disabled readonly>
                                            <p class="help-block small text-muted field-note"><i class="fa fa-lock"></i> System locked attribute. License values cannot be modified directly.</p>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputExperience" class="col-sm-3 control-label">Years of Experience</label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <input type="number" name="experience_years" class="form-control" id="inputExperience" min="0" value="{{ old('experience_years', Auth::user()->counselor->experience_years) }}" required>
                                                <span class="input-group-addon bg-gray">Years</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputStatus" class="col-sm-3 control-label">Availability Status</label>
                                        <div class="col-sm-9">
                                            <select name="status" id="inputStatus" class="form-control" required>
                                                <option value="available" {{ old('status', Auth::user()->counselor->status) === 'available' ? 'selected' : '' }}>Available</option>
                                                <option value="busy" {{ old('status', Auth::user()->counselor->status) === 'busy' ? 'selected' : '' }}>Busy</option>
                                                <option value="on_leave" {{ old('status', Auth::user()->counselor->status) === 'on_leave' ? 'selected' : '' }}>On Leave</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputBio" class="col-sm-3 control-label">Professional Biography</label>
                                        <div class="col-sm-9">
                                            <textarea name="bio" id="inputBio" class="form-control" rows="4" placeholder="Describe your background clinical practices...">{{ old('bio', Auth::user()->counselor->bio) }}</textarea>
                                        </div>
                                    </div>
                                @endif

                                {{-- SECTION 3: Universal Security Layer / Password Option (Optional field for all users) --}}
                                <h4 class="settings-section-heading settings-section-spaced">
                                    <i class="fa fa-lock text-muted"></i> Account Security <small>(Leave blank to keep current password)</small>
                                </h4>

                                <div class="form-group">
                                    <label for="inputPassword" class="col-sm-3 control-label">New Password</label>
                                    <div class="col-sm-9">
                                        <input type="password" name="password" class="form-control" id="inputPassword" placeholder="Minimum 8 characters">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputPasswordConfirmation" class="col-sm-3 control-label">Confirm Password</label>
                                    <div class="col-sm-9">
                                        <input type="password" name="password_confirmation" class="form-control" id="inputPasswordConfirmation" placeholder="Repeat new password">
                                    </div>
                                </div>

                                <hr class="settings-divider">

                                {{-- Submission Action Button Container --}}
                                <div class="form-group settings-submit-row">
                                    <div class="col-sm-offset-3 col-sm-9">
                                        <button type="submit" class="btn btn-primary action-btn">
                                            <i class="fa fa-save"></i> Save Profile Modifications
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    .modern-alert {
        border-radius: 8px;
        border: none;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    }

    /* Profile card */
    .profile-card {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
    }

    .profile-card .box-profile {
        background: #fff;
        padding: 30px 20px 25px;
    }

    .profile-avatar-wrap {
        text-align: center;
        margin-bottom: 16px;
    }

    .profile-user-img {
        width: 100px;
        height: 100px;
        margin: 0 auto;
        border: 3px solid #3c8dbc;
        padding: 3px;
        object-fit: cover;
        box-shadow: 0 4px 12px rgba(60, 141, 188, 0.25);
        transition: transform 0.2s ease;
    }

    .profile-avatar-wrap:hover .profile-user-img {
        transform: scale(1.04);
    }

    .profile-username {
        font-size: 21px;
        text-align: center;
        margin-top: 5px;
        margin-bottom: 6px;
        font-weight: 700;
        color: #1e293b;
    }

    .profile-role {
        text-align: center;
        margin-bottom: 22px;
    }

    .role-pill {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        padding: 5px 14px;
        border-radius: 20px;
    }

    .profile-info-list {
        margin-bottom: 0;
    }

    .profile-info-list .list-group-item {
        padding: 12px 4px;
        border-top: 1px solid #f1f5f9;
        border-left: none;
        border-right: none;
        font-size: 13px;
        color: #334155;
    }

    .profile-info-list .list-group-item:first-child {
        border-top: none;
    }

    .info-list-last {
        border-bottom: 1px solid #f1f5f9;
    }

    .info-value {
        font-weight: 600;
        color: #1e293b;
    }

    .info-pill {
        font-size: 11px;
        padding: 4px 10px;
        border-radius: 12px;
    }

    .info-muted {
        color: #94a3b8;
    }

    /* Tabs panel */
    .settings-tabs {
        border-radius: 10px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
    }

    .settings-tab-link {
        font-weight: 600;
        padding: 15px 20px;
    }

    .settings-tab-link i {
        margin-right: 5px;
    }

    .settings-tab-content {
        padding: 28px 24px;
    }

    .settings-section-heading {
        margin-top: 0;
        margin-bottom: 20px;
        font-weight: 700;
        font-size: 15px;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 10px;
        letter-spacing: 0.2px;
    }

    .settings-section-heading i {
        margin-right: 6px;
    }

    .settings-section-spaced {
        margin-top: 36px;
    }

    .readonly-input {
        background-color: #f8fafc !important;
        cursor: not-allowed;
        color: #94a3b8;
    }

    .field-note {
        margin-top: 6px;
        margin-bottom: 0;
    }

    .settings-divider {
        margin: 26px 0 20px;
        border-color: #f1f5f9;
    }

    .settings-submit-row {
        margin-bottom: 0;
    }

    .action-btn {
        padding: 9px 22px;
        font-weight: 600;
        border-radius: 6px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .action-btn i {
        margin-right: 5px;
    }

    .action-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
</style>
@endsection
