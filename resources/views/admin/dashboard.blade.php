@extends('layouts.master')

@section('content-wrapper')
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Chikomo Care Dashboard
            <small>System Behavior &amp; Risk Monitoring</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>

    <section class="content">
        {{-- High-Level Metrics Row --}}
        <div class="row">
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-aqua metric-box">
                    <div class="inner">
                        <h3>{{ $totalConversations ?? 0 }}</h3>
                        <p>Total Conversations</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-comments"></i>
                    </div>
                    <div class="metric-box-divider"></div>
                    <a href="{{ route('analytics') }}" class="small-box-footer">View Details <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-green metric-box">
                    <div class="inner">
                        <h3>{{ $lowRiskPercent ?? 100 }}<sup class="metric-suffix">%</sup></h3>
                        <p>Low Risk Conversations</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-shield"></i>
                    </div>
                    <div class="metric-box-divider"></div>
                    <a href="{{ route('analytics') }}" class="small-box-footer">Risk Reports <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-yellow metric-box">
                    <div class="inner">
                        <h3>{{ $anonymousUsersCount ?? 0 }}</h3>
                        <p>Registered Anonymous Users</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-user-secret"></i>
                    </div>
                    <div class="metric-box-divider"></div>
                    <a href="#" class="small-box-footer">User Management <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-red metric-box">
                    <div class="inner">
                        <h3>{{ $flaggedCount ?? 0 }}</h3>
                        <p>Flagged Conversations</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-flag"></i>
                    </div>
                    <div class="metric-box-divider"></div>
                    <a href="#" class="small-box-footer">Review Flags <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- User Interaction Flow Ledger --}}
            <div class="col-md-8">
                <div class="box box-primary dashboard-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-exchange text-primary"></i> User Engagement (Aliases)
                        </h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover match-table mb-0">
                                <thead>
                                    <tr class="table-head-row">
                                        <th>Alias Name</th>
                                        <th>Risk Classification</th>
                                        <th>Session Started At</th>
                                        <th>Triage Security Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($recentConversations) && count($recentConversations) > 0)
                                        @foreach($recentConversations as $convo)
                                            <tr>
                                                <td class="alias-cell">{{ $convo->alias ?? 'Anonymous Guest Profile' }}</td>
                                                <td>
                                                    @if(isset($convo->risk_level) && strtolower($convo->risk_level) == 'high')
                                                        <span class="label label-danger risk-badge">High Risk</span>
                                                    @elseif(isset($convo->risk_level) && strtolower($convo->risk_level) == 'medium')
                                                        <span class="label label-warning risk-badge">Medium Risk</span>
                                                    @else
                                                        <span class="label label-success risk-badge">Low Risk</span>
                                                    @endif
                                                </td>
                                                <td class="session-time-cell">
                                                    {{ isset($convo->created_at) ? \Carbon\Carbon::parse($convo->created_at)->format('Y-m-d H:i') : 'N/A' }}
                                                </td>
                                                <td>
                                                    @if(isset($convo->is_flagged) && $convo->is_flagged == 1)
                                                        <span class="text-red status-flag"><i class="fa fa-warning"></i> Flagged Breach</span>
                                                    @else
                                                        <span class="text-success status-flag"><i class="fa fa-check-circle"></i> Clean Session</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" class="text-center empty-state">
                                                <i class="fa fa-info-circle fa-2x"></i>
                                                <div>No active chat session metrics discovered in the conversations registry.</div>
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
                <div class="box box-default dashboard-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-heartbeat text-muted"></i> System Diagnostics Health
                        </h3>
                    </div>
                    <div class="box-body">
                        <p class="diagnostic-row"><strong>Database Context:</strong> <span class="pull-right diagnostic-value">{{ $dbVersion ?? 'Unknown' }}</span></p>
                        <p class="diagnostic-row"><strong>Engine Compilation Stack:</strong> <span class="pull-right diagnostic-value">PHP {{ $phpVersion ?? 'Unknown' }}</span></p>
                        <hr class="diagnostic-divider">

                        <div class="progress-group">
                            <span class="progress-text">System Conversational Load Capacity</span>
                            <span class="progress-number"><b>{{ $totalConversations ?? 0 }}</b>/1,000</span>
                            <div class="progress sm modern-progress">
                                <div class="progress-bar progress-bar-aqua" style="width: {{ min((($totalConversations ?? 0) / 1000) * 100, 100) }}%;"></div>
                            </div>
                        </div>

                        <div class="progress-group progress-group-last">
                            <span class="progress-text">Asynchronous Worker Core Backlog Failures</span>
                            <span class="progress-number"><b>{{ $failedJobsCount ?? 0 }}</b>/Critical</span>
                            <div class="progress sm modern-progress">
                                <div class="progress-bar progress-bar-red" style="width: {{ (isset($failedJobsCount) && $failedJobsCount > 0) ? '100' : '0' }}%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    /* Metric boxes */
    .metric-box {
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        overflow: hidden;
    }

    .metric-box:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }

    .metric-box .inner h3 {
        font-weight: 700;
    }

    .metric-suffix {
        font-size: 20px;
    }

    .metric-box-divider {
        background: rgba(0, 0, 0, 0.1);
        height: 4px;
        width: 100%;
    }

    .metric-box .small-box-footer {
        transition: background 0.15s ease;
    }

    /* Content boxes */
    .dashboard-box {
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
        border-top: none;
    }

    .dashboard-box .box-header {
        padding: 16px 18px;
    }

    .dashboard-box .box-title {
        font-weight: 600;
        letter-spacing: 0.2px;
    }

    /* Table */
    .table-head-row th {
        background: #f9fafb;
        padding: 12px 10px;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #64748b;
        font-weight: 700;
        border-bottom: 2px solid #eef1f4 !important;
    }

    .match-table td {
        padding: 12px 10px;
        vertical-align: middle;
    }

    .alias-cell {
        font-weight: 600;
        color: #1e293b;
    }

    .session-time-cell {
        color: #64748b;
        font-family: 'SFMono-Regular', Consolas, monospace;
        font-size: 12.5px;
    }

    .risk-badge {
        font-weight: 600;
        padding: 0.35em 0.9em;
        border-radius: 20px;
        letter-spacing: 0.2px;
    }

    .status-flag {
        font-weight: 600;
        font-size: 12.5px;
    }

    .empty-state {
        padding: 36px 20px;
        color: #94a3b8;
    }

    .empty-state i {
        display: block;
        margin-bottom: 10px;
        opacity: 0.6;
    }

    /* Diagnostics panel */
    .diagnostic-row {
        margin-bottom: 10px;
        font-size: 13px;
        color: #475569;
    }

    .diagnostic-value {
        font-family: 'SFMono-Regular', Consolas, monospace;
        color: #94a3b8;
        font-size: 12.5px;
    }

    .diagnostic-divider {
        margin: 14px 0;
        border-color: #eef1f4;
    }

    .progress-group {
        margin-bottom: 18px;
    }

    .progress-group-last {
        margin-bottom: 5px;
    }

    .progress-text {
        font-weight: 600;
        font-size: 13px;
        color: #334155;
    }

    .progress-number {
        color: #94a3b8;
        font-size: 12.5px;
    }

    .modern-progress {
        background: #eef1f4;
        border-radius: 20px;
        height: 8px;
        overflow: hidden;
        margin-top: 6px;
    }

    .modern-progress .progress-bar {
        border-radius: 20px;
        transition: width 0.4s ease;
    }
</style>
@endsection
