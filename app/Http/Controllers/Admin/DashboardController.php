<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Stats for small boxes
        $totalSessions = Conversation::count();
        $totalMessages = Message::count();
        $activeToday = Conversation::whereDate('updated_at', Carbon::today())->count();

        // Chart Data: Conversations in the last 7 days
        $days = [];
        $counts = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $days[] = $date->format('D');
            $counts[] = Conversation::whereDate('created_at', $date)->count();
        }

        return view('admin.dashboard', compact(
            'totalSessions',
            'totalMessages',
            'activeToday',
            'days',
            'counts'
        ));
    }
}
