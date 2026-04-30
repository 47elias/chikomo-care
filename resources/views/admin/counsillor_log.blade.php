@extends('layouts.master')
<title>Assignment Logs - Chikomo Care</title>

@section('content-wrapper')
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Assignment Logs
            <small>Counselor-Student Engagements</small>
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Active Engagement Registry</h3>
                        <div class="box-tools">
                            <div class="input-group input-group-sm" style="width: 150px;">
                                <input type="text" name="table_search" class="form-control pull-right" placeholder="Search Alias...">
                                <div class="input-group-btn">
                                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Log ID</th>
                                    <th>Counselor</th>
                                    <th>Student Alias</th>
                                    <th>Risk Level</th>
                                    <th>Start Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($assignments as $log)
                                <tr>
                                    <td>#{{ $log->id }}</td>
                                    <td>
                                        <b>{{ $log->counselor->user->name }}</b><br>
                                        <small class="text-muted">{{ $log->counselor->specialization }}</small>
                                    </td>
                                    <td>
                                        <span class="label label-default">{{ $log->conversation->alias }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $riskClass = [
                                                'low' => 'label-success',
                                                'medium' => 'label-warning',
                                                'high' => 'label-danger'
                                            ][$log->conversation->risk_level] ?? 'label-default';
                                        @endphp
                                        <span class="label {{ $riskClass }}">
                                            {{ strtoupper($log->conversation->risk_level) }}
                                        </span>
                                    </td>
                                    <td>{{ $log->created_at->format('d M Y, H:i') }}</td>
                                    <td>
                                        @if($log->is_active)
                                            <span class="text-green"><i class="fa fa-circle"></i> Active Engagement</span>
                                        @else
                                            <span class="text-gray"><i class="fa fa-check-circle"></i> Closed</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-xs btn-info"><i class="fa fa-eye"></i> View Logs</a>
                                        <button class="btn btn-xs btn-danger"><i class="fa fa-user-times"></i> Reassign</button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No active counselor assignments found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer clearfix">
                        {{-- Pagination link placeholder --}}
                        <ul class="pagination pagination-sm no-margin pull-right">
                            <li><a href="#">&laquo;</a></li>
                            <li><a href="#">1</a></li>
                            <li><a href="#">&raquo;</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
