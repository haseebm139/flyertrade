@extends('admin.layouts.app')

@section('title', 'Settings')
@section('header', 'Settings')
@section('content')
<style>
    .JM___heading_title{
        font-size:1.042vw;
        font-weight: 600;
        line-height:2vw;
    }
</style>
<style>
    .charge-input {
        padding: 0.6vw;
        border-radius: 0.5vw;
        border: 0.1vw solid #ddd;
        font-size: 0.729vw;
        width: 100%;
        background-color: #fff;
    }
    .tab-content {
        display: none;
    }
    .tab-content.active {
        display: block;
    }
    .tabs-vertical-wrapper .tab {
        cursor: pointer;
    }

    .message-row .message-actions {
        height: 2.865vw;
    }
    .message-row input{
          height: 2.865vw;
    }

    /* Livewire toggles: full-size invisible input so change events always fire */
    .settings-manager-livewire .toggle-switch {
        position: relative;
    }
    .settings-manager-livewire .toggle-switch input {
        position: absolute;
        left: 0;
        top: 0;
        width: 50px;
        height: 24px;
        opacity: 0;
        margin: 0;
        cursor: pointer;
        z-index: 2;
    }
    .settings-manager-livewire .toggle-switch .slider {
        z-index: 1;
    }

</style>

<livewire:admin.settings.settings-manager />

@endsection

@push('styles')
@endpush

@push('scripts')
@endpush
