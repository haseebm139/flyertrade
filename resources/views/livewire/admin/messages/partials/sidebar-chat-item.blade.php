@php
    $avatar = $this->resolveAvatar($conversation['userImage'] ?? null);
    $imageSrc = $avatar['imageSrc'];
    $fallbackSrc = $avatar['fallbackSrc'];
@endphp
<li wire:key="{{ $wireKey }}"
    class="user-list-item {{ $isActive ? 'active' : '' }}"
    :class="{ 'active': uiActiveId === '{{ (string) $conversation['id'] }}' }"
    data-name="{{ $conversation['userName'] ?? 'Unknown' }}"
    data-email="{{ $conversation['userId'] ?? '' }}"
    wire:click="selectConversation('{{ (string) $conversation['id'] }}')"
    @click="if (uiActiveId === '{{ (string) $conversation['id'] }}') return;
        uiActiveId = '{{ (string) $conversation['id'] }}';
        previewName = $el.dataset.name || '';
        previewEmail = $el.dataset.email || '';
        previewImage = $el.dataset.image || '';
        switching = true;">
    <img src="{{ $imageSrc }}" class="user-avatar" data-image="{{ $imageSrc }}" loading="lazy"
        onerror="this.onerror=null;this.src='{{ $fallbackSrc }}';" />
    <div class="user-infos">
        <div class="user-header">
            <strong>{{ $conversation['userName'] ?? 'Unknown' }}</strong>
        </div>
        <small>{{ $conversation['lastMessage'] ?? '' }}</small>
    </div>
    <div class="msg_info_part">
        <span class="time">{{ $conversation['lastMessageTime'] ?? '' }}</span>
        @if (!empty($conversation['unreadCount']))
            <span class="unread-count">{{ $conversation['unreadCount'] }}</span>
        @endif
    </div>
</li>
