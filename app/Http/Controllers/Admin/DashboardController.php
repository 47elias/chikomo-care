<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    /**
     * Aggregate dynamic system metrics for the administrative overview interface.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // 1. Core metric aggregation with structural table validation checks
        $totalConversations = Schema::hasTable('conversations') ? DB::table('conversations')->count() : 0;
        $flaggedCount = Schema::hasTable('conversations') ? DB::table('conversations')->where('is_flagged', 1)->count() : 0;
        $anonymousUsersCount = Schema::hasTable('anyms-users') ? DB::table('anyms-users')->count() : 0;

        // Calculate low-risk interaction balance values
        $lowRiskCount = Schema::hasTable('conversations') ? DB::table('conversations')->where(function($query) {
            $query->where('risk_level', 'low')
                  ->orWhereNull('risk_level');
        })->count() : 0;

        $lowRiskPercent = $totalConversations > 0 ? round(($lowRiskCount / $totalConversations) * 100) : 100;

        // 2. Fetch recent interaction stream vectors for database overview
        $recentConversations = Schema::hasTable('conversations')
            ? DB::table('conversations')
                ->select('id', 'alias', 'risk_level', 'created_at', 'is_flagged')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get()
            : collect([]);

        // 3. Collect underlying architectural framework metrics
        $phpVersion = PHP_VERSION;

        try {
            $dbVersionResult = DB::select("SELECT VERSION() as version");
            $dbVersion = !empty($dbVersionResult) ? $dbVersionResult[0]->version : 'MariaDB 10.4.32';
        } catch (\Exception $e) {
            $dbVersion = 'Database Connection Offline';
        }

        $failedJobsCount = Schema::hasTable('failed_jobs') ? DB::table('failed_jobs')->count() : 0;

        // 4. Return view layout injecting fully processed variable arrays
        return view('admin.dashboard', compact(
            'totalConversations',
            'flaggedCount',
            'anonymousUsersCount',
            'lowRiskPercent',
            'recentConversations',
            'phpVersion',
            'dbVersion',
            'failedJobsCount'
        ));
    }
}
