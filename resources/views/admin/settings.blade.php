@extends('layouts.master')
<title>Account Settings & Profile Customization - Chikomo Care</title>

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
            <div class="alert alert-success alert-dismissible shadow">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> Success!</h4>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible shadow">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-ban"></i> Error!</h4>
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible shadow">
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
                <div class="box box-primary shadow" style="border-radius: 4px; overflow: hidden;">
                    <div class="box-body box-profile" style="background: #fff; padding: 25px 15px;">
                        <div class="text-center" style="position: relative; margin-bottom: 15px;">
                            <img class="profile-user-img img-responsive img-circle shadow"
                                 src="{{ asset('dist/img/avatar5.png') }}"
                                 alt="User Profile Picture"
                                 style="width: 100px; height: 100px; margin: 0 auto; border: 3px solid #3c8dbc; padding: 3px; object-fit: cover;">
                        </div>

                        <h3 class="profile-username text-center" style="font-size: 21px; margin-top: 5px; margin-bottom: 2px; font-weight: 600; color: #333;">
                            {{ Auth::user()->name }}
                        </h3>

                        <p class="text-muted text-center" style="font-size: 13px; margin-bottom: 20px; font-weight: 700; text-transform: uppercase; color: #777;">
                            <span class="label bg-navy">{{ str_replace('_', ' ', Auth::user()->role) }}</span>
                        </p>

                        <ul class="list-group list-group-unbordered" style="margin-bottom: 20px;">
                            <li class="list-group-item" style="padding: 12px 0; border-top: 1px solid #f4f4f4;">
                                <b>Email Address</b>
                                <span class="pull-right text-dark small" style="font-weight: 600;">{{ Auth::user()->email }}</span>
                            </li>

                            {{-- Conditional Counselor Sidebar Fields --}}
                            @if(Auth::user()->role === 'counselor' && Auth::user()->counselor)
                                <li class="list-group-item" style="padding: 12px 0;">
                                    <b>License Registration</b>
                                    <span class="pull-right label label-default font-weight-600" style="font-size: 11px; padding: 4px 8px;">
                                        {{ Auth::user()->counselor->license_number }}
                                    </span>
                                </li>
                                <li class="list-group-item" style="padding: 12px 0;">
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

                            <li class="list-group-item" style="padding: 12px 0; border-bottom: 1px solid #f4f4f4;">
                                <b>Member Since</b>
                                <span class="pull-right text-muted">{{ Auth::user()->created_at->format('M Y') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Right Column: Dynamic Form Panel --}}
            <div class="col-md-8">
                <div class="nav-tabs-custom shadow" style="border-radius: 4px; overflow: hidden; background: #fff;">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#profile-settings" data-toggle="tab" style="font-weight: 600; padding: 15px 20px;">
                                <i class="fa fa-sliders text-blue" style="margin-right: 5px;"></i> Edit Profile Information
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content" style="padding: 25px 20px;">
                        <div class="active tab-pane" id="profile-settings">
                            {{-- Form routes back to a universal profile management endpoint --}}
                            <form class="form-horizontal" action="{{ route('profile.update') }}" method="POST">
                                @csrf
                                @method('PUT')

                                {{-- SECTION 1: Standard Core User Accounts Details (All Roles) --}}
                                <h4 style="margin-top: 0; margin-bottom: 20px; font-weight: 600; color: #444; border-bottom: 1px solid #f4f4f4; padding-bottom: 8px;">
                                    <i class="fa fa-user text-muted" style="margin-right: 5px;"></i> Account Details <small>(Base Information - Read Only)</small>
                                </h4>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Display Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="name" class="form-control" id="inputName" placeholder="Your full name" value="{{ old('name', Auth::user()->name) }}" readonly style="background-color: #eee; cursor: not-allowed;" required>
                                        <p class="help-block small text-muted" style="margin-top: 5px; margin-bottom: 0;"><i class="fa fa-lock"></i> Account name modification disabled.</p>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Email Address</label>
                                    <div class="col-sm-9">
                                        <input type="email" name="email" class="form-control" id="inputEmail" placeholder="yourname@domain.com" value="{{ old('email', Auth::user()->email) }}" readonly style="background-color: #eee; cursor: not-allowed;" required>
                                        <p class="help-block small text-muted" style="margin-top: 5px; margin-bottom: 0;"><i class="fa fa-lock"></i> Account system email identity disabled.</p>
                                    </div>
                                </div>


                                {{-- SECTION 2: Extra Professional Metadata Fields (Only Loaded for Active Counselors) --}}
                                @if(Auth::user()->role === 'counselor' && Auth::user()->counselor)
                                    <h4 style="margin-top: 35px; margin-bottom: 20px; font-weight: 600; color: #444; border-bottom: 1px solid #f4f4f4; padding-bottom: 8px;">
                                        <i class="fa fa-graduation-cap text-muted" style="margin-right: 5px;"></i> Professional Metadata <small>(Counselor Directory Extensions)</small>
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
                                            <input type="text" class="form-control" id="inputLicense" value="{{ Auth::user()->counselor->license_number }}" disabled readonly style="background-color: #eee; cursor: not-allowed;">
                                            <p class="help-block small text-muted" style="margin-top: 5px; margin-bottom: 0;"><i class="fa fa-lock"></i> System locked attribute. License values cannot be modified directly.</p>
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
                                <h4 style="margin-top: 35px; margin-bottom: 20px; font-weight: 600; color: #444; border-bottom: 1px solid #f4f4f4; padding-bottom: 8px;">
                                    <i class="fa fa-lock text-muted" style="margin-right: 5px;"></i> Account Security <small>(Leave blank to keep current password)</small>
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

                                <hr style="margin: 25px 0 20px 0;">

                                {{-- Submission Action Button Container --}}
                                <div class="form-group" style="margin-bottom: 0;">
                                    <div class="col-sm-offset-3 col-sm-9">
                                        <button type="submit" class="btn btn-primary shadow" style="padding: 7px 20px; font-weight: 600;">
                                            <i class="fa fa-save" style="margin-right: 5px;"></i> Save Profile Modifications
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
@endsection
