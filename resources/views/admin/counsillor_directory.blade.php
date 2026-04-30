@extends('layouts.master')
<title>Counselor Directory - Chikomo Care</title>

@section('content-wrapper')
<div class="content-wrapper">
    {{-- Page Header --}}
    <section class="content-header">
        <h1>
            Counselor Directory
            <small>Mental Health Professionals</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Counselors</li>
        </ol>
    </section>

    {{-- Main Content --}}
    <section class="content">
        {{-- Search and Filter Row --}}
        <div class="row" style="margin-bottom: 15px;">
            <div class="col-md-12">
                <div class="box box-solid">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" class="form-control" placeholder="Search by name or specialization...">
                            </div>
                            <div class="col-md-3">
                                <select class="form-control">
                                    <option>All Specializations</option>
                                    <option>Trauma</option>
                                    <option>Anxiety & Depression</option>
                                    <option>Substance Abuse</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary btn-block"><i class="fa fa-filter"></i> Filter</button>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-success btn-block"><i class="fa fa-plus"></i> Add New Counselor</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Counselor Grid --}}
        <div class="row">
            {{-- Counselor Card 1 --}}
            <div class="col-md-4">
                <div class="box box-widget widget-user">
                    <div class="widget-user-header bg-aqua-active">
                        <h3 class="widget-user-username">Dr. Nyasha Mapfaka</h3>
                        <h5 class="widget-user-desc">Senior Psychologist</h5>
                    </div>
                    <div class="widget-user-image">
                        <img class="img-circle" src="{{ asset('dist/img/user1-128x128.jpg') }}" alt="User Avatar">
                    </div>
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">12</h5>
                                    <span class="description-text">CLIENTS</span>
                                </div>
                            </div>
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">4.8</h5>
                                    <span class="description-text">RATING</span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="description-block">
                                    <h5 class="description-header">Low</h5>
                                    <span class="description-text">RISK PREF</span>
                                </div>
                            </div>
                        </div>
                        <div class="text-center" style="padding-top: 10px;">
                            <button class="btn btn-sm btn-default"><i class="fa fa-envelope"></i> Message</button>
                            <button class="btn btn-sm btn-info"><i class="fa fa-user"></i> Profile</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Counselor Card 2 --}}
            <div class="col-md-4">
                <div class="box box-widget widget-user">
                    <div class="widget-user-header bg-green-active">
                        <h3 class="widget-user-username">Abigal Nhidza</h3>
                        <h5 class="widget-user-desc">Trauma Specialist</h5>
                    </div>
                    <div class="widget-user-image">
                        <img class="img-circle" src="{{ asset('dist/img/user8-128x128.jpg') }}" alt="User Avatar">
                    </div>
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">8</h5>
                                    <span class="description-text">CLIENTS</span>
                                </div>
                            </div>
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">5.0</h5>
                                    <span class="description-text">RATING</span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="description-block">
                                    <h5 class="description-header">High</h5>
                                    <span class="description-text">RISK PREF</span>
                                </div>
                            </div>
                        </div>
                        <div class="text-center" style="padding-top: 10px;">
                            <button class="btn btn-sm btn-default"><i class="fa fa-envelope"></i> Message</button>
                            <button class="btn btn-sm btn-info"><i class="fa fa-user"></i> Profile</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Counselor Card 3 --}}
            <div class="col-md-4">
                <div class="box box-widget widget-user">
                    <div class="widget-user-header bg-yellow-active">
                        <h3 class="widget-user-username">Tinotenda Hamandishe</h3>
                        <h5 class="widget-user-desc">Behavioral Therapist</h5>
                    </div>
                    <div class="widget-user-image">
                        <img class="img-circle" src="{{ asset('dist/img/user7-128x128.jpg') }}" alt="User Avatar">
                    </div>
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">15</h5>
                                    <span class="description-text">CLIENTS</span>
                                </div>
                            </div>
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">4.2</h5>
                                    <span class="description-text">RATING</span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="description-block">
                                    <h5 class="description-header">Med</h5>
                                    <span class="description-text">RISK PREF</span>
                                </div>
                            </div>
                        </div>
                        <div class="text-center" style="padding-top: 10px;">
                            <button class="btn btn-sm btn-default"><i class="fa fa-envelope"></i> Message</button>
                            <button class="btn btn-sm btn-info"><i class="fa fa-user"></i> Profile</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table View for Bulk Management --}}
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Active Counselor Status</h3>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <tr>
                                <th>ID</th>
                                <th>Counselor</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Assigned Tokens</th>
                                <th>Actions</th>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>Dr. Nyasha Mapfaka</td>
                                <td>nyasha@chikomocare.co.zw</td>
                                <td><span class="label label-success">Online</span></td>
                                <td>{{-- Linked to conversation tokens --}} 3</td>
                                <td>
                                    <a href="#" class="btn btn-xs btn-warning"><i class="fa fa-edit"></i></a>
                                    <a href="#" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                            {{-- Add more rows based on the users table --}}
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
