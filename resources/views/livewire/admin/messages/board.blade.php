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
                <svg class="searc_icon" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M21 21L15.0001 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z"
                        stroke="#555555" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
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
                <div class="user-actions">
                    <label>
                        <input type="checkbox" id="selectAll" wire:model="selectAll" /> Select all
                    </label>
                    <div class="filter-menu">
                        <select id="filterStatus" wire:model="filterStatus">
                            <option value="all">All</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
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
                                </div>
                                <small>{{ $conversation['lastMessage'] ?? '' }}</small>
                            </div>
                            <div class="msg_info_part">
                                <span class="time">{{ $conversation['lastMessageTime'] ?? '' }}</span>
                                @if (!empty($conversation['unreadCount']))
                                    <span class="unread-count">{{ $conversation['unreadCount'] }}</span>
                                @endif
                            </div>
                            <input type="checkbox" class="select-user" onclick="event.stopPropagation()">
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
                        <img src="{{ asset('assets/images/icons/back.svg') }}" alt="" class="icon-back"
                            role="button" style="cursor:pointer" wire:click="closeConversation">
                        <div class="user-info" bis_skin_checked="1">
                            <img src="{{ asset($activeConversationMeta['userImage'] ?? 'assets/images/icons/five.svg') }}"
                                alt="avatar">
                            <div bis_skin_checked="1">
                                <p class="user-name" style="font-weight:600; color:black;">
                                    {{ $activeConversationMeta['userName'] ?? 'Support' }}</p>
                                <p class="user-email">{{ $activeConversationMeta['userEmail'] ?? '' }}</p>
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
                        <button class="new-email new-email-btn"><svg width="14" height="14"
                                viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6.58333 0.75V12.4167M0.75 6.58333H12.4167" stroke="#004E42"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg> New email</button>
                    </div>
                </div>

                <div class="chat-body" id="chatBody" wire:poll.2000ms="pollMessages">
                    @if ($loadingMessages)
                        <p style="padding:20px">Loading...</p>
                    @endif

                    @foreach ($messages as $message)
                        <div
                            class="message {{ ($message['sender'] ?? 'user') === 'support' ? 'message-right' : 'message-left' }}">
                            @if (!empty($message['mediaUrl']))
                                @if (($message['messageType'] ?? '') === 'image')
                                    <p><img src="{{ $message['mediaUrl'] }}" alt="attachment"
                                            style="max-width: 240px; border-radius: 6px;"></p>
                                @elseif (($message['messageType'] ?? '') === 'video')
                                    <p><video src="{{ $message['mediaUrl'] }}" controls
                                            style="max-width: 240px; border-radius: 6px;"></video></p>
                                @else
                                    <p><a href="{{ $message['mediaUrl'] }}" target="_blank" rel="noopener">View
                                            attachment</a></p>
                                @endif
                            @endif
                            @if (!empty($message['text']))
                                <p>{{ $message['text'] }}</p>
                            @endif
                            <span class="timestamp">{{ $message['time'] ?? '' }}</span>
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
                    @if (!empty($replyMediaUrl))
                        <div class="chat-attachment-preview">
                            @if ($replyMediaType === 'image')
                                <img src="{{ $replyMediaUrl }}" alt="Attachment preview">
                            @elseif ($replyMediaType === 'video')
                                <video src="{{ $replyMediaUrl }}" controls></video>
                            @else
                                <span class="attachment-filename">{{ $replyMediaUrl }}</span>
                            @endif
                            <button type="button" class="attachment-remove" wire:click="clearAttachment">×</button>
                        </div>
                    @endif
                    <input id="chatInput" wire:model="replyMessage" wire:keydown.enter.prevent="sendReply"
                        type="text" placeholder="Reply message......">
                    <div class="footer-icons"><img src="{{ asset('assets/images/icons/emoji.svg') }}"
                            alt=""><img src="{{ asset('assets/images/icons/txt.svg') }}"
                            alt=""><span class="attachment" wire:ignore>
                            <div class="file-upload">
                                <img class="attach theme-attach"
                                    src="{{ asset('assets/images/icons/ic_attachment.svg') }}" alt="Attach">
                                <input id="chatAttachmentInput" type="file" accept="image/*,video/*">
                            </div>
                        </span></div>
                    <button id="sendBtn" class="send-btn" type="button" wire:click="sendReply"><img
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


    <style>
        .chat-attachment-preview {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
            padding: 6px 8px;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            background: #fff;
        }

        .chat-attachment-preview img {
            max-height: 60px;
            border-radius: 4px;
        }

        .chat-attachment-preview video {
            max-height: 60px;
            border-radius: 4px;
        }

        .attachment-remove {
            border: none;
            background: #f2f2f2;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 16px;
            line-height: 22px;
        }
    </style>
    <script>
        document.addEventListener('livewire:initialized', () => {
            const bindAttachmentInput = () => {
                const input = document.getElementById('chatAttachmentInput');
                if (!input || input.dataset.bound === '1') return;
                input.dataset.bound = '1';

                input.addEventListener('change', async (event) => {
                    const file = event.target.files?.[0];
                    if (!file) return;

                    const formData = new FormData();
                    formData.append('file', file);

                    const csrfToken = document.querySelector('meta[name="csrf-token"]')
                        ?.getAttribute('content');
                    const headers = csrfToken ? {
                        'X-CSRF-TOKEN': csrfToken
                    } : {};

                    try {
                        const response = await fetch('/admin/chat/upload-media', {
                            method: 'POST',
                            body: formData,
                            headers,
                            credentials: 'same-origin',
                        });

                        const payload = await response.json();
                        if (!response.ok || payload?.status === 'error') {
                            throw new Error(payload?.message || 'Upload failed');
                        }

                        const data = payload?.data || payload;
                        const component = Livewire.find(@json($this->getId()));
                        if (component) {
                            component.set('replyMediaUrl', data.url || '');
                            component.set('replyMediaType', data.type || '');
                        }
                    } catch (err) {
                        console.error(err);
                        alert(err?.message || 'Failed to upload file');
                    } finally {
                        event.target.value = '';
                    }
                });
            };

            Livewire.on('scroll-chat-bottom', () => {
                const el = document.getElementById('chatBody');
                if (el) el.scrollTop = el.scrollHeight;
            });

            bindAttachmentInput();

            Livewire.hook('message.processed', () => {
                bindAttachmentInput();
            });
        });
    </script>
</div>
