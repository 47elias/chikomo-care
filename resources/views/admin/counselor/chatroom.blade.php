@extends('layouts.master')

@section('content-wrapper')
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            {{-- Chat Room Workspace Window Frame --}}
            <div class="col-md-8">
                <div class="box box-success direct-chat direct-chat-success chat-box-modern">
                    <div class="box-header with-border chat-header">
                        <h3 class="box-title">Secure Console: {{ $conversation->alias ?? 'Anonymous Client' }}</h3>
                        <span class="live-indicator"><i class="fa fa-circle"></i> Live</span>
                    </div>
                    <div class="box-body">
                        <div class="direct-chat-messages" id="chat-box-container">
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
                                            <div class="direct-chat-text client-bubble">
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
                                            <div class="direct-chat-text counselor-bubble">
                                                {{ $msg->content }}
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <div class="text-center empty-chat-state" id="no-messages-prompt">
                                    <i class="fa fa-comments-o fa-3x"></i>
                                    <p>Connection secured. No message logs recorded yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="box-footer chat-footer">
                        <form id="secure-message-form">
                            @csrf
                            <div class="input-group">
                                <input type="text" id="message-input-field" name="message" placeholder="Type response securely..." class="form-control chat-input" autocomplete="off" required>
                                <span class="input-group-btn">
                                    <button type="submit" id="send-btn" class="btn btn-success btn-flat chat-send-btn">
                                        <i class="fa fa-paper-plane"></i> Send
                                    </button>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Right Side Column Panel: Session Termination Closure & Case Form Parameters --}}
            <div class="col-md-4">
                <div class="box box-danger archive-panel">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-lock text-danger"></i> Session Archive Control</h3>
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
                            <button type="submit" class="btn btn-danger btn-block archive-btn">
                                <i class="fa fa-power-off"></i> Archive & Close Connection
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    .chat-box-modern {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
    }

    .chat-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 18px;
    }

    .chat-header .box-title {
        font-weight: 600;
    }

    .live-indicator {
        font-size: 11px;
        font-weight: 700;
        color: #00a65a;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .live-indicator i {
        font-size: 8px;
        margin-right: 4px;
        animation: pulseLive 1.5s ease-in-out infinite;
    }

    @keyframes pulseLive {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.3; }
    }

    .direct-chat-messages {
        height: 400px;
        padding: 18px;
        background: #f4f6f9;
        overflow-y: auto;
    }

    .client-bubble {
        background: #fff !important;
        color: #334155 !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 10px !important;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
    }

    .counselor-bubble {
        background: #00a65a !important;
        border-color: #00a65a !important;
        border-radius: 10px !important;
        box-shadow: 0 1px 4px rgba(0, 166, 90, 0.2);
    }

    .empty-chat-state {
        padding-top: 100px;
        color: #94a3b8;
    }

    .empty-chat-state p {
        margin-top: 10px;
    }

    .chat-footer {
        padding: 14px 18px;
        background: #fff;
        border-top: 1px solid #eef1f4;
    }

    .chat-input {
        border-radius: 8px 0 0 8px !important;
        border: 1px solid #e2e8f0;
        transition: border-color 0.15s ease;
    }

    .chat-input:focus {
        border-color: #00a65a;
        box-shadow: none;
    }

    .chat-send-btn {
        border-radius: 0 8px 8px 0 !important;
        font-weight: 600;
        padding: 6px 18px;
    }

    .chat-send-btn i {
        margin-right: 4px;
    }

    /* Archive panel */
    .archive-panel {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
    }

    .archive-panel .box-title {
        font-weight: 600;
    }

    .archive-btn {
        font-weight: 600;
        border-radius: 6px;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .archive-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }
</style>

{{-- Real-Time Chat Polling Sync Engine --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        var chatBox = $('#chat-box-container');
        var conversationId = "{{ $conversation->id }}";
        var lastMessageId = "{{ $conversation->messages->last()->id ?? 0 }}";

        function scrollToBottom() {
            chatBox.scrollTop(chatBox[0].scrollHeight);
        }
        scrollToBottom();

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

                    var cslrHtml = `
                        <div class="direct-chat-msg right">
                            <div class="direct-chat-info clearfix">
                                <span class="direct-chat-name pull-right">You (Counselor)</span>
                                <span class="direct-chat-timestamp pull-left">${response.time ?? 'Just now'}</span>
                            </div>
                            <div class="direct-chat-text counselor-bubble">
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
                                        <div class="direct-chat-text client-bubble">
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

        setInterval(pollIncomingMessages, 2500);

        function escapeHtml(text) {
            return text.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
        }
    });
</script>
@endsection
