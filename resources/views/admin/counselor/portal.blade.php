@extends('layouts.master')

@section('content-wrapper')
<style>
    :root {
        --cp-ink: #1f2a37;
        --cp-muted: #6b7785;
        --cp-line: #e7eaee;
        --cp-surface: #ffffff;
        --cp-canvas: #f6f7f9;
        --cp-teal: #1c7f77;
        --cp-teal-soft: #e6f3f2;
        --cp-amber: #b8791a;
        --cp-amber-soft: #fbf1e0;
        --cp-rose: #b5424a;
        --cp-rose-soft: #fbeaec;
    }

    .cp-wrapper { background: var(--cp-canvas); }

    .cp-header {
        padding: 22px 26px;
        background: var(--cp-surface);
        border: 1px solid var(--cp-line);
        border-radius: 10px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .cp-header h1 {
        font-size: 20px;
        font-weight: 700;
        color: var(--cp-ink);
        margin: 0 0 4px 0;
        letter-spacing: -0.01em;
    }
    .cp-header .cp-subtitle {
        font-size: 13px;
        color: var(--cp-muted);
        font-weight: 400;
    }
    .cp-header .breadcrumb {
        background: none;
        padding: 0;
        margin: 10px 0 0 0;
        font-size: 12px;
    }
    .cp-live-indicator {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        font-weight: 700;
        color: var(--cp-teal);
        background: var(--cp-teal-soft);
        padding: 7px 14px;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .cp-live-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--cp-teal);
        animation: cp-pulse 1.6s infinite;
    }
    @keyframes cp-pulse {
        0% { box-shadow: 0 0 0 0 rgba(28,127,119,0.45); }
        70% { box-shadow: 0 0 0 8px rgba(28,127,119,0); }
        100% { box-shadow: 0 0 0 0 rgba(28,127,119,0); }
    }

    #alert-container .alert {
        border: none;
        border-radius: 8px;
        margin-bottom: 16px;
    }

    .cp-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.65fr) minmax(0, 1fr);
        gap: 18px;
        align-items: start;
    }
    @media (max-width: 991px) {
        .cp-grid { grid-template-columns: 1fr; }
    }

    .cp-panel {
        background: var(--cp-surface);
        border: 1px solid var(--cp-line);
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 18px;
    }
    .cp-panel-header {
        padding: 16px 20px;
        border-bottom: 1px solid var(--cp-line);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }
    .cp-panel-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--cp-ink);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .cp-panel-title i { color: var(--cp-amber); }
    .cp-panel-title.cp-success i { color: var(--cp-teal); }
    .cp-panel-title.cp-archive i { color: var(--cp-muted); }

    .cp-count-chip {
        font-size: 11px;
        font-weight: 700;
        background: var(--cp-canvas);
        color: var(--cp-muted);
        padding: 3px 10px;
        border-radius: 20px;
    }

    /* Queue table */
    .cp-table { margin-bottom: 0; }
    .cp-table thead th {
        background: var(--cp-canvas);
        color: var(--cp-muted);
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 12px 20px;
        border-bottom: 1px solid var(--cp-line);
        border-top: none !important;
        white-space: nowrap;
    }
    .cp-table tbody td {
        padding: 13px 20px;
        vertical-align: middle;
        border-top: 1px solid var(--cp-line);
        font-size: 13px;
        color: var(--cp-ink);
    }
    .cp-table tbody tr:hover { background: var(--cp-canvas); }
    .cp-empty-row td {
        text-align: center;
        color: var(--cp-muted);
        font-size: 13px;
        font-weight: 600;
        padding: 30px 20px !important;
    }

    .cp-alias-cell {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
    }
    .cp-alias-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: var(--cp-teal-soft);
        color: var(--cp-teal);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        flex-shrink: 0;
    }

    .pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        padding: 4px 10px;
        border-radius: 20px;
        white-space: nowrap;
    }
    .pill::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block;
    }
    .pill-high { background: var(--cp-rose-soft); color: var(--cp-rose); }
    .pill-high::before { background: var(--cp-rose); }
    .pill-standard { background: var(--cp-teal-soft); color: var(--cp-teal); }
    .pill-standard::before { background: var(--cp-teal); }

    .cp-accept-form { display: inline-block; }
    .btn-accept {
        border: none;
        background: var(--cp-teal);
        color: #fff;
        font-size: 12px;
        font-weight: 700;
        padding: 7px 16px;
        border-radius: 6px;
        transition: background .15s ease;
    }
    .btn-accept:hover { background: #16645e; color: #fff; }

    /* Active connections */
    .cp-chat-list {
        padding: 14px 20px;
        display: grid;
        gap: 10px;
    }
    .cp-chat-card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        border: 1px solid var(--cp-line);
        border-radius: 8px;
        padding: 12px 14px;
        background: var(--cp-surface);
    }
    .cp-chat-card-left {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }
    .cp-chat-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--cp-teal-soft);
        color: var(--cp-teal);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
    }
    .cp-chat-name {
        font-size: 13.5px;
        font-weight: 700;
        color: var(--cp-ink);
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .cp-chat-sub {
        font-size: 11.5px;
        color: var(--cp-muted);
    }
    .btn-open-workspace {
        border: 1px solid var(--cp-teal);
        background: var(--cp-teal-soft);
        color: var(--cp-teal);
        font-size: 12px;
        font-weight: 700;
        padding: 7px 14px;
        border-radius: 6px;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .btn-open-workspace:hover { background: var(--cp-teal); color: #fff; }

    .cp-empty-state {
        padding: 34px 20px;
        text-align: center;
        color: var(--cp-muted);
        font-size: 13px;
        font-weight: 600;
    }
    .cp-empty-state i {
        display: block;
        font-size: 26px;
        color: #d5dae0;
        margin-bottom: 8px;
    }

    /* Archive timeline */
    .cp-archive-body {
        max-height: 560px;
        overflow-y: auto;
        padding: 6px 20px 16px 20px;
    }
    .cp-archive-item {
        display: flex;
        gap: 12px;
        padding: 12px 0;
        border-bottom: 1px solid var(--cp-line);
    }
    .cp-archive-item:last-child { border-bottom: none; }
    .cp-archive-icon {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        background: var(--cp-canvas);
        color: var(--cp-muted);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        flex-shrink: 0;
    }
    .cp-archive-title {
        font-size: 13px;
        font-weight: 700;
        color: var(--cp-ink);
        margin: 0;
    }
    .cp-archive-time {
        font-size: 11.5px;
        color: var(--cp-muted);
    }
</style>

<div class="content-wrapper cp-wrapper">
    <section class="content-header">
        <div class="cp-header">
            <div>
                <h1>
                    Counselor Support Hub
                    <div class="cp-subtitle">Live session routing queue</div>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Counselor Portal</li>
                </ol>
            </div>
            <div class="cp-live-indicator">
                <span class="cp-live-dot"></span> Live &middot; updates every 3s
            </div>
        </div>
    </section>

    <section class="content">
        {{-- Alert Containers --}}
        <div id="alert-container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><h4><i class="icon fa fa-check"></i> Success</h4>{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><h4><i class="icon fa fa-ban"></i> Alert</h4>{{ session('error') }}</div>
            @endif
        </div>

        <div class="cp-grid">
            {{-- Left column: queue + active --}}
            <div>
                {{-- Live Incoming Requests --}}
                <div class="cp-panel">
                    <div class="cp-panel-header">
                        <h3 class="cp-panel-title">
                            <i class="fa fa-bell"></i> Incoming Request Queue
                        </h3>
                        <span class="cp-count-chip" id="queue-count-chip">0 waiting</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table cp-table">
                            <thead>
                                <tr>
                                    <th>User Alias</th>
                                    <th>Risk Classification</th>
                                    <th>Requested At</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="live-queue-table-body">
                                <tr class="cp-empty-row"><td colspan="4">Loading queue&hellip;</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Active Sessions --}}
                <div class="cp-panel">
                    <div class="cp-panel-header">
                        <h3 class="cp-panel-title cp-success"><i class="fa fa-comments"></i> Your Active Connections</h3>
                        <span class="cp-count-chip">{{ $activeChats->count() ?? 0 }} open</span>
                    </div>
                    <div id="active-chats-container">
                        @forelse($activeChats as $chat)
                            <div class="cp-chat-list" style="padding-bottom: 0;">
                                <div class="cp-chat-card">
                                    <div class="cp-chat-card-left">
                                        <span class="cp-chat-avatar"><i class="fa fa-user-secret"></i></span>
                                        <div>
                                            <p class="cp-chat-name">{{ $chat->alias ?? 'Anonymous Client' }}</p>
                                            <span class="cp-chat-sub">Session in progress</span>
                                        </div>
                                    </div>
                                    <a href="{{ route('counselor.chat', $chat->id) }}" class="btn-open-workspace">Open Workspace</a>
                                </div>
                            </div>
                        @empty
                            <div class="cp-empty-state">
                                <i class="fa fa-moon-o"></i>
                                No active sessions right now.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Right column: archive --}}
            <div>
                <div class="cp-panel">
                    <div class="cp-panel-header">
                        <h3 class="cp-panel-title cp-archive"><i class="fa fa-history"></i> Archive Logs</h3>
                    </div>
                    <div class="cp-archive-body">
                        @forelse($historicalLogs as $logItem)
                            <div class="cp-archive-item">
                                <span class="cp-archive-icon"><i class="fa fa-folder"></i></span>
                                <div>
                                    <p class="cp-archive-title">Closed Case #{{ $logItem->conversation_id }}</p>
                                    <span class="cp-archive-time"><i class="fa fa-clock-o"></i> {{ \Carbon\Carbon::parse($logItem->session_ended_at)->format('d M') }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="cp-empty-state">
                                <i class="fa fa-inbox"></i>
                                No closed cases yet.
                            </div>
                        @endforelse
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
                    const countChip = $('#queue-count-chip');

                    if (!data || data.length === 0) {
                        tableBody.html('<tr class="cp-empty-row"><td colspan="4">No pending requests.</td></tr>');
                        countChip.text('0 waiting');
                        return;
                    }

                    countChip.text(data.length + (data.length === 1 ? ' waiting' : ' waiting'));

                    let rows = '';
                    data.forEach(req => {
                        const riskBadge = req.risk_level === 'high' ?
                            '<span class="pill pill-high">High Risk</span>' :
                            '<span class="pill pill-standard">Standard</span>';

                        rows += `
                            <tr>
                                <td>
                                    <div class="cp-alias-cell">
                                        <span class="cp-alias-avatar"><i class="fa fa-user-secret"></i></span>
                                        ${req.alias}
                                    </div>
                                </td>
                                <td>${riskBadge}</td>
                                <td>${req.formatted_time}</td>
                                <td class="text-center">
                                    <form class="cp-accept-form" action="/counselor-portal/accept/${req.id}" method="POST">
                                        <input type="hidden" name="_token" value="${csrfToken}">
                                        <button type="submit" class="btn-accept">Accept</button>
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
