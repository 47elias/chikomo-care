<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\CounselorLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CounselorPortalController extends Controller
{
    /**
     * Only conversations where the user actually requested a human
     * ('is_human_request' = true) should ever surface here.
     */
    private function humanOnly($query)
    {
        return $query->where('is_human_request', true);
    }

    public function index()
    {
        $counselorId = Auth::id();

        $activeChats = $this->humanOnly(Conversation::query())
            ->where('counselor_id', $counselorId)
            ->where('status', 'active')
            ->orderBy('updated_at', 'desc')
            ->get();

        $historicalLogs = CounselorLog::with('conversation')
            ->where('counselor_id', $counselorId)
            ->whereNotNull('session_ended_at')
            ->orderBy('session_ended_at', 'desc')
            ->limit(50)
            ->get();

        return view('admin.counselor.portal', compact('activeChats', 'historicalLogs'));
    }

    public function queueJson()
    {
        $incomingRequests = $this->humanOnly(Conversation::query())
            ->whereNull('counselor_id')
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();

        $formattedData = $incomingRequests->map(function ($req) {
            return [
                'id' => $req->id,
                'alias' => $req->alias ?? 'Anonymous Guest',
                'risk_level' => $req->risk_level ?? 'low',
                'formatted_time' => optional($req->created_at)->diffForHumans(),
            ];
        });

        return response()->json($formattedData);
    }

    /**
     * Accept a pending request. Locked/transactional so two counselors
     * clicking Accept on the same row at once can't both win it.
     */
    public function acceptRequest($id)
    {
        $counselorId = Auth::id();

        return DB::transaction(function () use ($id, $counselorId) {
            $conversation = $this->humanOnly(Conversation::query())
                ->where('id', $id)
                ->whereNull('counselor_id')
                ->where('status', 'pending')
                ->lockForUpdate()
                ->first();

            if (!$conversation) {
                // FIX: route name corrected to match web.php
                // (was 'counselor.portal', which doesn't exist and
                // would throw RouteNotFoundException on every race).
                return redirect()->route('counselor-portal.index')
                    ->with('error', 'That request has already been picked up by another counselor.');
            }

            $conversation->update([
                'counselor_id' => $counselorId,
                'status' => 'active',
            ]);

            CounselorLog::create([
                'conversation_id' => $conversation->id,
                'counselor_id' => $counselorId,
                'session_started_at' => now(),
            ]);

            return redirect()->route('counselor.chat', $conversation->id)
                ->with('success', 'Connected with ' . ($conversation->alias ?? 'Anonymous Guest') . '.');
        });
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
            ->latest('session_started_at')
            ->first();

        if ($log) {
            $log->update([
                'session_ended_at' => now(),
                'summary_notes' => $request->input('summary_notes', 'Session closed.'),
            ]);
        }

        // FIX: route name corrected to match web.php
        return redirect()->route('counselor-portal.index')->with('success', 'Session archived.');
    }
}
