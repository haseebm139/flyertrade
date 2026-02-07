<div class="messages-board-root">
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
                    <button class="add-user-btn new-email-btn" type="button" wire:click="openCompose('email')">
                        <img class="icons-btn" src="{{ asset('assets/images/icons/sms.svg') }}" alt=""> New Email
                    </button>
                    <button class="export-btn" type="button" wire:click="openCompose('message')">
                        <img class="icons-btn" src="{{ asset('assets/images/icons/messages.svg') }}" alt=""> New
                        Message
                    </button>
                @endcan
            </div>
            <div class="toolbar-right">
                <h2 class="page-title">Messaging</h2>
            </div>
        </div>

        <div class="messages-email-container">
            @include('livewire.admin.messages.partials.sidebar')
            @switch($this->panelState)
                @case('compose_email')
                    <div class="email-compose" id="emailComposePanel">
                        <div class="compose-header">
                            <div class="heading-with-icon">
                                <img src="{{ asset('assets/images/icons/back.svg') }}" alt=""
                                    class="icon-back compose-back-btn" wire:click="closeCompose">
                                <h2>Compose email</h2>
                            </div>

                            @php
                                $recipients = $this->selectedRecipients;
                                $visibleRecipients = array_slice($recipients, 0, 5);
                                $moreCount = max(count($recipients) - 5, 0);
                            @endphp
                            <div class="recipient-container">
                                @if (count($recipients) > 0)
                                    <span class="label">to</span>
                                @endif

                                <div class="recipient-list">
                                    @foreach ($visibleRecipients as $recipient)
                                        @php
                                            $defaultAvatar = 'assets/images/avatar/default.png';
                                            $image = trim((string) ($recipient['userImage'] ?? $defaultAvatar));
                                            if ($image === '' || $image === 'null') {
                                                $image = $defaultAvatar;
                                            }
                                            $isUrl = \Illuminate\Support\Str::startsWith($image, [
                                                'http://',
                                                'https://',
                                            ]);
                                            $imageSrc = $isUrl ? $image : asset($image);
                                            $fallbackSrc = asset($defaultAvatar);
                                        @endphp
                                        <img src="{{ $imageSrc }}" alt="Recipient"
                                            onerror="this.onerror=null;this.src='{{ $fallbackSrc }}';">
                                    @endforeach
                                </div>
                                @if ($moreCount > 0)
                                    <span class="others">+{{ $moreCount }} others</span>
                                @endif
                            </div>
                        </div>

                        <div class="compose-body">
                            <input type="text" class="subject-input" placeholder="Subject">
                            <textarea class="message-area" placeholder=""></textarea>
                        </div>

                        <div class="compose-footer">
                            <span class="attachment">
                                <div class="file-upload">
                                    <img class="attach" src="{{ asset('assets/images/icons/ic_attachment.svg') }}"
                                        alt="Attach">
                                    <input type="file">
                                </div>
                            </span>
                            <button class="send-btn" type="button">Send</button>
                        </div>
                    </div>
                @break

                @case('compose_message')
                    <div class="email-compose" id="messageComposePanel">
                        <div class="compose-header">
                            <div class="heading-with-icon">
                                <img src="{{ asset('assets/images/icons/back.svg') }}" alt=""
                                    class="icon-back compose-back-btn" wire:click="closeCompose">
                                <h2>Compose message</h2>
                            </div>

                            @php
                                $recipients = $this->selectedRecipients;
                                $visibleRecipients = array_slice($recipients, 0, 5);
                                $moreCount = max(count($recipients) - 5, 0);
                            @endphp
                            <div class="recipient-container">
                                @if (count($recipients) > 0)
                                    <span class="label">to</span>
                                @endif
                                <div class="recipient-list">
                                    @foreach ($visibleRecipients as $recipient)
                                        @php
                                            $defaultAvatar = 'assets/images/avatar/default.png';
                                            $image = trim((string) ($recipient['userImage'] ?? $defaultAvatar));
                                            if ($image === '' || $image === 'null') {
                                                $image = $defaultAvatar;
                                            }
                                            $isUrl = \Illuminate\Support\Str::startsWith($image, [
                                                'http://',
                                                'https://',
                                            ]);
                                            $imageSrc = $isUrl ? $image : asset($image);
                                            $fallbackSrc = asset($defaultAvatar);
                                        @endphp
                                        <img src="{{ $imageSrc }}" alt="Recipient"
                                            onerror="this.onerror=null;this.src='{{ $fallbackSrc }}';">
                                    @endforeach
                                </div>
                                @if ($moreCount > 0)
                                    <span class="others">+{{ $moreCount }} others</span>
                                @endif
                            </div>
                        </div>

                        <div class="compose-body">
                            <textarea class="message-area" placeholder="Type your message" wire:model.defer="composeMessageText"></textarea>
                        </div>

                        <div class="compose-footer">
                            <span class="attachment">
                                <div class="file-upload">
                                    <img class="attach" src="{{ asset('assets/images/icons/ic_attachment.svg') }}"
                                        alt="Attach">
                                    <input type="file">
                                </div>
                            </span>
                            <button class="send-btn" type="button" wire:click="sendComposeMessage"
                                wire:loading.attr="disabled" wire:target="sendComposeMessage">
                                <span wire:loading.remove wire:target="sendComposeMessage">Send</span>
                                <span class="btn-loading" wire:loading wire:target="sendComposeMessage">
                                    <span class="btn-spinner" aria-hidden="true"></span>
                                    Sending...
                                </span>
                            </button>
                        </div>
                    </div>
                @break

                @case('chat')
                    <div class="message-chat-theme" id="messageChatPanel" wire:key="chat-body-{{ $activeConversationId }}"
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
                                <button class="new-email new-email-btn" type="button" wire:click="openCompose('email')"><svg
                                        width="14" height="14" viewBox="0 0 14 14" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M6.58333 0.75V12.4167M0.75 6.58333H12.4167" stroke="#004E42"
                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
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
                                <div style="padding: 12px; text-align: center;"
                                    x-show="!switching && messagesId === uiActiveId" x-cloak>
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
                                    @php
                                        $isSupport = ($message['sender'] ?? 'user') === 'support';
                                        $createdAtTs = (int) ($message['createdAtTs'] ?? 0);
                                        $messageTime = $createdAtTs
                                            ? \Carbon\Carbon::createFromTimestamp($createdAtTs)->format('g:ia')
                                            : '';
                                        $timestampLabel = $messageTime ? 'Message sent ' . $messageTime : '';
                                    @endphp
                                    <div class="message {{ $isSupport ? 'message-right' : 'message-left' }}">
                                        @if (!empty($message['mediaUrl']))
                                            @if (($message['messageType'] ?? '') === 'image')
                                                <p><img src="{{ $message['mediaUrl'] }}" alt="attachment"
                                                        class="img-loading" data-shimmer="true"
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
                                        <span class="timestamp"
                                            @if (!$isSupport) style="color:#8e8e8e;" @endif>
                                            {{ $timestampLabel }}
                                        </span>
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
                            <button id="sendBtn" class="send-btn" type="button" wire:click="sendReply"
                                wire:loading.attr="disabled" wire:target="sendReply">
                                <span class="btn-icon" wire:loading.remove wire:target="sendReply">
                                    <img src="{{ asset('assets/images/icons/send-chat-icon.svg') }}" alt="">
                                </span>
                                <span class="btn-loading" wire:loading wire:target="sendReply">
                                    <span class="btn-spinner" aria-hidden="true"></span>
                                </span>
                            </button>
                        </div>
                    </div>
                @break

                @case('empty')
                    <section class="content-panel" id="messageEmptyPanel">
                        <div class="display-chat">
                            <div class="chat-display-img">
                                <img src="{{ asset('assets/images/icons/chat-img.svg') }}" alt="Chat Icon" class="chat-img">
                                <h2 class="chat-title">Select a message to view</h2>
                            </div>
                        </div>
                    </section>
                @break

            @endswitch
        </div>
    </div>
</div>
