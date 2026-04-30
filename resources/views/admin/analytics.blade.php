@extends('layouts.master')
<title>Analytics - Chikomo Care</title>

@section('content-wrapper')
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Analytics Insights
            <small>Visualized Behavioral Data</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Analytics</li>
        </ol>
    </section>

    <section class="content">
        {{-- Top Row: Performance Indicators --}}
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-aqua">
                    <span class="info-box-icon"><i class="fa fa-line-chart"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Engagement Rate</span>
                        <span class="info-box-number">85%</span>
                        <div class="progress">
                            <div class="progress-bar" style="width: 85%"></div>
                        </div>
                        <span class="progress-description">15% increase from last week</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-green">
                    <span class="info-box-icon"><i class="fa fa-shield"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Safety Score</span>
                        <span class="info-box-number">100%</span>
                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                        <span class="progress-description">No critical flags detected</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-yellow">
                    <span class="info-box-icon"><i class="fa fa-clock-o"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Avg Response</span>
                        <span class="info-box-number">1.2m</span>
                        <div class="progress">
                            <div class="progress-bar" style="width: 70%"></div>
                        </div>
                        <span class="progress-description">AI processing time stability</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-red">
                    <span class="info-box-icon"><i class="fa fa-warning"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Active Risks</span>
                        <span class="info-box-number">0</span>
                        <div class="progress">
                            <div class="progress-bar" style="width: 0%"></div>
                        </div>
                        <span class="progress-description">High-risk cases requiring review</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Line Chart: Conversation Volume --}}
            <div class="col-md-7">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Conversation Volume (Last 7 Days)</h3>
                    </div>
                    <div class="box-body">
                        <div class="chart">
                            <canvas id="lineChart" style="height:250px"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pie Chart: Risk Distribution --}}
            <div class="col-md-5">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">Risk Level Distribution</h3>
                    </div>
                    <div class="box-body">
                        <canvas id="pieChart" style="height:250px"></canvas>
                    </div>
                    <div class="box-footer no-padding">
                        <ul class="nav nav-pills nav-stacked">
                            <li><a href="#">Low Risk <span class="pull-right text-green">100%</span></a></li>
                            <li><a href="#">Medium Risk <span class="pull-right text-yellow">0%</span></a></li>
                            <li><a href="#">High Risk <span class="pull-right text-red">0%</span></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

{{-- Chart.js Scripts --}}
<script src="{{ asset('bower_components/chart.js/Chart.js') }}"></script>
<script>
  $(function () {
    // --- PIE CHART (Risk Levels) ---
    var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
    var pieChart       = new Chart(pieChartCanvas)
    var PieData        = [
      { value: 6, color: '#00a65a', highlight: '#00a65a', label: 'Low Risk' }, // Data from dump
      { value: 0, color: '#f39c12', highlight: '#f39c12', label: 'Medium Risk' },
      { value: 0, color: '#f56954', highlight: '#f56954', label: 'High Risk' }
    ]
    var pieOptions     = { segmentShowStroke: true, segmentStrokeColor: '#fff', segmentStrokeWidth: 2, percentageInnerCutout: 50, animationSteps: 100, animationEasing: 'easeOutBounce', animateRotate: true, animateScale: false, responsive: true, maintainAspectRatio: true }
    pieChart.Doughnut(PieData, pieOptions)

    // --- LINE CHART (Engagement) ---
    var lineChartCanvas          = $('#lineChart').get(0).getContext('2d')
    var lineChart                = new Chart(lineChartCanvas)
    var lineChartData = {
      labels  : ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
      datasets: [
        {
          label               : 'Conversations',
          fillColor           : 'rgba(60,141,188,0.9)',
          strokeColor         : 'rgba(60,141,188,0.8)',
          pointColor          : '#3b8bba',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data                : [0, 0, 0, 6, 0, 0, 0] // Reflecting the bulk entry on April 23rd
        }
      ]
    }
    lineChart.Line(lineChartData, { responsive: true, maintainAspectRatio: true })
  })
</script>
@endsection
