<div>
    <div class="users-toolbar border-0 p-0">
        <div class="toolbar-left">
            @can('Create Messages')
                <button class="add-user-btn new-email-btn" type="button">
                    <img class="icons-btn" src="{{ asset('assets/images/icons/sms.svg') }}" alt=""> New Email
                </button>
                <button class="export-btn" type="button">
                    <img class="icons-btn" src="{{ asset('assets/images/icons/messages.svg') }}" alt=""> New Message
                </button>
            @endcan
        </div>
        <div class="toolbar-right">
            <h2 class="page-title">Messaging</h2>
        </div>
    </div>

    <div class="messages-email-container">
        <aside class="sidebars">
            <div class="search-bars">
                <input type="search" placeholder="Search" wire:model.live.debounce.300ms="search" />
            </div>

            <div class="filters">
                <button class="tab filter-btn {{ $filter === 'all' ? 'tab-active' : '' }}" type="button"
                    wire:click="setFilter('all')">All</button>
                <button class="tab filter-btn {{ $filter === 'unread' ? 'tab-active' : '' }}" type="button"
                    wire:click="setFilter('unread')">Unread</button>
                <button class="tab filter-btn {{ $filter === 'emails' ? 'tab-active' : '' }}" type="button"
                    wire:click="setFilter('emails')">Emails</button>
                <button class="tab filter-btn {{ $filter === 'chats' ? 'tab-active' : '' }}" type="button"
                    wire:click="setFilter('chats')">Chats</button>
            </div>

            <div class="chat-user-sections">
                <div class="tab chat-tabss {{ $audience === 'service-users' ? 'tab-active' : '' }}" type="button"
                    wire:click="setAudience('service-users')">Service users</div>
                <div class="tab chat-tabss {{ $audience === 'service-provider' ? 'tab-active' : '' }}" type="button"
                    wire:click="setAudience('service-provider')">Service Provider</div>
            </div>

            <div class="tab-content active">
                <ul class="user-list">
                    @forelse ($conversations as $conversation)
                        <li class="user-list-item {{ $activeConversationId === $conversation['id'] ? 'active' : '' }}"
                            wire:click="selectConversation('{{ $conversation['id'] }}')">
                            @php
                                $image = $conversation['userImage'] ?? 'assets/images/avatar/default.png';
                                $isUrl = \Illuminate\Support\Str::startsWith($image, ['http://', 'https://']);
                            @endphp
                            <img src="{{ $isUrl ? $image : asset($image) }}" class="user-avatar" />
                            <div class="user-infos">
                                <div class="user-header">
                                    <strong>{{ $conversation['userName'] ?? 'Unknown' }}</strong>
                                    <span class="time">{{ $conversation['lastMessageTime'] ?? '' }}</span>
                                </div>
                                <small>{{ $conversation['lastMessage'] ?? '' }}</small>
                            </div>
                            @if (!empty($conversation['unreadCount']))
                                <span class="unread-count">{{ $conversation['unreadCount'] }}</span>
                            @endif
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

        <section class="content-panel">
            <div class="display-chat">
                <div class="chat-display-img">
                    <img src="{{ asset('assets/images/icons/chat-img.svg') }}" alt="Chat Icon" class="chat-img">
                    <h2 class="chat-title">Select a message to view</h2>
                </div>
            </div>
        </section>
    </div>
</div>
