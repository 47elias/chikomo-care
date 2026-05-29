@extends('layouts.master')

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
        {{-- Alert Containers --}}
        <div id="alert-container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">×</button><h4><i class="icon fa fa-check"></i> Success</h4>{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">×</button><h4><i class="icon fa fa-ban"></i> Alert</h4>{{ session('error') }}</div>
            @endif
        </div>

        <div class="row">
            <div class="col-md-7">
                {{-- Live Incoming Requests --}}
                <div class="box box-warning shadow-sm">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="font-weight: 600; color: #e67e22;">
                            <i class="fa fa-bell"></i> Incoming Request Queue
                        </h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>User Alias</th>
                                        <th>Risk Classification</th>
                                        <th>Requested At</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="live-queue-table-body">
                                    {{-- Populated via AJAX --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Active Sessions --}}
                <div class="box box-success shadow-sm">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="color: #27ae60;"><i class="fa fa-comments"></i> Your Active Connections</h3>
                    </div>
                    <div class="box-body" id="active-chats-container">
                        @forelse($activeChats as $chat)
                            <div class="attachment-block" style="border: 1px solid #ddd; padding: 10px; margin-bottom: 5px;">
                                <h4><a href="{{ route('counselor.chat', $chat->id) }}">{{ $chat->alias ?? 'Anonymous Client' }}</a></h4>
                                <a href="{{ route('counselor.chat', $chat->id) }}" class="btn btn-sm btn-success">Open Workspace</a>
                            </div>
                        @empty
                            <p class="text-muted text-center">No active sessions.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Historical Logs --}}
            <div class="col-md-5">
                <div class="box box-default shadow-sm">
                    <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-history"></i> Archive Logs</h3></div>
                    <div class="box-body" style="max-height: 520px; overflow-y: auto;">
                        <ul class="timeline timeline-inverse">
                            @foreach($historicalLogs as $logItem)
                                <li>
                                    <i class="fa fa-folder bg-purple"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="fa fa-clock-o"></i> {{ \Carbon\Carbon::parse($logItem->session_ended_at)->format('d M') }}</span>
                                        <h3 class="timeline-header">Closed Case #{{ $logItem->conversation_id }}</h3>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        const csrfToken = "{{ csrf_token() }}";

        function updateQueue() {
            $.ajax({
                url: "{{ route('counselor.queue.json') }}",
                type: "GET",
                headers: { 'X-CSRF-TOKEN': csrfToken },
                success: function(data) {
                    const tableBody = $('#live-queue-table-body');

                    if (!data || data.length === 0) {
                        tableBody.html('<tr><td colspan="4" class="text-center">No pending requests.</td></tr>');
                        return;
                    }

                    let rows = '';
                    data.forEach(req => {
                        const riskBadge = req.risk_level === 'high' ?
                            '<span class="label label-danger">High Risk</span>' :
                            '<span class="label label-success">Standard</span>';

                        rows += `
                            <tr>
                                <td>${req.alias}</td>
                                <td>${riskBadge}</td>
                                <td>${req.formatted_time}</td>
                                <td class="text-center">
                                    <form action="/counselor-portal/accept/${req.id}" method="POST">
                                        <input type="hidden" name="_token" value="${csrfToken}">
                                        <button type="submit" class="btn btn-xs btn-primary">Accept</button>
                                    </form>
                                </td>
                            </tr>
                        `;
                    });
                    tableBody.html(rows);
                },
                error: function(err) { console.error("Polling error:", err); }
            });
        }

        // Poll every 3 seconds
        setInterval(updateQueue, 3000);
        updateQueue();
    });
</script>
@endsection
