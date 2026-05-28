@extends('layouts.master')
<title>Anonymous Users Analytics - Chikomo Care</title>

@section('content-wrapper')
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Anonymous User Insights
            <small>Visualized Client Behavioral Data</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Anonymous Analytics</li>
        </ol>
    </section>

    <section class="content">
        {{-- Performance Indicators Row --}}
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-aqua shadow-sm">
                    <span class="info-box-icon"><i class="fa fa-user-secret"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Anonymous Clients</span>
                        <span class="info-box-number">{{ $totalAnonymousUsers }}</span>
                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                        <span class="progress-description">Total guest profiles tracked</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-green shadow-sm">
                    <span class="info-box-icon"><i class="fa fa-shield"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Unflagged Users</span>
                        <span class="info-box-number">
                            {{ $totalAnonymousUsers > 0 ? round((($totalAnonymousUsers - $flaggedCount) / $totalAnonymousUsers) * 100) : 100 }}%
                        </span>
                        <div class="progress">
                            <div class="progress-bar" style="width: {{ $totalAnonymousUsers > 0 ? round((($totalAnonymousUsers - $flaggedCount) / $totalAnonymousUsers) * 100) : 100 }}%"></div>
                        </div>
                        <span class="progress-description">{{ $flaggedCount }} secure records flagged</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-yellow shadow-sm">
                    <span class="info-box-icon"><i class="fa fa-warning"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Medium Risks</span>
                        <span class="info-box-number">{{ $mediumRiskCount }}</span>
                        <div class="progress">
                            <div class="progress-bar" style="width: {{ $mediumRiskPercent }}%"></div>
                        </div>
                        <span class="progress-description">Comprises {{ $mediumRiskPercent }}% of guest pool</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-red shadow-sm">
                    <span class="info-box-icon"><i class="fa fa-exclamation-triangle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Critical Alerts</span>
                        <span class="info-box-number">{{ $highRiskCount }}</span>
                        <div class="progress">
                            <div class="progress-bar" style="width: {{ $highRiskPercent }}%"></div>
                        </div>
                        <span class="progress-description">Requires identity triaging</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Line Chart: Anonymous Session Volume over 7 Days --}}
            <div class="col-md-7">
                <div class="box box-info shadow-sm" style="border-radius: 4px;">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="font-weight: 600;">
                            <i class="fa fa-line-chart text-info"></i> Anonymous Users Traffic (Last 7 Days)
                        </h3>
                    </div>
                    <div class="box-body">
                        <div class="chart">
                            <canvas id="lineChart" style="height:250px; width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Doughnut Chart: Anonymous Client Risk Level Distribution --}}
            <div class="col-md-5">
                <div class="box box-danger shadow-sm" style="border-radius: 4px;">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="font-weight: 600;">
                            <i class="fa fa-pie-chart text-danger"></i> Anonymous Risk Distribution
                        </h3>
                    </div>
                    <div class="box-body">
                        <div class="chart">
                            <canvas id="pieChart" style="height:250px; width: 100%;"></canvas>
                        </div>
                    </div>
                    <div class="box-footer no-padding" style="background: #fff;">
                        <ul class="nav nav-pills nav-stacked">
                            <li><a href="#" style="padding: 12px 15px;">Low Risk / Unassigned <span class="pull-right text-green" style="font-weight: 600;"><i class="fa fa-circle"></i> {{ $lowRiskPercent }}% ({{ $lowRiskCount }})</span></a></li>
                            <li><a href="#" style="padding: 12px 15px;">Medium Risk <span class="pull-right text-yellow" style="font-weight: 600;"><i class="fa fa-circle"></i> {{ $mediumRiskPercent }}% ({{ $mediumRiskCount }})</span></a></li>
                            <li><a href="#" style="padding: 12px 15px;">High Risk <span class="pull-right text-red" style="font-weight: 600;"><i class="fa fa-circle"></i> {{ $highRiskPercent }}% ({{ $highRiskCount }})</span></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

{{-- Standardizing on a stable, production-ready release via CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<script>
  // DOMContentLoaded executes instantly without requiring $ jQuery initialization timing hooks
  document.addEventListener("DOMContentLoaded", function () {

    // --- MODERN DOUGHNUT CHART SETUP ---
    var pieCtx = document.getElementById('pieChart').getContext('2d');
    new Chart(pieCtx, {
        type: 'doughnut',
        data: {
            labels: ['Low Risk', 'Medium Risk', 'High Risk'],
            datasets: [{
                data: [{{ $lowRiskCount }}, {{ $mediumRiskCount }}, {{ $highRiskCount }}],
                backgroundColor: ['#00a65a', '#f39c12', '#f56954'],
                hoverBackgroundColor: ['#27ae60', '#f1c40f', '#e74c3c'],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false // Kept clean since you have custom list metrics below the box
                }
            }
        }
    });

    // --- MODERN LINE CHART SETUP ---
    var lineCtx = document.getElementById('lineChart').getContext('2d');
    new Chart(lineCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($daysOfWeekLabels) !!},
            datasets: [{
                label: 'Anonymous Interactions Pipeline Count',
                data: {!! json_encode($weeklyDataPoints) !!},
                backgroundColor: 'rgba(0, 192, 239, 0.15)',
                borderColor: 'rgba(0, 192, 239, 1)',
                pointBackgroundColor: '#00c0ef',
                pointBorderColor: 'rgba(0,192,239,1)',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgba(0,192,239,1)',
                tension: 0.3, // Replaces bezierCurveTension
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                }
            }
        }
    });
  });
</script>
@endsection
