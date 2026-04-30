@extends('layouts.master')
<title>Dashboard - Chikomo Care</title>
@section('content-wrapper')
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Chikomo Care Dashboard
            <small>System Behavior & Risk Monitoring</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>

    <section class="content">
        {{-- High-Level Metrics based on SQL Tables --}}
        <div class="row">
            {{-- Total Conversations from `conversations` table --}}
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>6</h3> {{-- Count from INSERT INTO `conversations` --}}
                        <p>Total Conversations</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-comments"></i>
                    </div>
                    <a href="#" class="small-box-footer">View Details <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

            {{-- Risk Level Tracking --}}
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>100<sup style="font-size: 20px">%</sup></h3>
                        <p>Low Risk Conversations</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-shield"></i>
                    </div>
                    <a href="#" class="small-box-footer">Risk Reports <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

            {{-- Anonymous Users --}}
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>0</h3> {{-- table `anyms-users` is currently empty in dump --}}
                        <p>Registered Anonymous Users</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-user-secret"></i>
                    </div>
                    <a href="#" class="small-box-footer">User Management <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

            {{-- Flagged Content --}}
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>0</h3> {{-- all `is_flagged` values are 0 in dump --}}
                        <p>Flagged Conversations</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-flag"></i>
                    </div>
                    <a href="#" class="small-box-footer">Review Flags <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Conversation Activity Chart --}}
            <div class="col-md-8">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">User Engagement (Aliases)</h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Alias</th>
                                        <th>Risk Level</th>
                                        <th>Started At</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Data derived from `conversations` dump --}}
                                    <tr>
                                        <td>Brave Mountain</td>
                                        <td><span class="label label-success">Low</span></td>
                                        <td>2026-04-23</td>
                                        <td><i class="fa fa-circle text-success"></i> Active</td>
                                    </tr>
                                    <tr>
                                        <td>Resilient Shield</td>
                                        <td><span class="label label-success">Low</span></td>
                                        <td>2026-04-23</td>
                                        <td><i class="fa fa-circle text-success"></i> Active</td>
                                    </tr>
                                    <tr>
                                        <td>Quiet Shield</td>
                                        <td><span class="label label-success">Low</span></td>
                                        <td>2026-04-23</td>
                                        <td><i class="fa fa-circle text-success"></i> Active</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Infrastructure Health --}}
            <div class="col-md-4">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">System Health</h3>
                    </div>
                    <div class="box-body">
                        <p><strong>Database:</strong> MariaDB 10.4.32</p>
                        <p><strong>PHP Version:</strong> 8.2.12</p>
                        <hr>
                        <div class="progress-group">
                            <span class="progress-text">Active Sessions</span>
                            <span class="progress-number"><b>2</b>/100</span>
                            <div class="progress sm">
                                <div class="progress-bar progress-bar-aqua" style="width: 2%"></div>
                            </div>
                        </div>
                        <div class="progress-group">
                            <span class="progress-text">Failed Jobs</span>
                            <span class="progress-number"><b>0</b>/0</span>
                            <div class="progress sm">
                                <div class="progress-bar progress-bar-red" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
