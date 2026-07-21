@extends('layouts.master')

@section('content-wrapper')
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Peer Experience Narrative Logs
            <small>User-Submitted Moderation Board</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Peer Stories</li>
        </ol>
    </section>

    <section class="content">
        {{-- Flash Messaging Feedback Notification Center --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible modern-alert">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> Action Completed Successfully</h4>
                {{ session('success') }}
            </div>
        @endif

        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary dashboard-box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-book text-primary"></i> Experience Evaluation Queue</h3>
                    </div>

                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover modern-table mb-0">
                                <thead>
                                    <tr class="table-head-row">
                                        <th class="col-author">Author Identity</th>
                                        <th class="col-title">Story Title</th>
                                        <th>Narrative Content Segment</th>
                                        <th class="text-center col-rating">Quality Rating</th>
                                        <th class="text-center col-status">Moderation Status</th>
                                        <th class="text-center col-actions">Action Parameters</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($stories) > 0)
                                        @foreach($stories as $story)
                                            <tr>
                                                <td class="author-cell">
                                                    <i class="fa fa-user-secret text-muted"></i> {{ $story->author_alias }}
                                                </td>
                                                <td class="story-title-cell">{{ $story->title }}</td>
                                                <td class="content-cell">
                                                    {{ Str::limit($story->content, 180, '...') }}
                                                </td>
                                                <td class="text-center">
                                                    <span class="rating-value">
                                                        <i class="fa fa-star"></i> {{ number_format($story->rating_average, 1) }}
                                                    </span>
                                                    <br>
                                                    <small class="rating-votes">({{ $story->total_ratings_count }} votes)</small>
                                                </td>
                                                <td class="text-center">
                                                    @if($story->is_approved == 1)
                                                        <span class="label label-success status-pill">Publicly Active</span>
                                                    @else
                                                        <span class="label label-warning status-pill">Pending Validation</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        {{-- Visibility Triage Switch Button --}}
                                                        <form action="{{ route('peer-stories.toggle', $story->id) }}" method="POST" class="inline-form">
                                                            @csrf
                                                            @method('PATCH')
                                                            @if($story->is_approved == 1)
                                                                <button type="submit" class="btn btn-xs btn-default text-orange table-action-btn" title="Revoke Visibility"><i class="fa fa-ban"></i> Hide</button>
                                                            @else
                                                                <button type="submit" class="btn btn-xs btn-default text-green table-action-btn" title="Grant Public Status"><i class="fa fa-check"></i> Approve</button>
                                                            @endif
                                                        </form>

                                                        {{-- Discard Record Button Element --}}
                                                        <form action="{{ route('peer-stories.destroy', $story->id) }}" method="POST" class="inline-form" onsubmit="return confirm('Confirm structural purge of this shared narrative documentation block?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-xs btn-default text-red table-action-btn" title="Purge Log Frame"><i class="fa fa-trash"></i> Delete</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6" class="text-center empty-state">
                                                <i class="fa fa-clone fa-2x"></i>
                                                <div>No incoming peer story logs submitted from the platform client environments yet.</div>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    .modern-alert {
        border-radius: 8px;
        border: none;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    }

    .dashboard-box {
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
    }

    .dashboard-box .box-header {
        padding: 16px 18px;
    }

    .dashboard-box .box-title {
        font-weight: 600;
    }

    /* Table */
    .modern-table {
        margin-bottom: 0;
    }

    .table-head-row th {
        background: #f9fafb;
        padding: 12px 10px;
        font-size: 11.5px;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #64748b;
        font-weight: 700;
        border-bottom: 2px solid #eef1f4 !important;
    }

    .col-author { width: 150px; }
    .col-title { width: 200px; }
    .col-rating { width: 120px; }
    .col-status { width: 130px; }
    .col-actions { width: 160px; }

    .modern-table td {
        padding: 12px 10px;
        vertical-align: middle;
    }

    .author-cell {
        font-weight: 600;
        color: #475569;
        font-size: 13px;
    }

    .author-cell i {
        margin-right: 4px;
    }

    .story-title-cell {
        font-weight: 600;
        color: #1e293b;
    }

    .content-cell {
        color: #64748b;
        font-size: 13px;
        line-height: 1.5;
    }

    .rating-value {
        font-weight: 700;
        font-size: 14px;
        color: #f0ad4e;
    }

    .rating-votes {
        color: #94a3b8;
    }

    .status-pill {
        font-weight: 600;
        padding: 0.35em 0.9em;
        border-radius: 20px;
        font-size: 11px;
        letter-spacing: 0.2px;
    }

    .inline-form {
        display: inline-block;
    }

    .table-action-btn {
        border-radius: 4px;
        font-weight: 500;
        transition: background 0.15s ease;
    }

    .table-action-btn:hover {
        background: #f1f5f9;
    }

    .empty-state {
        padding: 40px;
        color: #94a3b8;
    }

    .empty-state i {
        display: block;
        margin-bottom: 8px;
        opacity: 0.6;
    }
</style>
@endsection
