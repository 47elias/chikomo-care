@extends('layouts.master')
<title>Peer Stories Moderation - Chikomo Care</title>

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
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i> Action Completed Successfully</h4>
                {{ session('success') }}
            </div>
        @endif

        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary shadow-sm" style="border-radius: 4px;">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="font-weight: 600;"><i class="fa fa-book text-primary"></i> Experience Evaluation Queue</h3>
                    </div>

                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0">
                                <thead>
                                    <tr style="background: #f9fafb;">
                                        <th style="padding: 12px 8px; width: 150px;">Author Identity</th>
                                        <th style="padding: 12px 8px; width: 200px;">Story Title Title</th>
                                        <th style="padding: 12px 8px;">Narrative Content Segment</th>
                                        <th style="padding: 12px 8px; width: 120px;" class="text-center">Quality Rating</th>
                                        <th style="padding: 12px 8px; width: 130px;" class="text-center">Moderation Status</th>
                                        <th style="padding: 12px 8px; width: 160px;" class="text-center">Action Parameters</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($stories) > 0)
                                        @foreach($stories as $story)
                                            <tr>
                                                <td style="font-weight: 600; padding: 12px 8px; color: #444;">
                                                    <i class="fa fa-user-secret text-muted"></i> {{ $story->author_alias }}
                                                </td>
                                                <td style="font-weight: 600; padding: 12px 8px; color: #2c3e50;">{{ $story->title }}</td>
                                                <td style="color: #555; padding: 12px 8px; font-size: 13px;">
                                                    {{ Str::limit($story->content, 180, '...') }}
                                                </td>
                                                <td style="padding: 12px 8px;" class="text-center">
                                                    <span class="text-yellow" style="font-weight: bold; font-size: 14px;">
                                                        <i class="fa fa-star"></i> {{ number_format($story->rating_average, 1) }}
                                                    </span>
                                                    <br>
                                                    <small class="text-muted">({{ $story->total_ratings_count }} votes)</small>
                                                </td>
                                                <td style="padding: 12px 8px;" class="text-center">
                                                    @if($story->is_approved == 1)
                                                        <span class="label label-success" style="font-weight: 600; padding: .3em .8em; font-size: 11px;">Publicly Active</span>
                                                    @else
                                                        <span class="label label-warning" style="font-weight: 600; padding: .3em .8em; font-size: 11px;">Pending Validation</span>
                                                    @endif
                                                </td>
                                                <td style="padding: 12px 8px;" class="text-center">
                                                    <div class="btn-group">
                                                        {{-- Visibility Triage Switch Button --}}
                                                        <form action="{{ route('peer-stories.toggle', $story->id) }}" method="POST" style="display:inline-block;">
                                                            @csrf
                                                            @method('PATCH')
                                                            @if($story->is_approved == 1)
                                                                <button type="submit" class="btn btn-xs btn-default text-orange" title="Revoke Visibility"><i class="fa fa-ban"></i> Hide</button>
                                                            @else
                                                                <button type="submit" class="btn btn-xs btn-default text-green" title="Grant Public Status"><i class="fa fa-check"></i> Approve</button>
                                                            @endif
                                                        </form>

                                                        {{-- Discard Record Button Element --}}
                                                        <form action="{{ route('peer-stories.destroy', $story->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Confirm structural purge of this shared narrative documentation block?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-xs btn-default text-red" title="Purge Log Frame"><i class="fa fa-trash"></i> Delete</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6" class="text-center text-muted" style="padding: 40px;">
                                                <i class="fa fa-clone fa-2x d-block mb-2"></i><br>
                                                No incoming peer story logs submitted from the platform client environments yet.
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
@endsection
