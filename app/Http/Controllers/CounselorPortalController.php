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
     * Scope the query to only return human-requested conversations.
     * We use 'is_flagged' as the discriminator.
     */
    private function humanOnly($query)
    {
        return $query->where('is_flagged', true);
    }

    public function index()
    {
        $counselorId = Auth::id();

        // 1. Pending human requests only
        $incomingRequests = $this->humanOnly(Conversation::query())
            ->whereNull('counselor_id')
            ->whereIn('status', ['pending', 'searching'])
            ->orderBy('created_at', 'asc')
            ->get();

        // 2. Active human chats
        $activeChats = $this->humanOnly(Conversation::query())
            ->where('counselor_id', $counselorId)
            ->where('status', 'active')
            ->orderBy('updated_at', 'desc')
            ->get();

        // 3. Historical logs
        $historicalLogs = CounselorLog::with('conversation')
            ->where('counselor_id', $counselorId)
            ->orderBy('session_ended_at', 'desc')
            ->get();

        return view('admin.counselor.portal', compact('incomingRequests', 'activeChats', 'historicalLogs'));
    }

    public function queueJson()
    {
        $incomingRequests = $this->humanOnly(Conversation::query())
            ->whereNull('counselor_id')
            ->whereIn('status', ['pending', 'searching'])
            ->orderBy('created_at', 'asc')
            ->get();

        $formattedData = $incomingRequests->map(function ($req) {
            return [
                'id' => $req->id,
                'alias' => $req->alias ?? 'Anonymous Guest',
                'risk_level' => $req->risk_level ?? 'low',
                'formatted_time' => $req->created_at ? Carbon::parse($req->created_at)->format('H:i:s (d M)') : now()->format('H:i:s (d M)')
            ];
        });

        return response()->json($formattedData);
    }

    public function acceptRequest($id)
    {
        $conversation = $this->humanOnly(Conversation::query())
            ->where('id', $id)
            ->whereNull('counselor_id')
            ->whereIn('status', ['pending', 'searching'])
            ->firstOrFail();

        $conversation->update([
            'counselor_id' => Auth::id(),
            'status' => 'active'
        ]);

        CounselorLog::create([
            'conversation_id' => $conversation->id,
            'counselor_id' => Auth::id(),
            'session_started_at' => now(),
        ]);

        return redirect()->route('counselor.chat', $conversation->id)
            ->with('success', 'Connection established.');
    }

    public function liveChatRoom($id)
    {
        $conversation = $this->humanOnly(Conversation::query())
            ->with('messages')
            ->where('id', $id)
            ->where('counselor_id', Auth::id())
            ->where('status', 'active')
            ->firstOrFail();

        return view('admin.counselor.chatroom', compact('conversation'));
    }

    public function closeSession(Request $request, $id)
    {
        $conversation = $this->humanOnly(Conversation::query())
            ->where('id', $id)
            ->where('counselor_id', Auth::id())
            ->where('status', 'active')
            ->firstOrFail();

        $conversation->update(['status' => 'completed']);

        $log = CounselorLog::where('conversation_id', $id)
            ->where('counselor_id', Auth::id())
            ->whereNull('session_ended_at')
            ->first();

        if ($log) {
            $log->update([
                'session_ended_at' => now(),
                'summary_notes' => $request->input('summary_notes', 'Session closed.')
            ]);
        }

        return redirect()->route('counselor-portal.index')->with('success', 'Session archived.');
    }
}
