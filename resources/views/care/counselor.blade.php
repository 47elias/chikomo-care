@extends('care.layout')
@section('title', 'Campus Counselor Hotline')
@section('content')
    <div class="counselor-panel" data-counselor-sync="{{ route('care.counselor.sync') }}">
        <div id="counselor-state" class="counselor-state">
            @include('care.partials.counselor-state')
        </div>
        <form method="POST" action="{{ route('care.counselor.send') }}" class="message-form" id="counselor-form" data-busy-form @if(!$conversation->is_human_request || $conversation->status !== 'active' || !$conversation->counselor_id) hidden @endif>
            @csrf
            <label class="sr-only" for="message">Your message to the counselor</label>
            <input id="message" name="message" value="{{ old('message') }}" placeholder="Type a response to the active counselor..." required maxlength="2000" autocomplete="off">
            <button type="submit">Send</button>
        </form>
    </div>
@endsection
