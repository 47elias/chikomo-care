@extends('layouts.master')
<title>Active Counselor Directory - Chikomo Care</title>

@section('content-wrapper')
<div class="content-wrapper">
    {{-- Header Section --}}
    <section class="content-header">
        <h1>
            Active Counselors
            <small>Live Operational Profiles & Analytics</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Active Counselors</li>
        </ol>
    </section>

    <section class="content">
        {{-- Session Feedback System Alerts --}}
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

        {{-- Statistics & Analytics Header Insight Row --}}
        <div class="row">
            <div class="col-md-8">
                <div class="box box-primary shadow">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-dot-circle-o text-green"></i> Live Counselor Analytics</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-6 border-right">
                                <div class="description-block">
                                    <h5 class="description-header text-green">{{ $counselors->count() }}</h5>
                                    <span class="description-text">ONLINE & AVAILABLE</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="description-block">
                                    <h5 class="description-header text-blue">{{ \App\Models\User::where('role', 'counselor')->count() }}</h5>
                                    <span class="description-text">TOTAL REGISTERED STAFF</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box box-solid bg-navy shadow">
                    <div class="box-header">
                        <h3 class="box-title">Directory Management</h3>
                    </div>
                    <div class="box-body">
                        <button type="button" class="btn btn-success btn-block btn-flat shadow" data-toggle="modal" data-target="#modal-add-counselor">
                            <i class="fa fa-user-plus"></i> Register New Counselor
                        </button>
                        <div class="input-group style-group-sm" style="margin-top: 10px;">
                            <input type="text" id="counselorSearchInput" class="form-control" placeholder="Search active name or specialty...">
                            <span class="input-group-addon bg-gray"><i class="fa fa-search"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Dynamic Uniform Counselor Directory Grid --}}
        <div class="row layout-flex-grid" id="counselorGridContainer" style="display: flex; flex-wrap: wrap;">
            @forelse($counselors as $counselor)
                @if($counselor->user)
                    <div class="col-md-4 col-sm-6 counselor-widget-card"
                         style="margin-bottom: 30px; display: flex; flex-direction: column;"
                         data-name="{{ strtolower($counselor->user->name) }}"
                         data-email="{{ strtolower($counselor->user->email) }}"
                         data-specialization="{{ strtolower($counselor->specialization) }}">

                        <div class="box box-widget widget-user shadow-lg" style="display: flex; flex-direction: column; height: 100%; margin-bottom: 0; background: #fff; border-radius: 4px; overflow: hidden;">

                            {{-- Unified Header Height --}}
                            <div class="widget-user-header bg-green-active" style="height: 110px; padding: 20px; text-align: center;">
                                <h3 class="widget-user-username" style="margin-top: 0; margin-bottom: 4px; font-size: 22px; font-weight: 300; text-shadow: 0 1px 2px rgba(0,0,0,0.2); overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    {{ $counselor->user->name }}
                                </h3>
                                <h5 class="widget-user-desc" style="margin-top: 0; opacity: 0.95; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-weight: 400;">
                                    {{ $counselor->specialization }}
                                </h5>
                            </div>

                            {{-- Centered Uniform Profile Image Anchor --}}
                            <div class="widget-user-image" style="position: absolute; top: 65px; left: 50%; margin-left: -45px; z-index: 5;">
                                <img class="img-circle shadow" src="{{ asset('dist/img/avatar5.png') }}" alt="Counselor Profile Avatar" style="width: 90px; height: 90px; border: 3px solid #fff; background: #fff; object-fit: cover;">
                            </div>

                            {{-- Card Body and Footer Content Forced to Match Vertically --}}
                            <div class="box-footer" style="padding-top: 55px; background-color: #fff; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between;">
                                <div class="row">
                                    <div class="col-xs-4 border-right">
                                        <div class="description-block" style="margin: 10px 0;">
                                            <h5 class="description-header" style="font-size: 16px; font-weight: 600; color: #333;">{{ $counselor->experience_years }}</h5>
                                            <span class="description-text" style="font-size: 10px; color: #777; font-weight: 700;">YRS EXP</span>
                                        </div>
                                    </div>
                                    <div class="col-xs-4 border-right">
                                        <div class="description-block" style="margin: 10px 0;">
                                            <h5 class="description-header" style="font-size: 12px; font-weight: 600; color: #333; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; padding: 2px 0;" title="{{ $counselor->license_number }}">
                                                {{ $counselor->license_number }}
                                            </h5>
                                            <span class="description-text" style="font-size: 10px; color: #777; font-weight: 700;">LICENSE</span>
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="description-block" style="margin: 10px 0;">
                                            <h5 class="description-header" style="margin-bottom: 2px;">
                                                @if(strtolower($counselor->status) === 'busy')
                                                    <span class="label label-warning shadow-sm">BUSY</span>
                                                @elseif(strtolower($counselor->status) === 'on_leave')
                                                    <span class="label label-danger shadow-sm">ON LEAVE</span>
                                                @else
                                                    <span class="label label-success shadow-sm">ACTIVE</span>
                                                @endif
                                            </h5>
                                            <span class="description-text" style="font-size: 10px; color: #777; font-weight: 700;">STATUS</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Unified Descriptive Content Block --}}
                                <div>
                                    <hr style="margin: 12px 0;">
                                    <div style="height: 40px; overflow: hidden; margin-bottom: 10px; padding: 0 5px;">
                                        <p class="text-muted text-center small" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; line-height: 1.4; margin: 0;">
                                            <em>"{{ $counselor->bio ?? 'No professional biography provided yet.' }}"</em>
                                        </p>
                                    </div>
                                    <hr style="margin: 12px 0 15px 0;">
                                </div>

                                {{-- Unified Responsive Action Layout Bottom Bar --}}
                                <div class="btn-group btn-group-justified" style="width: 100%; table-layout: fixed;">
                                    <div class="btn-group">
                                        <form action="{{ route('counselors.toggle-status', $counselor->id) }}" method="POST" style="display: block; width: 100%;">
                                            @csrf
                                            @method('PATCH')
                                            @if(strtolower($counselor->status) === 'busy')
                                                <button type="submit" class="btn btn-default btn-sm btn-block" style="border-radius: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="Make counselor active">
                                                    <i class="fa fa-check text-green"></i> Go Active
                                                </button>
                                            @else
                                                <button type="submit" class="btn btn-default btn-sm btn-block" style="border-radius: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="Mark counselor as busy">
                                                    <i class="fa fa-ban text-yellow"></i> Go Busy
                                                </button>
                                            @endif
                                        </form>
                                    </div>

                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default btn-sm edit-counselor-btn" style="border-radius: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"
                                                data-id="{{ $counselor->id }}"
                                                data-name="{{ $counselor->user->name }}"
                                                data-email="{{ $counselor->user->email }}"
                                                data-specialization="{{ $counselor->specialization }}"
                                                data-license="{{ $counselor->license_number }}"
                                                data-experience="{{ $counselor->experience_years }}"
                                                data-status="{{ $counselor->status }}"
                                                data-bio="{{ $counselor->bio }}">
                                            <i class="fa fa-edit text-blue"></i> Edit
                                        </button>
                                    </div>

                                    <div class="btn-group">
                                        <form action="{{ route('counselors.destroy', $counselor->id) }}" method="POST" style="display: block; width: 100%;" onsubmit="return confirm('Are you sure you want to completely remove this professional counselor and their linked system access account?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-default btn-sm btn-block" style="border-radius: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="Delete Profile">
                                                <i class="fa fa-trash text-red"></i> Drop
                                            </button>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                @endif
            @empty
                <div class="col-md-12" id="emptyDirectoryContainer">
                    <div class="callout callout-success shadow-lg" style="background: white !important; border-left-color: #00a65a !important;">
                        <h4 style="color: #333;"><i class="fa fa-info-circle"></i> No Active Counselors</h4>
                        <p style="color: #666;">There are currently no active professional counselors available in the directory system layout.</p>
                    </div>
                </div>
            @endforelse

            <div class="col-md-12" id="noSearchResultsWarning" style="display: none;">
                <div class="callout callout-warning shadow-lg" style="background: white !important; border-left-color: #f39c12 !important;">
                    <h4 style="color: #333;"><i class="fa fa-search-plus"></i> No Match Discovered</h4>
                    <p style="color: #666;">No active counselors match your typed filtering query parameters.</p>
                </div>
            </div>
        </div>
    </section>
