@extends('layouts.app')

@section('title', 'Messages')

@section('content')
<div class="row g-4" style="height: calc(100vh - 160px); min-height: 500px;">
    <!-- Sidebar: Conversation list -->
    <div class="col-md-4 col-lg-3 h-100">
        <div class="card glass-card h-100 p-3 d-flex flex-column">
            <h5 class="fw-bold mb-3"><i class="bi bi-chat-left-dots-fill text-primary-color me-2"></i> Conversations</h5>
            
            <div class="list-group list-group-flush overflow-y-auto flex-grow-1" style="max-height: 100%;">
                @forelse($chats as $c)
                    @php
                        $otherUser = Auth::user()->isStudent() ? $c->tutor->user : $c->student->user;
                        $isActive = $activeChat && $activeChat->id === $c->id;
                    @endphp
                    <a href="{{ route('chat.index', ['chat_id' => $c->id]) }}" class="list-group-item list-group-item-action border-0 rounded-3 mb-2 p-3 d-flex align-items-center gap-3 {{ $isActive ? 'bg-primary-color bg-opacity-10 active' : 'bg-body-tertiary bg-opacity-40' }}">
                        @if($otherUser->profile_picture)
                            <img src="{{ asset('storage/' . $otherUser->profile_picture) }}" class="avatar-circle" alt="{{ $otherUser->name }}" style="width: 40px; height: 40px;">
                        @else
                            <div class="avatar-circle" style="width: 40px; height: 40px;">
                                {{ substr($otherUser->name, 0, 1) }}
                            </div>
                        @endif
                        
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="fw-bold mb-0 text-sm text-reset">{{ $otherUser->name }}</h6>
                                @if($c->latestMessage)
                                    <small class="text-muted text-xs">{{ $c->latestMessage->created_at->format('H:i') }}</small>
                                @endif
                            </div>
                            <small class="text-muted text-xs text-truncate d-block mt-0.5">
                                @if($c->latestMessage)
                                    {{ $c->latestMessage->message }}
                                @else
                                    No messages yet.
                                @endif
                            </small>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-chat-square-text fs-2 d-block mb-2"></i>
                        <small>No active chats found. Browse tutors to message them.</small>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Main Panel: Message Exchange log -->
    <div class="col-md-8 col-lg-9 h-100">
        @if($activeChat)
            @php
                $partner = Auth::user()->isStudent() ? $activeChat->tutor->user : $activeChat->student->user;
            @endphp
            <div class="card glass-card h-100 d-flex flex-column">
                <!-- Chat Header -->
                <div class="card-header border-bottom bg-transparent p-3 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        @if($partner->profile_picture)
                            <img src="{{ asset('storage/' . $partner->profile_picture) }}" class="avatar-circle" alt="{{ $partner->name }}">
                        @else
                            <div class="avatar-circle">
                                {{ substr($partner->name, 0, 1) }}
                            </div>
                        @endif
                        <div>
                            <h6 class="fw-bold mb-0">{{ $partner->name }}</h6>
                            <small class="text-muted text-xs text-capitalize">{{ $partner->role }} Profile &bull; Online</small>
                        </div>
                    </div>
                    
                    @if(Auth::user()->isStudent())
                        <a href="{{ route('tutors.show', $activeChat->tutor_id) }}" class="btn btn-sm btn-outline-primary fw-semibold text-xs">View Bio</a>
                    @endif
                </div>

                <!-- Messages area -->
                <div class="card-body overflow-y-auto p-4 flex-grow-1" id="messages-box">
                    <div id="messages-list">
                        @include('chat.partials.message_list')
                    </div>
                </div>

                <!-- Footer: Input composer -->
                <div class="card-footer border-top bg-transparent p-3">
                    <form action="{{ route('chat.send', $activeChat->id) }}" method="POST" id="sendMessageForm">
                        @csrf
                        <div class="input-group">
                            <input type="text" name="message" id="message-text" class="form-control rounded-pill-start border-primary border-opacity-20 py-2.5 px-3" required autocomplete="off" placeholder="Write your message here...">
                            <button type="submit" class="btn btn-primary rounded-pill-end px-4 fw-semibold"><i class="bi bi-send-fill"></i> Send</button>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <div class="card glass-card h-100 d-flex align-items-center justify-content-center p-5 text-center text-muted">
                <div>
                    <i class="bi bi-chat-dots text-primary-color bg-opacity-10 display-1 mb-3"></i>
                    <h4 class="fw-bold">Your Messenger</h4>
                    <p class="mx-auto" style="max-width: 400px;">Select a contact from the sidebar list to exchange questions, resource links, and coordinate schedules with your tutors.</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
@if($activeChat)
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const messagesBox = document.getElementById('messages-box');
        const form = document.getElementById('sendMessageForm');
        const messageInput = document.getElementById('message-text');
        const messagesList = document.getElementById('messages-list');

        // Scroll to bottom on load
        function scrollToBottom() {
            messagesBox.scrollTop = messagesBox.scrollHeight;
        }
        scrollToBottom();

        // Submit via AJAX
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const text = messageInput.value.trim();
            if (!text) return;

            const formData = new FormData(form);

            fetch(`{{ route('chat.send', $activeChat->id) }}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                // If it's a list template, it appends, or we reload.
                // For simplicity, fetchMessages runs and refreshes, but we can append instantly
                messageInput.value = '';
                refreshMessages();
            })
            .catch(error => console.error('Error sending message:', error));
        });

        // Polling loop: load messages
        function refreshMessages() {
            fetch(`{{ route('chat.fetch', $activeChat->id) }}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                const wasAtBottom = (messagesBox.scrollHeight - messagesBox.clientHeight - messagesBox.scrollTop < 50);
                messagesList.innerHTML = html;
                if (wasAtBottom) {
                    scrollToBottom();
                }
            });
        }

        // Poll every 3 seconds
        setInterval(refreshMessages, 3000);
    });
</script>
@endif
@endsection
