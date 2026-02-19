@php
    $avatar = $this->resolveAvatar($log->recipient_image ?? null);
    $imageSrc = $avatar['imageSrc'];
    $fallbackSrc = $avatar['fallbackSrc'];
@endphp
<li wire:key="{{ $wireKey }}"
    class="user-list-item {{ $isActive ? 'active' : '' }}"
    wire:click="selectEmailLog({{ $log->id }})">
    <img src="{{ $imageSrc }}" class="user-avatar" data-image="{{ $imageSrc }}" loading="lazy"
        onerror="this.onerror=null;this.src='{{ $fallbackSrc }}';" />
    <div class="user-infos">
        <div class="user-header">
            <strong>{{ $log->recipient_email }}</strong>
        </div>
        <small>{{ $log->subject ?? 'Message from Flyertrade' }}</small>
    </div>
    <div class="msg_info_part">
        <span class="time">{{ optional($log->created_at)->diffForHumans() }}</span>
    </div>
</li>
