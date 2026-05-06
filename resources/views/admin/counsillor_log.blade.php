@extends('layouts.master')
<title>Assignment Logs - Chikomo Care</title>

@section('content-wrapper')
<div class="content-wrapper">
    {{-- Header Section --}}
    <section class="content-header">
        <h1>
            Assignment Logs
            <small>Manage Counselor-Student Engagements</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Assignment Logs</li>
        </ol>
    </section>

    <section class="content">
        {{-- High-Level Metrics --}}
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box shadow">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Engagements</span>
                        <span class="info-box-number">{{ $assignments->count() }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box shadow">
                    <span class="info-box-icon bg-green"><i class="fa fa-comments"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Active Chats</span>
                        <span class="info-box-number">{{ $assignments->where('is_active', true)->count() }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box shadow">
                    <span class="info-box-icon bg-red"><i class="fa fa-warning"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">High Risk Cases</span>
                        <span class="info-box-number">{{ $assignments->filter(fn($a) => $a->conversation->risk_level === 'high')->count() }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box shadow">
                    <span class="info-box-icon bg-navy"><i class="fa fa-check-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Resolution Rate</span>
                        <span class="info-box-number">85%</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Table Section --}}
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary shadow-lg">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-list"></i> Detailed Engagement Registry</h3>

                        <div class="box-tools">
                            <form action="#" method="GET" class="input-group input-group-sm" style="width: 250px;">
                                <input type="text" name="search" class="form-control" placeholder="Search by Alias or Counselor...">
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                                </span>
                            </form>
                        </div>
                    </div>

                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover table-striped">
                            <thead class="bg-gray">
                                <tr>
                                    <th>#ID</th>
                                    <th>Counselor In-Charge</th>
                                    <th>Student Alias</th>
                                    <th>Risk Level</th>
                                    <th>Initiated</th>
                                    <th>Engagement Status</th>
                                    <th class="text-center">Manage</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($assignments as $log)
                                <tr>
                                    <td><span class="text-muted">#{{ str_pad($log->id, 4, '0', STR_PAD_LEFT) }}</span></td>
                                    <td>
                                        <div class="user-block">
                                            <img class="img-circle" src="{{ asset('dist/img/avatar5.png') }}" alt="User Image">
                                            <span class="username"><a href="#">{{ $log->counselor->user->name }}</a></span>
                                            <span class="description">{{ $log->counselor->specialization }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="label label-default" style="font-size: 11px;">
                                            <i class="fa fa-user-secret"></i> {{ $log->conversation->alias }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $riskColor = match($log->conversation->risk_level) {
                                                'high' => 'danger',
                                                'medium' => 'warning',
                                                'low' => 'success',
                                                default => 'default'
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $riskColor }}">
                                            {{ strtoupper($log->conversation->risk_level) }}
                                        </span>
                                    </td>
                                    <td>{{ $log->created_at->format('M d, Y') }}<br><small>{{ $log->created_at->diffForHumans() }}</small></td>
                                    <td>
                                        @if($log->is_active)
                                            <span class="label label-success shadow-sm"><i class="fa fa-play"></i> ACTIVE</span>
                                        @else
                                            <span class="label label-gray"><i class="fa fa-lock"></i> CLOSED</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                                Options <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-right">
                                                <li><a href="#"><i class="fa fa-eye text-blue"></i> View Conversation</a></li>
                                                <li><a href="#"><i class="fa fa-exchange text-orange"></i> Reassign Case</a></li>
                                                <li class="divider"></li>
                                                <li><a href="#"><i class="fa fa-check text-green"></i> Mark as Resolved</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="text-muted" style="padding: 40px;">
                                            <i class="fa fa-folder-open-o fa-4x"></i>
                                            <h4>No Assignment Logs Found</h4>
                                            <p>All counselor-student engagements will appear here once initiated.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($assignments->hasPages())
                    <div class="box-footer clearfix">
                        {{ $assignments->links('pagination::bootstrap-4') }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
