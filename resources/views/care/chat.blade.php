@extends('care.layout')
@section('title', 'Chikomo Conversational AI')
@section('content')
    <div class="messages" data-messages aria-label="Conversation messages">
        @include('care.partials.messages')
    </div>
    <form method="POST" action="{{ route('care.chat.send') }}" class="message-form" data-busy-form>
        @csrf
        <label class="sr-only" for="message">Your message to Chikomo AI</label>
        <input id="message" name="message" value="{{ old('message') }}" placeholder="Type any thoughts anonymously..." required maxlength="2000" autocomplete="off">
        <button type="submit" data-busy-label="Thinking…">Send</button>
    </form>
@endsection
