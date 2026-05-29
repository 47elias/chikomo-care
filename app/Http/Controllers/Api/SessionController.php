<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SessionController extends Controller
{
    public function initialize(Request $request)
    {
        $token = $request->header('X-Chikomo-Token');
        $conversation = null;

        if (!empty($token)) {
            $conversation = Conversation::where('token', $token)->first();
        }

        if (!$conversation) {
            $conversation = Conversation::create([
                'token' => Str::random(40),
                'alias' => $this->generateUniqueAlias(),
                'status' => 'pending',
                'risk_level' => 'low',
                'is_flagged' => 0
            ]);
        }

        return response()->json([
            'token' => $conversation->token,
            'alias' => $conversation->alias,
            'status' => $conversation->status,
            'counselor_id' => $conversation->counselor_id
        ]);
    }

    private function generateUniqueAlias(): string
    {
        $adj = ['Steady', 'Resilient', 'Quiet', 'Brave', 'Bright', 'Kind', 'Calm', 'Noble', 'Wise', 'Gentle'];
        $noun = ['Mountain', 'River', 'Shield', 'Path', 'Star', 'Guardian', 'Anchor', 'Forest', 'Beacon', 'Ocean'];

        do {
            $generatedAlias = $adj[array_rand($adj)] . ' ' . $noun[array_rand($noun)] . ' ' . rand(100, 999);
        } while (Conversation::where('alias', $generatedAlias)->exists());

        return $generatedAlias;
    }
}
