<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') · Chikomo Care</title>
    
    <!-- Tailwind CSS Play CDN (No Node.js or Vite required) -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Standard Static Assets (Place your files in public/css and public/js) -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body class="care-app" data-restore-url="{{ route('care.session.restore') }}">
    <div class="care-shell">
        <aside id="care-sidebar" class="care-sidebar" aria-label="Main navigation">
            <div class="identity">
                <div class="avatar">{{ mb_substr($conversation->alias, 0, 1) }}</div>
                <div><small>Anonymous Identity</small><strong>{{ $conversation->alias }}</strong></div>
                <button type="button" class="mobile-menu" data-menu-close aria-label="Close navigation">×</button>
            </div>
            <nav>
                @foreach (['care.chat' => 'Chikomo AI Guidance', 'care.counselor' => 'Live Counselor Desk', 'care.modules' => 'Stress Modules', 'care.stories' => 'Peer Stories Hub'] as $route => $label)
                    <a href="{{ route($route) }}" @class(['nav-link', 'active' => request()->routeIs($route)]) @if(request()->routeIs($route)) aria-current="page" @endif>{{ $label }}</a>
                @endforeach
            </nav>
            <div class="conversation-list">
                @if(request()->routeIs('care.chat'))
                    <h2>AI Conversations</h2>
                    @foreach($conversations as $chat)
                        <form method="POST" action="{{ route('care.conversations.select', $chat) }}">
                            @csrf
                            <button class="history-link" type="submit">{{ $chat->alias }}</button>
                        </form>
                    @endforeach
                @endif
            </div>
            <footer>Bindura University <span>Chikomo Care</span></footer>
        </aside>
        <button type="button" class="menu-backdrop" data-menu-close aria-label="Close navigation" hidden></button>
        <main class="care-main">
            <header class="page-header">
                <button type="button" class="mobile-menu" data-menu-open aria-controls="care-sidebar" aria-expanded="false" aria-label="Open navigation">☰</button>
                <h1>@yield('title')</h1>
            </header>
            @if(session('success'))<p class="notice" role="status">{{ session('success') }}</p>@endif
            @if($errors->any())
                <div class="notice error" role="alert"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
            @endif
            <p class="notice error" id="connection-error" role="alert" hidden></p>
            @yield('content')
        </main>
    </div>
</body>
</html>