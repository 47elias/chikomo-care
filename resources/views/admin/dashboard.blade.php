@extends('layouts.master')
<title>Dashboard - Chikomo Care</title>

@section('content-wrapper')
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Chikomo Care Dashboard
            <small>System Behavior & Risk Monitoring</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>

    <section class="content">
        {{-- High-Level Metrics Row --}}
        <div class="row">
            {{-- Total Conversations Widget --}}
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-aqua shadow-sm">
                    <div class="inner">
                        <h3>{{ $totalConversations ?? 0 }}</h3>
                        <p>Total Conversations</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-comments"></i>
                    </div>
                    <div style="background: rgba(0,0,0,0.1); height: 4px; width: 100%;"></div>
                    <a href="{{ route('analytics') }}" class="small-box-footer">View Details <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

            {{-- Risk Level Tracking Widget --}}
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-green shadow-sm">
                    <div class="inner">
                        <h3>{{ $lowRiskPercent ?? 100 }}<sup style="font-size: 20px">%</sup></h3>
                        <p>Low Risk Conversations</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-shield"></i>
                    </div>
                    <div style="background: rgba(0,0,0,0.1); height: 4px; width: 100%;"></div>
                    <a href="{{ route('analytics') }}" class="small-box-footer">Risk Reports <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

            {{-- Registered Anonymous Users Widget --}}
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-yellow shadow-sm">
                    <div class="inner">
                        <h3>{{ $anonymousUsersCount ?? 0 }}</h3>
                        <p>Registered Anonymous Users</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-user-secret"></i>
                    </div>
                    <div style="background: rgba(0,0,0,0.1); height: 4px; width: 100%;"></div>
                    <a href="#" class="small-box-footer">User Management <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

            {{-- Flagged Content Operational Review Widget --}}
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-red shadow-sm">
                    <div class="inner">
                        <h3>{{ $flaggedCount ?? 0 }}</h3>
                        <p>Flagged Conversations</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-flag"></i>
                    </div>
                    <div style="background: rgba(0,0,0,0.1); height: 4px; width: 100%;"></div>
                    <a href="#" class="small-box-footer">Review Flags <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- User Interaction Flow Ledger --}}
            <div class="col-md-8">
                <div class="box box-primary shadow-sm" style="border-radius: 4px;">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="font-weight: 600;">
                            <i class="fa fa-exchange text-primary"></i> User Engagement (Aliases)
                        </h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover match-table mb-0">
                                <thead>
                                    <tr style="background: #f9fafb;">
                                        <th style="padding: 12px 8px;">Alias Name</th>
                                        <th style="padding: 12px 8px;">Risk Classification</th>
                                        <th style="padding: 12px 8px;">Session Started At</th>
                                        <th style="padding: 12px 8px;">Triage Security Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($recentConversations) && count($recentConversations) > 0)
                                        @foreach($recentConversations as $convo)
                                            <tr>
                                                <td style="font-weight: 600; padding: 12px 8px;">{{ $convo->alias ?? 'Anonymous Guest Profile' }}</td>
                                                <td style="padding: 12px 8px;">
                                                    @if(isset($convo->risk_level) && strtolower($convo->risk_level) == 'high')
                                                        <span class="label label-danger" style="font-weight: 600; padding: .3em .8em;">High Risk</span>
                                                    @elseif(isset($convo->risk_level) && strtolower($convo->risk_level) == 'medium')
                                                        <span class="label label-warning" style="font-weight: 600; padding: .3em .8em;">Medium Risk</span>
                                                    @else
                                                        <span class="label label-success" style="font-weight: 600; padding: .3em .8em;">Low Risk</span>
                                                    @endif
                                                </td>
                                                <td style="color: #666; padding: 12px 8px;">
                                                    {{ isset($convo->created_at) ? \Carbon\Carbon::parse($convo->created_at)->format('Y-m-d H:i') : 'N/A' }}
                                                </td>
                                                <td style="padding: 12px 8px;">
                                                    @if(isset($convo->is_flagged) && $convo->is_flagged == 1)
                                                        <span class="text-red" style="font-weight: 600;"><i class="fa fa-warning"></i> Flagged Breach</span>
                                                    @else
                                                        <span class="text-success" style="font-weight: 600;"><i class="fa fa-check-circle"></i> Clean Session</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" class="text-center text-muted" style="padding: 24px;">
                                                <i class="fa fa-info-circle fa-2x d-block mb-2"></i><br>
                                                No active chat session metrics discovered in the conversations registry.
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Infrastructure Operational Health Diagnostics --}}
            <div class="col-md-4">
                <div class="box box-default shadow-sm" style="border-radius: 4px;">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="font-weight: 600;">
                            <i class="fa fa-heartbeat text-muted"></i> System Diagnostics Health
                        </h3>
                    </div>
                    <div class="box-body">
                        <p style="margin-bottom: 8px;"><strong>Database Context:</strong> <span class="pull-right text-muted" style="font-family: monospace;">{{ $dbVersion ?? 'Unknown' }}</span></p>
                        <p style="margin-bottom: 8px;"><strong>Engine Compilation Stack:</strong> <span class="pull-right text-muted" style="font-family: monospace;">PHP {{ $phpVersion ?? 'Unknown' }}</span></p>
                        <hr style="margin-top: 12px; margin-bottom: 12px;">

                        <div class="progress-group" style="margin-bottom: 15px;">
                            <span class="progress-text" style="font-weight: 600;">System Conversational Load Capacity</span>
                            <span class="progress-number"><b>{{ $totalConversations ?? 0 }}</b>/1,000</span>
                            <div class="progress sm" style="background: #eee; border-radius: 2px;">
                                <div class="progress-bar progress-bar-aqua" style="width: {{ min((($totalConversations ?? 0) / 1000) * 100, 100) }}%; border-radius: 2px;"></div>
                            </div>
                        </div>

                        <div class="progress-group" style="margin-bottom: 5px;">
                            <span class="progress-text" style="font-weight: 600;">Asynchronous Worker Core Backlog Failures</span>
                            <span class="progress-number"><b>{{ $failedJobsCount ?? 0 }}</b>/Critical</span>
                            <div class="progress sm" style="background: #eee; border-radius: 2px;">
                                <div class="progress-bar progress-bar-red" style="width: {{ (isset($failedJobsCount) && $failedJobsCount > 0) ? '100' : '0' }}%; border-radius: 2px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
