<aside class="sidebars" wire:key="conversation-sidebar" wire:poll.5000ms="pollConversations" wire:ignore.self
    x-data="{ search: '' }">
    <div class="search-bars">
        <svg class="searc_icon" width="24" height="24" viewBox="0 0 24 24" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path
                d="M21 21L15.0001 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z"
                stroke="#555555" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        <input type="search" placeholder="Search" x-model.debounce.200ms="search" />
    </div>

    <div class="filters" data-livewire-tabs="true">
        <button class="tab filter-btn {{ $filter === 'all' ? 'tab-active' : '' }}"
            wire:click="switchTab('filter','all')">
            All
        </button>
        <button class="tab filter-btn {{ $filter === 'unread' ? 'tab-active' : '' }}"
            wire:click="switchTab('filter','unread')">
            Unread
        </button>
        <button class="tab filter-btn {{ $filter === 'emails' ? 'tab-active' : '' }}"
            wire:click="switchTab('filter','emails')">
            Emails
        </button>
        <button class="tab filter-btn {{ $filter === 'chats' ? 'tab-active' : '' }}"
            wire:click="switchTab('filter','chats')">
            Chats
        </button>
    </div>

    <div class="chat-user-sections">
        <div class="tab chat-tabss {{ $audience === 'service-users' ? 'active' : '' }}"
            wire:click="switchTab('audience','service-users')">
            Service users
        </div>
        <div class="tab chat-tabss {{ $audience === 'service-provider' ? 'active' : '' }}"
            wire:click="switchTab('audience','service-provider')">
            Service Provider
        </div>
    </div>

    <div class="tab-content active">
        @if (!empty($conversations))
            <div class="user-actions">
                <label>
                    <input type="checkbox" id="selectAll" data-livewire-select="true" @checked($selectAll)
                        wire:click="toggleSelectAll" />
                    Select all
                </label>
                <div class="filter-menu">
                    <select id="filterStatus" wire:model="filterStatus">
                        <option value="all">All</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
        @endif
        <ul class="user-list" wire:key="conversation-list">
            @forelse ($conversations as $conversation)
                <li wire:key="conversation-{{ (string) $conversation['id'] }}"
                    class="user-list-item {{ $activeConversationId === $conversation['id'] ? 'active' : '' }}"
                    :class="{ 'active': uiActiveId === '{{ (string) $conversation['id'] }}' }"
                    data-search="{{ \Illuminate\Support\Str::lower((string) ($conversation['userName'] ?? '') . ' ' . (string) ($conversation['userId'] ?? '')) }}"
                    data-name="{{ $conversation['userName'] ?? 'Unknown' }}"
                    data-email="{{ $conversation['userId'] ?? '' }}"
                    x-show="!search || ($el.dataset.search && $el.dataset.search.includes(search.toLowerCase().trim()))"
                    wire:click="selectConversation('{{ (string) $conversation['id'] }}')"
                    @click="if (uiActiveId === '{{ (string) $conversation['id'] }}') return;
                        uiActiveId = '{{ (string) $conversation['id'] }}';
                        previewName = $el.dataset.name || '';
                        previewEmail = $el.dataset.email || '';
                        previewImage = $el.dataset.image || '';
                        switching = true;">
                    @php
                        $defaultAvatar = 'assets/images/avatar/default.png';
                        $image = $conversation['userImage'] ?? $defaultAvatar;
                        $image = trim((string) $image);
                        if ($image === '' || $image === 'null') {
                            $image = $defaultAvatar;
                        }
                        $isUrl = \Illuminate\Support\Str::startsWith($image, ['http://', 'https://']);
                        $imageSrc = $isUrl ? $image : asset($image);
                        $fallbackSrc = asset($defaultAvatar);
                    @endphp
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
                    <input type="checkbox" class="select-user" wire:model="selectedConversationIds" wire:click.stop
                        value="{{ (string) $conversation['id'] }}">
                </li>
            @empty
                <li class="user-list-item">
                    <div class="user-infos">
                        <strong>No conversations found.</strong>
                    </div>
                </li>
            @endforelse
        </ul>
    </div>
</aside>
