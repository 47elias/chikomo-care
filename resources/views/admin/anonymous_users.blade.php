@extends('layouts.master')
<title>Anonymous Identity Directory - Chikomo Care</title>

@section('content-wrapper')
<div class="content-wrapper">
    {{-- Header Section --}}
    <section class="content-header">
        <h1>
            Anonymous Session Registry
            <small>Manage guest tracking tokens and telemetry logs</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Anonymous Directory</li>
        </ol>
    </section>

    {{-- Main Content Window --}}
    <section class="content">
        {{-- Session Feedback Alerts --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible shadow">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> Action Completed!</h4>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible shadow">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-ban"></i> System Exception!</h4>
                {{ session('error') }}
            </div>
        @endif

        {{-- Overview Analytics Cards --}}
        <div class="row">
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="info-box shadow">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-mask"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Guest Tokens</span>
                        <span class="info-box-number" style="font-size: 22px; font-weight: 600; margin-top: 4px;">
                            {{ $totalAnonymousCount ?? 0 }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="info-box shadow">
                    <span class="info-box-icon bg-green"><i class="fa fa-clock-o"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Active This Hour</span>
                        <span class="info-box-number" style="font-size: 22px; font-weight: 600; margin-top: 4px;">
                            {{ $activeRecentCount ?? 0 }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-12 col-xs-12">
                <div class="info-box shadow">
                    <span class="info-box-icon bg-red"><i class="fa fa-trash"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">System Auto-Purge Policy</span>
                        <span class="info-box-number" style="font-size: 14px; font-weight: 700; color: #dd4b39; margin-top: 8px; text-transform: uppercase;">
                            <span class="label label-danger">Every 30 Days</span>
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
                            <i class="fa fa-database text-red" style="margin-right: 5px;"></i> Isolated Session Records
                        </h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-sm btn-default" onclick="window.location.reload();">
                                <i class="fa fa-refresh"></i> Refresh Log Table
                            </button>
                        </div>
                    </div>

                    <div class="box-body table-responsive no-padding" style="background: #fff;">
                        <table class="table table-hover table-striped" style="margin-bottom: 0; vertical-align: middle;">
                            <thead>
                                <tr style="background-color: #fafafa; border-bottom: 2px solid #f4f4f4;">
                                    <th style="padding: 12px 15px; width: 80px;" class="text-center">ID</th>
                                    <th style="padding: 12px 15px;">Anonymous Identifier Token</th>
                                    <th style="padding: 12px 15px; width: 180px;">Origin Device / IP Address</th>
                                    <th style="padding: 12px 15px; width: 180px;">Generated Stamp</th>
                                    <th style="padding: 12px 15px; width: 180px;">Last Activity</th>
                                    <th style="padding: 12px 15px; width: 130px;" class="text-center">Operations</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($anonymousUsers ?? [] as $anonUser)
                                    <tr>
                                        <td class="text-center" style="padding: 12px 15px; font-weight: 600; color: #777;">
                                            {{ $anonUser->id }}
                                        </td>
                                        <td style="padding: 12px 15px; font-family: monospace; font-size: 13px; color: #555;">
                                            <i class="fa fa-key text-muted" style="margin-right: 5px;"></i> {{ $anonUser->guest_token ?? 'anon_'.md5($anonUser->id) }}
                                        </td>
                                        <td style="padding: 12px 15px; color: #444;">
                                            <span class="label label-default" style="font-size: 11px; font-weight: 600;">
                                                {{ $anonUser->ip_address ?? 'Protected/Masked' }}
                                            </span>
                                        </td>
                                        <td style="padding: 12px 15px; color: #666; font-size: 13px;">
                                            {{ $anonUser->created_at ? $anonUser->created_at->format('d M Y, h:i A') : 'N/A' }}
                                        </td>
                                        <td style="padding: 12px 15px; font-size: 13px;">
                                            <span class="text-muted">
                                                {{ $anonUser->updated_at ? $anonUser->updated_at->diffForHumans() : 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="text-center" style="padding: 8px 15px;">
                                            <form action="{{ route('admin.anonymous-users.destroy', $anonUser->id) }}" method="POST" onsubmit="return confirm('Warning: Terminating this anonymous record will erase all unlinked tracking properties completely. Continue?');" style="margin: 0;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-xs btn-danger btn-flat shadow-sm" style="font-weight: 600; padding: 3px 8px;">
                                                    <i class="fa fa-trash" style="margin-right: 3px;"></i> Purge
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted" style="padding: 40px 15px;">
                                            <div style="font-size: 36px; color: #d2d6de; margin-bottom: 10px;">
                                                <i class="fa fa-folder-open-o"></i>
                                            </div>
                                            <span style="font-size: 14px; font-weight: 600;">No active anonymous system registry paths located.</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Data Pagination Links Footer Section --}}
                    @if(isset($anonymousUsers) && method_exists($anonymousUsers, 'links'))
                        <div class="box-footer clearfix" style="background: #fff; padding: 15px; border-top: 1px solid #f4f4f4;">
                            <div class="no-margin pull-right">
                                {{ $anonymousUsers->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
