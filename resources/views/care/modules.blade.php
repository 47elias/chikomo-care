@extends('care.layout')
@section('title', 'Stress Reduction Repository')
@section('content')
    <div class="page-content">
        <p class="intro">Access guidelines and resource attachments uploaded by platform specialists.</p>
        <div class="module-grid">
            @forelse($modules as $module)
                <article class="card">
                    <div class="avatar">{{ mb_substr($module->title, 0, 1) }}</div>
                    <h2>{{ $module->title }}</h2>
                    <p>{{ $module->description }}</p>
                    <div class="resource-meta">
                        <span>Downloads: <strong>{{ $module->download_count }}</strong></span>
                        <form method="POST" action="{{ route('care.modules.download', $module) }}">
                            @csrf
                            <button class="text-button" type="submit">Download Resource →</button>
                        </form>
                    </div>
                    @php($feedback = $comments->get($module->id, collect()))
                    <details>
                        <summary>Feedback ({{ $feedback->count() }})</summary>
                        <div class="feedback-list">
                            @forelse($feedback as $comment)
                                <div class="feedback"><small>{{ $comment->author_alias }} · {{ $comment->created_at }}</small><p>{{ $comment->content }}</p></div>
                            @empty
                                <p>No comments submitted yet.</p>
                            @endforelse
                        </div>
                        <form method="POST" action="{{ route('care.modules.comment', $module) }}" class="feedback-form" data-busy-form>
                            @csrf
                            <label class="sr-only" for="comment-{{ $module->id }}">Feedback on {{ $module->title }}</label>
                            <input id="comment-{{ $module->id }}" name="comment" placeholder="Write response anonymously..." required maxlength="2000">
                            <button type="submit">Send</button>
                        </form>
                    </details>
                </article>
            @empty
                <p class="empty-state">No managed files uploaded yet. Check back later.</p>
            @endforelse
        </div>
    </div>
@endsection
