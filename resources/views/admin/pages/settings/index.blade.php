@extends('admin.layouts.app')

@section('title', 'Settings')
@section('header', 'Settings')
@section('content')

<style>
    .charge-input {
        padding: 0.6vw;
        border-radius: 0.5vw;
        border: 0.1vw solid #ddd;
        font-size: 1vw;
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
</style>

<livewire:admin.settings.settings-manager />

@endsection

@push('styles')
@endpush

@push('scripts')
@endpush
