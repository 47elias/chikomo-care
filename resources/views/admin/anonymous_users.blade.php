@extends('layouts.master')
<title>Anonymous Interactions Registry - Chikomo Care</title>

@section('content-wrapper')
<div class="content-wrapper">
    {{-- Header Section --}}
    <section class="content-header">
        <h1>
            Anonymous Conversations Registry
            <small>Monitor ongoing guest identity tokens, risk evaluations, and telemetry logs</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Anonymous Registry</li>
        </ol>
    </section>

    {{-- Main Content Window --}}
    <section class="content">
        @php
            // SAFETY FALLBACK CHECK: If your routing optimization cache acts up,
            // this guarantees the view has an instance to process without crashing out with a 500.
            if (!isset($conversations)) {
                $conversations = \App\Models\Conversation::orderBy('created_at', 'desc')->paginate(10);
            }
        @endphp

        {{-- Overview Analytics Cards --}}
        <div class="row">
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="info-box shadow">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-comments"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Conversations</span>
                        <span class="info-box-number" style="font-size: 22px; font-weight: 600; margin-top: 4px;">
                            {{ $conversations->total() }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="info-box shadow">
                    <span class="info-box-icon bg-red"><i class="fa fa-flag"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Flagged Communications</span>
                        <span class="info-box-number" style="font-size: 22px; font-weight: 600; margin-top: 4px;">
                            {{ $conversations->where('is_flagged', true)->count() }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-12 col-xs-12">
                <div class="info-box shadow">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-warning"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">High Risk Signals</span>
                        <span class="info-box-number" style="font-size: 22px; font-weight: 600; margin-top: 4px;">
                            {{ $conversations->where('risk_level', 'high')->count() }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Core Registry Record Data Table --}}
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-danger shadow" style="border-radius: 4px; overflow: hidden;">
                    <div class="box-header with-border" style="background: #fff; padding: 15px;">
                        <h3 class="box-title" style="font-weight: 600; color: #444;">
                            <i class="fa fa-database text-red" style="margin-right: 5px;"></i> Active Schema Instances
                        </h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-sm btn-default" onclick="window.location.reload();">
                                <i class="fa fa-refresh"></i> Refresh Data Pool
                            </button>
                        </div>
                    </div>

                    <div class="box-body table-responsive no-padding" style="background: #fff;">
                        <table class="table table-hover table-striped" style="margin-bottom: 0; vertical-align: middle;">
                            <thead>
                                <tr style="background-color: #fafafa; border-bottom: 2px solid #f4f4f4;">
                                    <th style="padding: 12px 15px; width: 100px;" class="text-center">Ref ID</th>
                                    <th style="padding: 12px 15px; width: 220px;">Assigned Alias Name</th>
                                    <th style="padding: 12px 15px;">Secure Session Token</th>
                                    <th style="padding: 12px 15px; width: 140px;" class="text-center">Flag Status</th>
                                    <th style="padding: 12px 15px; width: 150px;" class="text-center">Risk Assessment</th>
                                    <th style="padding: 12px 15px; width: 180px;">Created Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($conversations->count() > 0)
                                    @foreach($conversations as $conversation)
                                        <tr>
                                            <td class="text-center" style="padding: 12px 15px; font-weight: 600; color: #777;">
                                                #{{ $conversation->id }}
                                            </td>
                                            <td style="padding: 12px 15px; font-weight: 600; color: #3c8dbc;">
                                                <i class="fa fa-user-secret" style="margin-right: 5px;"></i> {{ $conversation->alias ?? 'Anonymous Client' }}
                                            </td>
                                            <td style="padding: 12px 15px; font-family: monospace; font-size: 13px; color: #555;">
                                                @if(!empty($conversation->token))
                                                    <i class="fa fa-key text-muted" style="margin-right: 5px;"></i> <span title="{{ $conversation->token }}">{{ substr($conversation->token, 0, 24) }}...</span>
                                                @else
                                                    <span class="text-danger"><i class="fa fa-exclamation-triangle"></i> Token Unset/Expired</span>
                                                @endif
                                            </td>
                                            <td class="text-center" style="padding: 12px 15px; vertical-align: middle;">
                                                @if($conversation->is_flagged)
                                                    <span class="label label-danger shadow-sm" style="font-size: 10px; font-weight: 700; padding: 3px 8px;">FLAGGED</span>
                                                @else
                                                    <span class="label label-success shadow-sm" style="font-size: 10px; font-weight: 700; padding: 3px 8px;">CLEAR</span>
                                                @endif
                                            </td>
                                            <td class="text-center" style="padding: 12px 15px; vertical-align: middle;">
                                                @if($conversation->risk_level === 'high')
                                                    <span class="label label-danger" style="font-size: 10px; font-weight: 700; text-transform: uppercase;">HIGH</span>
                                                @elseif($conversation->risk_level === 'medium')
                                                    <span class="label label-warning" style="font-size: 10px; font-weight: 700; text-transform: uppercase;">MEDIUM</span>
                                                @else
                                                    <span class="label label-info" style="font-size: 10px; font-weight: 700; text-transform: uppercase;">LOW</span>
                                                @endif
                                            </td>
                                            <td style="padding: 12px 15px; color: #666; font-size: 13px;">
                                                {{ $conversation->created_at ? $conversation->created_at->format('d M Y, h:i A') : 'N/A' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center text-muted" style="padding: 40px 15px;">
                                            <div style="font-size: 36px; color: #d2d6de; margin-bottom: 10px;">
                                                <i class="fa fa-folder-open-o"></i>
                                            </div>
                                            <span style="font-size: 14px; font-weight: 600;">No tracked conversation pipelines found inside the database registries.</span>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    {{-- Data Pagination Links Footer Section --}}
                    <div class="box-footer clearfix" style="background: #fff; padding: 15px; border-top: 1px solid #f4f4f4;">
                        <div class="no-margin pull-right">
                            {{ $conversations->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
