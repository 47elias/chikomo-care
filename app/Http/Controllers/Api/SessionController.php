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

        // Only try to find if the token isn't empty/null
        if (!empty($token)) {
            $conversation = Conversation::where('token', $token)->first();
        }

        // If no token provided OR token doesn't exist in DB, create a new one
        if (!$conversation) {
            $conversation = Conversation::create([
                'token' => Str::random(40),
                'alias' => $this->generateAlias()
            ]);
        }

        return response()->json([
            'token' => $conversation->token,
            'alias' => $conversation->alias,
        ]);
    }

    private function generateAlias(): string
    {
        $adj = ['Steady', 'Resilient', 'Quiet', 'Brave', 'Bright', 'Kind', 'Calm'];
        $noun = ['Mountain', 'River', 'Shield', 'Path', 'Star', 'Guardian', 'Anchor'];
        return $adj[array_rand($adj)] . ' ' . $noun[array_rand($noun)];
    }
}
