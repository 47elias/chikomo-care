@if(!$conversation->is_human_request)
    <div class="center-state">
        <h2>Connect with a Real Person</h2>
        <p>Request a conversation with an on-campus counselor for personal guidance and support.</p>
        <form method="POST" action="{{ route('care.counselor.request') }}" data-busy-form>
            @csrf
            <input type="hidden" name="risk_level" value="low">
            <button type="submit">Establish Connection Request</button>
        </form>
    </div>
@elseif($conversation->status === 'pending' || $conversation->status === 'searching')
    <div class="center-state" role="status"><h2>Placing Request in Active Queue...</h2><p>A counselor will handle your chat shortly.</p></div>
@else
    <div class="messages" data-messages aria-label="Counselor messages">
        @include('care.partials.messages', ['messages' => $conversation->messages()->whereIn('sender_type', ['user', 'moderator'])->orderBy('id')->get()])
    </div>
    @if($conversation->status === 'completed')
        <p class="notice" role="status">This live guidance session has been concluded by the counselor.</p>
    @endif
@endif
