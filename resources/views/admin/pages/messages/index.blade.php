@extends('admin.layouts.app')

@section('title', 'Messaging')
@section('header', 'Messaging')
@section('content')
<style>
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
    .search-bars svg{
        width:1.25vw;
        height:1.25vw;
    }
</style>
<div class="users-toolbar border-0 p-0">
    <div class="toolbar-left">
        @can('Create Messages')
        <button class="add-user-btn new-email-btn">
            <img class="icons-btn" src="{{asset('assets/images/icons/sms.svg')}}" alt=""> New Email
        </button>
        <button class="export-btn">
            <img class="icons-btn" src="{{asset('assets/images/icons/messages.svg')}}" alt=""> New Message
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
            <svg class="searc_icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M21 21L15.0001 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z" stroke="#555555" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <input type="search" placeholder="Search" />
        </div>

        <div class="filters">
            <button class="tab filter-btn tab-active" data-target="all">All</button>
            <button class="tab filter-btn" data-target="unread">Unread</button>
            <button class="tab filter-btn" data-target="emails">Emails</button>
            <button class="tab filter-btn" data-target="chats">Chats</button>
        </div>
        <div class="chat-user-sections" bis_skin_checked="1">
            <div class="tab chat-tabss " data-target="service-users" bis_skin_checked="1">Service
                users
            </div>
            <div class="tab chat-tabss " data-target="service-provider" bis_skin_checked="1">Service
                Provider
            </div>

        </div>
        <div id="service-users" class="tab-content" bis_skin_checked="1">
            <div class="user-actions">
                <label>
                    <input type="checkbox" id="selectAll" /> Select all
                </label>

                <div class="filter-menu">
                    <select id="filterStatus">
                        <option value="all">All</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>

            <ul class="user-list">
                <li class="user-list-item active">
                    <img src="{{asset('assets/images/icons/five.svg')}}" class="user-avatar" />
                    <div class="user-infos">
                        <div class="user-header">
                            <strong>Johnson James</strong>
                        </div>
                        <small>bfahey@example.org</small>
                    </div>
                    <input type="checkbox" class="select-user" />
                </li>

                <li class="user-list-item inactive">
                    <img src="{{asset('assets/images/icons/five.svg')}}" class="user-avatar" />
                    <div class="user-infos">
                        <div class="user-header">
                            <strong>Emma Watson</strong>
                        </div>
                        <small>Thanks for the quick help!</small>
                    </div>
                    <input type="checkbox" class="select-user" />
                </li>

                <li class="user-list-item active">
                    <img src="{{asset('assets/images/icons/five.svg')}}" class="user-avatar" />
                    <div class="user-infos">
                        <div class="user-header">
                            <strong>Chris Evans</strong>
                        </div>
                        <small>chrisevans@example.org</small>
                    </div>
                    <input type="checkbox" class="select-user" />
                </li>
            </ul>

        </div>
        <div id="service-provider" class="tab-content " bis_skin_checked="1">

        </div>
        <!-- ====== ALL TAB ====== -->
        <div id="all" class="tab-content active">

            <ul class="user-list">
                <li class="user-list-item">
                    <img src="{{asset('assets/images/icons/five.svg')}}" class="user-avatar" />
                    <div class="user-infos">
                        <div class="user-header">
                            <strong>Johnson James</strong>

                        </div>
                        <small>Hi, I booked a plumber for tomorrow.</small>
                    </div>
                    <div class="msg_info_part">
                        <span class="time">12:00pm</span>
                        <span class="unread-count">2</span>
                    </div>

                </li>
                <li class="user-list-item">
                    <img src="{{asset('assets/images/icons/five.svg')}}" class="user-avatar" />
                    <div class="user-infos">
                        <div class="user-header">
                            <strong>Emma Watson</strong>

                        </div>
                        <small>Thanks for the quick help!</small>
                    </div>
                    <div class="msg_info_part">
                        <span class="time">12:00pm</span>
                        <!-- <span class="unread-count">2</span> -->
                    </div>
                </li>
            </ul>
        </div>

        <!-- ====== UNREAD TAB ====== -->
        <div id="unread" class="tab-content">
            <ul class="user-list">
                <li class="user-list-item">
                    <img src="{{asset('assets/images/icons/five.svg')}}" class="user-avatar" />
                    <div class="user-infos">
                        <div class="user-header">
                            <strong>Johnson James</strong>

                        </div>
                        <small>Hi, I booked a plumber for tomorrow.</small>
                    </div>
                    <div class="msg_info_part">
                        <span class="time">12:00pm</span>
                        <span class="unread-count">2</span>
                    </div>

                </li>
            </ul>
        </div>

        <!-- ====== EMAILS TAB ====== -->
        <div id="emails" class="tab-content">
            <ul class="user-list">
                <li class="user-list-item">
                    <img src="{{asset('assets/images/icons/five.svg')}}" class="user-avatar" />
                    <div class="user-infos">
                        <div class="user-header">
                            <strong>Emma Watson</strong>

                        </div>
                        <small>Thanks for the quick help!</small>
                    </div>
                    <div class="msg_info_part">
                        <span class="time">Yesterday</span>
                        <!-- <span class="unread-count">2</span> -->
                    </div>
                </li>
            </ul>
        </div>

        <!-- ====== CHATS TAB ====== -->
        <div id="chats" class="tab-content">
            <ul class="user-list">
                <li class="user-list-item">
                    <img src="{{asset('assets/images/icons/five.svg')}}" class="user-avatar" />
                    <div class="user-infos">
                        <div class="user-header">
                            <strong>Johnson James</strong>

                        </div>
                        <small>Hi, I booked a plumber for tomorrow.</small>
                    </div>
                    <div class="msg_info_part">
                        <span class="time">12:00pm</span>
                        <span class="unread-count">2</span>
                    </div>

                </li>
                <li class="user-list-item">
                    <img src="{{asset('assets/images/icons/five.svg')}}" class="user-avatar" />
                    <div class="user-infos">
                        <div class="user-header">
                            <strong>Emma Watson</strong>

                        </div>
                        <small>Thanks for the quick help!</small>
                    </div>
                    <div class="msg_info_part">
                        <span class="time">12:00pm</span>
                        <!-- <span class="unread-count">2</span> -->
                    </div>
                </li>
            </ul>
        </div>
    </aside>

    <!-- COMPOSE EMAIL -->
    <div class="email-compose">
        <div class="compose-header">
            <div class="heading-with-icon">
                <img src="{{asset('assets/images/icons/back.svg')}}" alt="" class="icon-back">
                <h2>Compose email</h2>
            </div>

            <div class="recipient-container">
                <span class="label">to</span>
                <div class="recipient-list">
                    <img src="{{asset('assets/images/icons/five.svg')}}" alt="Recipient 1">
                    <img src="{{asset('assets/images/icons/five.svg')}}" alt="Recipient 2">
                    <img src="{{asset('assets/images/icons/five.svg')}}" alt="Recipient 3">
                    <img src="{{asset('assets/images/icons/five.svg')}}" alt="Recipient 4">
                    <img src="{{asset('assets/images/icons/five.svg')}}" alt="Recipient 5">
                </div>
                <span class="others">+20 others</span>
            </div>
        </div>

        <div class="compose-body">
            <input type="text" class="subject-input" placeholder="Subject">
            <textarea class="message-area" placeholder=""></textarea>
        </div>

        <div class="compose-footer">
            <span class="attachment">
                <div class="file-upload">
                    <img class="attach" src="{{asset('assets/images/icons/ic_attachment.svg')}}" alt="Attach">
                    <input type="file">
                </div>
            </span>
            <button class="send-btn">Send</button>
        </div>
    </div>

    <!-- CONTENT PANEL -->
    <section class="content-panel">
        <div class="display-chat">
            <div class="chat-display-img">
                <img src="{{asset('assets/images/icons/chat-img.svg')}}" alt="Chat Icon" class="chat-img">
                <h2 class="chat-title">Select a message to view</h2>
            </div>
        </div>
    </section>

    <!-- VIEW EMAIL -->
    <div class="view-email">
        <div class="compose-header">
            <div class="heading-with-icon">
                <img src="{{asset('assets/images/icons/back.svg')}}" alt="" class="icon-back">
                <h2>Compose email</h2>
            </div>

            <div class="recipient-container">
                <span class="label">to</span>
                <div class="recipient-list">
                    <img src="{{asset('assets/images/icons/five.svg')}}" alt="Recipient 1">
                    <img src="{{asset('assets/images/icons/five.svg')}}" alt="Recipient 2">
                    <img src="{{asset('assets/images/icons/five.svg')}}" alt="Recipient 3">
                    <img src="{{asset('assets/images/icons/five.svg')}}" alt="Recipient 4">
                    <img src="{{asset('assets/images/icons/five.svg')}}" alt="Recipient 5">
                </div>
                <span class="others">+20 others</span>
            </div>
        </div>

        <div class="compose-body">
            <h2 class="email-title">Pending update on booking</h2>
            <h3 class="email-subjecter">Dear Johnson,</h3>
            <p class="email-viewer">
                Lorem ipsum dolor sit amet consectetur. Imperdiet nunc auctor amet diam sed etiam. Vitae
                sit ultrices volutpat sollicitudin eu massa. Integer magna consectetur arcu integer eu
                faucibus. Mauris viverra risus id maecenas. Diam eu blandit convallis in a sem id
                aliquet. <br>
                Sed nunc vehicula orci euismod. Sit sed non volutpat cras. Faucibus tempor est a massa
                posuere id.
                Elementum vulputate in varius egestas sit suspendisse sit nec proin. Sed quis facilisi
                sem neque ullamcorper.
                Tincidunt turpis urna rhoncus imperdiet facilisi bibendum malesuada. Orci massa sed id
                semper pretium vestibulum
                magna a.
            </p>
        </div>
    </div>
    <style>
        .message-chat-theme .message-left,
        .message-chat-theme .message-right {
            background: #fff;
        }

        .message-chat-theme .message-left p {
            background: rgba(149, 109, 55, 0.1);
            padding: 1.042vw;
            border-radius: 0.417vw 0.417vw 0 0.417vw;
            color: #8e8e8e;
        }

        .message-chat-theme .message-right p {

            background: #004e42;
            padding: 1.042vw;
            border-radius: 0.417vw 0.417vw 0 0.417vw;
        }

        .message-chat-theme .timestamp {
            text-align: right;
            color: #555;
        }
    </style>
    <!-- MESSAGE CHAT -->
    <div class="message-chat-theme">
        <div class="chat-header">
            <div class="heading-with-icon" bis_skin_checked="1">
                <img src="{{asset('assets/images/icons/back.svg')}}" alt="" class="icon-back">
                <div class="user-info" bis_skin_checked="1">
                    <img src="{{asset('assets/images/icons/five.svg')}}" alt="avatar">
                    <div bis_skin_checked="1">
                        <p class="user-name" style="font-weight:600; color:black;">Antonetta Walker</p>
                        <p class="user-email">bfahey@example.org</p>
                    </div>
                </div>
            </div>


            <div class="header-right">
                <span>1 of 200</span>
                <div class="icons">
                    <img src="{{asset('assets/images/icons/message-icon-prev.svg')}}" alt="Refresh Icon">
                    <img src="{{asset('assets/images/icons/message-icon-next.svg')}}" alt="Expand Icon">
                </div>
                <div class="icons">
                    <img src="{{asset('assets/images/icons/dots_message.png')}}" alt="Refresh Icon">

                </div>
                <button class="new-email new-email-btn"><svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6.58333 0.75V12.4167M0.75 6.58333H12.4167" stroke="#004E42" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg> New email</button>
            </div>
        </div>

        <div class="chat-body" id="chatBody">
            <!-- Message 1 Left -->
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
            </div>
        </div>



        <div class="chat-footer">
            <input id="chatInput" type="text" placeholder="Reply message......">
            <div class="footer-icons"><img src="{{asset('assets/images/icons/emoji.svg')}}" alt=""><img
                    src="{{asset('assets/images/icons/txt.svg')}}" alt=""><span class="attachment">
                    <div class="file-upload">
                        <img class="attach theme-attach" src="{{asset('assets/images/icons/ic_attachment.svg')}}" alt="Attach">
                        <input type="file">
                    </div>
                </span></div>
            <button id="sendBtn" class="send-btn"><img src="{{asset('assets/images/icons/send-chat-icon.svg')}}"
                    alt=""></button>
        </div>
    </div>



    @endsection