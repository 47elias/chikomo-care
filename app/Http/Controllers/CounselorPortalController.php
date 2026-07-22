<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\CounselorLog;
use App\Models\Message;
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

    /**
     * NEW: Persist a counselor's outgoing reply.
     * This is a plain save — it does NOT call any AI/model logic,
     * which is exactly what was missing (the endpoint didn't exist,
     * so the Blade JS's POST to /send was 404ing).
     */
    public function sendMessage(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        $conversation = $this->humanOnly(Conversation::query())
            ->where('id', $id)
            ->where('counselor_id', Auth::id())
            ->where('status', 'active')
            ->firstOrFail();

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'content' => $request->input('content'),
            'sender_type' => 'moderator', // counselor reply; enum has no 'counselor' value yet
        ]);

        $conversation->touch();

        return response()->json([
            'id' => $message->id,
            'time' => $message->created_at->format('H:i'),
        ]);
    }

    /**
     * NEW: Poll for new client messages since last_id.
     * Only returns 'user' messages so the counselor UI doesn't
     * echo its own just-sent bubble back a second time.
     */
    public function syncMessages(Request $request, $id)
    {
        $conversation = $this->humanOnly(Conversation::query())
            ->where('id', $id)
            ->where('counselor_id', Auth::id())
            ->firstOrFail();

        $lastId = (int) $request->query('last_id', 0);

        $newMessages = $conversation->messages()
            ->where('id', '>', $lastId)
            ->where('sender_type', 'user')
            ->orderBy('id')
            ->get()
            ->map(function ($m) use ($conversation) {
                return [
                    'id' => $m->id,
                    'content' => $m->content,
                    'sender_type' => $m->sender_type,
                    'alias' => $conversation->alias ?? 'Anonymous Guest',
                    'time' => $m->created_at->format('H:i'),
                ];
            });

        return response()->json($newMessages);
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

        return redirect()->route('counselor-portal.index')->with('success', 'Session archived.');
    }
}
