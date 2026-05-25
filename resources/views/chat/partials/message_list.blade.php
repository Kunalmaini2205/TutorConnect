@forelse($messages as $message)
    @include('chat.partials.message', ['message' => $message])
@empty
    <div class="text-center py-5 text-muted">
        <i class="bi bi-chat-quote display-4 mb-2 d-block text-primary-color bg-opacity-10"></i>
        <small>No messages exchanged yet. Type below to begin the conversation!</small>
    </div>
@endforelse
