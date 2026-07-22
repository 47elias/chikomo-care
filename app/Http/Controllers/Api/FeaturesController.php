<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeaturesController extends Controller
{
    // Fetch Admin Uploaded Stress Modules
    public function getStressModules()
    {
        $modules = DB::table('stress_modules')->get();
        return response()->json($modules);
    }

    // Fetch Approved Peer Stories
    public function getPeerStories()
    {
        $stories = DB::table('peer_stories')
            ->where('is_approved', 1)
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($stories);
    }

    // Post an Anonymous Story
    public function postPeerStory(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $token = $request->header('X-Chikomo-Token');
        $conversation = Conversation::where('token', $token)->first();
        $alias = $conversation ? $conversation->alias : 'Anonymous Peer';

        DB::table('peer_stories')->insert([
            'author_alias' => $alias,
            'title' => $request->title,
            'content' => $request->content,
            // Requires admin moderation before appearing publicly.
            // Build/confirm an approval queue before relying on this default.
            'is_approved' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json(['message' => 'Story submitted for review!'], 201);
    }

    // Request Human Counselor Chat Room
    public function requestCounselor(Request $request)
    {
 \Illuminate\Support\Facades\Log::info('requestCounselor HIT', [
        'risk_level_input' => $request->input('risk_level'),
        'token' => $request->header('X-Chikomo-Token'),
    ]);

    $request->validate([
        'risk_level' => 'sometimes|in:low,medium,high',
    ]);



        $request->validate([
            'risk_level' => 'sometimes|in:low,medium,high',
        ]);

        $token = $request->header('X-Chikomo-Token');
        $conversation = Conversation::where('token', $token)->first();

        if (!$conversation) {
            return response()->json(['error' => 'Session not found'], 404);
        }

        $riskLevel = $request->input('risk_level', $conversation->risk_level);

        // Change status to pending to hit the Counselor Portal Queue,
        // persist the risk level the frontend actually sent instead of
        // silently keeping whatever the conversation already had, and
        // flag this row as a genuine human-handoff request — this is
        // the field CounselorPortalController's queue actually filters on.
        $conversation->update([
            'status' => 'pending',
            'risk_level' => $riskLevel,
            'is_human_request' => true,
            'is_flagged' => $riskLevel === 'high' ? true : $conversation->is_flagged,
        ]);

        return response()->json([
            'success' => true,
            'status' => 'pending',
            'alias' => $conversation->alias,
            'conversation' => $conversation,
        ]);
    }

    // Long poll/check for active counselor assignment
    public function checkCounselorStatus(Request $request)
    {
        $token = $request->header('X-Chikomo-Token');
        $conversation = Conversation::where('token', $token)->first();

        if (!$conversation) {
            return response()->json(['error' => 'Session not found'], 404);
        }

        return response()->json([
            'status' => $conversation->status,
            'counselor_id' => $conversation->counselor_id
        ]);
    }

    // Fetch Counselor Chat History
    public function getCounselorHistory(Request $request)
    {
        $token = $request->header('X-Chikomo-Token');
        $conversation = Conversation::where('token', $token)->first();

        if (!$conversation) {
            return response()->json([]);
        }

        $messages = DB::table('messages')
            ->where('conversation_id', $conversation->id)
            // 'counselor' removed: the `messages.sender_type` column is
            // enum('user','ai','moderator') — 'counselor' can never match
            // and any insert attempting it would fail the enum constraint.
            // If counselor replies need their own category, migrate the
            // enum to add 'counselor' rather than referencing it here.
            ->whereIn('sender_type', ['user', 'moderator'])
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'conversation_id' => $msg->conversation_id,
                    'sender_type' => $msg->sender_type,
                    'content' => $msg->content,
                    'time' => \Carbon\Carbon::parse($msg->created_at)->format('H:i'),
                    'created_at' => $msg->created_at
                ];
            });

        return response()->json($messages);
    }
}
