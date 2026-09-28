@extends('care.layout')
@section('title', 'Shared Journeys & Anecdotes')
@section('content')
    <div class="page-content">
        <p class="intro">Read accounts posted anonymously by student peers, or share your own thoughts without revealing your identity.</p>
        <form method="POST" action="{{ route('care.stories.post') }}" class="card story-form" data-busy-form>
            @csrf
            <h2>Express Your Experience Anonymously</h2>
            <label for="story-title">Story title</label>
            <input id="story-title" name="title" value="{{ old('title') }}" placeholder="Give your story a clear theme title..." required maxlength="255">
            <label for="story-content">Your experience</label>
            <textarea id="story-content" name="content" rows="4" placeholder="Share your experience. Avoid personal names, addresses, or other identifying details." required>{{ old('content') }}</textarea>
            <button type="submit">Post Story to Forum</button>
        </form>
        <h2 class="section-title">Community Submissions</h2>
        @forelse($stories as $story)
            <article class="card story"><small>By: {{ $story->author_alias }}</small><h2>{{ $story->title }}</h2><p>{{ $story->content }}</p></article>
        @empty
            <p class="empty-state">No anonymous peer accounts posted yet.</p>
        @endforelse
    </div>
@endsection
