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

        .search-bars svg {
            width: 1.25vw;
            height: 1.25vw;
        }
    </style>
    <livewire:admin.messages.board />
@endsection
