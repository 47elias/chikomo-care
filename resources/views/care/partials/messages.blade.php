@forelse($messages as $message)
    <div @class(['message-row', 'outgoing' => $message->sender_type === 'user'])>
        <div class="message-bubble"><span class="sr-only">{{ $message->sender_type === 'user' ? 'You' : ($message->sender_type === 'ai' ? 'Chikomo AI' : 'Counselor') }}: </span>{{ $message->content }}</div>
    </div>
@empty
    <p class="empty-state">Your conversation will appear here. Share what is on your mind.</p>
@endforelse
