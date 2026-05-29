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
                        <div class="direct-chat-messages" style="height: 380px; padding: 15px; background: #f4f6f9;">
                            {{-- Interactive backend text payloads fall into this area --}}
                            <div class="direct-chat-msg">
                                <div class="direct-chat-info clearfix">
                                    <span class="direct-chat-name pull-left">{{ $conversation->alias ?? 'Anonymous Client' }}</span>
                                    <span class="direct-chat-timestamp pull-right">{{ now()->format('H:i') }}</span>
                                </div>
                                <img class="direct-chat-img" src="{{ asset('dist/img/user2-160x160.jpg') }}" alt="User Image">
                                <div class="direct-chat-text" style="background: #fff; color: #444; border: 1px solid #ddd;">
                                    Hello, I need someone to talk to about my current stress load.
                                </div>
                            </div>

                            <div class="direct-chat-msg right">
                                <div class="direct-chat-info clearfix">
                                    <span class="direct-chat-name pull-right">You (Counselor)</span>
                                    <span class="direct-chat-timestamp pull-left">{{ now()->format('H:i') }}</span>
                                </div>
                                <div class="direct-chat-text" style="background: #00a65a; border-color: #00a65a;">
                                    Welcome. I am here to help you safely navigate this situation. Please share what is on your mind.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <form action="#" method="post">
                            <div class="input-group">
                                <input type="text" name="message" placeholder="Type response securely..." class="form-control" style="border-radius: 0;">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-success btn-flat" style="font-weight: 600;">Send Message</button>
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
                    <form action="{{ route('counselor.close', $conversation->id) }}" method="POST">
                        @csrf
                        <div class="box-body">
                            <div class="form-group">
                                <label for="summary_notes">Case Review / Summary Notes</label>
                                <textarea name="summary_notes" id="summary_notes" class="form-control" rows="8" placeholder="Enter session details, diagnostic tracking observations, or closure summaries for the log database records..." required></textarea>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-danger btn-block" style="font-weight: 600;" onsubmit="return confirm('Confirm total closure of this chat timeline module workspace?');">
                                <i class="fa fa-power-off"></i> Archive & Close Connection
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
