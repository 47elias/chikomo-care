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
            'is_approved' => 1, // Set to 1 for immediate testing, can change to 0 for admin moderation
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json(['message' => 'Story shared successfully!'], 201);
    }

    // Request Human Counselor Chat Room
    public function requestCounselor(Request $request)
    {
        $token = $request->header('X-Chikomo-Token');
        $conversation = Conversation::where('token', $token)->first();

        if (!$conversation) {
            return response()->json(['error' => 'Session not found'], 404);
        }

        // Change status to requested or searching
        $conversation->update(['status' => 'searching']);

        return response()->json([
            'status' => 'searching',
            'alias' => $conversation->alias
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
            ->whereIn('sender_type', ['user', 'moderator'])
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }
}
