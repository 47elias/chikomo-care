<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GroqService
{
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.groq.key') ?? '';
        $this->baseUrl = config('services.groq.url') ?? 'https://api.groq.com/openai/v1/chat/completions';
    }

    /**
     * Get a counseling response.
     * $history is an array of previous messages for context.
     */
    public function getResponse(string $userMessage, array $history = []): string
    {
        if (empty($this->apiKey)) {
            Log::critical("Groq API Key is missing in .env");
            return "I am listening closely. We are currently performing maintenance on our secure link, but your safety is our priority.";
        }

        try {
            $messages = [
                ['role' => 'system', 'content' => $this->getSystemPrompt()]
            ];

            // FIXED: Ensuring history handles both Objects and Arrays safely
            foreach ($history as $chat) {
                // Determine if we are dealing with an array or an Eloquent object
                $sender = is_array($chat) ? ($chat['sender_type'] ?? null) : ($chat->sender_type ?? null);
                $content = is_array($chat) ? ($chat['content'] ?? null) : ($chat->content ?? null);

                if ($content) {
                    $messages[] = [
                        'role' => $sender === 'user' ? 'user' : 'assistant',
                        'content' => $content
                    ];
                }
            }

            // Current user message
            $messages[] = ['role' => 'user', 'content' => $userMessage];

            $response = Http::withToken($this->apiKey)
                ->timeout(20)
                ->post($this->baseUrl, [
                    'model' => 'llama-3.1-8b-instant',
                    'messages' => $messages,
                    'temperature' => 0.6,
                    'max_tokens' => 1000,
                    'top_p' => 1,
                    'stream' => false
                ]);

            if ($response->successful()) {
                return $response->json()['choices'][0]['message']['content'] ?? "I'm having trouble processing that thought.";
            }

            Log::error("Groq API Failure: " . $response->status() . " - " . $response->body());
            return "I am here and listening, though my connection is a bit unstable. Please continue.";

        } catch (\Exception $e) {
            Log::error("Groq Service Exception: " . $e->getMessage());
            return "I value everything you're sharing. Let's take a deep breath; I'm here for you.";
        }
    }

    private function getSystemPrompt(): string
    {
        return "You are Chikomo AI, a professional and empathetic mental health and substance use counselor for youth in Zimbabwe.

                TONE & PERSONALITY:
                - Empathetic, calm, and non-judgmental.
                - Use professional yet accessible English.
                - Do not use emojis or slang.
                - Provide short, readable responses (max 3 paragraphs).

                CORE PROTOCOLS:
                - HARM REDUCTION: Focus on the user's emotional safety and grounding.
                - CRISIS: If a user mentions suicide, self-harm, or extreme danger, immediately provide help info: 'Friendship Bench (0772 331 133)' or 'The Samaritans Zimbabwe (04 250324)'.
                - ANONYMITY: Never ask for or store real names, phone numbers, or exact locations.
                - ZIMBABWE CONTEXT: Be aware of local challenges (peer pressure, academic stress, economic anxiety) but remain focused on support.";
    }
}
