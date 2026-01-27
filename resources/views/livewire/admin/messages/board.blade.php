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
        <aside class="sidebars" wire:key="conversation-sidebar">
            <div class="search-bars">
                <input type="search" placeholder="Search" wire:model.debounce.500ms="search" />
            </div>

            <div class="filters">

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
                <ul class="user-list" wire:key="conversation-list">
                    @forelse ($conversations as $conversation)
                        <li class="user-list-item {{ $activeConversationId === $conversation['id'] ? 'active' : '' }}"
                            wire:click="selectConversation('{{ (string) $conversation['id'] }}')">
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
                            <img src="{{ $imageSrc }}" class="user-avatar"
                                onerror="this.onerror=null;this.src='{{ $fallbackSrc }}';" />
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
        @if ($this->hasActiveConversation)
            <div class="message-chat-theme">
                <div class="chat-header">
                    <div class="heading-with-icon" bis_skin_checked="1">
                        <img src="{{ asset('assets/images/icons/back.svg') }}" alt="" class="icon-back">
                        <div class="user-info" bis_skin_checked="1">
                            <img src="{{ asset('assets/images/icons/five.svg') }}" alt="avatar">
                            <div bis_skin_checked="1">
                                <p class="user-name" style="font-weight:600; color:black;">Antonetta Walker</p>
                                <p class="user-email">bfahey@example.org</p>
                            </div>
                        </div>
                    </div>


                    <div class="header-right">
                        <span>1 of 200</span>
                        <div class="icons">
                            <img src="{{ asset('assets/images/icons/message-icon-prev.svg') }}" alt="Refresh Icon">
                            <img src="{{ asset('assets/images/icons/message-icon-next.svg') }}" alt="Expand Icon">
                        </div>
                        <div class="icons">
                            <img src="{{ asset('assets/images/icons/dots_message.svg') }}" alt="Refresh Icon">

                        </div>
                        <button class="new-email new-email-btn"><svg width="14" height="14" viewBox="0 0 14 14"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6.58333 0.75V12.4167M0.75 6.58333H12.4167" stroke="#004E42" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg> New email</button>
                    </div>
                </div>

                <div class="chat-body" id="chatBody">
                    @if ($loadingMessages)
                        <p style="padding:20px">Loading...</p>
                    @endif

                    @foreach ($messages as $message)
                        <div class="message {{ $message['sender'] === 'support' ? 'message-right' : 'message-left' }}">
                            <p>{{ $message['text'] }}</p>
                            <span class="timestamp">{{ $message['time'] }}</span>
                        </div>
                    @endforeach
                    {{-- <!-- Message 1 Left -->
                    <div class="message message-left">
                        <p>
                            Lorem ipsum dolor sit amet consectetur. Dui sapien sagittis egestas sit quam nunc
                            sodales sem. Gravida maecenas condimentum elementum felis. Eu non et in sed. Odio
                            magna lectus condimentum neque nibh duis id. A morbi tristique quis velit sit.
                        </p>
                        <span class="timestamp" style="color:#8e8e8e;">Message sent 12pm</span>
                    </div>

                    <!-- Message 2 Right -->
                    <div class="message message-right">
                        <p>
                            Lorem ipsum dolor sit amet consectetur. Dui sapien sagittis egestas sit quam nunc
                            sodales sem. Gravida maecenas condimentum elementum.
                        </p>
                        <span class="timestamp">Message sent 12:12pm</span>
                    </div>

                    <!-- Message 3 Left -->
                    <div class="message message-left">
                        <p>
                            Lorem ipsum dolor sit amet consectetur. Dui sapien sagittis egestas sit quam nunc
                            sodales sem. Gravida maecenas condimentum elementum felis. Eu non et in sed. Odio
                            magna lectus condimentum neque nibh duis id. A morbi tristique quis velit sit.
                        </p>
                        <span class="timestamp" style="color:#8e8e8e;">Message sent 12pm</span>
                    </div>

                    <!-- Message 4 Right -->
                    <div class="message message-right">
                        <p>
                            Lorem ipsum dolor sit amet consectetur. Dui sapien sagittis egestas sit quam nunc
                            sodales sem. Gravida maecenas condimentum elementum.
                        </p>
                        <span class="timestamp">Message sent 12:14pm</span>
                    </div>

                    <!-- Message 5 Left -->
                    <div class="message message-left">
                        <p>
                            Lorem ipsum dolor sit amet consectetur. Dui sapien sagittis egestas sit quam nunc
                            sodales sem. Gravida maecenas condimentum elementum felis. Eu non et in sed. Odio
                            magna lectus condimentum neque nibh duis id. A morbi tristique quis velit sit.
                        </p>
                        <span class="timestamp" style="color:#8e8e8e;">Message sent 12pm</span>
                    </div> --}}
                </div>



                <div class="chat-footer">
                    <input id="chatInput" wire:model.defer="replyMessage" wire:keydown.enter="sendReply" type="text"
                        placeholder="Reply message......">
                    <div class="footer-icons"><img src="{{ asset('assets/images/icons/emoji.svg') }}"
                            alt=""><img src="{{ asset('assets/images/icons/txt.svg') }}" alt=""><span
                            class="attachment">
                            <div class="file-upload">
                                <img class="attach theme-attach"
                                    src="{{ asset('assets/images/icons/ic_attachment.svg') }}" alt="Attach">
                                <input type="file">
                            </div>
                        </span></div>
                    <button id="sendBtn" class="send-btn"><img
                            src="{{ asset('assets/images/icons/send-chat-icon.svg') }}" alt=""></button>
                </div>
            </div>
        @else
            <section class="content-panel">
                <div class="display-chat">
                    <div class="chat-display-img">
                        <img src="{{ asset('assets/images/icons/chat-img.svg') }}" alt="Chat Icon" class="chat-img">
                        <h2 class="chat-title">Select a message to view</h2>
                    </div>
                </div>
            </section>
        @endif


    </div>


    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('scroll-chat-bottom', () => {
                const el = document.getElementById('chatBody');
                if (el) el.scrollTop = el.scrollHeight;
            });
        });
    </script>
</div>
