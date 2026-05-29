<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\CounselorLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CounselorPortalController extends Controller
{
    /**
     * Display pending requests created within the last 5 minutes
     * along with active sessions assigned to the counselor.
     */
    public function index()
    {
        $counselorId = Auth::id();

        // Calculate the time threshold boundary exactly 5 minutes ago from now
        $fiveMinutesAgo = Carbon::now()->subMinutes(5);

        // 1. Grab unassigned conversations waiting for help created within the last 5 minutes
        $incomingRequests = Conversation::where('status', 'pending')
            ->where('created_at', '>=', $fiveMinutesAgo)
            ->orderBy('created_at', 'asc')
            ->get();

        // 2. Active conversations claimed by the logged-in counselor
        $activeChats = Conversation::where('counselor_id', $counselorId)
            ->where('status', 'active')
            ->orderBy('updated_at', 'desc')
            ->get();

        // 3. Historically closed case logs for audit review
        $historicalLogs = CounselorLog::with('conversation')
            ->where('counselor_id', $counselorId)
            ->orderBy('session_ended_at', 'desc')
            ->get();

        return view('admin.counselor.portal', compact('incomingRequests', 'activeChats', 'historicalLogs'));
    }

    /**
     * Accept a pending anonymous connection request and spin up a session.
     */
    public function acceptRequest($id)
    {
        $conversation = Conversation::findOrFail($id);

        // Enforce the 5-minute safety expiry limit check on assignment attempts as well
        if ($conversation->created_at->lt(Carbon::now()->subMinutes(5)) && $conversation->status === 'pending') {
            return redirect()->back()->with('error', 'This conversation request has expired as it exceeded the 5-minute waiting window.');
        }

        if ($conversation->status !== 'pending') {
            return redirect()->back()->with('error', 'This request has already been claimed by another counselor.');
        }

        // Assign current counselor and update status to active
        $conversation->update([
            'counselor_id' => Auth::id(),
            'status' => 'active'
        ]);

        // Open structural trace log link record
        CounselorLog::create([
            'conversation_id' => $conversation->id,
            'counselor_id' => Auth::id(),
            'session_started_at' => now(),
        ]);

        return redirect()->route('counselor.chat', $conversation->id)
            ->with('success', 'Connection established. You are now consulting with ' . ($conversation->alias ?? 'Anonymous Guest'));
    }

    /**
     * Direct interface gateway proxy pass for handling standard real-time text exchange records.
     */
    public function liveChatRoom($id)
    {
        $conversation = Conversation::where('id', $id)
            ->where('counselor_id', Auth::id())
            ->where('status', 'active')
            ->firstOrFail();

        return view('admin.counselor.chatroom', compact('conversation'));
    }

    /**
     * Gracefully close a chat session and save operational session timeline indices.
     */
    public function closeSession(Request $request, $id)
    {
        $conversation = Conversation::findOrFail($id);

        $conversation->update(['status' => 'completed']);

        // Finalize historical record parameters
        $log = CounselorLog::where('conversation_id', $id)
            ->where('counselor_id', Auth::id())
            ->whereNull('session_ended_at')
            ->first();

        if ($log) {
            $log->update([
                'session_ended_at' => now(),
                'summary_notes' => $request->input('summary_notes', 'Session closed successfully.')
            ]);
        }

        return redirect()->route('counselor-portal.index')->with('success', 'Session archived successfully.');
    }
}
