<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Services\GroqService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    protected GroqService $groq;

    public function __construct(GroqService $groq)
    {
        $this->groq = $groq;
    }

    /**
     * Display a listing of conversations (for the Sidebar).
     * Filtered so a specific user alias only sees their own sessions.
     */
    public function index(Request $request)
    {
        // 1. Get the token from the custom header or query string
        $currentToken = $request->header('X-Chikomo-Token') ?? $request->query('token');

        // If no token is provided, the user is likely brand new with no history yet.
        if (!$currentToken) {
            return response()->json([]);
        }

        // 2. Find the alias associated with this specific token
        $currentSession = Conversation::where('token', $currentToken)->first();

        // If the token doesn't exist in the DB, return empty list
        if (!$currentSession) {
            return response()->json([]);
        }

        // 3. Fetch all conversations that share this alias
        $conversations = Conversation::withCount('messages')
            ->where('alias', $currentSession->alias)
            ->whereNotNull('alias') // Ensure we don't accidentally match multiple null aliases
            ->orderBy('updated_at', 'desc')
            ->get(['id', 'token', 'alias', 'updated_at']);

        return response()->json($conversations);
    }

    /**
     * Retrieve conversation history for a persistent session.
     */
    public function history(Request $request)
    {
        // Use manual check instead of validate to avoid 422 redirect loops in SPA
        $token = $request->input('token');

        if (!$token) {
            return response()->json(['messages' => [], 'alias' => 'New Guest']);
        }

        $conversation = Conversation::where('token', $token)->first();

        if (!$conversation) {
            return response()->json([
                'messages' => [],
                'alias' => 'New Guest'
            ]);
        }

        $messages = $conversation->messages()
            ->select('content', 'sender_type', 'created_at')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($msg) {
                return [
                    'content' => $msg->content,
                    'sender_type' => $msg->sender_type,
                    'timestamp' => $msg->created_at->diffForHumans(),
                ];
            });

        return response()->json([
            'messages' => $messages,
            'alias' => $conversation->alias ?? 'Anonymous'
        ]);
    }

    /**
     * Store user message and generate AI response.
     */
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
            'token' => 'required|string',
        ]);

        $conversation = Conversation::where('token', $request->token)->first();

        if (!$conversation) {
            return response()->json([
                'error' => 'Conversation session expired.',
                'message' => 'Please refresh to start a new session.'
            ], 404);
        }

        return DB::transaction(function () use ($request, $conversation) {
            // 1. Save User Message
            Message::create([
                'conversation_id' => $conversation->id,
                'content' => $request->message,
                'sender_type' => 'user',
            ]);

            // 2. Prepare Context (Last 8 messages)
            $history = $conversation->messages()
                ->latest()
                ->take(8)
                ->get()
                ->reverse()
                ->map(function ($msg) {
                    return [
                        'content' => $msg->content,
                        'role' => $msg->sender_type === 'ai' ? 'assistant' : 'user'
                    ];
                })
                ->toArray();

            // 3. Request AI Response
            try {
                $aiContent = $this->groq->getResponse($request->message, $history);
            } catch (\Exception $e) {
                Log::error("Groq Service Failure: " . $e->getMessage());
                $aiContent = "I'm having a brief technical moment. Could you try sending that again?";
            }

            // 4. Save AI Message
            $aiMessage = Message::create([
                'conversation_id' => $conversation->id,
                'content' => $aiContent,
                'sender_type' => 'ai',
            ]);

            $conversation->touch();

            return response()->json([
                'content' => $aiMessage->content,
                'sender_type' => 'ai',
                'timestamp' => $aiMessage->created_at->diffForHumans(),
            ]);
        });
    }

    /**
     * Delete a conversation.
     */
    public function destroy($token)
    {
        $conversation = Conversation::where('token', $token)->first();
        if ($conversation) {
            $conversation->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['error' => 'Not found'], 404);
    }
}
