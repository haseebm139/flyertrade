@extends('admin.layouts.app')

@section('title', 'Messaging')
@section('header', 'Messaging')
@section('content')
    <style>
         
        .search-bars input {
             background: url("/assets/images/icons/search-icon_chat.svg") no-repeat 0.8vw center;
             background-size: 1vw;
        }
        .sidebars {
            background: #F6F6F6;
            width: 20vw;
        }

        .filters {
            flex-wrap: wrap;
            background: rgba(23, 165, 90, 0.1);
            border-radius: 0.417vw;
            justify-content: space-between;
        }

        .filter-btn {
            border: none !important;
            color: #004e42;
            padding: 0.5vw 0.7vw;
            line-height: 1;
            min-width: 3.073vw;
        }

        .tab.active {
            border-radius: 0.208vw;
        }

        .chat-user-sections {
            justify-content: flex-start;
        }

        .tab.active,
        .tab:hover {
            background: rgba(23, 165, 90, 0.1);
        }

        .tab.chat-tabss {
            font-size: 0.833vw;
        }

        .user-infos strong {
            font-size: 0.833vw;
        }

        .user-infos {
            gap: 10px;
        }

        .msg_info_part {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 10px;
        }

        .user-infos small {
            font-size: 0.729vw;
        }

        .user-list-item {
            display: flex;
            align-items: stretch;
        }

        .user-list {
            max-height: 60vh;
            overflow-y: auto;
            padding-right: 0.417vw;
        }

        .search-bars input::placeholder {
            font-size: 0.833vw;

            color: #555;
            /* font-weight: 500; */
        }

        .search-bars input {
            font-size: 0.833vw;
            padding-left: 2.083vw;
            color: #555;
            font-weight: 500;
        }

        .search-bars {
            position: relative;
        }

        .searc_icon {
            position: absolute;
            left: 10px;
            top: 30%;
            transform: translateY(-30%);
        }

        .message-chat-theme .chat-header {
            background: #fff;
        }

        .message-chat-theme .new-email {
            font-size: 0.729vw;
            color: #004e42;
            font-weight: 600;

        }

        .header-right span {
            color: #717171;
        }

        .search-bars svg {
            width: 1.25vw;
            height: 1.25vw;
        }
    </style>
    <livewire:admin.messages.board />
@endsection

@push('styles')
    <style>
        .is-hidden {
            display: none !important;
        }

        [x-cloak] {
            display: none !important;
        }

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
@endpush

@push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            const isNearBottom = (el, threshold = 120) => {
                return el.scrollHeight - el.scrollTop - el.clientHeight <= threshold;
            };

            const scrollChatBottom = (force = false) => {
                const el = document.getElementById('chatBody');
                if (!el) return;
                if (!force && !isNearBottom(el)) return;
                requestAnimationFrame(() => {
                    el.scrollTop = el.scrollHeight;
                });
            };

            Livewire.on('scroll-chat-bottom', () => {
                scrollChatBottom(true);
                setTimeout(() => scrollChatBottom(true), 50);
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
                const body = document.getElementById('chatBody');
                if (body) {
                    scrollChatBottom(false);
                }
            });
        });
    </script>
@endpush
