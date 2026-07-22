@extends('layouts.master')
<title>Anonymous Interactions Registry - Chikomo Care</title>

@section('content-wrapper')
<style>
    :root {
        --registry-ink: #1f2a37;
        --registry-muted: #6b7785;
        --registry-line: #e7eaee;
        --registry-surface: #ffffff;
        --registry-canvas: #f6f7f9;
        --registry-teal: #1c7f77;
        --registry-teal-soft: #e6f3f2;
        --registry-amber: #b8791a;
        --registry-amber-soft: #fbf1e0;
        --registry-rose: #b5424a;
        --registry-rose-soft: #fbeaec;
    }

    .registry-header {
        padding: 22px 26px;
        background: var(--registry-surface);
        border: 1px solid var(--registry-line);
        border-radius: 10px;
        margin-bottom: 20px;
    }
    .registry-header h1 {
        font-size: 20px;
        font-weight: 700;
        color: var(--registry-ink);
        margin: 0 0 4px 0;
        letter-spacing: -0.01em;
    }
    .registry-header .registry-subtitle {
        font-size: 13px;
        color: var(--registry-muted);
        font-weight: 400;
    }
    .registry-header .breadcrumb {
        background: none;
        padding: 0;
        margin: 12px 0 0 0;
        font-size: 12px;
    }

    .registry-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 20px;
    }
    @media (max-width: 767px) {
        .registry-stats { grid-template-columns: 1fr; }
    }
    .stat-card {
        background: var(--registry-surface);
        border: 1px solid var(--registry-line);
        border-radius: 10px;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 14px;
        border-left: 3px solid var(--registry-teal);
    }
    .stat-card.stat-flagged { border-left-color: var(--registry-rose); }
    .stat-card.stat-risk { border-left-color: var(--registry-amber); }

    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
        background: var(--registry-teal-soft);
        color: var(--registry-teal);
    }
    .stat-flagged .stat-icon { background: var(--registry-rose-soft); color: var(--registry-rose); }
    .stat-risk .stat-icon { background: var(--registry-amber-soft); color: var(--registry-amber); }

    .stat-label {
        font-size: 12px;
        color: var(--registry-muted);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 2px;
    }
    .stat-number {
        font-size: 24px;
        font-weight: 700;
        color: var(--registry-ink);
        line-height: 1;
    }

    .registry-panel {
        background: var(--registry-surface);
        border: 1px solid var(--registry-line);
        border-radius: 10px;
        overflow: hidden;
    }
    .registry-panel-header {
        padding: 16px 20px;
        border-bottom: 1px solid var(--registry-line);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .registry-panel-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--registry-ink);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .registry-panel-title i { color: var(--registry-teal); }

    .btn-refresh {
        border: 1px solid var(--registry-line);
        background: var(--registry-canvas);
        color: var(--registry-ink);
        font-size: 12px;
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 6px;
    }
    .btn-refresh:hover { background: #eef0f2; color: var(--registry-ink); }

    .registry-table { margin-bottom: 0; }
    .registry-table thead th {
        background: var(--registry-canvas);
        color: var(--registry-muted);
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 12px 20px;
        border-bottom: 1px solid var(--registry-line);
        border-top: none !important;
        white-space: nowrap;
    }
    .registry-table tbody td {
        padding: 14px 20px;
        vertical-align: middle;
        border-top: 1px solid var(--registry-line);
        font-size: 13px;
        color: var(--registry-ink);
    }
    .registry-table tbody tr:hover { background: var(--registry-canvas); }

    .ref-id {
        font-family: 'SFMono-Regular', Consolas, monospace;
        font-size: 12px;
        font-weight: 700;
        color: var(--registry-muted);
    }

    .alias-cell {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
    }
    .alias-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: var(--registry-teal-soft);
        color: var(--registry-teal);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        flex-shrink: 0;
    }

    .token-value {
        font-family: 'SFMono-Regular', Consolas, monospace;
        font-size: 12px;
        color: var(--registry-muted);
        background: var(--registry-canvas);
        padding: 3px 8px;
        border-radius: 5px;
        display: inline-block;
    }
    .token-missing {
        color: var(--registry-rose);
        font-size: 12px;
        font-weight: 600;
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
    }
    .pill::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block;
    }
    .pill-clear { background: var(--registry-teal-soft); color: var(--registry-teal); }
    .pill-clear::before { background: var(--registry-teal); }
    .pill-flagged { background: var(--registry-rose-soft); color: var(--registry-rose); }
    .pill-flagged::before { background: var(--registry-rose); }
    .pill-high { background: var(--registry-rose-soft); color: var(--registry-rose); }
    .pill-high::before { background: var(--registry-rose); }
    .pill-medium { background: var(--registry-amber-soft); color: var(--registry-amber); }
    .pill-medium::before { background: var(--registry-amber); }
    .pill-low { background: #eef1f3; color: var(--registry-muted); }
    .pill-low::before { background: var(--registry-muted); }

    .date-cell { color: var(--registry-muted); font-size: 12.5px; }

    .empty-state {
        padding: 56px 20px;
        text-align: center;
    }
    .empty-state i {
        font-size: 34px;
        color: #d5dae0;
        margin-bottom: 10px;
        display: block;
    }
    .empty-state p {
        font-size: 13px;
        font-weight: 600;
        color: var(--registry-muted);
        margin: 0;
    }

    .registry-footer {
        padding: 14px 20px;
        border-top: 1px solid var(--registry-line);
    }
</style>

<div class="content-wrapper" style="background: var(--registry-canvas);">
    {{-- Header Section --}}
    <section class="content-header" style="padding: 20px 20px 0 20px;">
        <div class="registry-header">
            <h1>
                Anonymous Conversations Registry
                <div class="registry-subtitle">Monitor ongoing guest identity tokens, risk evaluations, and telemetry logs</div>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Anonymous Registry</li>
            </ol>
        </div>
    </section>

    {{-- Main Content Window --}}
    <section class="content" style="padding: 0 20px 20px 20px;">
        @php
            // SAFETY FALLBACK CHECK: If your routing optimization cache acts up,
            // this guarantees the view has an instance to process without crashing out with a 500.
            if (!isset($conversations)) {
                $conversations = \App\Models\Conversation::orderBy('created_at', 'desc')->paginate(10);
            }
        @endphp

        {{-- Overview Analytics Cards --}}
        <div class="registry-stats">
            <div class="stat-card">
                <div class="stat-icon"><i class="fa fa-comments"></i></div>
                <div>
                    <div class="stat-label">Total Conversations</div>
                    <div class="stat-number">{{ $conversations->total() }}</div>
                </div>
            </div>

            <div class="stat-card stat-flagged">
                <div class="stat-icon"><i class="fa fa-flag"></i></div>
                <div>
                    <div class="stat-label">Flagged Communications</div>
                    <div class="stat-number">{{ $conversations->where('is_flagged', true)->count() }}</div>
                </div>
            </div>

            <div class="stat-card stat-risk">
                <div class="stat-icon"><i class="fa fa-warning"></i></div>
                <div>
                    <div class="stat-label">High Risk Signals</div>
                    <div class="stat-number">{{ $conversations->where('risk_level', 'high')->count() }}</div>
                </div>
            </div>
        </div>

        {{-- Core Registry Record Data Table --}}
        <div class="registry-panel">
            <div class="registry-panel-header">
                <h3 class="registry-panel-title">
                    <i class="fa fa-database"></i> Active Schema Instances
                </h3>
                <button type="button" class="btn-refresh" onclick="window.location.reload();">
                    <i class="fa fa-refresh"></i> Refresh
                </button>
            </div>

            <div class="table-responsive no-padding">
                <table class="table registry-table">
                    <thead>
                        <tr>
                            <th class="text-center">Ref ID</th>
                            <th>Assigned Alias</th>
                            <th>Secure Session Token</th>
                            <th class="text-center">Flag Status</th>
                            <th class="text-center">Risk</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($conversations->count() > 0)
                            @foreach($conversations as $conversation)
                                <tr>
                                    <td class="text-center ref-id">#{{ $conversation->id }}</td>
                                    <td>
                                        <div class="alias-cell">
                                            <span class="alias-avatar"><i class="fa fa-user-secret"></i></span>
                                            {{ $conversation->alias ?? 'Anonymous Client' }}
                                        </div>
                                    </td>
                                    <td>
                                        @if(!empty($conversation->token))
                                            <span class="token-value" title="{{ $conversation->token }}">{{ substr($conversation->token, 0, 24) }}&hellip;</span>
                                        @else
                                            <span class="token-missing"><i class="fa fa-exclamation-triangle"></i> Token unset or expired</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($conversation->is_flagged)
                                            <span class="pill pill-flagged">Flagged</span>
                                        @else
                                            <span class="pill pill-clear">Clear</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($conversation->risk_level === 'high')
                                            <span class="pill pill-high">High</span>
                                        @elseif($conversation->risk_level === 'medium')
                                            <span class="pill pill-medium">Medium</span>
                                        @else
                                            <span class="pill pill-low">Low</span>
                                        @endif
                                    </td>
                                    <td class="date-cell">
                                        {{ $conversation->created_at ? $conversation->created_at->format('d M Y, h:i A') : 'N/A' }}
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <i class="fa fa-folder-open-o"></i>
                                        <p>No tracked conversation pipelines found inside the database registries.</p>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            {{-- Data Pagination Links Footer Section --}}
            <div class="registry-footer clearfix">
                <div class="pull-right">
                    {{ $conversations->links() }}
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
