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
                                    <h5 class="description-header text-red">0</h5>
                                    <span class="description-text">HIGH RISK CASES</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                {{-- Quick Actions Box --}}
                <div class="box box-solid bg-navy">
                    <div class="box-header">
                        <h3 class="box-title">Management Actions</h3>
                    </div>
                    <div class="box-body">
                        <button type="button" class="btn btn-success btn-block btn-lg" data-toggle="modal" data-target="#modal-add-counselor">
                            <i class="fa fa-user-plus"></i> Register New Counselor
                        </button>
                        <a href="#" class="btn btn-info btn-block">
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
                        {{-- Background color changes based on status --}}
                        <div class="widget-user-header {{ $counselor->status == 'available' ? 'bg-green-active' : 'bg-gray-active' }}">
                            <h3 class="widget-user-username">{{ $counselor->user->name }}</h3>
                            <h5 class="widget-user-desc">{{ $counselor->specialization }}</h5>
                        </div>
                        <div class="widget-user-image">
                            <img class="img-circle" src="{{ asset('dist/img/avatar5.png') }}" alt="Counselor Avatar" style="border: 3px solid #fff;">
                        </div>
                        <div class="box-footer">
                            <div class="row">
                                <div class="col-sm-4 border-right">
                                    <div class="description-block">
                                        <h5 class="description-header">{{ $counselor->experience_years }}</h5>
                                        <span class="description-text">EXP.</span>
                                    </div>
                                </div>
                                <div class="col-sm-4 border-right">
                                    <div class="description-block">
                                        <h5 class="description-header">4</h5>
                                        <span class="description-text">CLIENTS</span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="description-block">
                                        <h5 class="description-header">
                                            <span class="label {{ $counselor->status == 'available' ? 'label-success' : 'label-default' }}">
                                                {{ strtoupper($counselor->status) }}
                                            </span>
                                        </h5>
                                    </div>
                                </div>
                            </div>

                            <hr style="margin: 10px 0;">

                            <div class="btn-group-vertical btn-block">
                                <a href="#" class="btn btn-primary"><i class="fa fa-folder-open-o"></i> View Full Case History</a>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-sm" title="Edit Profile"><i class="fa fa-edit"></i> Edit</button>
                                    <button type="button" class="btn btn-danger btn-sm" title="Suspend Access"><i class="fa fa-ban"></i> Suspend</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-md-12">
                    <div class="alert alert-info alert-dismissible">
                        <h4><i class="icon fa fa-info"></i> No Counselors Found!</h4>
                        Register your first professional counselor using the button above to begin management.
                    </div>
                </div>
            @endforelse
        </div>
    </section>
</div>

{{-- MODAL: Add New Counselor --}}
<div class="modal fade" id="modal-add-counselor">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="#" method="POST"> {{-- Point to your store route --}}
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
                                <label>Full Name</label>
                                <input type="text" name="name" class="form-control" placeholder="e.g. Dr. Musa Elias" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email (System Access)</label>
                                <input type="email" name="email" class="form-control" placeholder="email@chikomocare.co.zw" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Specialization</label>
                        <select name="specialization" class="form-control">
                            <option value="Trauma & Grief">Trauma & Grief</option>
                            <option value="Anxiety & Depression">Anxiety & Depression</option>
                            <option value="Academic Counseling">Academic Counseling</option>
                            <option value="Substance Abuse">Substance Abuse</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>License Number</label>
                                <input type="text" name="license_number" class="form-control" placeholder="ZIM-MED-XXXX" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Years of Experience</label>
                                <input type="number" name="experience_years" class="form-control" value="1">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Short Biography</label>
                        <textarea name="bio" class="form-control" rows="3" placeholder="Brief professional background..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save Counselor</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
