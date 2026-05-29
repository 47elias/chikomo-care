@extends('layouts.master')
<title>Counselor Console - Chikomo Care</title>

@section('content-wrapper')
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Counselor Support Hub
            <small>Live Session Routing Queue</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Counselor Portal</li>
        </ol>
    </section>

    <section class="content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i> Connected</h4>
                {{ session('success') }}
            </div>
        @endif

        <div class="row">
            {{-- Left Column: Live Queue and Active Connections --}}
            <div class="col-md-7">
                {{-- Live Incoming Triage Pool Requests Box --}}
                <div class="box box-warning shadow-sm" style="border-radius: 4px;">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="font-weight: 600; color: #e67e22;">
                            <i class="fa fa-bell text-orange animated infinite pulse"></i> Incoming Request Queue
                        </h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr style="background: #f9fafb;">
                                        <th>User Alias</th>
                                        <th>Risk Classification</th>
                                        <th>Requested At</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($incomingRequests) > 0)
                                        @foreach($incomingRequests as $req)
                                            <tr>
                                                <td style="font-weight: 600;"><i class="fa fa-user-secret text-muted"></i> {{ $req->alias ?? 'Anonymous Guest' }}</td>
                                                <td>
                                                    @if(strtolower($req->risk_level) == 'high')
                                                        <span class="label label-danger">High Risk</span>
                                                    @else
                                                        <span class="label label-success">Standard</span>
                                                    @endif
                                                </td>
                                                <td class="text-muted">{{ \Carbon\Carbon::parse($req->created_at)->format('H:i:s (d M)') }}</td>
                                                <td class="text-center">
                                                    <form action="{{ route('counselor.accept', $req->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-xs btn-primary style-flat" style="font-weight:600;">
                                                            <i class="fa fa-sign-in"></i> Accept Connection
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" class="text-center text-muted" style="padding: 24px;">
                                                <i class="fa fa-refresh fa-spin text-muted"></i> Waiting for new client requests...
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Active ongoing sessions box --}}
                <div class="box box-success shadow-sm" style="border-radius: 4px;">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="font-weight: 600; color: #27ae60;">
                            <i class="fa fa-comments text-success"></i> Your Active Counseling Connections
                        </h3>
                    </div>
                    <div class="box-body">
                        @if(count($activeChats) > 0)
                            @foreach($activeChats as $chat)
                                <div class="attachment-block clearfix" style="background: #fff; border: 1px solid #ddd; margin-bottom: 10px; padding: 12px; border-radius: 3px;">
                                    <div class="attachment-pushed" style="margin-left: 0;">
                                        <h4 class="attachment-heading" style="margin: 0 0 5px 0;">
                                            <a href="{{ route('counselor.chat', $chat->id) }}" style="font-weight: 700; color: #2c3e50;">
                                                <i class="fa fa-comment text-green"></i> Session: {{ $chat->alias ?? 'Anonymous Client' }}
                                            </a>
                                            <span class="pull-right label label-success">Active Connection</span>
                                        </h4>
                                        <div class="attachment-text text-muted" style="font-size: 12px; margin-bottom: 8px;">
                                            Risk Triage Rank Status: <strong>{{ strtoupper($chat->risk_level ?? 'LOW') }}</strong> | Opened: {{ $chat->updated_at->diffForHumans() }}
                                        </div>
                                        <a href="{{ route('counselor.chat', $chat->id) }}" class="btn btn-sm btn-success btn-flat"><i class="fa fa-keyboard-o"></i> Open Console Workspace</a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted text-center" style="padding: 15px; margin: 0;">You have no active chat sessions open at this time.</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Right Column: Historical Log Archives --}}
            <div class="col-md-5">
                <div class="box box-default shadow-sm" style="border-radius: 4px;">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="font-weight: 600;"><i class="fa fa-history text-muted"></i> Archive Logs & Summaries</h3>
                    </div>
                    <div class="box-body" style="max-height: 520px; overflow-y: auto;">
                        @if(count($historicalLogs) > 0)
                            <ul class="timeline timeline-inverse" style="margin-bottom: 0;">
                                @foreach($historicalLogs as $logItem)
                                    <li>
                                        <i class="fa fa-folder bg-purple"></i>
                                        <div class="timeline-item" style="box-shadow: none; background: #f8f9fa; border: 1px solid #eee;">
                                            <span class="time" style="color: #999;"><i class="fa fa-clock-o"></i> {{ \Carbon\Carbon::parse($logItem->session_ended_at)->format('d M Y') }}</span>
                                            <h3 class="timeline-header" style="font-size: 13px; font-weight: 700;">
                                                Closed Case ID: #{{ $logItem->conversation_id }} ({{ $logItem->conversation->alias ?? 'Anonymous Peer' }})
                                            </h3>
                                            <div class="timeline-body" style="font-size: 12px; color: #555; padding: 8px;">
                                                <strong>Case Notes:</strong> {{ $logItem->summary_notes ?? 'No operational summary recorded.' }}
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                                <li><i class="fa fa-clock-o bg-gray"></i></li>
                            </ul>
                        @else
                            <p class="text-muted text-center" style="padding: 20px; margin: 0;">No completed counseling records saved to your account log archive.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
