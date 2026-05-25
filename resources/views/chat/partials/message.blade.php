@php
    $isMe = $message->sender_id === Auth::id();
@endphp

<div class="d-flex mb-3 {{ $isMe ? 'justify-content-end' : 'justify-content-start' }}">
    <div class="d-flex align-items-end gap-2" style="max-width: 75%;">
        @if(!$isMe)
            @if($message->sender->profile_picture)
                <img src="{{ asset('storage/' . $message->sender->profile_picture) }}" class="avatar-circle" alt="{{ $message->sender->name }}" style="width: 28px; height: 28px;">
            @else
                <div class="avatar-circle" style="width: 28px; height: 28px; font-size: 0.8rem;">
                    {{ substr($message->sender->name, 0, 1) }}
                </div>
            @endif
        @endif
        
        <div>
            <div class="p-3 rounded-4 {{ $isMe ? 'bg-primary-color text-white' : 'bg-body-secondary' }}" style="font-size: 0.95rem; border-bottom-{{ $isMe ? 'right' : 'left' }}-radius: 4px;">
                {{ $message->message }}
            </div>
            <small class="text-muted text-xs mt-1 d-block {{ $isMe ? 'text-end' : 'text-start' }}">
                {{ $message->created_at->format('h:i A') }}
                @if($isMe)
                    <i class="bi bi-check2{{ $message->is_read ? '-all text-primary-color' : '' }} ms-1"></i>
                @endif
            </small>
        </div>
    </div>
</div>
