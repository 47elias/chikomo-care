<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ClientChatRequestController extends Controller
{
    /**
     * Create a fresh live queue request from the React Client Application interface
     */
    public function store(Request $request)
    {
        // 1. Validate incoming React payload criteria parameters
        $request->validate([
            'risk_level' => 'sometimes|in:low,medium,high',
        ]);

        // 2. Generate a secure random anonymous client pseudonym alias pairing combination
        $prefixes = ['Brave', 'Resilient', 'Quiet', 'Bright', 'Calm', 'Steady', 'Kind'];
        $nouns = ['Mountain', 'Shield', 'River', 'Star', 'Guardian', 'Path', 'Anchor'];
        $generatedAlias = $prefixes[array_rand($prefixes)] . ' ' . $nouns[array_rand($nouns)];

        // 3. Persist the request row into the SAME table the counselor portal reads from.
        // (Previously wrote to HumanConversation, which CounselorPortalController never queries —
        // that's why requests never showed up in the counselor queue.)
        $conversation = Conversation::create([
            'token' => Str::random(40), // Unique tracking token identifier
            'alias' => $generatedAlias,
            'risk_level' => $request->input('risk_level', 'low'),
            'status' => 'pending', // Marks it as active and visible inside the counselor queue
            'counselor_id' => null, // Left empty until claimed by an online counselor
            'is_flagged' => $request->input('risk_level') === 'high' ? true : false,
            'is_human_request' => true, // REQUIRED: CounselorPortalController::humanOnly() filters on this
        ]);

        // 4. Return the data properties back to React to mount the client terminal screen window
        return response()->json([
            'success' => true,
            'message' => 'Your live helper matching queue session request has been registered.',
            'conversation' => [
                'id' => $conversation->id,
                'token' => $conversation->token,
                'alias' => $conversation->alias,
                'status' => $conversation->status,
                'risk_level' => $conversation->risk_level,
            ]
        ], 201);
    }
}
