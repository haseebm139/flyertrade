<div x-data="{
    uiActiveId: @entangle('activeConversationId'),
    messagesId: @entangle('messagesConversationId'),
    previewName: '',
    previewEmail: '',
    previewImage: '',
    switching: false,
    loading: @entangle('loadingMessages'),
    attachmentPreviewUrl: '',
    attachmentPreviewType: ''
}" x-effect="if (!loading && messagesId === uiActiveId) switching = false"
    x-init="window.addEventListener('clear-attachment-preview', () => {
        attachmentPreviewUrl = '';
        attachmentPreviewType = '';
    })">
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
        @include('livewire.admin.messages.partials.sidebar')
        @if ($this->hasActiveConversation)
            <div class="message-chat-theme" wire:key="chat-body-{{ $activeConversationId }}"
                wire:init="initConversation" style="position: relative;">
                @if ($loadingMessages)
                    <div class="chat-loading-overlay">
                        <div class="chat-loading-spinner"></div>
                        <div class="chat-loading-text">Loading conversation...</div>
                    </div>
                @endif
                <div class="chat-header">
                    <div class="heading-with-icon" bis_skin_checked="1">
                        <img src="{{ asset('assets/images/icons/back.svg') }}" alt="" class="icon-back"
                            role="button" style="cursor:pointer" wire:click="closeConversation">
                        <div class="user-info" bis_skin_checked="1">
                            <img :src="switching && previewImage ? previewImage :
                                '{{ asset($activeConversationMeta['userImage'] ?? 'assets/images/icons/five.svg') }}'"
                                class="img-loading" data-shimmer="true"
                                onload="this.classList.remove('img-loading');this.removeAttribute('data-shimmer');"
                                onerror="this.classList.remove('img-loading');this.removeAttribute('data-shimmer');"
                                alt="avatar">
                            <div bis_skin_checked="1">
                                <p class="user-name" style="font-weight:600; color:black;">
                                    <span
                                        x-text="switching && previewName ? previewName : '{{ $activeConversationMeta['userName'] ?? 'Support' }}'"></span>
                                </p>
                                <p class="user-email">
                                    <span
                                        x-text="switching && previewEmail ? previewEmail : '{{ $activeConversationMeta['userEmail'] ?? '' }}'"></span>
                                </p>
                            </div>
                        </div>
                    </div>


                    <div class="header-right">
                        @php
                            $activeIndex = null;
                            foreach ($conversations as $idx => $conv) {
                                if ((string) ($conv['id'] ?? '') === (string) $activeConversationId) {
                                    $activeIndex = $idx + 1;
                                    break;
                                }
                            }
                            $totalConversations = is_countable($conversations) ? count($conversations) : 0;
                        @endphp
                        <span>{{ $activeIndex ?? 0 }} of {{ $totalConversations }}</span>
                        @if ($newIncomingCount > 0)
                            <button type="button" class="new-email" wire:click="markMessagesSeen">
                                New {{ $newIncomingCount }}
                            </button>
                        @endif
                        <div class="icons">
                            <img src="{{ asset('assets/images/icons/message-icon-prev.svg') }}" alt="Prev Icon"
                                class="chat-nav-icon" role="button" wire:click="selectPreviousConversation">
                            <img src="{{ asset('assets/images/icons/message-icon-next.svg') }}" alt="Next Icon"
                                class="chat-nav-icon" role="button" wire:click="selectNextConversation">
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

                <div class="chat-body" id="chatBody" wire:poll.2000ms="pollMessages">
                    <div x-show="switching" x-cloak class="chat-skeleton">
                        <div class="chat-skeleton-row left"></div>
                        <div class="chat-skeleton-row right"></div>
                        <div class="chat-skeleton-row left"></div>
                        <div class="chat-skeleton-row right"></div>
                        <div class="chat-skeleton-row left"></div>
                    </div>
                    @if ($hasMoreMessages)
                        <div style="padding: 12px; text-align: center;" x-show="!switching && messagesId === uiActiveId"
                            x-cloak>
                            @if ($loadingMoreMessages)
                                <div class="chat-loadmore-shimmer">
                                    <span class="chat-loadmore-dot"></span>
                                    <span class="chat-loadmore-dot"></span>
                                    <span class="chat-loadmore-dot"></span>
                                </div>
                                <div class="chat-loadmore-text">Loading older messages...</div>
                            @else
                                <button type="button" class="new-email" wire:click="loadMoreMessages">
                                    Load older messages
                                </button>
                            @endif
                        </div>
                    @endif
                    @if ($loadingMessages)
                        <p style="padding:20px">Loading...</p>
                    @endif

                    <div x-show="!switching && messagesId === uiActiveId" x-cloak>
                        @foreach ($messages as $message)
                            <div
                                class="message {{ ($message['sender'] ?? 'user') === 'support' ? 'message-right' : 'message-left' }}">
                                @if (!empty($message['mediaUrl']))
                                    @if (($message['messageType'] ?? '') === 'image')
                                        <p><img src="{{ $message['mediaUrl'] }}" alt="attachment" class="img-loading"
                                                data-shimmer="true"
                                                onload="this.classList.remove('img-loading');this.removeAttribute('data-shimmer');"
                                                onerror="this.classList.remove('img-loading');this.removeAttribute('data-shimmer');"
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
                                <span class="timestamp" data-ts="{{ (int) ($message['createdAtTs'] ?? 0) }}"></span>
                            </div>
                        @endforeach
                    </div>
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
                    <template x-if="attachmentPreviewUrl">
                        <div class="chat-attachment-preview">
                            <template x-if="attachmentPreviewType === 'image'">
                                <img :src="attachmentPreviewUrl" alt="Attachment preview">
                            </template>
                            <template x-if="attachmentPreviewType === 'video'">
                                <video :src="attachmentPreviewUrl" controls></video>
                            </template>
                            <template x-if="attachmentPreviewType !== 'image' && attachmentPreviewType !== 'video'">
                                <span class="attachment-filename">Attachment selected</span>
                            </template>
                            <button type="button" class="attachment-remove"
                                @click="attachmentPreviewUrl=''; attachmentPreviewType=''; $wire.clearAttachment()">
                                ×
                            </button>
                        </div>
                    </template>
                    <input id="chatInput" wire:model="replyMessage" wire:keydown.enter.prevent="sendReply"
                        type="text" placeholder="Reply message......">
                    <div class="footer-icons">
                        {{-- <img src="{{ asset('assets/images/icons/emoji.svg') }}" alt="">
                        <img src="{{ asset('assets/images/icons/txt.svg') }}" alt=""> --}}
                        <span class="attachment" wire:ignore>
                            <div class="file-upload">
                                <img class="attach theme-attach"
                                    src="{{ asset('assets/images/icons/ic_attachment.svg') }}" alt="Attach">
                                <input id="chatAttachmentInput" type="file" accept="image/*,video/*"
                                    wire:model.defer="replyMediaFile"
                                    @change="
                                        const file = $event.target.files?.[0];
                                        if (!file) return;
                                        attachmentPreviewUrl = URL.createObjectURL(file);
                                        attachmentPreviewType = file.type.startsWith('image/')
                                            ? 'image'
                                            : (file.type.startsWith('video/') ? 'video' : 'file');
                                    ">
                            </div>
                        </span>
                    </div>
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
    <style>
        .chat-loading-overlay {
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0.85);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 5;
            backdrop-filter: blur(2px);
        }

        .chat-loading-spinner {
            width: 36px;
            height: 36px;
            border: 3px solid #e5e7eb;
            border-top-color: #004e42;
            border-radius: 50%;
            animation: chat-spin 0.9s linear infinite;
            margin-bottom: 10px;
        }

        .chat-loading-text {
            font-size: 0.833vw;
            color: #555;
            font-weight: 600;
        }

        @keyframes chat-spin {
            to {
                transform: rotate(360deg);
            }
        }

        @media (max-width: 600px) {
            .chat-loading-text {
                font-size: 3vw;
            }
        }

        .chat-loadmore-shimmer {
            display: inline-flex;
            gap: 6px;
            align-items: center;
            justify-content: center;
            margin-bottom: 6px;
        }

        .chat-loadmore-dot {
            width: 8px;
            height: 8px;
            background: #004e42;
            border-radius: 50%;
            animation: chat-dot-pulse 0.9s ease-in-out infinite;
            opacity: 0.6;
        }

        .chat-loadmore-dot:nth-child(2) {
            animation-delay: 0.15s;
        }

        .chat-loadmore-dot:nth-child(3) {
            animation-delay: 0.3s;
        }

        .chat-loadmore-text {
            font-size: 0.781vw;
            color: #555;
            font-weight: 600;
        }

        @keyframes chat-dot-pulse {

            0%,
            100% {
                transform: scale(0.9);
                opacity: 0.5;
            }

            50% {
                transform: scale(1.2);
                opacity: 1;
            }
        }

        @media (max-width: 600px) {
            .chat-loadmore-text {
                font-size: 3vw;
            }
        }

        .chat-skeleton {
            display: flex;
            flex-direction: column;
            gap: 10px;
            padding: 12px 16px;
        }

        .chat-skeleton-row {
            height: 14px;
            border-radius: 8px;
            background: linear-gradient(90deg, #f1f1f1 25%, #e7e7e7 37%, #f1f1f1 63%);
            background-size: 400% 100%;
            animation: chat-shimmer 1.2s ease-in-out infinite;
        }

        .chat-skeleton-row.left {
            width: 55%;
            align-self: flex-start;
        }

        .chat-skeleton-row.right {
            width: 45%;
            align-self: flex-end;
        }

        @keyframes chat-shimmer {
            0% {
                background-position: 100% 0;
            }

            100% {
                background-position: -100% 0;
            }
        }

        .img-loading {
            background: linear-gradient(90deg, #f1f1f1 25%, #e7e7e7 37%, #f1f1f1 63%);
            background-size: 400% 100%;
            animation: chat-shimmer 1.2s ease-in-out infinite;
        }

        .chat-nav-icon {
            cursor: pointer;
            transition: transform 0.15s ease, opacity 0.15s ease;
        }

        .chat-nav-icon:hover {
            transform: translateY(-1px) scale(1.05);
            opacity: 0.85;
        }

        .chat-nav-icon:active {
            transform: translateY(0) scale(0.95);
            opacity: 0.7;
        }
    </style>
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('scroll-chat-bottom', () => {
                const el = document.getElementById('chatBody');
                if (el) el.scrollTop = el.scrollHeight;
            });
            Livewire.on('scroll-chat-top', () => {
                const el = document.getElementById('chatBody');
                if (el) el.scrollTop = 0;
            });
            Livewire.on('clear-attachment-preview', () => {
                window.dispatchEvent(new CustomEvent('clear-attachment-preview'));
                const input = document.getElementById('chatAttachmentInput');
                if (input) input.value = '';
            });

            const clearLoadedImages = () => {
                document.querySelectorAll('img.img-loading').forEach(img => {
                    if (img.complete) {
                        img.classList.remove('img-loading');
                        img.removeAttribute('data-shimmer');
                    }
                });
            };

            const updateTimestamps = () => {
                const nodes = document.querySelectorAll('.timestamp[data-ts]');
                const nowMs = Date.now();
                nodes.forEach(node => {
                    const ts = parseInt(node.dataset.ts || '0', 10);
                    if (!ts) return;
                    const diffSec = Math.max(0, Math.floor((nowMs - ts * 1000) / 1000));
                    let text = '';
                    if (diffSec < 60) {
                        text = 'just now';
                    } else if (diffSec < 3600) {
                        const mins = Math.floor(diffSec / 60);
                        text = mins + ' minute' + (mins === 1 ? '' : 's') + ' ago';
                    } else if (diffSec < 86400) {
                        const hours = Math.floor(diffSec / 3600);
                        text = hours + ' hour' + (hours === 1 ? '' : 's') + ' ago';
                    } else {
                        const days = Math.floor(diffSec / 86400);
                        text = days + ' day' + (days === 1 ? '' : 's') + ' ago';
                    }
                    node.textContent = text;
                });
            };

            updateTimestamps();
            setInterval(updateTimestamps, 60000);
            clearLoadedImages();

            Livewire.hook('message.processed', () => {
                updateTimestamps();
                clearLoadedImages();
            });
        });
    </script>
</div>