</div>

{{-- MODAL 1: Register New Counselor --}}
<div class="modal fade" id="modal-add-counselor">
    <div class="modal-dialog">
        <div class="modal-content shadow-lg" style="border-radius: 8px; overflow: hidden;">
            <form action="{{ route('counselors.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-green">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title"><i class="fa fa-user-md"></i> Register New Counselor Profile</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Full Name <span class="text-red">*</span></label>
                                <input type="text" name="name" class="form-control" placeholder="e.g. Dr. Musa Elias" value="{{ old('name') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email Address <span class="text-red">*</span></label>
                                <input type="email" name="email" class="form-control" placeholder="email@chikomocare.co.zw" value="{{ old('email') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Professional Specialization <span class="text-red">*</span></label>
                        <select name="specialization" class="form-control" required>
                            <option value="Trauma & Grief" {{ old('specialization') === 'Trauma & Grief' ? 'selected' : '' }}>Trauma & Grief</option>
                            <option value="Anxiety & Depression" {{ old('specialization') === 'Anxiety & Depression' ? 'selected' : '' }}>Anxiety & Depression</option>
                            <option value="Academic Counseling" {{ old('specialization') === 'Academic Counseling' ? 'selected' : '' }}>Academic Counseling</option>
                            <option value="Substance Abuse" {{ old('specialization') === 'Substance Abuse' ? 'selected' : '' }}>Substance Abuse</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Medical License No. <span class="text-red">*</span></label>
                                <input type="text" name="license_number" class="form-control" placeholder="ZIM-MED-XXXX" value="{{ old('license_number') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Years of Experience <span class="text-red">*</span></label>
                                <input type="number" name="experience_years" class="form-control" value="{{ old('experience_years', 1) }}" min="0" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Professional Biography</label>
                        <textarea name="bio" class="form-control" rows="3" placeholder="Brief descriptive overview outlining operational background, certifications, etc...">{{ old('bio') }}</textarea>
                    </div>

                    <p class="text-muted small"><i class="fa fa-info-circle"></i> Auth Account initialized will use standard authentication credentials password string: <strong>ChikomoCare2026</strong></p>
                </div>
                <div class="modal-footer bg-gray-light">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success shadow"><i class="fa fa-save"></i> Complete Registration</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL 2: Edit Counselor Profile --}}
