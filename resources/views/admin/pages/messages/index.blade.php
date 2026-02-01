@extends('admin.layouts.app')

@section('title', 'Messaging')
@section('header', 'Messaging')
@section('content')

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
                                <span class="time">12:00pm</span>
                            </div>
                            <small>Hi, I booked a plumber for tomorrow.</small>
                        </div>
                        <span class="unread-count">2</span>
                    </li>
                    <li class="user-list-item">
                        <img src="{{asset('assets/images/icons/five.svg')}}" class="user-avatar" />
                        <div class="user-infos">
                            <div class="user-header">
                                <strong>Emma Watson</strong>
                                <span class="time">11:45am</span>
                            </div>
                            <small>Thanks for the quick help!</small>
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
                                <strong>Ali Raza</strong>
                                <span class="time">9:30am</span>
                            </div>
                            <small>Can you confirm the service time?</small>
                        </div>
                        <span class="unread-count">1</span>
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
                                <strong>Support Team</strong>
                                <span class="time">Yesterday</span>
                            </div>
                            <small>Your invoice #4523 has been sent.</small>
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
                                <strong>Ali Khan</strong>
                                <span class="time">10:15am</span>
                            </div>
                            <small>Can we reschedule for tomorrow?</small>
                        </div>
                        <span class="unread-count">1</span>
                    </li>
                    <li class="user-list-item">
                        <img src="{{asset('assets/images/icons/five.svg')}}" class="user-avatar" />
                        <div class="user-infos">
                            <div class="user-header">
                                <strong>Lucy Cleaner</strong>
                                <span class="time">8:00am</span>
                            </div>
                            <small>Just finished cleaning at 12th Ave.</small>
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
                        <img src="{{asset('assets/images/icons/dots_message.svg')}}" alt="Refresh Icon">

                    </div>
                    <button class="new-email new-email-btn">+ New email</button>
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
                    <span class="timestamp" style="color:grey;">Message sent 12pm</span>
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
                    <span class="timestamp" style="color:grey;">Message sent 12pm</span>
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
                    <span class="timestamp" style="color:grey;">Message sent 12pm</span>
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
