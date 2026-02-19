<aside class="sidebars" wire:key="conversation-sidebar" wire:poll.5000ms="pollConversations" wire:ignore.self>
    @if ($showCompose)
        <div class="search-bars">
            <label style="font-weight:600;margin-bottom:0.417vw;display:block;">Select Customer</label>
             
            <input type="search" placeholder="Search" wire:model.debounce.300ms="searchCompose" />
        </div>
    @else
        <div class="search-bars">
            @if ($filter === 'emails')
                <input type="search" placeholder="Search by email" wire:model.debounce.300ms="emailSearch" />
            @else
                <input type="search" placeholder="Search" wire:model.debounce.300ms="search" />
            @endif
        </div>
    @endif

    @if (!$showCompose)
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
    @endif

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
        @if ($showCompose)
            <div class="user-actions">
                <label>
                    <input type="checkbox" id="selectAll" data-livewire-select="true" wire:model="selectAll" />
                    Select all
                </label>
                <div class="filter-menu">
                    <img class="btn-icons" src="http://127.0.0.1:8000/assets/images/icons/button-icon.svg " alt="">
                </div>
            </div>
        @endif
        <ul class="user-list" wire:key="conversation-list">
            @if ($showCompose)
                @forelse ($this->composeUsers as $user)
                    <li wire:key="compose-user-{{ $user->id }}" class="user-list-item">
                        @php
                            $defaultAvatar = 'assets/images/avatar/default.png';
                            $image = trim((string) ($user->avatar ?? $defaultAvatar));
                            if ($image === '' || $image === 'null') {
                                $image = $defaultAvatar;
                            }
                            $isUrl = \Illuminate\Support\Str::startsWith($image, ['http://', 'https://']);
                            $imageSrc = $isUrl ? $image : asset($image);
                            $fallbackSrc = asset($defaultAvatar);
                        @endphp
                        <img src="{{ $imageSrc }}" class="user-avatar"
                            onerror="this.onerror=null;this.src='{{ $fallbackSrc }}';" />
                        <div class="user-infos">
                            <div class="user-header">
                                <strong>{{ $user->name ?? 'Unknown' }}</strong>
                            </div>
                            <small>{{ $user->email ?? '' }}</small>
                        </div>
                        <input type="checkbox" class="select-user" wire:model="selectedConversationIds"
                            wire:click.stop value="{{ $user->email }}">
                    </li>
                @empty
                    <li class="user-list-item">
                        <div class="user-infos">
                            <strong>No users found.</strong>
                        </div>
                    </li>
                @endforelse
            @else
                @if ($filter === 'all')
                    @forelse ($this->allItems as $item)
                        @php
                            $itemType = $item['type'] ?? null;
                            $log = $item['data'] ?? null;
                        @endphp
                        @if ($itemType === 'email')
                            @include('livewire.admin.messages.partials.sidebar-email-item', [
                                'log' => $log,
                                'wireKey' => 'all-email-' . $log->id,
                                'isActive' => $activeEmailLogId === $log->id,
                            ])
                        @else
                            @php($conversation = is_array($item['data'] ?? null) ? $item['data'] : [])
                            @include('livewire.admin.messages.partials.sidebar-chat-item', [
                                'conversation' => $conversation,
                                'wireKey' => 'all-chat-' . (string) ($conversation['id'] ?? ''),
                                'isActive' => $activeConversationId === ($conversation['id'] ?? null),
                            ])
                        @endif
                    @empty
                        <li class="user-list-item">
                            <div class="user-infos">
                                <strong>No items found.</strong>
                            </div>
                        </li>
                    @endforelse
                @elseif ($filter === 'emails')
                    @forelse ($this->emailLogs as $log)
                        @include('livewire.admin.messages.partials.sidebar-email-item', [
                            'log' => $log,
                            'wireKey' => 'email-log-' . $log->id,
                            'isActive' => $activeEmailLogId === $log->id,
                        ])
                    @empty
                        <li class="user-list-item">
                            <div class="user-infos">
                                <strong>No emails found.</strong>
                            </div>
                        </li>
                    @endforelse
                    @if ($this->emailLogs->count() >= $this->emailLogPage * 20)
                        <li class="user-list-item">
                            <button type="button" class="new-email" wire:click="loadMoreEmailLogs">
                                Load more
                            </button>
                        </li>
                    @endif
                @else
                    @forelse ($conversations as $conversation)
                        @include('livewire.admin.messages.partials.sidebar-chat-item', [
                            'conversation' => $conversation,
                            'wireKey' => 'conversation-' . (string) ($conversation['id'] ?? ''),
                            'isActive' => $activeConversationId === ($conversation['id'] ?? null),
                        ])
                    @empty
                        <li class="user-list-item">
                            <div class="user-infos">
                                <strong>No conversations found.</strong>
                            </div>
                        </li>
                    @endforelse
                @endif
            @endif
        </ul>
    </div>
</aside>
