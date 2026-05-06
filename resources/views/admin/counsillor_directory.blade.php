@extends('layouts.master')
<title>Counselor Management - Chikomo Care</title>

@section('content-wrapper')
<div class="content-wrapper">
    {{-- Header Section --}}
    <section class="content-header">
        <h1>
            Counselor Management
            <small>Professional Oversight & Analytics</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Counselors</li>
        </ol>
    </section>

    <section class="content">
        {{-- Session Feedback --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible shadow">
                <button type="button" class="close" data-dismiss="danger" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> Success!</h4>
                {{ session('success') }}
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

        {{-- Statistics & Actions Row --}}
        <div class="row">
            <div class="col-md-8">
                <div class="box box-primary shadow">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-bar-chart"></i> Counselor Insights</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">{{ $counselors->count() }}</h5>
                                    <span class="description-text">TOTAL STAFF</span>
                                </div>
                            </div>
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header text-green">
                                        {{ $counselors->where('status', 'available')->count() }}
                                    </h5>
                                    <span class="description-text">AVAILABLE NOW</span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="description-block">
                                    <h5 class="description-header text-red">
                                        {{ $counselors->where('status', 'busy')->count() }}
                                    </h5>
                                    <span class="description-text">CURRENTLY BUSY</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box box-solid bg-navy shadow">
                    <div class="box-header">
                        <h3 class="box-title">Management Actions</h3>
                    </div>
                    <div class="box-body">
                        <button type="button" class="btn btn-success btn-block btn-lg shadow" data-toggle="modal" data-target="#modal-add-counselor">
                            <i class="fa fa-user-plus"></i> Register New Counselor
                        </button>
                        <a href="#" class="btn btn-info btn-block shadow">
                            <i class="fa fa-download"></i> Export Staff Report
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Counselor Grid --}}
        <div class="row">
            @forelse($counselors as $counselor)
                <div class="col-md-4 col-sm-6">
                    <div class="box box-widget widget-user shadow-lg">
                        @php
                            $statusTheme = $counselor->status == 'available' ? 'bg-green-active' : 'bg-yellow-active';
                        @endphp
                        <div class="widget-user-header {{ $statusTheme }}">
                            <h3 class="widget-user-username">{{ $counselor->user->name }}</h3>
                            <h5 class="widget-user-desc">{{ $counselor->specialization }}</h5>
                        </div>
                        <div class="widget-user-image">
                            <img class="img-circle shadow" src="{{ asset('dist/img/avatar5.png') }}" alt="Counselor Avatar" style="border: 3px solid #fff;">
                        </div>
                        <div class="box-footer">
                            <div class="row">
                                <div class="col-sm-4 border-right">
                                    <div class="description-block">
                                        <h5 class="description-header">{{ $counselor->experience_years }}</h5>
                                        <span class="description-text">YRS EXP</span>
                                    </div>
                                </div>
                                <div class="col-sm-4 border-right">
                                    <div class="description-block">
                                        {{-- This would be dynamic in a real app based on CounselorAssignments relationship --}}
                                        <h5 class="description-header">0</h5>
                                        <span class="description-text">CASES</span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="description-block">
                                        <h5 class="description-header">
                                            <span class="label {{ $counselor->status == 'available' ? 'label-success' : 'label-warning' }} shadow-sm">
                                                {{ strtoupper($counselor->status) }}
                                            </span>
                                        </h5>
                                    </div>
                                </div>
                            </div>

                            <hr style="margin: 10px 0;">

                            <div class="btn-group-vertical btn-block">
                                <a href="#" class="btn btn-primary shadow-sm"><i class="fa fa-folder-open-o"></i> View Full Case History</a>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-sm shadow-sm" title="Edit Profile"><i class="fa fa-edit"></i> Edit</button>
                                    <button type="button" class="btn btn-danger btn-sm shadow-sm" title="Suspend Access"><i class="fa fa-ban"></i> Suspend</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-md-12">
                    <div class="callout callout-info shadow-lg" style="background: white !important; border-left-color: #00c0ef !important;">
                        <h4 style="color: #333;"><i class="fa fa-info-circle"></i> No Counselors Registered</h4>
                        <p style="color: #666;">There are currently no professional counselors in the directory. Use the "Register New Counselor" button to populate the system.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </section>
</div>

{{-- MODAL: Add New Counselor --}}
<div class="modal fade" id="modal-add-counselor">
    <div class="modal-dialog">
        <div class="modal-content shadow-lg" style="border-radius: 8px; overflow: hidden;">
            <form action="{{ route('counselors.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-green">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title"><i class="fa fa-user-md"></i> Register New Counselor</h4>
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
                        <select name="specialization" class="form-control select2" style="width: 100%;">
                            <option value="Trauma & Grief">Trauma & Grief</option>
                            <option value="Anxiety & Depression">Anxiety & Depression</option>
                            <option value="Academic Counseling">Academic Counseling</option>
                            <option value="Substance Abuse">Substance Abuse</option>
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
                                <label>Years of Experience</label>
                                <input type="number" name="experience_years" class="form-control" value="{{ old('experience_years', 1) }}" min="0">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Professional Biography</label>
                        <textarea name="bio" class="form-control" rows="4" placeholder="Brief summary of professional background and expertise...">{{ old('bio') }}</textarea>
                    </div>

                    <p class="text-muted small"><i class="fa fa-info-circle"></i> A default system password will be generated for new counselor accounts.</p>
                </div>
                <div class="modal-footer bg-gray-light">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success shadow"><i class="fa fa-save"></i> Complete Registration</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