<div class="modal fade" id="modal-edit-counselor">
    <div class="modal-dialog">
        <div class="modal-content shadow-lg" style="border-radius: 8px; overflow: hidden;">
            <form method="POST" id="editCounselorForm" action="">
                @csrf
                @method('PUT')
                <div class="modal-header bg-blue">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title"><i class="fa fa-edit"></i> Modify Profile Settings</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_name">Full Name</label>
                                <input type="text" name="name" id="edit_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_email">Email Address</label>
                                <input type="email" name="email" id="edit_email" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit_specialization">Professional Specialization</label>
                        <select name="specialization" id="edit_specialization" class="form-control" required>
                            <option value="Trauma & Grief">Trauma & Grief</option>
                            <option value="Anxiety & Depression">Anxiety & Depression</option>
                            <option value="Academic Counseling">Academic Counseling</option>
                            <option value="Substance Abuse">Substance Abuse</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_license">Medical License No. <small class="text-muted">(Read-Only)</small></label>
                                <input type="text" id="edit_license" class="form-control" disabled readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_experience">Years of Experience</label>
                                <input type="number" name="experience_years" id="edit_experience" class="form-control" min="0" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit_status">Operational Availability State</label>
                        <select name="status" id="edit_status" class="form-control" required>
                            <option value="available">Available</option>
                            <option value="busy">Busy</option>
                            <option value="on_leave">On Leave</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="edit_bio">Professional Biography</label>
                        <textarea name="bio" id="edit_bio" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-gray-light">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary shadow"><i class="fa fa-save"></i> Save Profile Modifications</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Interaction Engine Scripting Block Context Handler --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- 1. Populate and Map Data Attributes into Edit Modal Window Form Inputs ---
        $('.edit-counselor-btn').on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var email = $(this).data('email');
            var specialization = $(this).data('specialization');
            var license = $(this).data('license');
            var experience = $(this).data('experience');
            var status = $(this).data('status');
            var bio = $(this).data('bio');

            $('#editCounselorForm').attr('action', '/admin/counselors/' + id);

            $('#edit_name').val(name);
            $('#edit_email').val(email);
            $('#edit_specialization').val(specialization);
            $('#edit_license').val(license);
            $('#edit_experience').val(experience);
            $('#edit_status').val(status);
            $('#edit_bio').val(bio);

            $('#modal-edit-counselor').modal('show');
        });

        // --- 2. Lightweight Instant Real-time Client-Side Search Engine Filter ---
        var searchInput = document.getElementById('counselorSearchInput');

        searchInput.addEventListener('input', function() {
            var query = searchInput.value.toLowerCase().trim();
            var systemCards = document.querySelectorAll('.counselor-widget-card');
            var activeMatches = 0;

            systemCards.forEach(function(card) {
                var searchName = card.getAttribute('data-name');
                var searchEmail = card.getAttribute('data-email');
                var searchSpec = card.getAttribute('data-specialization');

                if (searchName.includes(query) || searchEmail.includes(query) || searchSpec.includes(query)) {
                    card.style.display = 'flex'; // Maintained uniform structure status on filter display matches
                    activeMatches++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Handle warning visibility state logic blocks dynamically
            var warningBanner = document.getElementById('noSearchResultsWarning');
            if(systemCards.length > 0) {
                if(activeMatches === 0) {
                    warningBanner.style.display = 'block';
                } else {
                    warningBanner.style.display = 'none';
                }
            }
        });
    });
</script>
@endsection
