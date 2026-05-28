<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Display visual analytics driven by anonymous user data pools.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // 1. Calculate Core Anonymous User Analytics Metrics
        $totalAnonymousUsers = DB::table('conversations')->count();
        $flaggedCount = DB::table('conversations')->where('is_flagged', 1)->count();
        $highRiskCount = DB::table('conversations')->where('risk_level', 'high')->count();
        $mediumRiskCount = DB::table('conversations')->where('risk_level', 'medium')->count();
        $lowRiskCount = DB::table('conversations')->where(function($query) {
            $query->where('risk_level', 'low')
                  ->orWhereNull('risk_level');
        })->count();

        // Calculate dynamic percentage distribution for visual graph panels
        $lowRiskPercent = $totalAnonymousUsers > 0 ? round(($lowRiskCount / $totalAnonymousUsers) * 100) : 0;
        $mediumRiskPercent = $totalAnonymousUsers > 0 ? round(($mediumRiskCount / $totalAnonymousUsers) * 100) : 0;
        $highRiskPercent = $totalAnonymousUsers > 0 ? round(($highRiskCount / $totalAnonymousUsers) * 100) : 0;

        // 2. Generate Dynamic 7-Day Running Anonymous Volume Waveform
        $daysOfWeekLabels = [];
        $weeklyDataPoints = [];

        for ($i = 6; $i >= 0; $i--) {
            $targetDate = Carbon::today()->subDays($i);

            // Format labels to represent day strings (e.g., Monday, Tuesday)
            $daysOfWeekLabels[] = $targetDate->format('l');

            // Count anonymous interactions recorded inside this exact 24-hour calendar block
            $dayCount = DB::table('conversations')
                ->whereDate('created_at', $targetDate)
                ->count();

            $weeklyDataPoints[] = $dayCount;
        }

        return view('admin.analytics', compact(
            'totalAnonymousUsers',
            'flaggedCount',
            'highRiskCount',
            'mediumRiskCount',
            'lowRiskCount',
            'lowRiskPercent',
            'mediumRiskPercent',
            'highRiskPercent',
            'daysOfWeekLabels',
            'weeklyDataPoints'
        ));
    }
}
