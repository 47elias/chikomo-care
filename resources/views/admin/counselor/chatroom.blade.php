@extends('layouts.master')
<title>Workspace Console - Chikomo Care</title>

@section('content-wrapper')
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            {{-- Chat Room Workspace Window Frame --}}
            <div class="col-md-8">
                <div class="box box-success direct-chat direct-chat-success shadow-sm" style="border-radius: 4px;">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="font-weight: 600;">Secure Console: {{ $conversation->alias ?? 'Anonymous Client' }}</h3>
                    </div>
                    <div class="box-body">
                        <div class="direct-chat-messages" id="chat-box-container" style="height: 380px; padding: 15px; background: #f4f6f9; overflow-y: auto;">
                            {{-- Interactive backend text payloads fall into this area --}}
                            @if($conversation->messages && count($conversation->messages) > 0)
                                @foreach($conversation->messages as $msg)
                                    @if($msg->sender_type === 'user')
                                        {{-- Client Message --}}
                                        <div class="direct-chat-msg">
                                            <div class="direct-chat-info clearfix">
                                                <span class="direct-chat-name pull-left">{{ $conversation->alias ?? 'Anonymous Client' }}</span>
                                                <span class="direct-chat-timestamp pull-right">{{ \Carbon\Carbon::parse($msg->created_at)->format('H:i') }}</span>
                                            </div>
                                            <img class="direct-chat-img" src="{{ asset('dist/img/user2-160x160.jpg') }}" alt="User Image">
                                            <div class="direct-chat-text" style="background: #fff; color: #444; border: 1px solid #ddd;">
                                                {{ $msg->content }}
                                            </div>
                                        </div>
                                    @else
                                        {{-- Counselor Message --}}
                                        <div class="direct-chat-msg right">
                                            <div class="direct-chat-info clearfix">
                                                <span class="direct-chat-name pull-right">You (Counselor)</span>
                                                <span class="direct-chat-timestamp pull-left">{{ \Carbon\Carbon::parse($msg->created_at)->format('H:i') }}</span>
                                            </div>
                                            <div class="direct-chat-text" style="background: #00a65a; border-color: #00a65a;">
                                                {{ $msg->content }}
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <div class="text-center text-muted" id="no-messages-prompt" style="padding-top: 100px;">
                                    <i class="fa fa-comments-o fa-3x"></i>
                                    <p style="margin-top: 10px;">Connection secured. No message logs recorded yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="box-footer">
                        <form id="secure-message-form">
                            @csrf
                            <div class="input-group">
                                <input type="text" id="message-input-field" name="message" placeholder="Type response securely..." class="form-control" style="border-radius: 0;" autocomplete="off" required>
                                <span class="input-group-btn">
                                    <button type="submit" id="send-btn" class="btn btn-success btn-flat" style="font-weight: 600;">Send Message</button>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Right Side Column Panel: Session Termination Closure & Case Form Parameters --}}
            <div class="col-md-4">
                <div class="box box-danger shadow-sm" style="border-radius: 4px;">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="font-weight: 600;"><i class="fa fa-lock text-danger"></i> Session Archive Control</h3>
                    </div>
                    <form action="{{ route('counselor.close', $conversation->id) }}" method="POST" onsubmit="return confirm('Confirm total closure of this chat timeline module workspace?');">
                        @csrf
                        <div class="box-body">
                            <div class="form-group">
                                <label for="summary_notes">Case Review / Summary Notes</label>
                                <textarea name="summary_notes" id="summary_notes" class="form-control" rows="8" placeholder="Enter session details, diagnostic tracking observations, or closure summaries for the log database records..." required></textarea>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-danger btn-block" style="font-weight: 600;">
                                <i class="fa fa-power-off"></i> Archive & Close Connection
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

{{-- Real-Time Chat Polling Sync Engine --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        var chatBox = $('#chat-box-container');
        var conversationId = "{{ $conversation->id }}";
        var lastMessageId = "{{ $conversation->messages->last()->id ?? 0 }}";

        // Automatically scroll to the absolute bottom of the chat container
        function scrollToBottom() {
            chatBox.scrollTop(chatBox[0].scrollHeight);
        }
        scrollToBottom();

        {{-- Form Outgoing Message Post Dispatcher --}}
        $('#secure-message-form').on('submit', function(e) {
            e.preventDefault();
            var messageText = $('#message-input-field').val().trim();
            if (messageText === '') return;

            $('#send-btn').prop('disabled', true);

            $.ajax({
                url: "{{ url('/counselor-portal/chat') }}/" + conversationId + "/send",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    content: messageText
                },
                success: function(response) {
                    $('#message-input-field').val('');
                    $('#no-messages-prompt').remove();

                    // Render message directly to keep the front-end fluid
                    var cslrHtml = `
                        <div class="direct-chat-msg right">
                            <div class="direct-chat-info clearfix">
                                <span class="direct-chat-name pull-right">You (Counselor)</span>
                                <span class="direct-chat-timestamp pull-left">${response.time ?? 'Just now'}</span>
                            </div>
                            <div class="direct-chat-text" style="background: #00a65a; border-color: #00a65a;">
                                ${escapeHtml(messageText)}
                            </div>
                        </div>
                    `;
                    chatBox.append(cslrHtml);
                    scrollToBottom();
                    if(response.id) lastMessageId = response.id;
                },
                error: function() {
                    alert('Message delivery failed. Check your local Apache/MySQL connection status.');
                },
                complete: function() {
                    $('#send-btn').prop('disabled', false);
                }
            });
        });

        {{-- Background Polling Message Stream Reader --}}
        function pollIncomingMessages() {
            $.ajax({
                url: "{{ url('/counselor-portal/chat') }}/" + conversationId + "/messages/sync",
                type: "GET",
                data: { last_id: lastMessageId },
                dataType: "json",
                success: function(messages) {
                    if (messages.length > 0) {
                        $('#no-messages-prompt').remove();
                        $.each(messages, function(index, msg) {
                            if (msg.sender_type === 'user') {
                                var userHtml = `
                                    <div class="direct-chat-msg">
                                        <div class="direct-chat-info clearfix">
                                            <span class="direct-chat-name pull-left">${msg.alias}</span>
                                            <span class="direct-chat-timestamp pull-right">${msg.time}</span>
                                        </div>
                                        <img class="direct-chat-img" src="{{ asset('dist/img/user2-160x160.jpg') }}" alt="User Image">
                                        <div class="direct-chat-text" style="background: #fff; color: #444; border: 1px solid #ddd;">
                                            ${escapeHtml(msg.content)}
                                        </div>
                                    </div>
                                `;
                                chatBox.append(userHtml);
                            }
                            lastMessageId = msg.id;
                        });
                        scrollToBottom();
                    }
                }
            });
        }

        // Poll messages every 2.5 seconds
        setInterval(pollIncomingMessages, 2500);

        // Simple HTML entity escaper function for safety
        function escapeHtml(text) {
            return text.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
        }
    });
</script>
@endsection
